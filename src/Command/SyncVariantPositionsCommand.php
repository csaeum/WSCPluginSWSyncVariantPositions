<?php

declare(strict_types=1);

namespace WSCPluginSWSyncVariantPositions\Command;

use Shopware\Core\Framework\Context;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WSCPluginSWSyncVariantPositions\Service\ValueObject\SyncOptions;
use WSCPluginSWSyncVariantPositions\Service\VariantPositionSyncService;

#[AsCommand(
    name: 'wsc:sync-variant-positions',
    description: 'Synchronisiert `product_configurator_setting.position` mit `property_group_option_translation.position` (Reihenfolge der Variantenauswahl)'
)]
class SyncVariantPositionsCommand extends Command
{
    public function __construct(
        private readonly VariantPositionSyncService $syncService
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
        $dryRun = (bool) $input->getOption('dry-run');
        $productId = $input->getOption('product-id');

        $io->title('Configurator-Positionen synchronisieren');

        if ($dryRun) {
            $io->note('Dry-Run Modus aktiv - es werden keine Änderungen vorgenommen');
        }

        if (is_string($productId) && $productId !== '') {
            $io->note(sprintf('Filter aktiv: productId=%s', $productId));
        }

        $io->section('Lade Product-Configurator-Settings...');

        // Create sync options
        $options = new SyncOptions(
            dryRun: $dryRun,
            productId: $productId
        );

        // Execute sync via service
        $result = $this->syncService->syncPositions($options, $context);

        // Handle errors
        if (!$result->success) {
            $io->error(sprintf('Fehler bei der Synchronisation: %s', $result->errorMessage));
            return Command::FAILURE;
        }

        // Display preview items if verbose or dry-run
        if (($output->isVerbose() || $dryRun) && !empty($result->previewItems)) {
            $io->section('Vorschau der Änderungen (erste 20):');
            foreach ($result->previewItems as $item) {
                $io->text(sprintf(
                    'Update %s: %d -> %d (optionId=%s)',
                    substr($item['settingId'], 0, 8) . '...',
                    $item['currentPosition'],
                    $item['targetPosition'],
                    substr($item['optionId'], 0, 8) . '...'
                ));
            }
        }

        // Display results
        $io->section('Ergebnis');
        $io->text(sprintf('Unverändert: %d', $result->unchangedCount));
        $io->text(sprintf('Übersprungen (ohne Option): %d', $result->skippedCount));
        $io->text(sprintf('Zu aktualisieren: %d', $result->updatedCount));

        if ($dryRun) {
            $io->warning('Dry-Run beendet. Führe den Befehl ohne --dry-run aus, um die Änderungen anzuwenden.');
            return Command::SUCCESS;
        }

        if ($result->updatedCount === 0) {
            $io->success('Alle Configurator-Positionen sind bereits synchron!');
            return Command::SUCCESS;
        }

        $io->success(sprintf('Erfolgreich %d Configurator-Positionen synchronisiert!', $result->updatedCount));
        $io->note('Hinweis: In der Regel reicht die DAL-Änderung aus; bei Storefront-Problemen ggf. Cache leeren: bin/console cache:clear');

        return Command::SUCCESS;
    }
}
