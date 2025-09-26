<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) { die(); }

use Bitrix\Main\Loader;
use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\PageNavigation;
use DellinDev\CompanyCustom\Application\Contract\CompanyServiceInterface;

if (!Loader::includeModule('dellindev.companycustom')) {
    throw new \RuntimeException('Module dellindev.companycustom not installed/loaded');
}

$service  = ServiceLocator::getInstance()->get(CompanyServiceInterface::class);
$gridId   = (string)($arParams['GRID_ID'] ?? 'DELLINDEV_COMPANY_GRID');

$gridOpts  = new GridOptions($gridId);
$sorting   = $gridOpts->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
$sort      = (array)$sorting['sort'];
$navParams = $gridOpts->GetNavParams();
$pageSize  = (int)($navParams['nPageSize'] ?? ($arParams['PAGE_SIZE'] ?? 20));

$nav = new PageNavigation($gridId);
$nav->allowAllRecords(false)
    ->setPageSize($pageSize)
    ->initFromUri();

$userId = (int)(CurrentUser::get()?->getId() ?? 0);
$select = ['ID', 'TITLE', 'ASSIGNED_BY_ID'];
$limit  = $nav->getLimit();
$offset = $nav->getOffset();

$params = [
    'userId' => $userId,
    'filter' => is_array($arParams['FILTER'] ?? null) ? $arParams['FILTER'] : [],
    'order'  => $sort,
    'limit'  => $limit,
    'offset' => $offset,
    'select' => $select,
];

$items = $service->listCompanies($params);
$total = $service->countCompanies($params); // см. п.2 — добавили метод

$nav->setRecordCount((int)$total);

$columns = [
    ['id' => 'ID',             'name' => 'ID',           'sort' => 'ID',             'default' => true],
    ['id' => 'TITLE',          'name' => 'Название',     'sort' => 'TITLE',          'default' => true],
    ['id' => 'ASSIGNED_BY_ID', 'name' => 'Ответственный','sort' => 'ASSIGNED_BY_ID', 'default' => true],
];

$rows = [];
foreach ($items as $row) {
    $rows[] = [
        'id'      => (int)$row['ID'],
        'data'    => $row,
        'columns' => [
            'ID'             => (int)$row['ID'],
            'TITLE'          => (string)($row['TITLE'] ?? ''),
            'ASSIGNED_BY_ID' => (int)($row['ASSIGNED_BY_ID'] ?? 0),
        ],
        'editable' => false,
        'actions'  => [],
    ];
}

$arResult = [
    'GRID_ID' => $gridId,
    'COLUMNS' => $columns,
    'ROWS'    => $rows,
    'NAV'     => $nav,
    'TOTAL'   => (int)$total,
    'SORT'    => $sort,
];

$this->IncludeComponentTemplate();