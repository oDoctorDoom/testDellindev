<?php declare(strict_types=1);

use Bitrix\Main\DI\ServiceLocator;

// Composer autoload (опционально)
$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
require_once $autoload;
}

// Попробуем стандартную регистрацию из .services.php
ServiceLocator::getInstance()->registerByModuleSettings('dellindev.companycustom');

// --- Fallback: если почему-то сервисы не зарегистрировались ---
$sl = ServiceLocator::getInstance();

$svcIface = \DellinDev\CompanyCustom\Application\Contract\CompanyServiceInterface::class;

if (!$sl->has($svcIface)) {
// Подключаем классы напрямую
$repo = new \DellinDev\CompanyCustom\Infrastructure\Repository\CrmCompanyRepository();
$perm = new \DellinDev\CompanyCustom\Infrastructure\Security\CrmPermissionService();
$cache = new \DellinDev\CompanyCustom\Infrastructure\Cache\ComponentCache();

$svc = new \DellinDev\CompanyCustom\Application\Service\CompanyService(
$repo,
$perm,
$cache
);

$sl->addInstance($svcIface, $svc);
}