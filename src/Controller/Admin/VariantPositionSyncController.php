<?php

declare(strict_types=1);

namespace WSCPluginSWSyncVariantPositions\Controller\Admin;

use Shopware\Core\Framework\Context;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use WSCPluginSWSyncVariantPositions\Service\ValueObject\SyncOptions;
use WSCPluginSWSyncVariantPositions\Service\VariantPositionSyncService;

#[Route(defaults: ['_routeScope' => ['api']])]
class VariantPositionSyncController extends AbstractController
{
    public function __construct(
        private readonly VariantPositionSyncService $syncService
    ) {
    }

    #[Route(
        path: '/api/_action/wsc-sync-variant-positions/sync',
        name: 'api.action.wsc.sync.variant.positions',
        methods: ['POST']
    )]
    public function sync(Request $request, Context $context): JsonResponse
    {
        try {
            $data = $request->request->all();
            $dryRun = (bool) ($data['dryRun'] ?? false);
            $productId = $data['productId'] ?? null;

            $options = new SyncOptions(
                dryRun: $dryRun,
                productId: is_string($productId) && $productId !== '' ? $productId : null
            );

            $result = $this->syncService->syncPositions($options, $context);

            if (!$result->success) {
                return new JsonResponse([
                    'success' => false,
                    'error' => $result->errorMessage,
                ], 500);
            }

            return new JsonResponse([
                'success' => true,
                'data' => [
                    'unchangedCount' => $result->unchangedCount,
                    'skippedCount' => $result->skippedCount,
                    'updatedCount' => $result->updatedCount,
                    'previewItems' => $result->previewItems,
                    'executionTime' => $result->executionTime,
                ],
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    #[Route(
        path: '/api/_action/wsc-sync-variant-positions/preview',
        name: 'api.action.wsc.sync.variant.positions.preview',
        methods: ['POST']
    )]
    public function preview(Request $request, Context $context): JsonResponse
    {
        try {
            $data = $request->request->all();
            $productId = $data['productId'] ?? null;

            $options = new SyncOptions(
                dryRun: true, // Always dry-run for preview
                productId: is_string($productId) && $productId !== '' ? $productId : null
            );

            $result = $this->syncService->syncPositions($options, $context);

            if (!$result->success) {
                return new JsonResponse([
                    'success' => false,
                    'error' => $result->errorMessage,
                ], 500);
            }

            return new JsonResponse([
                'success' => true,
                'data' => [
                    'unchangedCount' => $result->unchangedCount,
                    'skippedCount' => $result->skippedCount,
                    'updatedCount' => $result->updatedCount,
                    'previewItems' => $result->previewItems,
                    'executionTime' => $result->executionTime,
                ],
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'error' => $exception->getMessage(),
            ], 500);
        }
    }
}
