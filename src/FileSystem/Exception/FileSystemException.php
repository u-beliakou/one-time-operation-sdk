<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\FileSystem\Exception;

class FileSystemException extends \Exception
{
    public const UNABLE_TO_CREATE_DIRECTORY = 1;
    public const UNABLE_TO_WRITE_FILE = 2;
    public const UNABLE_TO_ACCESS_DIRECTORY = 3;
}