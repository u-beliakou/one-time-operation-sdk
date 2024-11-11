<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Operation;

interface OperationInterface
{
    public function execute(): void;

    public function getName(): string;
}