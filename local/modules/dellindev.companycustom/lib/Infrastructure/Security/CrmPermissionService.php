<? declare(strict_types=1);

namespace DellinDev\CompanyCustom\Infrastructure\Security;

use Bitrix\Main\Engine\CurrentUser;
use DellinDev\CompanyCustom\Application\Contract\PermissionServiceInterface;

final class CrmPermissionService implements PermissionServiceInterface
{
    public function isAdmin(int $userId): bool
    {
        $u = CurrentUser::get();
        return $u && $u->isAdmin();
    }

    public function restrictFilterByUser(array $filter, int $userId): array
    {
        if ($this->isAdmin($userId)) {
            return $filter;
        }

        $filter['=ASSIGNED_BY_ID'] = (int)$userId;

        return $filter;
    }
}