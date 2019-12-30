<?php

namespace Orba\Magento2Codegen\Test\Unit\Service;

use Orba\Magento2Codegen\Service\Twig\FiltersExtension;
use Orba\Magento2Codegen\Service\Twig\TwigToSchema;
use Orba\Magento2Codegen\Service\TwigTemplateProcessor;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;
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
            new TwigToSchema(),
            new FiltersExtension()
        );
    }

    public function testGetPropertiesInTextReturnsEmptyArrayIfThereAreNoPropertiesInText(): void
    {
        $result = $this->twigTemplateProcessor->getPropertiesInText('no properties');
        $this->assertSame([], $result);
    }

    public function testGetPropertiesInTextReturnsArrayWithSimplePropertiesFoundInText(): void
    {
        $result = $this->twigTemplateProcessor->getPropertiesInText('i {{ like }} {{ nice }} properties');
        $this->assertSame(['like', 'nice'], $result);
    }

    public function testGetPropertiesInTextReturnsArrayWithPropertiesFoundInIfStatementInText(): void
    {
        $result = $this->twigTemplateProcessor
            ->getPropertiesInText('some {% if text==\'string\' %}property{% endif %}');
        $this->assertSame(['text'], $result);
    }

    public function testGetPropertiesInTextReturnsArrayWithPropertiesFoundInIfStatementBodyInText(): void
    {
        $result = $this->twigTemplateProcessor
            ->getPropertiesInText('some {% if 1==1 %}{{ text }}{% endif %}');
        $this->assertSame(['text'], $result);
    }

    public function testGetPropertiesInTextReturnsArrayWithPropertiesFoundInElseIfStatementInText(): void
    {
        $result = $this->twigTemplateProcessor
            ->getPropertiesInText('some {% if 1==2 %}foo{% elseif text==\'string\' %}bar{% endif %}');
        $this->assertSame(['text'], $result);
    }

    public function testGetPropertiesInTextReturnsArrayWithPropertiesFoundInElseIfStatementBodyInText(): void
    {
        $result = $this->twigTemplateProcessor
            ->getPropertiesInText('some {% if 1==2 %}foo{% elseif 1==1 %}{{ text }}{% endif %}');
        $this->assertSame(['text'], $result);
    }

    public function testGetPropertiesInTextReturnsArrayWithPropertiesFoundInForStatementInText(): void
    {
        $result = $this->twigTemplateProcessor
            ->getPropertiesInText('{% for element in elements %}{{ element }} {% endfor %}');
        $this->assertSame(['elements'], $result);
    }

    public function testGetPropertiesInTextReturnsArrayWithPropertiesFoundInForStatementBodyInText(): void
    {
        $result = $this->twigTemplateProcessor
            ->getPropertiesInText('{% for i in 0..10 %}{{ text }} {% endfor %}');
        $this->assertSame(['text'], $result);
    }

    public function testReplacePropertiesInTextReturnsSameStringIfPropertyBagIsEmptyAndTextHasNoProperties(): void
    {
        $result = $this->twigTemplateProcessor
            ->replacePropertiesInText('some text', new TemplatePropertyBag());
        $this->assertSame('some text', $result);
    }

    public function testReplacePropertiesInTextReturnsProcessedTemplateWithSimpleProperty(): void
    {
        $propertyBag = new TemplatePropertyBag();
        $propertyBag['text'] = 'string';
        $result = $this->twigTemplateProcessor
            ->replacePropertiesInText('some {{ text }}', $propertyBag);
        $this->assertSame('some string', $result);
    }

    public function testReplacePropertiesInTextReturnsProcessedTemplateWithIfStatement(): void
    {
        $propertyBag = new TemplatePropertyBag();
        $propertyBag['text'] = 'string';
        $result = $this->twigTemplateProcessor
            ->replacePropertiesInText('some {% if text==\'string\' %}text{% endif %}', $propertyBag);
        $this->assertSame('some text', $result);
    }

    public function testReplacePropertiesInTextReturnsProcessedTemplateWithElseStatement(): void
    {
        $propertyBag = new TemplatePropertyBag();
        $propertyBag['text'] = 'string';
        $result = $this->twigTemplateProcessor
            ->replacePropertiesInText('some {% if text==\'foo\' %}bar{% else %}text{% endif %}', $propertyBag);
        $this->assertSame('some text', $result);
    }

    public function testReplacePropertiesInTextReturnsProcessedTemplateWithElseIfStatement(): void
    {
        $propertyBag = new TemplatePropertyBag();
        $propertyBag['text'] = 'string';
        $result = $this->twigTemplateProcessor
            ->replacePropertiesInText('some {% if text==\'foo\' %}bar{% elseif text==\'string\' %}text{% endif %}', $propertyBag);
        $this->assertSame('some text', $result);
    }

    public function testReplacePropertiesInTextReturnsProcessedTemplateWithForStatement(): void
    {
        $propertyBag = new TemplatePropertyBag();
        $propertyBag['elements'] = ['foo', 'bar'];
        $result = $this->twigTemplateProcessor
            ->replacePropertiesInText('{% for element in elements %}{{ element }} {% endfor %}', $propertyBag);
        $this->assertSame('foo bar ', $result);
    }

    public function testReplacePropertiesInTextReturnsProcessedTemplateWithNestedProperty(): void
    {
        $propertyBag = new TemplatePropertyBag();
        $propertyBag['user'] = ['firstname' => 'Foo', 'lastname' => 'Bar'];
        $result = $this->twigTemplateProcessor
            ->replacePropertiesInText('My name is {{ user.firstname }} {{ user.lastname }}.', $propertyBag);
        $this->assertSame('My name is Foo Bar.', $result);
    }

    public function testReplacePropertiesInTextReturnsProcessedTemplateWithUpperFilteredProperty(): void
    {
        $propertyBag = new TemplatePropertyBag();
        $propertyBag['text'] = 'string';
        $result = $this->twigTemplateProcessor
            ->replacePropertiesInText('some {{ text|upper }}', $propertyBag);
        $this->assertSame('some STRING', $result);
    }

    public function testReplacePropertiesInTextThrowsExceptionIfDisallowedTagWasUsed(): void
    {
        $this->expectException(SecurityError::class);
        $this->twigTemplateProcessor
            ->replacePropertiesInText('{% apply upper %}This text becomes uppercase{% endapply %}', new TemplatePropertyBag());
    }

    public function testReplacePropertiesInTextThrowsExceptionIfDisallowedFilterWasUsed(): void
    {
        $this->expectException(SecurityError::class);
        $this->twigTemplateProcessor
            ->replacePropertiesInText('hello {{ \'world\'|trim }}', new TemplatePropertyBag());
    }
}