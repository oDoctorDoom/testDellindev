<?php
declare(strict_types=1);

namespace DellinDev\CompanyCustom\Application\Contract;

interface CompanyRepositoryInterface
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function getList(array $params): array;
    public function getCount(array $params): int;
}
