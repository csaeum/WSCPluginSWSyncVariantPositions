<?php

declare(strict_types=1);

namespace WSCPluginSWSyncVariantPositions\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

class SyncVariantPositionsTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'wsc.sync_variant_positions';
    }

    public static function getDefaultInterval(): int
    {
        return 3600; // 1 hour default
    }
}
