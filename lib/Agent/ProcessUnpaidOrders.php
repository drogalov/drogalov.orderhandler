<?php

namespace Drogalov\OrderHandler\Agent;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Type\DateTime;
use Bitrix\Sale\Order;
use Bitrix\Main\Loader;
use Drogalov\OrderHandler\Module;

/**
 * Агент для обработки неоплаченных заказов.
 */
class ProcessUnpaidOrders
{
    public static function run(): string
    {
        if (!Loader::includeModule('sale')) {
            return __METHOD__ . '();';
        }

        $hours = (int)Option::get(Module::ID, 'cancel_after_hours', 120);
        $statusToSet = Option::get(Module::ID, 'cancel_status', 'AN');
        $timeLimit = (new DateTime())->add("-{$hours} hours");

        $orders = Order::getList([
            'filter' => [
                '<DATE_INSERT' => $timeLimit,
                '!STATUS_ID' => 'P',
                '!CANCELED' => 'Y',
            ],
            'select' => ['ID', 'STATUS_ID'],
            'limit'  => 50,
        ]);

        while ($orderData = $orders->fetch()) {
            $orderObj = Order::load($orderData['ID']);
            if (!$orderObj) {
                continue;
            }

            $orderObj->setField('STATUS_ID', $statusToSet);

            $result = $orderObj->save();
            if (!$result->isSuccess()) {
                self::logError($result->getErrorMessages(), $orderData['ID']);
            }
        }

        return __METHOD__ . '();';
    }

    private static function logError(array $errors, int $orderId): void
    {
        \Bitrix\Main\Diag\Debug::writeToFile(
            [
                'orderId' => $orderId,
                'errors'  => $errors
            ],
            'Process unpaid orders error',
            '/log_order_process.txt'
        );
    }
}