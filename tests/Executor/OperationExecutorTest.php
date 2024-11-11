<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Tests\Executor;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ubeliakou\OneTimeOperationSdk\Inventory\Collection\OperationCollection;
use Ubeliakou\OneTimeOperationSdk\Executor\Dto\ExecutionContext;
use Ubeliakou\OneTimeOperationSdk\Executor\Exception\ExecutionException;
use Ubeliakou\OneTimeOperationSdk\Executor\OperationExecutor;
use Ubeliakou\OneTimeOperationSdk\Inventory\OperationInventory;
use Ubeliakou\OneTimeOperationSdk\Operation\OperationInterface;
use Ubeliakou\OneTimeOperationSdk\Registry\Exception\RegistryExceptionFactory;
use Ubeliakou\OneTimeOperationSdk\Registry\RegistryInterface;
use Ubeliakou\OneTimeOperationSdk\Tests\Mocked\FakeOperationFactory;

class OperationExecutorTest extends TestCase
{
    private MockObject $mockedRegistry;
    private MockObject $mockedInventory;
    private ExecutionContext $context;
    private OperationExecutor $sut;

    protected function setUp(): void
    {
        $this->mockedRegistry = $this->createMock(RegistryInterface::class);
        $this->mockedInventory = $this->createMock(OperationInventory::class);

        $this->context = new ExecutionContext('prod', 'target');

        $this->sut = new OperationExecutor($this->mockedRegistry, $this->mockedInventory);
    }

    public function testExecuteReturnsZeroOnNoOperations(): void
    {
        $this->givenRegistryEnsuresThatTableExists();
        $this->givenInventoryFindsOperations([]);
        $this->givenRegistryReturnsExecutedOperationNames([]);

        $context = new ExecutionContext('prod', 'target');

        $actual = $this->sut->execute($context);

        $this->assertSame(0, $actual);
    }

    public function testExecuteReturnsZeroOnOperationsAreFilteredOutByContext(): void
    {
        $this->givenRegistryEnsuresThatTableExists();
        $this->givenInventoryFindsOperations([
            FakeOperationFactory::createEnvironmentAware(['test']),
            FakeOperationFactory::createProjectAware(['foreign']),
        ]);
        $this->givenRegistryReturnsExecutedOperationNames([]);

        $actual = $this->sut->execute($this->context);

        $this->assertSame(0, $actual);
    }

    public function testExecuteReturnsZeroOnOperationIsAlreadyExecuted(): void
    {
        $this->givenRegistryEnsuresThatTableExists();
        $this->givenInventoryFindsOperations([
            FakeOperationFactory::createEnvironmentAware(['prod'], '20241010202050'),
        ]);
        $this->givenRegistryReturnsExecutedOperationNames(['20241010202050']);

        $context = new ExecutionContext('prod', 'target');

        $actual = $this->sut->execute($context);

        $this->assertSame(0, $actual);
    }

    public function testExecuteThrowsExceptionOnRegistryThrowsItsOwnException(): void
    {
        $this->givenRegistryEnsuresThatTableExists();
        $this->givenInventoryFindsOperations([
            FakeOperationFactory::createEnvironmentAware(['prod'], '20241010202050'),
        ]);
        $this->givenRegistryReturnsExecutedOperationNames([]);
        $this->givenRegistryMethodMarkAsExecutedThrowsException();

        $this->expectException(ExecutionException::class);
        $this->expectExceptionCode(ExecutionException::REGISTRY_ERROR);

        $actual = $this->sut->execute($this->context);

        $this->assertSame(0, $actual);
    }

    public function testExecuteThrowsExceptionOnOperationThrowsItsOwnException(): void
    {
        $this->givenRegistryEnsuresThatTableExists();

        $mockedOperation = $this->createMock(OperationInterface::class);
        $mockedOperation->expects($this->once())->method('execute')->willThrowException(new \Exception());

        $this->givenInventoryFindsOperations([$mockedOperation]);
        $this->givenRegistryReturnsExecutedOperationNames([]);

        $this->expectException(ExecutionException::class);
        $this->expectExceptionCode(ExecutionException::UNEXPECTED_ERROR);

        $actual = $this->sut->execute($this->context);

        $this->assertSame(0, $actual);
    }

    public function testExecuteHappyPath(): void
    {
        $this->givenRegistryEnsuresThatTableExists();
        $this->givenInventoryFindsOperations([
            FakeOperationFactory::createEnvironmentAware([$this->context->environment], '20241010202050'),
            FakeOperationFactory::createProjectAware([$this->context->project], '20241010202051')
        ]);
        $this->givenRegistryReturnsExecutedOperationNames([]);
        $this->givenRegistryMarksOperationAsExecuted(['20241010202050', '20241010202051']);

        $actual = $this->sut->execute($this->context);

        $this->assertSame(2, $actual);
    }

    private function givenRegistryEnsuresThatTableExists(): void
    {
        $this->mockedRegistry->expects($this->once())->method('ensureTableExists');
    }

    /**
     * @param OperationInterface[] $operations
     */
    private function givenInventoryFindsOperations(array $operations): void
    {
        $collection = new OperationCollection($operations);
        $this->mockedInventory
            ->expects($this->once())
            ->method('getOperations')
            ->willReturn($collection);
    }

    /**
     * @param string[] $operationNames
     */
    private function givenRegistryReturnsExecutedOperationNames(array $operationNames): void
    {
        $this->mockedRegistry
            ->expects($this->once())
            ->method('getExecuted')
            ->willReturn($operationNames);
    }

    private function givenRegistryMethodMarkAsExecutedThrowsException(): void
    {
        $this->mockedRegistry
            ->expects($this->once())
            ->method('markAsExecuted')
            ->willThrowException(
                RegistryExceptionFactory::createQueryException('code')
            );
    }

    /**
     * @param string[] $operationNames
     */
    private function givenRegistryMarksOperationAsExecuted(array $operationNames): void
    {
        $matcher = $this->exactly(count($operationNames));
        $this->mockedRegistry
            ->expects($matcher)
            ->method('markAsExecuted')
            ->willReturnCallback(
                function (string $passedValue) use ($matcher, $operationNames) {
                    $expectedValue = $operationNames[$matcher->numberOfInvocations() - 1];
                    $this->assertSame($expectedValue, $passedValue);
                }
            );
    }
}