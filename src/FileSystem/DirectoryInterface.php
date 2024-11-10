<?php

namespace Ubeliakou\OneTimeOperationSdk\FileSystem;

use Ubeliakou\OneTimeOperationSdk\FileSystem\Dto\File;
use Ubeliakou\OneTimeOperationSdk\FileSystem\Exception\FileSystemException;

interface DirectoryInterface
{
    /** @return File[] */
    public function getFiles(): array;

    /** @throws FileSystemException */
    public function save(File $file, string $content): void;
    public function exists(File $file): bool;
    public function getPath(): string;
}