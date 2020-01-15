<?php

namespace Orba\Magento2Codegen\Test\Integration;

use PDO;

final class MysqlConnection
{
    /**
     * @var PDO|null
     */
    private static $instance = null;

    public static function getInstance(): PDO
    {
        if (static::$instance === null) {
            static::$instance = new PDO(
                sprintf(
                    'mysql:host=%s;port=%s;dbname=%s',
                    $_ENV['MAGENTO_DATABASE_HOST'],
                    $_ENV['MAGENTO_DATABASE_PORT'],
                    $_ENV['MAGENTO_DATABASE_NAME']
                ),
                $_ENV['MAGENTO_DATABASE_USERNAME'],
                $_ENV['MAGENTO_DATABASE_PASSWORD']
            );
        }

        return static::$instance;
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}