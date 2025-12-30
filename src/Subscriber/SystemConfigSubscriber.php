<?php

declare(strict_types=1);

namespace WSCPluginSWSyncVariantPositions\Subscriber;

use Doctrine\DBAL\Connection;
use Shopware\Core\System\SystemConfig\Event\SystemConfigChangedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WSCPluginSWSyncVariantPositions\ScheduledTask\SyncVariantPositionsTask;

class SystemConfigSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SystemConfigChangedEvent::class => 'onSystemConfigChanged',
        ];
    }

    public function onSystemConfigChanged(SystemConfigChangedEvent $event): void
    {
        // Only react to changes of our plugin's scheduledTaskInterval config
        if ($event->getKey() !== 'WSCPluginSWSyncVariantPositions.config.scheduledTaskInterval') {
            return;
        }

        $newInterval = (int) $event->getValue();

        // Use default interval if invalid value
        if ($newInterval <= 0) {
            $newInterval = SyncVariantPositionsTask::getDefaultInterval();
        }

        // Update the scheduled task interval in the database
        $this->connection->executeStatement(
            'UPDATE `scheduled_task`
             SET `run_interval` = :interval
             WHERE `scheduled_task_class` = :taskClass',
            [
                'interval' => $newInterval,
                'taskClass' => SyncVariantPositionsTask::class,
            ]
        );
    }
}
