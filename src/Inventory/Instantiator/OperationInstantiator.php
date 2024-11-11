<?php

namespace Ubeliakou\OneTimeOperationSdk\Inventory\Instantiator;

use Ubeliakou\OneTimeOperationSdk\Inventory\Exception\InventoryException;
use Ubeliakou\OneTimeOperationSdk\Inventory\Exception\InventoryExceptionFactory;
use Ubeliakou\OneTimeOperationSdk\Operation\OperationInterface;

class OperationInstantiator implements OperationInstantiatorInterface
{
    /**
     * @throws InventoryException
     */
    public function instantiate(string $className): OperationInterface
    {
        /** @var OperationInterface $operation */
        $operation = new $className();

        if (!$this->supports($operation)) {
            throw InventoryExceptionFactory::createOperationIsNotSupported(
                $operation->getName(),
                get_class($this)
            );
        }

        return $this->configure($operation);
    }

    protected function supports(OperationInterface $operation): bool
    {
        return true;
    }

    protected function configure(OperationInterface $operation): OperationInterface
    {
        // Nothing is expected to be configured
        return $operation;
    }
}