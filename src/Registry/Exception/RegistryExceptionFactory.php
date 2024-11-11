<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Registry\Exception;

use Throwable;

class RegistryExceptionFactory
{
    public static function createTableCreationException(Throwable $previous): RegistryException
    {
        return new RegistryException(
            'Failed to ensure table exists: ' . $previous->getMessage(),
            RegistryException::TABLE_CREATION_ERROR,
            $previous
        );
    }

    public static function createFetchTimestampException(Throwable $previous): RegistryException
    {
        return new RegistryException(
            'Failed to get last executed timestamp: ' . $previous->getMessage(),
            RegistryException::FETCH_TIMESTAMP_ERROR,
            $previous
        );
    }

    public static function createUpdateTimestampException(Throwable $previous): RegistryException
    {
        return new RegistryException(
            'Failed to update last executed timestamp: ' . $previous->getMessage(),
            RegistryException::UPDATE_TIMESTAMP_ERROR,
            $previous
        );
    }

    public static function createQueryException(string $errorCode): RegistryException
    {
        return new RegistryException(
            'Failed to execute a query timestamp: ' . $errorCode,
            RegistryException::QUERY_ERROR,
        );
    }
}