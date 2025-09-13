<?php

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'drogalov.orderhandler',
    [
        'Drogalov\\OrderHandler\\Agent\\ProcessUnpaidOrders' => 'lib/Agent/ProcessUnpaidOrders.php',
        'Drogalov\\OrderHandler\\Service\\UnpaidOrdersAgentHelper' => 'lib/Service/UnpaidOrdersAgentHelper.php',
    ]
);
