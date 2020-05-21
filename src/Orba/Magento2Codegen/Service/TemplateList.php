<?php

namespace Orba\Magento2Codegen\Service;

class TemplateList
{
    /**
     * @var DirectoryIteratorFactory
     */
    private $directoryIteratorFactory;

    public function __construct(DirectoryIteratorFactory $directoryIteratorFactory)
    {
        $this->directoryIteratorFactory = $directoryIteratorFactory;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $templates = [];
        $directoryIterator = $this->directoryIteratorFactory->create(TemplateDir::DIR);
        foreach ($directoryIterator as $dir) {
            if ($dir->isDir()) {
                $templates[] = $dir->getFilename();
            }
        }
        sort($templates);
        return $templates;
    }
}
