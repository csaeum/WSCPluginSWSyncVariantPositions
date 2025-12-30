<?php

declare(strict_types=1);

namespace WSCPluginSWSyncVariantPositions\Service;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use WSCPluginSWSyncVariantPositions\Service\ValueObject\SyncOptions;
use WSCPluginSWSyncVariantPositions\Service\ValueObject\SyncResult;

class VariantPositionSyncService
{
    public function __construct(
        private readonly EntityRepository $productConfiguratorSettingRepository,
        private readonly Connection $connection,
        private readonly SystemConfigService $systemConfigService
    ) {
    }

    public function syncPositions(SyncOptions $options, Context $context): SyncResult
    {
        $startTime = microtime(true);

        try {
            $results = $this->fetchConfiguratorSettings($options);

            $updatePayload = [];
            $previewItems = [];
            $updateCount = 0;
            $unchanged = 0;
            $skipped = 0;

            foreach ($results as $row) {
                $settingIdBinary = $row['setting_id_binary'];
                $optionIdBinary = $row['option_id_binary'];
                $currentPosition = (int) $row['current_position'];
                $targetPosition = $row['target_position'] !== null ? (int) $row['target_position'] : null;

                // Convert Binary to Hex using Shopware's UUID helper
                $settingIdHex = Uuid::fromBytesToHex($settingIdBinary);
                $optionIdHex = Uuid::fromBytesToHex($optionIdBinary);

                if ($targetPosition === null) {
                    $skipped++;
                    continue;
                }

                if ($currentPosition === $targetPosition) {
                    $unchanged++;
                    continue;
                }

                $updatePayload[] = [
                    'id' => $settingIdHex,
                    'position' => $targetPosition,
                ];

                // Store preview items (max 20 for display)
                if (count($previewItems) < 20) {
                    $previewItems[] = [
                        'settingId' => $settingIdHex,
                        'optionId' => $optionIdHex,
                        'currentPosition' => $currentPosition,
                        'targetPosition' => $targetPosition,
                    ];
                }

                // Batch update
                if (count($updatePayload) >= $options->batchSize) {
                    if (!$options->dryRun) {
                        $this->productConfiguratorSettingRepository->update($updatePayload, $context);
                    }
                    $updateCount += count($updatePayload);
                    $updatePayload = [];
                }
            }

            // Update remaining items
            if (!empty($updatePayload)) {
                if (!$options->dryRun) {
                    $this->productConfiguratorSettingRepository->update($updatePayload, $context);
                }
                $updateCount += count($updatePayload);
            }

            $executionTime = microtime(true) - $startTime;

            return new SyncResult(
                unchangedCount: $unchanged,
                skippedCount: $skipped,
                updatedCount: $updateCount,
                previewItems: $previewItems,
                executionTime: $executionTime,
                success: true
            );
        } catch (\Exception $exception) {
            $executionTime = microtime(true) - $startTime;

            return new SyncResult(
                unchangedCount: 0,
                skippedCount: 0,
                updatedCount: 0,
                previewItems: [],
                executionTime: $executionTime,
                success: false,
                errorMessage: $exception->getMessage()
            );
        }
    }

    private function fetchConfiguratorSettings(SyncOptions $options): array
    {
        // Use SQL for reading (safe, SELECT only)
        // Compare product_configurator_setting.position with property_group_option_translation.position
        // based on property_group_option_id
        // Get position from property_group_option_translation - use MAX() to avoid duplicate rows
        $sql = '
            SELECT
                pcs.id as setting_id_binary,
                pcs.property_group_option_id as option_id_binary,
                pcs.position as current_position,
                MAX(pgot.position) as target_position
            FROM product_configurator_setting pcs
            LEFT JOIN property_group_option_translation pgot
                ON pcs.property_group_option_id = pgot.property_group_option_id
                AND pgot.position IS NOT NULL
        ';

        $params = [];
        $whereConditions = [];

        if (is_string($options->productId) && $options->productId !== '') {
            $whereConditions[] = 'pcs.product_id = UNHEX(?)';
            $params[] = $options->productId;
        }

        if (!empty($whereConditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $whereConditions);
        }

        $sql .= ' GROUP BY pcs.id, pcs.property_group_option_id, pcs.position';

        return $this->connection->fetchAllAssociative($sql, $params);
    }
}
