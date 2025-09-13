<?php

namespace Drogalov\OrderHandler\Service;

use Bitrix\Main\Config\Option;
use Drogalov\OrderHandler\Module;

class ModuleDefaults
{
    /**
     * Возвращает массив дефолтных опций модуля
     *
     * @return array
     */
    public static function get(): array
    {
        return Option::getDefaults(Module::ID);
    }
}
