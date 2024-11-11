<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Inventory\Exception;

class InventoryException extends \Exception
{
    public const UNEXPECTED_FILENAME_FORMAT = 1;
    public const CLASS_NOT_FOUND = 2;
    public const OPERATION_NOT_SUPPORTED = 3;
    public const UNEXPECTED_ERROR = 4;
}