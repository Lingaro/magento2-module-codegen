<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\Magento;

use Lingaro\Magento2Codegen\Service\FilepathUtil;
use Symfony\Component\Filesystem\Filesystem;

class Detector
{
    private const MODULE_CONFIG_FILEPATH = 'etc/module.xml';
    private const ROOT_MAGENTO_TEST_FILEPATH = 'app/etc/NonComposerComponentRegistration.php';

    private Filesystem $filesystem;
    private FilepathUtil $filepathUtil;

    public function __construct(Filesystem $filesystem, FilepathUtil $filepathUtil)
    {
        $this->filesystem = $filesystem;
        $this->filepathUtil = $filepathUtil;
    }

    public function moduleExistsInDir(string $dir): bool
    {
        return $this->filesystem->exists(
            $this->filepathUtil->getAbsolutePath(self::MODULE_CONFIG_FILEPATH, $dir)
        );
    }

    public function rootEtcFileExistsInDir(string $dir): bool
    {
        return $this->filesystem->exists(
            $this->filepathUtil->getAbsolutePath(self::ROOT_MAGENTO_TEST_FILEPATH, $dir)
        );
    }
}
