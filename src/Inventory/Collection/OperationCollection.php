<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Inventory\Collection;

use Ubeliakou\OneTimeOperationSdk\Executor\Dto\ExecutionContext;
use Ubeliakou\OneTimeOperationSdk\Operation\EnvironmentAwareOperationInterface;
use Ubeliakou\OneTimeOperationSdk\Operation\OperationInterface;
use Ubeliakou\OneTimeOperationSdk\Operation\ProjectAwareOperationInterface;

/**
 * @extends BaseCollection<int, OperationInterface>
 */
class OperationCollection extends BaseCollection
{
    public function filterByContext(ExecutionContext $context): OperationCollection
    {
        return $this
            ->filterByEnvironment($context->environment)
            ->filterByProject($context->project);
    }

    public function filterByEnvironment(string $environment): OperationCollection
    {
        return $this->filter(
            function (OperationInterface $operation) use ($environment) {
                if ($operation instanceof EnvironmentAwareOperationInterface) {
                    return in_array($environment, $operation->getTargetEnvironments(), true);
                }

                return true;
            }
        );
    }

    public function filterByProject(string $project): OperationCollection
    {
        return $this->filter(
            function (OperationInterface $operation) use ($project) {
                if ($operation instanceof ProjectAwareOperationInterface) {
                    return in_array($project, $operation->getTargetProjects(), true);
                }

                return true;
            }
        );
    }

    /**
     * @param string[] $names
     * @return OperationCollection
     */
    public function excludeByName(array $names): OperationCollection
    {
        return $this->filter(
            function (OperationInterface $operation) use ($names) {
                return !in_array($operation->getName(), $names, true);
            }
        );
    }

    /**
     * @return BaseCollection<int, string>
     */
    public function mapNames(): BaseCollection
    {
        return $this->map(fn(OperationInterface $operation) => $operation->getName());
    }

    public function sortByName(): OperationCollection
    {
        return $this->sort(
            fn(OperationInterface $a, OperationInterface $b) => $a->getName() <=> $b->getName()
        );
    }
}