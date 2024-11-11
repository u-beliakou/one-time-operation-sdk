<?php

namespace Ubeliakou\OneTimeOperationSdk\Inventory\Instantiator;

use Ubeliakou\OneTimeOperationSdk\Operation\OperationInterface;

interface OperationInstantiatorInterface
{
    public function instantiate(string $className): OperationInterface;
}