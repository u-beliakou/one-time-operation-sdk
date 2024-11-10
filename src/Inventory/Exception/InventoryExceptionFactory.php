<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Inventory\Exception;

class InventoryExceptionFactory
{
    public static function createUnexpectedFilenameFormatException(string $filename): InventoryException
    {
        return new InventoryException(
            "The operation directory contains a file with unexpected name format: {$filename}",
            InventoryException::UNEXPECTED_FILENAME_FORMAT,
        );
    }
}