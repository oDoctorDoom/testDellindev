<?php
declare(strict_types=1);

namespace DellinDev\CompanyCustom\Application\Service;

use Bitrix\Main\Engine\CurrentUser;
use DellinDev\CompanyCustom\Application\Contract\CompanyRepositoryInterface;
use DellinDev\CompanyCustom\Application\Contract\CompanyServiceInterface;
use DellinDev\CompanyCustom\Application\Contract\PermissionServiceInterface;
use DellinDev\CompanyCustom\Infrastructure\Cache\ComponentCache;

final class CompanyService implements CompanyServiceInterface
{
    public function __construct(
        private readonly CompanyRepositoryInterface $repository,
        private readonly PermissionServiceInterface $permissionService,
        private readonly ComponentCache $cache
    ) {}
    public function countCompanies(array $params): int
    {
        $userId = (int)($params['userId'] ?? (CurrentUser::get()?->getId() ?? 0));
        $filter = (array)($params['filter'] ?? []);
        $securedFilter = $this->permissionService->restrictFilterByUser($filter, $userId);

        return $this->repository->getCount([
            'filter' => $securedFilter,
        ]);
    }
    public function listCompanies(array $params): array
    {
        $userId = (int)($params['userId'] ?? (CurrentUser::get()?->getId() ?? 0));
        $filter = (array)($params['filter'] ?? []);
        $order  = (array)($params['order']  ?? []);
        $limit  = (int)($params['limit']  ?? 20);
        $offset = (int)($params['offset'] ?? 0);
        $select = (array)($params['select'] ?? ['ID','TITLE','ASSIGNED_BY_ID']);

        $securedFilter = $this->permissionService->restrictFilterByUser($filter, $userId);

        $cacheId = sha1(json_encode([
            'u' => $userId, 'f' => $securedFilter, 'o' => $order, 'l' => $limit, 'p' => $offset, 's' => $select
        ], JSON_THROW_ON_ERROR));
        $cacheDir = '/dellindev/companycustom/grid';

        return $this->cache->remember($cacheId, $cacheDir, function () use ($securedFilter, $order, $limit, $offset, $select) {
            return $this->repository->getList([
                'filter' => $securedFilter,
                'order'  => $order,
                'limit'  => $limit,
                'offset' => $offset,
                'select' => $select,
            ]);
        });
    }
}
