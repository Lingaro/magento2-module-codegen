<?php

/**
 * @copyright Copyright © 2022 Orba. All rights reserved.
 * @author    info@orba.co
 */

namespace Orba\Magento2Codegen\Service\StringValidator;

use InvalidArgumentException;

use function count;

class PhpClassNameValidator implements ValidatorInterface
{
    private const MIN_CLASS_NAME_PARTS = 3;
    public const NOT_PROPER_CLASS_ERR_MSG = 'Entered value is not proper class name.';
    public const MULTIPLE_SLASHES_ERR_MSG
        = 'Entered value contains multiple backslashes in a row or backslash at the end of name.';
    public const NO_BACKSLASH_AT_START_ERR_MSG = 'Backslash is mandatory at the beginning of class name';
    public const WRONG_NAME_LENGTH_ERR_MSG = 'Class name should contain at least ' . self::MIN_CLASS_NAME_PARTS
        . ' parts (e.g. \Vendor\Module\SomeClass).';

    /**
     * @inheritDoc
     */
    public function validate(string $value, $condition): void
    {
        if ($condition) {
            $classParts = explode('\\', $value);

            foreach ($classParts as $key => $namePart) {
                $this->checkSlashes($key, $namePart);
                $this->checkAllowedSymbols($namePart);
            }

            $this->checkNameLength(count($classParts));
        }
    }

    /**
     * Check if class name contains slash at the beginning ot at the end, duplicated slashes in a row
     *
     * @throws InvalidArgumentException
     */
    private function checkSlashes(int $key, string $value): void
    {
        if ($key > 0 && !$value) {
            throw new InvalidArgumentException(self::MULTIPLE_SLASHES_ERR_MSG);
        } elseif ($key === 0 && $value) {
            throw new InvalidArgumentException(self::NO_BACKSLASH_AT_START_ERR_MSG);
        }
    }

    /**
     * First letter should be in upper case and the rest symbols including [a-zA-Z_0-9]
     *
     * @throws InvalidArgumentException
     */
    private function checkAllowedSymbols(string $value): void
    {
        if ($value) {
            if (!preg_match('/^[A-Z]+[\w]*$/', $value)) {
                throw new InvalidArgumentException(self::NOT_PROPER_CLASS_ERR_MSG);
            }
        }
    }

    /**
     * Class name should contain at least 3 parts
     *
     * @throws InvalidArgumentException
     */
    private function checkNameLength(int $partsCount): void
    {
        if ($partsCount <= self::MIN_CLASS_NAME_PARTS) {
            throw new InvalidArgumentException(self::WRONG_NAME_LENGTH_ERR_MSG);
        }
    }
}
