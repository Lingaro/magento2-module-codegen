<?php

namespace Orba\Magento2Codegen\Service\Magento;

use Orba\Magento2Codegen\Service\FilepathUtil;
use Symfony\Component\Filesystem\Filesystem;

class Detector
{
    const MODULE_CONFIG_FILEPATH = 'etc/module.xml';
    const ROOT_MAGENTO_TEST_FILE_NAME = 'index.php';
    const ROOT_MAGENTO_TEST_FILE_STRING = '$app = $bootstrap->createApplication(\Magento\Framework\App\Http::class);';

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

    public function coreIndexPhpExistsInDir(string $dir): bool
    {
        $indexPhpAbsolutePath = $this->filepathUtil->getAbsolutePath(self::ROOT_MAGENTO_TEST_FILE_NAME, $dir);
        if (!$this->filesystem->exists($indexPhpAbsolutePath)) {
            return false;
        }
        return strpos(
            $this->filepathUtil->getContent($indexPhpAbsolutePath),
            self::ROOT_MAGENTO_TEST_FILE_STRING
            ) !== false;
    }
}
