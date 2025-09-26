<?php require $_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php";
define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", 'Y');
define("NO_AGENT_STATISTIC",'Y');
define("NO_AGENT_CHECK", true);
define("DisableEventsCheck", true);
\Bitrix\Main\Loader::includeModule("intranet")
?>

<?php $APPLICATION->IncludeComponent('dellindev.companycustom:company.list','',[
    'GRID_ID'   => 'DELLINDEV_COMPANY_GRID',
    'PAGE_SIZE' => 20,
    'CACHE_TIME'=> 60
]);?><?php
require $_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"; ?>

