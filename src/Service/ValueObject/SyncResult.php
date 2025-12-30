<?php

declare(strict_types=1);

namespace WSCPluginSWSyncVariantPositions\Service\ValueObject;

class SyncResult
{
    public function __construct(
        public readonly int $unchangedCount,
        public readonly int $skippedCount,
        public readonly int $updatedCount,
        public readonly array $previewItems = [],
        public readonly float $executionTime = 0.0,
        public readonly bool $success = true,
        public readonly ?string $errorMessage = null
    ) {
    }
}
