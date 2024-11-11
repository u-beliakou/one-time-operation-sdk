<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Tests\Mocked;

use Ubeliakou\OneTimeOperationSdk\Operation\EnvironmentAwareOperationInterface;

class FakeEnvironmentAwareOperation extends FakeOperation implements EnvironmentAwareOperationInterface
{
    /** @var string[]  */
    private array $envs;

    /** @param string[] $envs  */
    public function setEnvironments(array $envs): self
    {
        $this->envs = $envs;

        return $this;
    }

    /** @return string[] */
    public function getTargetEnvironments(): array
    {
        return $this->envs;
    }
}