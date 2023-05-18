<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

namespace Namespace1;

use Namespace1\Tester\Php as PhpTester;

class Class1
{
    /**
     * @var FriesFryer
     */
    private $friesFryer;

    public function __construct(FriesFryer $friesFryer)
    {
        $this->friesFryer = $friesFryer;
    }

    public function getFriesFryer(): FriesFryer
    {
        return $this->friesFryer;
    }
}