<?php

namespace Orba\Magento2Codegen\Service\Twig\EscaperExtension;

class EscaperCollection
{
    /**
     * @var EscaperInterface[]
     */
    private $escapers;

    /**
     * @param EscaperInterface[] $escapers
     */
    public function __construct(array $escapers = [])
    {
        $this->escapers = $escapers;
    }

    public function getItems()
    {
        return $this->escapers;
    }
}
