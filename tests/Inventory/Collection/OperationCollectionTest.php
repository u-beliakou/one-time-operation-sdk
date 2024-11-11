<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Tests\Inventory\Collection;

use PHPUnit\Framework\TestCase;
use Ubeliakou\OneTimeOperationSdk\Inventory\Collection\OperationCollection;
use Ubeliakou\OneTimeOperationSdk\Tests\Mocked\FakeOperationFactory;

class OperationCollectionTest extends TestCase
{
    public function testFilterByProjectHappyPath(): void
    {
        $collection = new OperationCollection([
            FakeOperationFactory::createProjectAware(['relevant', 'non-relevant'], 'both'),
            FakeOperationFactory::createProjectAware(['relevant'], 'relevant'),
            FakeOperationFactory::createProjectAware(['non-relevant'], 'non-relevant'),
            FakeOperationFactory::createProjectAware([], 'empty'),
            FakeOperationFactory::createEnvironmentAware(['test'], 'env'),
            FakeOperationFactory::create('global'),
        ]);

        $actual = $collection->filterByProject('relevant');

        $expected = ['both', 'relevant', 'env', 'global'];
        $this->assertCount(4, $actual);
        $this->assertEquals($expected, $actual->mapNames()->toArray());
    }

    public function testFilterByEnvironmentHappyPath(): void
    {
        $collection = new OperationCollection([
            FakeOperationFactory::createEnvironmentAware(['relevant', 'non-relevant'], 'both'),
            FakeOperationFactory::createEnvironmentAware(['relevant'], 'relevant'),
            FakeOperationFactory::createEnvironmentAware(['non-relevant'], 'non-relevant'),
            FakeOperationFactory::createEnvironmentAware([], 'empty'),
            FakeOperationFactory::createProjectAware(['project'], 'project'),
            FakeOperationFactory::create('global'),
        ]);

        $actual = $collection->filterByEnvironment('relevant');

        $expected = ['both', 'relevant', 'project', 'global'];
        $this->assertCount(4, $actual);
        $this->assertEquals($expected, $actual->mapNames()->toArray());
    }

    public function testExcludeByNameHappyPath(): void
    {
        $collection = new OperationCollection([
            FakeOperationFactory::create('pending'),
            FakeOperationFactory::create('executed'),
        ]);

        $actual = $collection->excludeByName(['executed']);

        $this->assertCount(1, $actual);
        $this->assertEquals(['pending'], $actual->mapNames()->toArray());
    }

    public function testSortByNameHappyPath(): void
    {
        $collection = new OperationCollection([
            FakeOperationFactory::create('Operation2024'),
            FakeOperationFactory::create('Operation2023'),
            FakeOperationFactory::create('Operation2025'),
        ]);

        $sortedNames = $collection->sortByName()->mapNames()->toArray();

        $expected = ['Operation2023', 'Operation2024', 'Operation2025'];
        $this->assertEquals($expected, $sortedNames);
    }
}