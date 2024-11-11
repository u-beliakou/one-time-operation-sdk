<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Tests\Mocked;

use Ubeliakou\OneTimeOperationSdk\Operation\ProjectAwareOperationInterface;

class FakeProjectAwareOperation extends FakeOperation implements ProjectAwareOperationInterface
{
    /** @var string[]  */
    private array $projects;

    /** @param string[] $projects */
    public function setProjects(array $projects): self
    {
        $this->projects = $projects;

        return $this;
    }

    /** @return string[] */
    public function getTargetProjects(): array
    {
        return $this->projects;
    }
}