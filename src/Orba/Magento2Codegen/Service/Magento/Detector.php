<?php

namespace Orba\Magento2Codegen\Service\Magento;

use Orba\Magento2Codegen\Service\FilepathUtil;
use Symfony\Component\Filesystem\Filesystem;

class Detector
{
    const MODULE_CONFIG_FILEPATH = 'etc/module.xml';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var FilepathUtil
     */
    private $filepathUtil;

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
}
