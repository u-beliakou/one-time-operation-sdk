<?php

namespace Ubeliakou\OneTimeOperationSdk\Inventory;

use Ubeliakou\OneTimeOperationSdk\Inventory\Dto\OperationFile;
use Ubeliakou\OneTimeOperationSdk\Inventory\Exception\InventoryException;
use Ubeliakou\OneTimeOperationSdk\Inventory\Exception\InventoryExceptionFactory;
use Ubeliakou\OneTimeOperationSdk\Generator\NamespaceResolver\NamespaceResolver;

class OperationIncluder
{
    private NamespaceResolver $namespaceResolver;

    public function __construct(NamespaceResolver $namespaceResolver)
    {
        $this->namespaceResolver = $namespaceResolver;
    }

    /**
     * @throws InventoryException
     */
    public function include(OperationFile $file): string
    {
        try {
            require_once $file->filePath;
        } catch (\Throwable $e) {
            throw InventoryExceptionFactory::createUnexpectedException($e);
        }

        $className = $this->namespaceResolver->getOperationNamespace() . "\\" . $file->className;

        if (!class_exists($className)) {
            throw InventoryExceptionFactory::createClassNotFoundException($className);
        }

        return $className;
    }
}