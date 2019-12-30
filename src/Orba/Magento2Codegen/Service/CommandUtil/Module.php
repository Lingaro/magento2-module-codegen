<?php

namespace Orba\Magento2Codegen\Service\CommandUtil;

use Orba\Magento2Codegen\Service\FilepathUtil;
use Orba\Magento2Codegen\Service\TemplatePropertyBagFactory;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;
use Symfony\Component\Filesystem\Filesystem;

class Module
{
    const MODULE_REGISTRATION_FILENAME = 'registration.php';

    /**
     * @var FilepathUtil
     */
    private $filepathUtil;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var TemplatePropertyBagFactory
     */
    private $propertyBagFactory;

    public function __construct(
        FilepathUtil $filepathUtil,
        Filesystem $filesystem,
        TemplatePropertyBagFactory $propertyBagFactory
    )
    {
        $this->filepathUtil = $filepathUtil;
        $this->filesystem = $filesystem;
        $this->propertyBagFactory = $propertyBagFactory;
    }

    public function exists(string $rootDir): bool
    {
        return $this->filesystem->exists(
            $this->filepathUtil->getAbsolutePath(self::MODULE_REGISTRATION_FILENAME, $rootDir)
        );
    }

    public function getPropertyBag(string $rootDir): TemplatePropertyBag
    {
        $registrationAbsolutePath =
            $this->filepathUtil->getAbsolutePath(self::MODULE_REGISTRATION_FILENAME, $rootDir);
        $content = $this->filepathUtil->getContent($registrationAbsolutePath);
        preg_match('/\'(.*)_(.*)\'/', $content, $matches);
        $propertyBag = $this->propertyBagFactory->create();
        if (isset($matches[1]) && isset($matches[2])) {
            $propertyBag['vendorName'] = $matches[1];
            $propertyBag['moduleName'] = $matches[2];
        }
        return $propertyBag;
    }
}