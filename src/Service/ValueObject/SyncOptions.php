<?php

declare(strict_types=1);

namespace WSCPluginSWSyncVariantPositions\Service\ValueObject;

class SyncOptions
{
    public function __construct(
        public readonly bool $dryRun = false,
        public readonly ?string $productId = null,
        public readonly int $batchSize = 500
    ) {
    }
}
