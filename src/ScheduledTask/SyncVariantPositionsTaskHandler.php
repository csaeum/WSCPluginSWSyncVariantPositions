<?php

declare(strict_types=1);

namespace WSCPluginSWSyncVariantPositions\ScheduledTask;

use Psr\Log\LoggerInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use WSCPluginSWSyncVariantPositions\Service\ValueObject\SyncOptions;
use WSCPluginSWSyncVariantPositions\Service\VariantPositionSyncService;

#[AsMessageHandler]
class SyncVariantPositionsTaskHandler extends ScheduledTaskHandler
{
    private LoggerInterface $logger;

    public function __construct(
        EntityRepository $scheduledTaskRepository,
        LoggerInterface $logger,
        private readonly VariantPositionSyncService $syncService,
        private readonly SystemConfigService $configService
    ) {
        parent::__construct($scheduledTaskRepository, $logger);
        $this->logger = $logger;
    }

    public function run(): void
    {
        // Check if scheduled sync is enabled
        $enabled = $this->configService->get('WSCPluginSWSyncVariantPositions.config.scheduledTaskEnabled');

        if (!$enabled) {
            $this->logger->info('Scheduled variant position sync is disabled');
            return;
        }

        // Get batch size from config
        $batchSize = (int) ($this->configService->get('WSCPluginSWSyncVariantPositions.config.batchSize') ?? 500);

        // Execute sync
        $options = new SyncOptions(
            dryRun: false,
            productId: null,
            batchSize: $batchSize
        );

        try {
            $result = $this->syncService->syncPositions($options, Context::createDefaultContext());

            if ($result->success) {
                $this->logger->info('Scheduled variant position sync completed successfully', [
                    'updated' => $result->updatedCount,
                    'unchanged' => $result->unchangedCount,
                    'skipped' => $result->skippedCount,
                    'executionTime' => $result->executionTime,
                ]);
            } else {
                $this->logger->error('Scheduled variant position sync failed', [
                    'error' => $result->errorMessage,
                ]);
            }
        } catch (\Exception $exception) {
            $this->logger->error('Scheduled variant position sync encountered an exception', [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
        }
    }
}
