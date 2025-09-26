<?php
declare(strict_types=1);

namespace DellinDev\CompanyCustom\Application\Contract;

interface PermissionServiceInterface
{
    public function isAdmin(int $userId): bool;
    public function restrictFilterByUser(array $baseFilter, int $userId): array;
}
