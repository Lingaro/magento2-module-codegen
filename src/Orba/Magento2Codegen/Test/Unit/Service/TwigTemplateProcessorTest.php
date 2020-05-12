<?php

namespace Orba\Magento2Codegen\Test\Unit\Service;

use Orba\Magento2Codegen\Service\Twig\FiltersExtension;
use Orba\Magento2Codegen\Service\Twig\FunctionsExtension;
use Orba\Magento2Codegen\Service\TwigTemplateProcessor;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\PropertyBag;
use Twig\Sandbox\SecurityError;

class TwigTemplateProcessorTest extends TestCase
{
    /**
     * @var TwigTemplateProcessor
     */
    private $twigTemplateProcessor;

    public function setUp(): void
    {
        $this->twigTemplateProcessor = new TwigTemplateProcessor(
            new FiltersExtension(),
            new FunctionsExtension()
        );
    }

    public function testReplacePropertiesInTextReturnsSameStringIfPropertyBagIsEmptyAndTextHasNoProperties(): void
    {
        $result = $this->twigTemplateProcessor
            ->replacePropertiesInText('some text', new PropertyBag());
        $this->assertSame('some text', $result);
    }

    public function testReplacePropertiesInTextReturnsProcessedTemplateWithSimpleProperty(): void
    {
        $propertyBag = new PropertyBag();
        $propertyBag['text'] = 'string';
        $result = $this->twigTemplateProcessor
            ->replacePropertiesInText('some {{ text }}', $propertyBag);
        $this->assertSame('some string', $result);
    }

    public function testReplacePropertiesInTextReturnsProcessedTemplateWithIfStatement(): void
    {
        $propertyBag = new PropertyBag();
        $propertyBag['text'] = 'string';
        $result = $this->twigTemplateProcessor
            ->replacePropertiesInText('some {% if text==\'string\' %}text{% endif %}', $propertyBag);
        $this->assertSame('some text', $result);
    }

    public function testReplacePropertiesInTextReturnsProcessedTemplateWithElseStatement(): void
    {
        $propertyBag = new PropertyBag();
        $propertyBag['text'] = 'string';
        $result = $this->twigTemplateProcessor
            ->replacePropertiesInText('some {% if text==\'foo\' %}bar{% else %}text{% endif %}', $propertyBag);
        $this->assertSame('some text', $result);
    }

    public function testReplacePropertiesInTextReturnsProcessedTemplateWithElseIfStatement(): void
    {
        $propertyBag = new PropertyBag();
        $propertyBag['text'] = 'string';
        $result = $this->twigTemplateProcessor
            ->replacePropertiesInText('some {% if text==\'foo\' %}bar{% elseif text==\'string\' %}text{% endif %}', $propertyBag);
        $this->assertSame('some text', $result);
    }

    public function testReplacePropertiesInTextReturnsProcessedTemplateWithForStatement(): void
    {
        $propertyBag = new PropertyBag();
        $propertyBag['elements'] = ['foo', 'bar'];
        $result = $this->twigTemplateProcessor
            ->replacePropertiesInText('{% for element in elements %}{{ element }} {% endfor %}', $propertyBag);
        $this->assertSame('foo bar ', $result);
    }

    public function testReplacePropertiesInTextReturnsProcessedTemplateWithNestedProperty(): void
    {
        $propertyBag = new PropertyBag();
        $propertyBag['user'] = ['firstname' => 'Foo', 'lastname' => 'Bar'];
        $result = $this->twigTemplateProcessor
            ->replacePropertiesInText('My name is {{ user.firstname }} {{ user.lastname }}.', $propertyBag);
        $this->assertSame('My name is Foo Bar.', $result);
    }

    public function testReplacePropertiesInTextReturnsProcessedTemplateWithUpperFilteredProperty(): void
    {
        $propertyBag = new PropertyBag();
        $propertyBag['text'] = 'string';
        $result = $this->twigTemplateProcessor
            ->replacePropertiesInText('some {{ text|upper }}', $propertyBag);
        $this->assertSame('some STRING', $result);
    }

    public function testReplacePropertiesInTextThrowsExceptionIfDisallowedTagWasUsed(): void
    {
        $this->expectException(SecurityError::class);
        $this->twigTemplateProcessor
            ->replacePropertiesInText('{% apply upper %}This text becomes uppercase{% endapply %}', new PropertyBag());
    }

    public function testReplacePropertiesInTextThrowsExceptionIfDisallowedFilterWasUsed(): void
    {
        $this->expectException(SecurityError::class);
        $this->twigTemplateProcessor
            ->replacePropertiesInText('hello {{ \'world\'|trim }}', new PropertyBag());
    }
}