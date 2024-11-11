<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Tests\Mocked;

use Ubeliakou\OneTimeOperationSdk\Operation\OperationInterface;

class FakeOperation implements OperationInterface
{
    private string $name;

    public function __construct(?string $name = null)
    {
        $this->name = $name ?? '' . rand(1000000, 9999999);
    }

    public function execute(): void
    {
        return;
    }

    public function getName(): string
    {
        return $this->name;
    }
}