<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Generator\Template;

interface OperationTemplateInterface
{
    public function compile(string $namespace, string $className, string $operationName): string;
}