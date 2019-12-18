<?php


namespace Orba\Magento2Codegen\Test\Unit;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass(): void
    {
        if (!defined('BP')) {
            define('BP', __DIR__ . '/_files');
        }
    }
}