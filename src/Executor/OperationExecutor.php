<?php

namespace Ubeliakou\OneTimeOperationSdk\Executor;

use Ubeliakou\OneTimeOperationSdk\Executor\Dto\ExecutionContext;
use Ubeliakou\OneTimeOperationSdk\Executor\Exception\ExecutionException;
use Ubeliakou\OneTimeOperationSdk\Executor\Exception\ExecutionExceptionFactory;
use Ubeliakou\OneTimeOperationSdk\Inventory\OperationInventory;
use Ubeliakou\OneTimeOperationSdk\Registry\Exception\RegistryException;
use Ubeliakou\OneTimeOperationSdk\Registry\RegistryInterface;

class OperationExecutor
{
    private RegistryInterface $registry;
    private OperationInventory $inventory;

    public function __construct(RegistryInterface $registry, OperationInventory $inventory)
    {
        $this->registry = $registry;
        $this->inventory = $inventory;
    }

    /**
     * @throws ExecutionException
     */
    public function execute(ExecutionContext $context): int
    {
        $this->registry->ensureTableExists();

        $executed = 0;
        $operations = $this->inventory
            ->getOperations()
            ->filterByContext($context)
            ->excludeByName($this->registry->getExecuted())
            ->sortByName();

        try {
            foreach ($operations as $operation) {
                $operation->execute();

                $this->registry->markAsExecuted($operation->getName());
                $executed++;
            }

        } catch (RegistryException $e) {
            throw ExecutionExceptionFactory::createRegistryException($e);
        } catch (\Throwable $e) {
            throw ExecutionExceptionFactory::createUnexpectedException($e);
        }

        return $executed;
    }
}