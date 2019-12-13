<?php

namespace Orba\Magento2Codegen\Service\FileMerger;

class DiXmlMerger extends AbstractXmlMerger
{
    /**
     * @var array
     */
    protected $idAttributes = [
        '/config/preference' => 'for',
        '/config/(type|virtualType)' => 'name',
        '/config/(type|virtualType)/plugin' => 'name',
        '/config/(type|virtualType)/arguments/argument' => 'name',
        '/config/(type|virtualType)/arguments/argument(/item)+' => 'name',
    ];

    /**
     * @var string
     */
    protected $typeAttributeName = 'xsi:type';
}