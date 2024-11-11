<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Inventory\Exception;

class InventoryExceptionFactory
{
    public static function createClassNotFoundException(string $className): InventoryException
    {
        return new InventoryException(
            "Class $className does not exist.",
            InventoryException::CLASS_NOT_FOUND
        );
    }

    public static function createOperationIsNotSupported(
        string $instantiatorName,
        string $operationName
    ): InventoryException {
        return new InventoryException(
            "The operation `{$operationName}` is not supported by `{$instantiatorName}`.",
            InventoryException::OPERATION_NOT_SUPPORTED
        );
    }

    public static function createUnexpectedFilenameFormatException(string $filename): InventoryException
    {
        return new InventoryException(
            "The operation directory contains a file with unexpected name format: {$filename}",
            InventoryException::UNEXPECTED_FILENAME_FORMAT,
        );
    }

    public static function createUnexpectedException(\Throwable $previous): InventoryException
    {
        return new InventoryException(
            'An unexpected error occurred: ' . $previous->getMessage(),
            InventoryException::UNEXPECTED_ERROR,
            $previous
        );
    }
}