<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Operation;

interface ProjectAwareOperationInterface
{
    /** @return string[] */
    public function getTargetProjects(): array;
}