<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\StringValidator;

use InvalidArgumentException;
use Orba\Magento2Codegen\Service\StringValidator\PhpClassNameValidator;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class PhpClassNameValidatorTest extends TestCase
{
    /**
     * @var PhpClassNameValidator
     */
    private $validator;

    public function setUp(): void
    {
        $this->validator = new PhpClassNameValidator();
    }

    public function testValidateExceptionMultipleSlashes(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->validator->validate('\Vendor\Module\Model\\\Something', true);
    }

    public function testValidateExceptionSlashAtTheEnd(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->validator->validate('\Vendor\Module\Model\Something\\', true);
    }

    /**
     * @dataProvider notProperClassNames
     */
    public function testValidateExceptionNotAllowedSymbolsInClassName($className): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->validator->validate($className, true);
    }

    public function testValidateExceptionNamespaceTooShort(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->validator->validate('\Vendor\Module', true);
    }

    public function testValidateExceptionNoSlashAtStartOfName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->validator->validate('Vendor\Module\Something', true);
    }

    public function testValidateSuccessNoException(): void
    {
        $this->validator->validate('\Vendor\Module\Something', true);
        $this->assertTrue(true);
    }

    public function notProperClassNames(): array
    {
        return [
            ['\Ven-dor\Module\Something'],
            ['\Vendor\Mod+ule\Something'],
            ['\Vendor\Module\Some/thing']
        ];
    }
}
