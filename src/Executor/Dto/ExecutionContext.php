<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Executor\Dto;

/**
 * @psalm-immutable
 */
class ExecutionContext
{
    public string $environment;
    public string $project;

    public function __construct(string $environment, string $project)
    {
        $this->environment = $environment;
        $this->project = $project;
    }
}