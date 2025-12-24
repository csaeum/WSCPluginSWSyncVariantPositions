<?php

declare(strict_types=1);

namespace WSCPluginSWSyncVariantPositions\Command;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'wsc:sync-variant-positions',
    description: 'Synchronisiert `product_configurator_setting.position` mit `property_group_option_translation.position` (Reihenfolge der Variantenauswahl)'
)]
class SyncVariantPositionsCommand extends Command
{
    public function __construct(
        private readonly EntityRepository $productConfiguratorSettingRepository,
        private readonly Connection $connection
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'dry-run',
            'd',
            InputOption::VALUE_NONE,
            'Zeigt nur an, was geändert werden würde, ohne tatsächlich zu ändern'
        );

        $this->addOption(
            'product-id',
            null,
            InputOption::VALUE_REQUIRED,
            'Optional: Nur ein bestimmtes Produkt (UUID in Hex-Format) synchronisieren'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $context = Context::createDefaultContext();
        $dryRun = $input->getOption('dry-run');
        $productId = $input->getOption('product-id');

        $io->title('Configurator-Positionen synchronisieren');

        if ($dryRun) {
            $io->note('Dry-Run Modus aktiv - es werden keine Änderungen vorgenommen');
        }

        $io->section('Lade Product-Configurator-Settings...');

        // Verwende SQL zum Lesen (sicher, nur SELECT)
        // Vergleiche product_configurator_setting.position mit property_group_option_translation.position
        // anhand der property_group_option_id
        // Hole die Position aus der property_group_option_translation - nutze MAX() um doppelte Zeilen zu vermeiden
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

        if (is_string($productId) && $productId !== '') {
            $whereConditions[] = 'pcs.product_id = UNHEX(?)';
            $params[] = $productId;
            $io->note(sprintf('Filter aktiv: productId=%s', $productId));
        }

        if (!empty($whereConditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $whereConditions);
        }

        $sql .= ' GROUP BY pcs.id, pcs.property_group_option_id, pcs.position';

        $results = $this->connection->fetchAllAssociative($sql, $params);

        $updatePayload = [];
        $updateCount = 0;
        $unchanged = 0;
        $skipped = 0;

        foreach ($results as $row) {
            $settingIdBinary = $row['setting_id_binary'];
            $optionIdBinary = $row['option_id_binary'];
            $currentPosition = (int) $row['current_position'];
            $targetPosition = $row['target_position'] !== null ? (int) $row['target_position'] : null;

            // Konvertiere Binary zu Hex mit Shopware's UUID-Helper
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

            if (($output->isVerbose() || $dryRun) && count($updatePayload) <= 20) {
                $io->text(sprintf(
                    'Update %s: %d -> %d (optionId=%s)',
                    substr($settingIdHex, 0, 8) . '...',
                    $currentPosition,
                    $targetPosition,
                    substr($optionIdHex, 0, 8) . '...'
                ));
            }

            if (count($updatePayload) >= 500) {
                if (!$dryRun) {
                    $this->productConfiguratorSettingRepository->update($updatePayload, $context);
                }
                $updateCount += count($updatePayload);
                $updatePayload = [];
            }
        }

        if (!empty($updatePayload)) {
            if (!$dryRun) {
                $this->productConfiguratorSettingRepository->update($updatePayload, $context);
            }
            $updateCount += count($updatePayload);
        }

        $io->section('Ergebnis');
        $io->text(sprintf('Unverändert: %d', $unchanged));
        $io->text(sprintf('Übersprungen (ohne Option): %d', $skipped));
        $io->text(sprintf('Zu aktualisieren: %d', $updateCount));

        if ($dryRun) {
            $io->warning('Dry-Run beendet. Führe den Befehl ohne --dry-run aus, um die Änderungen anzuwenden.');
            return Command::SUCCESS;
        }

        if ($updateCount === 0) {
            $io->success('Alle Configurator-Positionen sind bereits synchron!');
            return Command::SUCCESS;
        }

        $io->success(sprintf('Erfolgreich %d Configurator-Positionen synchronisiert!', $updateCount));
        $io->note('Hinweis: In der Regel reicht die DAL-Änderung aus; bei Storefront-Problemen ggf. Cache leeren: bin/console cache:clear');

        return Command::SUCCESS;
    }
}
