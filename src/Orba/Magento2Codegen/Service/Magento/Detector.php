<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\Magento;

use Orba\Magento2Codegen\Service\FilepathUtil;
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
