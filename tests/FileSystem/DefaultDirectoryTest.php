<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Tests\FileSystem;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Ubeliakou\OneTimeOperationSdk\FileSystem\DefaultDirectory;
use Ubeliakou\OneTimeOperationSdk\FileSystem\Dto\File;
use Ubeliakou\OneTimeOperationSdk\FileSystem\Exception\FileSystemException;

class DefaultDirectoryTest extends TestCase
{
    const TARGET_DIRECTORY_NAME = 'operations';

    private vfsStreamDirectory $root;
    private DefaultDirectory $sut;

    protected function setUp(): void
    {
        $this->root = vfsStream::setup();
        $this->sut = new DefaultDirectory($this->root->url() . '/' . self::TARGET_DIRECTORY_NAME);
    }

    public function testGetFilesThrowsExceptionOnUnableToCreateDirectory(): void
    {
        $this->root->chmod(0000);

        $this->expectException(FileSystemException::class);
        $this->expectExceptionCode(FileSystemException::UNABLE_TO_CREATE_DIRECTORY);

        $this->sut->getFiles();
    }

    public function testGetFilesThrowsExceptionOnUnableToAccessDirectory(): void
    {
        $this->createOperationDirectory(0000);

        $this->expectException(FileSystemException::class);
        $this->expectExceptionCode(FileSystemException::UNABLE_TO_ACCESS_DIRECTORY);

        $this->sut->getFiles();
    }

    public function testGetFilesHappyPath(): void
    {
        $operationDir = $this->createOperationDirectory(0777);

        $expectedFileNames = [
            '1.php',
            '2.php',
            '3.php'
        ];

        foreach ($expectedFileNames as $fileName) {
            vfsStream::newFile($fileName)->at($operationDir);
        }

        $actualOperationFileNames = array_map(
            fn (File $file) => $file->fileName,
            $this->sut->getFiles()
        );

        $this->assertEquals($expectedFileNames, $actualOperationFileNames);
    }

    public function testExistsReturnFalseOnFileDoesNotExist(): void
    {
        $file = $this->createFile('1.php');
        $this->assertFalse($this->sut->exists($file));
    }

    public function testExistsReturnTrueOnFileExists(): void
    {
        $operationDir = $this->createOperationDirectory(0777);

        $file = $this->createFile('1.php');
        vfsStream::newFile('1.php')->at($operationDir);

        $this->assertTrue($this->sut->exists($file));
    }

    public function testSaveThrowsExceptionOnUnableToCreateDirectory(): void
    {
        $this->root->chmod(0000);
        $file = $this->createFile('1.php');

        $this->expectException(FileSystemException::class);
        $this->expectExceptionCode(FileSystemException::UNABLE_TO_CREATE_DIRECTORY);

        $this->sut->save($file, '');
    }

    public function testSaveThrowsExceptionOnUnableToWriteFile(): void
    {
        $this->createOperationDirectory(0000);
        $file = $this->createFile('1.php');

        $this->expectException(FileSystemException::class);
        $this->expectExceptionCode(FileSystemException::UNABLE_TO_WRITE_FILE);

        $this->sut->save($file, '');
    }

    public function testSaveHappyPath(): void
    {
        $this->createOperationDirectory(0777);
        $file = $this->createFile('1.php');

        $this->sut->save($file, '');

        $this->assertFileExists($file->filePath);
    }

    private function createOperationDirectory(int $permissions): vfsStreamDirectory
    {
        return vfsStream::newDirectory(self::TARGET_DIRECTORY_NAME, $permissions)->at($this->root);
    }

    private function createFile(string $filename): File
    {
        return new File($filename, $this->root->url() . '/' . self::TARGET_DIRECTORY_NAME);
    }
}