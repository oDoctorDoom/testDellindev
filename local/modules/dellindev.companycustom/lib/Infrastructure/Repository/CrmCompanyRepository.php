<?php
declare(strict_types=1);

namespace DellinDev\CompanyCustom\Infrastructure\Repository;

use Bitrix\Main\Loader;
use DellinDev\CompanyCustom\Application\Contract\CompanyRepositoryInterface;

final class CrmCompanyRepository implements CompanyRepositoryInterface
{
    public function getCount(array $params): int
    {
        if (\Bitrix\Main\Loader::includeModule('crm') && class_exists('Bitrix\\Crm\\CompanyTable')) {
            $filter = (array)($params['filter'] ?? []);
            return (int)\Bitrix\Crm\CompanyTable::getCount($filter);
        }
        return 0;
    }
    public function getList(array $params): array
    {
        if (Loader::includeModule('crm') && class_exists('Bitrix\\Crm\\CompanyTable')) {
            $query = \Bitrix\Crm\CompanyTable::query()
                ->setSelect($params['select'] ?? ['ID','TITLE','ASSIGNED_BY_ID'])
                ->setFilter($params['filter'] ?? [])
                ->setOrder($params['order'] ?? ['ID' => 'DESC'])
                ->setLimit((int)($params['limit'] ?? 20))
                ->setOffset((int)($params['offset'] ?? 0));
            $rows = $query->fetchAll();
            return is_array($rows) ? $rows : [];
        }
        // Fallback demo data
        return [
            ['ID'=>1,'TITLE'=>'Acme','ASSIGNED_BY_ID'=>1],
            ['ID'=>2,'TITLE'=>'Globex','ASSIGNED_BY_ID'=>2],
        ];
    }
}
