<?php
require_once __DIR__ . '/../lib/Module.php';
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Drogalov\OrderHandler\Module;

class drogalov_orderhandler extends CModule
{
    public $MODULE_ID = Module::ID;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . "/version.php");
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->MODULE_NAME = 'Обработка заказов';
        $this->MODULE_DESCRIPTION = 'Модуль для автоматической обработки заказов.';
        $this->PARTNER_NAME = 'drogalov.pro';
        $this->PARTNER_URI = 'https://drogalov.pro';
    }

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $defaultOptions = Option::getDefaults($this->MODULE_ID);

        foreach ($defaultOptions as $key => $value) {
            Option::set($this->MODULE_ID, $key, $value);
        }
    }

    public function DoUninstall()
    {
        CAgent::RemoveModuleAgents($this->MODULE_ID);
        Option::delete($this->MODULE_ID);
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}
