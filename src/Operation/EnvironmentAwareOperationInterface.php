<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Operation;

interface EnvironmentAwareOperationInterface
{
    /** @return string[] */
    public function getTargetEnvironments(): array;
}