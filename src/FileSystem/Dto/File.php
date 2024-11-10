<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\FileSystem\Dto;

/**
 * @psalm-immutable
 */
class File
{
    public string $fileName;
    public string $filePath;
    public string $directoryPath;

    public function __construct(string $filename, string $directoryPath)
    {
        $this->fileName =  $filename;
        $this->directoryPath = $directoryPath;
        $this->filePath = "{$directoryPath}/{$this->fileName}";
    }
}