<?php

declare(strict_types=1);

namespace WSCPluginSWSyncVariantPositions;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UpdateContext;
use WSCPluginSWSyncVariantPositions\ScheduledTask\SyncVariantPositionsTask;

class WSCPluginSWSyncVariantPositions extends Plugin
{
    public function install(InstallContext $installContext): void
    {
        parent::install($installContext);
        $this->updateTaskInterval($installContext->getContext());
    }

    public function update(UpdateContext $updateContext): void
    {
        parent::update($updateContext);
        $this->updateTaskInterval($updateContext->getContext());
    }

    public function activate(ActivateContext $activateContext): void
    {
        parent::activate($activateContext);
        $this->updateTaskInterval($activateContext->getContext());
    }

    private function updateTaskInterval($context): void
    {
        // Get the configured interval from system config
        $systemConfigService = $this->container->get('Shopware\Core\System\SystemConfig\SystemConfigService');
        $configuredInterval = (int) $systemConfigService->get('WSCPluginSWSyncVariantPositions.config.scheduledTaskInterval');

        // If no interval is configured, use the default
        if ($configuredInterval <= 0) {
            $configuredInterval = SyncVariantPositionsTask::getDefaultInterval();
        }

        // Update the scheduled task interval in the database
        /** @var Connection $connection */
        $connection = $this->container->get(Connection::class);

        $connection->executeStatement(
            'UPDATE `scheduled_task`
             SET `run_interval` = :interval
             WHERE `scheduled_task_class` = :taskClass',
            [
                'interval' => $configuredInterval,
                'taskClass' => SyncVariantPositionsTask::class,
            ]
        );
    }
}
