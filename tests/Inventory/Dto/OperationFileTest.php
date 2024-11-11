<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Tests\Inventory\Dto;

use PHPUnit\Framework\TestCase;
use Ubeliakou\OneTimeOperationSdk\Inventory\Dto\OperationFile;
use Ubeliakou\OneTimeOperationSdk\Inventory\Exception\InventoryException;
use Ubeliakou\OneTimeOperationSdk\FileSystem\Dto\File;

class OperationFileTest extends TestCase
{
    public function testCreateFromFileThrowsExceptionOnUnexpectedFilenameFormat(): void
    {
        $file = new File('Opera1000.php', '/root');

        $this->expectException(InventoryException::class);
        $this->expectExceptionCode(InventoryException::UNEXPECTED_FILENAME_FORMAT);

        OperationFile::createFromFile($file);
    }

    public function testCreateFromFileHappyPath(): void
    {
        $timestamp = '20241010202050';
        $filename = "Operation{$timestamp}.php";

        $file = new File("Operation{$timestamp}.php", '/root');

        $actual = OperationFile::createFromFile($file);

        $this->assertEquals($timestamp, $actual->timestamp);
        $this->assertEquals($filename, $actual->fileName);
    }
}