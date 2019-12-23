<?php

namespace Orba\Magento2Codegen\Test\Unit\Service;

use Orba\Magento2Codegen\Service\TemplatePropertyUtil;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;

class TemplatePropertyUtilTest extends TestCase
{
    /**
     * @var TemplatePropertyUtil
     */
    private $templatePropertyUtil;

    public function setUp(): void
    {
        $this->templatePropertyUtil = new TemplatePropertyUtil();
    }

    public function testGetPropertiesInTextReturnsEmptyArrayIfThereAreNoPropertiesInText(): void
    {
        $result = $this->templatePropertyUtil->getPropertiesInText('no properties');
        $this->assertSame([], $result);
    }

    public function testGetPropertiesInTextReturnsArrayWithPropertiesNamesIfPropertiesExistInText(): void
    {
        $result = $this->templatePropertyUtil->getPropertiesInText('i ${like} ${nice} properties');
        $this->assertSame(['like', 'nice'], $result);
    }

    public function testReplacePropertiesInTextReturnsSameStringIfPropertyBagIsEmptyAndTextHasNoProperties(): void
    {
        $result = $this->templatePropertyUtil->replacePropertiesInText('some text', new TemplatePropertyBag());
        $this->assertSame('some text', $result);
    }

    public function testReplacePropertiesInTextReturnsSameStringIfPropertyBagIsEmptyAndTextHasSomeProperties(): void
    {
        $result = $this->templatePropertyUtil->replacePropertiesInText('some ${text}', new TemplatePropertyBag());
        $this->assertSame('some ${text}', $result);
    }

    public function testReplacePropertiesInTextReturnsTextWithReplacedPropertyIfItExistsInPropertyBag(): void
    {
        $propertyBag = new TemplatePropertyBag();
        $propertyBag['text'] = 'string';
        $result = $this->templatePropertyUtil->replacePropertiesInText('some ${text}', $propertyBag);
        $this->assertSame('some string', $result);
    }

    public function testReplacePropertiesInTextReturnsTextWithReplacedPropertiesIfThereAreMoreThanOneAndExistInPropertyBag(): void
    {
        $propertyBag = new TemplatePropertyBag();
        $propertyBag['foo'] = 'bar';
        $propertyBag['goo'] = 'zar';
        $propertyBag['moo'] = 'har';
        $result = $this->templatePropertyUtil
            ->replacePropertiesInText('foo ${foo} goo ${goo} moo ${moo} ${foo}', $propertyBag);
        $this->assertSame('foo bar goo zar moo har bar', $result);
    }

    public function testReplacePropertiesInTextReturnsTextWithPropertyReplacedTwoTimesIfItIsNestedWithTwoLevels(): void
    {
        $propertyBag = new TemplatePropertyBag();
        $propertyBag['foo'] = '${bar}';
        $propertyBag['bar'] = 'moo';
        $result = $this->templatePropertyUtil->replacePropertiesInText('foo ${foo} bar', $propertyBag);
        $this->assertSame('foo moo bar', $result);
    }

    public function testReplacePropertiesInTextReturnsTextWithPropertyReplacedFiveTimesIfItIsNestedWithMoreThanFiveLevels(): void
    {
        $propertyBag = new TemplatePropertyBag();
        $propertyBag['foo'] = '${bar}';
        $propertyBag['bar'] = '${moo}';
        $propertyBag['moo'] = '${zar}';
        $propertyBag['zar'] = '${gar}';
        $propertyBag['gar'] = '${loo}';
        $propertyBag['loo'] = '${mar}';
        $propertyBag['mar'] = 'tar';
        $result = $this->templatePropertyUtil->replacePropertiesInText('foo ${foo} bar', $propertyBag);
        $this->assertSame('foo ${loo} bar', $result);
    }
}