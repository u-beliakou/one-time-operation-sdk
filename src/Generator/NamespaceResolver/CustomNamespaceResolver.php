<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Generator\NamespaceResolver;

final class CustomNamespaceResolver extends NamespaceResolver
{
    private string $namespace;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function getApplicationNamespace(): string
    {
        return $this->namespace;
    }
}