<?php

namespace Drogalov\OrderHandler\Service;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Type\DateTime;
use Drogalov\OrderHandler\Module;

class UnpaidOrdersAgentHelper
{
    /**
     * Регистрирует агента
     */
    public static function registerAgent(): void
    {
        $defaults = ModuleDefaults::get();

        if (Option::get(Module::ID, 'enable_agent', $defaults['enable_agent']) !== 'Y') {
            return;
        }

        $interval = (int)Option::get(Module::ID, 'agent_interval', $defaults['agent_interval']);
        $startAgent = Option::get(Module::ID, 'start_agent', $defaults['start_agent']);

        $startTime = '';
        if (!empty($startAgent)) {
            $timestamp = strtotime($startAgent);
            if ($timestamp !== false) {
                $startTime = date('d.m.Y H:i:s', $timestamp);
            }
        }

        self::unregisterAgent();

        \CAgent::AddAgent(
            '\\Drogalov\\OrderHandler\\Agent\\ProcessUnpaidOrders::run();',
            Module::ID,
            'N',
            $interval,
            '',
            'Y',
            $startTime
        );
    }

    /**
     * Удаляет агента модуля
     */
    public static function unregisterAgent(): void
    {
        \CAgent::RemoveModuleAgents(Module::ID);
    }
}