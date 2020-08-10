<?php

namespace Orba\Magento2Codegen\Test\Unit\Service;

use Orba\Magento2Codegen\Model\InputPropertyInterface;
use Orba\Magento2Codegen\Service\PropertyDependencyChecker;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\PropertyBag;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;

class PropertyDependencyCheckerTest extends TestCase
{
    /**
     * @var PropertyDependencyChecker
     */
    private $propertyDependencyChecker;

    /**
     * @var MockObject|InputPropertyInterface
     */
    private $propertyMock;

    /**
     * @var PropertyBag
     */
    private $propertyBag;

    public function setUp(): void
    {
        $this->propertyDependencyChecker = new PropertyDependencyChecker();
        $this->propertyMock = $this->getMockBuilder(InputPropertyInterface::class)->getMockForAbstractClass();
        $this->propertyBag = new PropertyBag();
    }

    public function testAreRootConditionsMetReturnsTrueIfPropertyIsNotDependant(): void
    {
        $this->propertyMock->expects($this->any())->method('getDepend')->willReturn([]);
        $result = $this->propertyDependencyChecker->areRootConditionsMet($this->propertyMock, $this->propertyBag);
        $this->assertTrue($result);
    }

    public function testAreRootConditionsMetReturnsTrueIfThereAreNoDependencies(): void
    {
        $this->propertyMock->expects($this->any())->method('getDepend')->willReturn([]);
        $result = $this->propertyDependencyChecker->areRootConditionsMet($this->propertyMock, $this->propertyBag);
        $this->assertTrue($result);
    }

    public function testAreRootConditionsMetReturnsTrueIfThereIsScopeDependencyAndNoRootDependency(): void
    {
        $this->propertyMock->expects($this->any())->method('getDepend')->willReturn(['scope.property' => 'value']);
        $result = $this->propertyDependencyChecker->areRootConditionsMet($this->propertyMock, $this->propertyBag);
        $this->assertTrue($result);
    }

    public function testAreRootConditionsMetThrowsExceptionIfThereIsRootDependencyNotFoundInBag(): void
    {
        $this->expectException(RuntimeException::class);
        $this->propertyMock->expects($this->any())->method('getDepend')->willReturn(['notFoundProperty' => 'value']);
        $this->propertyDependencyChecker->areRootConditionsMet($this->propertyMock, $this->propertyBag);
    }

    /**
     * @dataProvider getPropertyValues
     */
    public function testAreRootConditionsMetReturnsTrueIfRootDependencyFoundInBagAndConditionIsMet($value): void
    {
        $this->propertyBag->add(['property' => $value]);
        $this->propertyMock->expects($this->any())->method('getDepend')->willReturn(['property' => $value]);
        $result = $this->propertyDependencyChecker->areRootConditionsMet($this->propertyMock, $this->propertyBag);
        $this->assertTrue($result);
    }

    public function testAreRootConditionsMetReturnsFalseIfRootDependencyFoundInBagAndConditionIsNotMet(): void
    {
        $this->propertyBag->add(['property' => 'value']);
        $this->propertyMock->expects($this->any())->method('getDepend')->willReturn(['property' => 'other value']);
        $result = $this->propertyDependencyChecker->areRootConditionsMet($this->propertyMock, $this->propertyBag);
        $this->assertFalse($result);
    }

    public function testAreRootConditionsMetReturnsTrueIfMultipleRootDependenciesFoundInBagAndAllMetConditions(): void
    {
        $this->propertyBag->add(['property' => 'value', 'foo' => 'bar']);
        $this->propertyMock->expects($this->any())->method('getDepend')
            ->willReturn(['property' => 'value', 'foo' => 'bar']);
        $result = $this->propertyDependencyChecker->areRootConditionsMet($this->propertyMock, $this->propertyBag);
        $this->assertTrue($result);
    }

    public function testAreRootConditionsMetReturnsFalseIfMultipleRootDependenciesFoundInBagButTheLatterDoesNotMetConditions(): void
    {
        $this->propertyBag->add(['property' => 'value', 'foo' => 'bar']);
        $this->propertyMock->expects($this->any())->method('getDepend')
            ->willReturn(['property' => 'value', 'foo' => 'boo']);
        $result = $this->propertyDependencyChecker->areRootConditionsMet($this->propertyMock, $this->propertyBag);
        $this->assertFalse($result);
    }

    public function testAreScopeConditionsMetReturnsTrueIfThereAreNoDependencies(): void
    {
        $this->propertyMock->expects($this->any())->method('getDepend')->willReturn([]);
        $result = $this->propertyDependencyChecker->areScopeConditionsMet('scope', $this->propertyMock, []);
        $this->assertTrue($result);
    }

    public function testAreScopeConditionsMetReturnsTrueIfThereIsRootDependencyAndNoScopeDependency(): void
    {
        $this->propertyMock->expects($this->any())->method('getDepend')->willReturn(['property' => 'value']);
        $result = $this->propertyDependencyChecker->areScopeConditionsMet('scope', $this->propertyMock, []);
        $this->assertTrue($result);
    }

    public function testAreScopeConditionsMetReturnsTrueIfThereIsScopeDependencyButScopeIsDifferentThenSpecifiedOne(): void
    {
        $this->propertyMock->expects($this->any())->method('getDepend')->willReturn(['foo.property' => 'value']);
        $result = $this->propertyDependencyChecker->areScopeConditionsMet('scope', $this->propertyMock, []);
        $this->assertTrue($result);
    }

    public function testAreScopeConditionsMetThrowsExceptionIfThereIsScopeDependencyNotFoundInValues(): void
    {
        $this->expectException(RuntimeException::class);
        $this->propertyMock->expects($this->any())->method('getDepend')
            ->willReturn(['scope.notFoundProperty' => 'value']);
        $this->propertyDependencyChecker->areScopeConditionsMet('scope', $this->propertyMock, []);
    }

    public function testAreScopeConditionsMetReturnsFalseIfScopeDependencyFoundInValuesAndConditionIsNotMet(): void
    {
        $this->propertyMock->expects($this->any())->method('getDepend')
            ->willReturn(['scope.property' => 'other value']);
        $result = $this->propertyDependencyChecker
            ->areScopeConditionsMet('scope', $this->propertyMock, ['property' => 'value']);
        $this->assertFalse($result);
    }

    /**
     * @dataProvider getPropertyValues
     */
    public function testAreScopeConditionsMetReturnsTrueIfScopeDependencyFoundInValuesAndConditionIsMet($value): void
    {
        $this->propertyMock->expects($this->any())->method('getDepend')
            ->willReturn(['scope.property' => $value]);
        $result = $this->propertyDependencyChecker
            ->areScopeConditionsMet('scope', $this->propertyMock, ['property' => $value]);
        $this->assertTrue($result);
    }

    public function testAreScopeConditionsMetReturnsTrueIfMultipleScopeDependenciesFoundInValuesAndAllMetConditions(): void
    {
        $this->propertyMock->expects($this->any())->method('getDepend')
            ->willReturn(['scope.property' => 'value', 'scope.foo' => 'bar']);
        $result = $this->propertyDependencyChecker
            ->areScopeConditionsMet('scope', $this->propertyMock, ['property' => 'value', 'foo' => 'bar']);
        $this->assertTrue($result);
    }

    public function testAreScopeConditionsMetReturnsFalseIfMultipleScopeDependenciesFoundInValuesButTheLatterDoesNotMetConditions(): void
    {
        $this->propertyMock->expects($this->any())->method('getDepend')
            ->willReturn(['scope.property' => 'value', 'scope.foo' => 'boo']);
        $result = $this->propertyDependencyChecker
            ->areScopeConditionsMet('scope', $this->propertyMock, ['property' => 'value', 'foo' => 'bar']);
        $this->assertFalse($result);
    }

    public function getPropertyValues(): array
    {
        return [
            ['value'],
            [true],
            [''],
            [null],
            [false]
        ];
    }
}
