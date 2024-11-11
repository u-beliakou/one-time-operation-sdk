<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Generator\Exception;

use Ubeliakou\OneTimeOperationSdk\FileSystem\Exception\FileSystemException;

class GenerationExceptionFactory
{
    public static function createFileAlreadyExistsException(string $filename): GenerationException
    {
        return new GenerationException(
            sprintf('The operation file "%s" already exists.', $filename),
            GenerationException::FILE_ALREADY_EXISTS
        );
    }

    public static function createFileSystemException(FileSystemException $exception): GenerationException
    {
        return new GenerationException(
            'An error occurred while interacting with the file system: ' . $exception->getMessage(),
            GenerationException::FILE_SYSTEM_ERROR,
            $exception
        );
    }

    public static function createUnknownException(\Throwable $exception): GenerationException
    {
        return new GenerationException(
            'An unexpected error occurred: ' . $exception->getMessage(),
            GenerationException::UNKNOWN_ERROR,
            $exception
        );
    }
}