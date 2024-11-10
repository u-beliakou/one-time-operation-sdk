<?php

namespace Ubeliakou\OneTimeOperationSdk\Generator\Template;

final class OperationTemplate implements OperationTemplateInterface
{
    public function compile(string $namespace, string $className, string $operationName): string
    {
        return "<?php

namespace $namespace;

use Ubeliakou\OneTimeOperationSdk\Operation\OperationInterface;

final class $className implements OperationInterface
{
    public function getName(): string
    {
        return '$operationName';
    }

    public function execute(): void
    {
        // TODO: Implement the operation logic here.
    }
}
";
    }
}