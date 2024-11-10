<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Inventory\Dto;

use Ubeliakou\OneTimeOperationSdk\Inventory\Exception\InventoryException;
use Ubeliakou\OneTimeOperationSdk\Inventory\Exception\InventoryExceptionFactory;
use Ubeliakou\OneTimeOperationSdk\FileSystem\Dto\File;

/**
 * @psalm-immutable
 */
final class OperationFile extends File
{
    public string $timestamp;
    public string $className;

    public function __construct(string $timestamp, string $directoryPath)
    {
        $this->timestamp = $timestamp;
        $this->className = "Operation{$this->timestamp}";

        parent::__construct("{$this->className}.php", $directoryPath);
    }

    /**
     * @throws InventoryException
     */
    public static function createFromFile(File $file): self
    {
        if (preg_match('/Operation(\d{14})\.php$/', $file->fileName, $matches)) {
            $timestamp = $matches[1];

            return new OperationFile($timestamp, $file->directoryPath);
        }

        throw InventoryExceptionFactory::createUnexpectedFilenameFormatException($file->fileName);
    }
}