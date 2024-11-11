<?php

namespace Ubeliakou\OneTimeOperationSdk\Executor\Exception;

class ExecutionException extends \Exception
{
    public const REGISTRY_ERROR = 1;
    public const UNEXPECTED_ERROR = 2;
}