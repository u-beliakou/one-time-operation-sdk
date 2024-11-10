<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\FileSystem;

use Exception;
use Ubeliakou\OneTimeOperationSdk\FileSystem\Dto\File;
use Ubeliakou\OneTimeOperationSdk\FileSystem\Exception\FileSystemException;
use Ubeliakou\OneTimeOperationSdk\FileSystem\Exception\FileSystemExceptionFactory;

class DefaultDirectory implements DirectoryInterface
{
    private string $outputPath;

    public function __construct(string $outputPath)
    {
        $this->outputPath = rtrim($outputPath, '/');
    }

    /**
     * @return File[]
     * @throws Exception
     */
    public function getFiles(): array
    {
        $this->ensureDirectoryExists();

        $ret = [];
        foreach ($this->scanDir() as $file) {
            $ret[] = new File($file, $this->outputPath);
        }

        return $ret;
    }

    /**
     * @throws FileSystemException
     */
    public function save(File $file, string $content): void
    {
        $this->ensureDirectoryExists();

        if (file_put_contents($file->filePath, $content) === false) {
            throw FileSystemExceptionFactory::createUnableToWriteFile($file->filePath);
        }
    }

    public function exists(File $file): bool
    {
        return file_exists($file->filePath);
    }

    public function getPath(): string
    {
        return $this->outputPath;
    }

    /**
     * @throws FileSystemException
     */
    private function ensureDirectoryExists(): void
    {
        if (!is_dir($this->outputPath)) {
            if (mkdir($this->outputPath, 0777, true) === false) {
                throw FileSystemExceptionFactory::createUnableToCreateDirectory($this->outputPath);
            }
        }
    }

    /**
     * @return string[]
     * @throws FileSystemException
     */
    private function scanDir(): array
    {
        $files = scandir($this->outputPath);
        if ($files === false) {
            throw FileSystemExceptionFactory::createUnableToAccessDirectory($this->outputPath);
        }

        return array_diff($files, ['.', '..']);
    }
}