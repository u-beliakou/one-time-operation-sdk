<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Tests\Inventory\Instantiator;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ubeliakou\OneTimeOperationSdk\Inventory\Exception\InventoryException;
use Ubeliakou\OneTimeOperationSdk\Inventory\Instantiator\OperationInstantiator;
use Ubeliakou\OneTimeOperationSdk\Tests\Mocked\FakeOperation;

class OperationInstantiatorTest extends TestCase
{
    /** @var OperationInstantiator&MockObject */
    private MockObject $sut;

    protected function setUp(): void
    {
        $this->sut = $this->getMockBuilder(OperationInstantiator::class)
            ->onlyMethods(['supports', 'configure'])
            ->getMock();
    }

    public function testInstantiateThrowsExceptionOnOperationIsNotSupported(): void
    {
        $this->givenSutSupportsOperation(FakeOperation::class, false);
        $this->expectException(InventoryException::class);
        $this->expectExceptionCode(InventoryException::OPERATION_NOT_SUPPORTED);

        $this->sut->instantiate(FakeOperation::class);
    }

    public function testInstantiateHappyPath(): void
    {
        $this->givenSutSupportsOperation(FakeOperation::class, true);
        $this->givenSutConfiguresOperation(FakeOperation::class);

        $actual = $this->sut->instantiate(FakeOperation::class);

        $this->assertInstanceOf(FakeOperation::class, $actual);
    }

    private function givenSutSupportsOperation(string $operationClassName, bool $returnValue): void
    {
        $this->sut
            ->expects($this->once())
            ->method('supports')
            ->with(
                $this->isInstanceOf($operationClassName)
            )
            ->willReturn($returnValue);
    }

    private function givenSutConfiguresOperation(string $operationClassName): void
    {
        $this->sut
            ->expects($this->once())
            ->method('configure')
            ->with(
                $this->isInstanceOf($operationClassName)
            )
            ->willReturnArgument(0);
    }
}