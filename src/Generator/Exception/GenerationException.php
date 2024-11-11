<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Generator\Exception;

class GenerationException extends \RuntimeException
{
    public const FILE_ALREADY_EXISTS = 1;
    public const FILE_SYSTEM_ERROR = 2;
    public const UNKNOWN_ERROR = 3;
}