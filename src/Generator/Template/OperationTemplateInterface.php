<?php

namespace Ubeliakou\OneTimeOperationSdk\Generator\Template;

interface OperationTemplateInterface
{
    public function compile(string $namespace, string $className, string $operationName): string;
}