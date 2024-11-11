<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Registry\Exception;

use RuntimeException;

class RegistryException extends RuntimeException
{
    public const TABLE_CREATION_ERROR = 1;
    public const FETCH_TIMESTAMP_ERROR = 2;
    public const UPDATE_TIMESTAMP_ERROR = 3;
    public const QUERY_ERROR = 4;
}