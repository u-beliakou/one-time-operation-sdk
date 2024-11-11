<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Inventory;

use Ubeliakou\OneTimeOperationSdk\Inventory\Collection\OperationCollection;
use Ubeliakou\OneTimeOperationSdk\Inventory\Dto\OperationFile;
use Ubeliakou\OneTimeOperationSdk\Inventory\Instantiator\OperationInstantiatorInterface;
use Ubeliakou\OneTimeOperationSdk\FileSystem\DirectoryInterface;
use Ubeliakou\OneTimeOperationSdk\FileSystem\Dto\File;

class OperationInventory
{
    private DirectoryInterface $directory;
    private OperationIncluder $includer;
    private OperationInstantiatorInterface $initializer;

    public function __construct(
        DirectoryInterface $directory,
        OperationIncluder $includer,
        OperationInstantiatorInterface $operationInitializer,
    ) {
        $this->directory = $directory;
        $this->includer = $includer;
        $this->initializer = $operationInitializer;
    }

    public function getOperations(): OperationCollection
    {
        $collection = new OperationCollection();

        foreach ($this->getOperationFiles() as $file) {
            $className = $this->includer->include($file);
            $operation = $this->initializer->instantiate($className);

            $collection->add($operation);
        }

        return $collection;
    }

    public function generateSubsequentFile(): OperationFile
    {
        $timestamp = date('YmdHis');
        return new OperationFile($timestamp, $this->directory->getPath());
    }

    /**
     * @return OperationFile[]
     */
    private function getOperationFiles(): array
    {
        return array_map(
            fn (File $file) => OperationFile::createFromFile($file),
            $this->directory->getFiles()
        );
    }
}