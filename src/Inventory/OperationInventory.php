<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Inventory;

use Ubeliakou\OneTimeOperationSdk\Inventory\Dto\OperationFile;
use Ubeliakou\OneTimeOperationSdk\FileSystem\DirectoryInterface;

class OperationInventory
{
    private DirectoryInterface $directory;

    public function __construct(DirectoryInterface $directory)
    {
        $this->directory = $directory;
    }

    public function generateSubsequentFile(): OperationFile
    {
        $timestamp = date('YmdHis');
        return new OperationFile($timestamp, $this->directory->getPath());
    }
}