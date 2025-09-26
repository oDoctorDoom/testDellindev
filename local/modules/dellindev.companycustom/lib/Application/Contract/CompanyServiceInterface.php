<?php
declare(strict_types=1);

namespace DellinDev\CompanyCustom\Application\Contract;

interface CompanyServiceInterface
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function listCompanies(array $params): array;
    public function countCompanies(array $params): int;

}
