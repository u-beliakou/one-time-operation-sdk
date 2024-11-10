<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\FileSystem\Exception;

class FileSystemExceptionFactory
{
    public static function createUnableToCreateDirectory(string $directory): FileSystemException
    {
        return new FileSystemException(
            "Unable to create directory: {$directory}",
            FileSystemException::UNABLE_TO_CREATE_DIRECTORY,
        );
    }

    public static function createUnableToAccessDirectory(string $directory): FileSystemException
    {
        return new FileSystemException(
            "Unable to access the following directory: {$directory}",
            FileSystemException::UNABLE_TO_ACCESS_DIRECTORY,
        );
    }

    public static function createUnableToWriteFile(string $filePath): FileSystemException
    {
        return new FileSystemException(
            "Unable to write to file: {$filePath}",
            FileSystemException::UNABLE_TO_WRITE_FILE,
        );
    }
}