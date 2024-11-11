<?php

namespace Ubeliakou\OneTimeOperationSdk\Executor\Exception;

use Ubeliakou\OneTimeOperationSdk\Registry\Exception\RegistryException;

class ExecutionExceptionFactory
{
    public static function createRegistryException(RegistryException $previous): ExecutionException
    {
        return new ExecutionException(
            'Registry error: ' . $previous->getMessage(),
            ExecutionException::REGISTRY_ERROR,
            $previous
        );
    }

    public static function createUnexpectedException(\Throwable $previous): ExecutionException
    {
        return new ExecutionException(
            'An unexpected error occurred: ' . $previous->getMessage(),
            ExecutionException::UNEXPECTED_ERROR,
            $previous
        );
    }
}