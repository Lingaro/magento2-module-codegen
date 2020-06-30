<?php

namespace Orba\Magento2Codegen\Service\CommandUtil;

use Orba\Magento2Codegen\Service\Magento\Detector;

class Root
{
    /**
     * @var Template
     */
    private $templateCommandUtil;

    /**
     * @var Detector
     */
    private $detector;

    public function __construct(Template $templateCommandUtil, Detector $detector)
    {
        $this->templateCommandUtil = $templateCommandUtil;
        $this->detector = $detector;
    }

    public function isCurrentDirMagentoRoot(): bool
    {
        return $this->detector->rootEtcFileExistsInDir($this->templateCommandUtil->getRootDir());
    }
}
