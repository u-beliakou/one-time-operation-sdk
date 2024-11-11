<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Generator\NamespaceResolver;

abstract class NamespaceResolver
{
    public abstract function getApplicationNamespace(): string;

    public function getOperationNamespace(): string
    {
        return $this->getApplicationNamespace() . '\\Operations';
    }
}