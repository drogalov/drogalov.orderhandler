<?php

namespace Drogalov\OrderHandler\Service;

use Bitrix\Main\Diag\Debug;

class LoggerService
{
    private const BASE_DIR = '/upload/logs/order_handler/';

    /**
     * Логирование ошибок
     *
     * @param string $message
     * @param array $context
     */
    public static function error(string $message, array $context = []): void
    {
        self::write('errors', $message, $context);
    }

    /**
     * Логирование информации (для отладки)
     *
     * @param string $message
     * @param array $context
     */
    public static function info(string $message, array $context = []): void
    {
        self::write('info', $message, $context);
    }

    /**
     * Базовый метод записи логов
     *
     * @param string $type
     * @param string $message
     * @param array $context
     */
    private static function write(string $type, string $message, array $context = []): void
    {
        $logDir = $_SERVER['DOCUMENT_ROOT'] . self::BASE_DIR;
        if (!is_dir($logDir)) {
            mkdir($logDir, 0775, true);
        }

        $logFile = $logDir . $type . '_' . date('Y-m-d') . '.log';

        $data = [
            'time'    => date('H:i:s'),
            'message' => $message,
        ];

        if (!empty($context)) {
            $data['context'] = $context;
        }

        Debug::writeToFile($data, strtoupper($type), $logFile);
    }
}
