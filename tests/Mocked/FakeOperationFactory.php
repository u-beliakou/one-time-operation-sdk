<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Tests\Mocked;

use Ubeliakou\OneTimeOperationSdk\Operation\OperationInterface;

class FakeOperationFactory
{
    public static function create(?string $name = null): OperationInterface
    {
        return new FakeOperation($name);
    }

    /**
     * @param string[] $projects
     */
    public static function createProjectAware(array $projects, ?string $name = null): OperationInterface
    {
        return (new FakeProjectAwareOperation($name))->setProjects($projects);
    }

    /**
     * @param string[] $environments
     */
    public static function createEnvironmentAware(array $environments, ?string $name = null): OperationInterface
    {
        return (new FakeEnvironmentAwareOperation($name))->setEnvironments($environments);
    }
}