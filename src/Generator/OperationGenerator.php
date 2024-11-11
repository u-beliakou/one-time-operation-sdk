<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Generator;

use Ubeliakou\OneTimeOperationSdk\Inventory\Dto\OperationFile;
use Ubeliakou\OneTimeOperationSdk\Inventory\OperationInventory;
use Ubeliakou\OneTimeOperationSdk\FileSystem\DirectoryInterface;
use Ubeliakou\OneTimeOperationSdk\FileSystem\Exception\FileSystemException;
use Ubeliakou\OneTimeOperationSdk\Generator\Exception\GenerationExceptionFactory;
use Ubeliakou\OneTimeOperationSdk\Generator\NamespaceResolver\NamespaceResolver;
use Ubeliakou\OneTimeOperationSdk\Generator\Template\OperationTemplateInterface;

class OperationGenerator
{
    private DirectoryInterface $directory;
    private OperationInventory $inventory;
    private NamespaceResolver $namespaceResolver;
    private OperationTemplateInterface $operationTemplate;

    public function __construct(
        DirectoryInterface $directory,
        OperationInventory $inventory,
        NamespaceResolver $namespaceResolver,
        OperationTemplateInterface $operationTemplate
    ) {
        $this->directory = $directory;
        $this->inventory = $inventory;
        $this->namespaceResolver = $namespaceResolver;
        $this->operationTemplate = $operationTemplate;
    }

    public function generate(): OperationFile
    {
        $namespace = $this->namespaceResolver->getOperationNamespace();
        $operationFile = $this->inventory->generateSubsequentFile();

        if ($this->directory->exists($operationFile)) {
            throw GenerationExceptionFactory::createFileAlreadyExistsException(
                $operationFile->fileName
            );
        }

        try {
            $template = $this->operationTemplate->compile(
                $namespace,
                $operationFile->className,
                $operationFile->timestamp,
            );

            $this->directory->save($operationFile, $template);
        } catch (FileSystemException $exception) {
            throw GenerationExceptionFactory::createFileSystemException($exception);
        } catch (\Throwable $exception) {
            throw GenerationExceptionFactory::createUnknownException($exception);
        }

        return $operationFile;
    }
}