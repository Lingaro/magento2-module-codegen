<?php

namespace Orba\Magento2Codegen\Service;

use Symfony\Component\Filesystem\Filesystem;

class TemplateDir
{
    const DIR = BP . '/templates';

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getPath(string $templateName):? string
    {
        $path = self::DIR . '/' . $templateName;
        if ($this->filesystem->exists($path)) {
            return $path;
        }
        return null;
    }
}