<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\FileMerger;

use InvalidArgumentException;
use Orba\Magento2Codegen\Service\FileMerger\XmlMerger;
use Orba\Magento2Codegen\Service\Magento\ConfigMergerFactory;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use SimpleXMLElement;

class XmlMergerTest extends TestCase
{
    /**
     * @var XmlMerger
     */
    private $xmlMerger;

    public function setUp(): void
    {
        $this->xmlMerger = new XmlMerger(new ConfigMergerFactory());
    }

    public function testMergeThrowsExceptionIfOldContentIsNotValidXml(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->xmlMerger->merge('foo', '<root></root>');
    }

    public function testMergeThrowsExceptionIfNewContentIsNotValidXml(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->xmlMerger->merge('<root></root>', 'bar');
    }

    public function testMergeThrowsExceptionIfRootNodesAreDifferent(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->xmlMerger->merge('<foo></foo>', '<bar></bar>');
    }

    public function testMergeReturnsSecondXmlIfBothXmlsHaveJustTextStringInRootNode(): void
    {
        $result = $this->xmlMerger->merge('<foo>bar</foo>', '<foo>baz</foo>');
        $xml = new SimpleXMLElement($result);
        $this->assertSame('foo', $xml->getName());
        $this->assertSame('baz', (string) $xml);
    }

    public function testMergeReturnsXmlWithOneChildNodeIfBothXmlsHaveJustOneChildNodeInRootNodeAndTheyAreTheSame(): void
    {
        $result = $this->xmlMerger->merge('<foo><hello /></foo>', '<foo><hello /></foo>');
        $xml = new SimpleXMLElement($result);
        $children = $xml->children();
        $this->assertSame(1, count($children));
        $this->assertSame('hello', $children[0]->getName());
    }

    public function testMergeReturnsXmlWithTwoChildrenNodesIfBothXmlsHaveJustOneChildNodeInRootNodeAndTheyAreDifferent(): void
    {
        $result = $this->xmlMerger->merge('<foo><hello /></foo>', '<foo><world /></foo>');
        $xml = new SimpleXMLElement($result);
        $names = ['hello', 'world'];
        $children = $xml->children();
        $this->assertSame(2, count($children));
        foreach ($children as $child) {
            $this->assertSame('', (string) $child);
            $names = array_diff($names, [$child->getName()]);
        }
        $this->assertSame([], $names);
    }

    public function testMergeReturnsSecondXmlIfBothXmlsHaveJustOneChildNodeInRootNodeAndTheyHaveSameNamesButDifferentIdArgument(): void
    {
        $result = $this->xmlMerger
            ->merge('<foo><hello id="1" /></foo>', '<foo><hello id="2" /></foo>');
        $xml = new SimpleXMLElement($result);
        $children = $xml->children();
        $this->assertSame(1, count($children));
        $this->assertSame('2', (string) $children[0]['id']);
    }

    public function testMergeReturnsXmlWithTwoChildNodesIfBothXmlsHaveJustOneChildNodeInRootNodeAndTheyHaveSameNamesButDifferentIdArgumentAndIdAttributeWasSet(): void
    {
        $this->xmlMerger->setParams(['idAttributes' => ['/foo/hello' => 'id']]);
        $result = $this->xmlMerger
            ->merge('<foo><hello id="1" /></foo>', '<foo><hello id="2" /></foo>');
        $xml = new SimpleXMLElement($result);
        $ids = ['1', '2'];
        $children = $xml->children();
        $this->assertSame(2, count($children));
        foreach ($children as $child) {
            $ids = array_diff($ids, [(string) $child['id']]);
        }
        $this->assertSame([], $ids);
    }

    public function testMergeReturnsSecondXmlIfBothXmlsHaveJustOneChildNodeInRootNodeAndTheyHaveSameNamesButDifferentTypeArgument(): void
    {
        $this->xmlMerger->setParams(['typeAttributeName' => 'type']);
        $result = $this->xmlMerger->merge('<foo><hello type="string">world</hello></foo>', '<foo><hello type="int">7</hello></foo>');
        $xml = new SimpleXMLElement($result);
        $children = $xml->children();
        $this->assertSame(1, count($children));
        $this->assertSame('int', (string) $children[0]['type']);
        $this->assertSame('7', (string) $children[0]);
    }
}