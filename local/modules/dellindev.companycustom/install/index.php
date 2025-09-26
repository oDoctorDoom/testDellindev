<?php
IncludeModuleLangFile(__FILE__);

class dellindev_companycustom extends CModule
{
    public $MODULE_ID = 'dellindev.companycustom';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;

    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = 'DellinDev Company Custom';
        $this->MODULE_DESCRIPTION = 'Companies list component and services (PSR/SOLID)';
        $this->PARTNER_NAME = 'DellinDev';
        $this->PARTNER_URI = 'https://example.com';
    }

    public function DoInstall()
    {
        RegisterModule($this->MODULE_ID);
        $this->InstallFiles();
        return true;
    }

    public function DoUninstall()
    {
        $this->UnInstallFiles();
        UnRegisterModule($this->MODULE_ID);
        return true;
    }

    public function InstallFiles()
    {
        $from = __DIR__ . '/components';
        $to = $_SERVER['DOCUMENT_ROOT'] . '/local/components';
        CheckDirPath($to . '/');
        CopyDirFiles($from, $to, true, true);
        return true;
    }

    public function UnInstallFiles()
    {
        $path = '/local/components/dellindev.companycustom';
        if (is_dir($_SERVER['DOCUMENT_ROOT'] . $path)) {
            DeleteDirFilesEx($path);
        }
        return true;
    }
}
