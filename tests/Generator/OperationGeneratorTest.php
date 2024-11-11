<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Tests\FileSystem;

use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ubeliakou\OneTimeOperationSdk\FileSystem\Exception\FileSystemException;
use Ubeliakou\OneTimeOperationSdk\Inventory\Dto\OperationFile;
use Ubeliakou\OneTimeOperationSdk\FileSystem\DefaultDirectory;
use Ubeliakou\OneTimeOperationSdk\Generator\Exception\GenerationException;
use Ubeliakou\OneTimeOperationSdk\Generator\NamespaceResolver\NamespaceResolver;
use Ubeliakou\OneTimeOperationSdk\Generator\OperationGenerator;
use Ubeliakou\OneTimeOperationSdk\Generator\Template\OperationTemplate;
use Ubeliakou\OneTimeOperationSdk\Inventory\OperationInventory;

class OperationGeneratorTest extends TestCase
{
    const EXPECTED_NAMESPACE = 'App\\Operations\\';

    private MockObject $mockedNamespaceResolver;
    private MockObject $mockedInventory;
    private MockObject $mockedDirectory;

    private OperationFile $file;

    private OperationGenerator $sut;

    protected function setUp(): void
    {
        $this->mockedNamespaceResolver = $this->createMock(NamespaceResolver::class);
        $this->mockedInventory = $this->createMock(OperationInventory::class);
        $this->mockedDirectory = $this->createMock(DefaultDirectory::class);

        $this->file = new OperationFile('20201010303030', '/temp');

        $this->sut = new OperationGenerator(
            $this->mockedDirectory,
            $this->mockedInventory,
            $this->mockedNamespaceResolver,
            new OperationTemplate()
        );
    }

    public function testGenerateThrowsExceptionOnOperationAlreadyExists(): void
    {
        $this->givenNamespaceResolverReturnsNamespace(self::EXPECTED_NAMESPACE);
        $this->givenInventoryReturnsFile($this->file);
        $this->givenDirectoryFindsFile($this->file, true);

        $this->expectException(GenerationException::class);
        $this->expectExceptionCode(GenerationException::FILE_ALREADY_EXISTS);

        $this->sut->generate();
    }

    public function testGenerateThrowsExceptionOnDirectoryThrowsException(): void
    {
        $this->givenNamespaceResolverReturnsNamespace(self::EXPECTED_NAMESPACE);
        $this->givenInventoryReturnsFile($this->file);
        $this->givenDirectoryFindsFile($this->file, false);
        $this->givenDirectoryThrowsExceptionOnSavingFile($this->file);

        $this->expectException(GenerationException::class);
        $this->expectExceptionCode(GenerationException::FILE_SYSTEM_ERROR);

        $this->sut->generate();
    }

    public function testGenerateHappyPath(): void
    {
        $this->givenNamespaceResolverReturnsNamespace(self::EXPECTED_NAMESPACE);
        $this->givenInventoryReturnsFile($this->file);
        $this->givenDirectoryFindsFile($this->file, false);
        $this->givenDirectorySavesFile($this->file);

        $operationFile = $this->sut->generate();

        $this->assertInstanceOf(OperationFile::class, $operationFile);
    }

    private function givenNamespaceResolverReturnsNamespace(string $namespace): void
    {
        $this->mockedNamespaceResolver
            ->expects($this->once())
            ->method('getOperationNamespace')
            ->willReturn($namespace);
    }

    private function givenInventoryReturnsFile(OperationFile $file): void
    {
        $this->mockedInventory
            ->expects($this->once())
            ->method('generateSubsequentFile')
            ->willReturn($file);
    }

    private function givenDirectoryFindsFile(OperationFile $file, bool $returnValue): void
    {
        $this->mockedDirectory
            ->expects($this->once())
            ->method('exists')
            ->with(
                $this->assertTheSameOperationFileCallback($file),
            )
            ->willReturn($returnValue);
    }

    private function givenDirectoryThrowsExceptionOnSavingFile(OperationFile $file): void
    {
        $this->mockedDirectory
            ->expects($this->once())
            ->method('save')
            ->with(
                $this->assertTheSameOperationFileCallback($file),
                $this->anything()
            )
            ->willThrowException(new FileSystemException());
    }

    private function givenDirectorySavesFile(OperationFile $file): void
    {
        $this->mockedDirectory
            ->expects($this->once())
            ->method('save')
            ->with(
                $this->assertTheSameOperationFileCallback($file),
                $this->anything()
            );
    }

    /**
     * @return Callback<OperationFile>
     */
    private function assertTheSameOperationFileCallback(OperationFile $expectedFile): Callback
    {
        return $this->callback(
            function (OperationFile $passedFile) use ($expectedFile): bool {
                return $passedFile->filePath === $expectedFile->filePath;
            }
        );
    }
}