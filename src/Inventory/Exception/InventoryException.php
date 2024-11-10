<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Inventory\Exception;

class InventoryException extends \Exception
{
    public const UNEXPECTED_FILENAME_FORMAT = 1;
}