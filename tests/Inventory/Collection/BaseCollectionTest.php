<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Tests\Inventory\Collection;

use PHPUnit\Framework\TestCase;
use Ubeliakou\OneTimeOperationSdk\Inventory\Collection\BaseCollection;

class BaseCollectionTest extends TestCase
{
    public function testCountReturnsZeroOnEmptyArray(): void
    {
        $collection = new BaseCollection();
        $this->assertCount(0, $collection);
    }

    public function testCountReturnsOneOnNonEmptyArray(): void
    {
        $collection = new BaseCollection(['something']);

        $this->assertCount(1, $collection);
    }

    public function testAddHappyPath(): void
    {
        $collection = new BaseCollection();
        $collection->add('something');

        $this->assertCount(1, $collection);
    }

    public function testGetIteratorHappyPath(): void
    {
        $expectedResult = ['something', 'anything'];
        $collection = new BaseCollection($expectedResult);

        $actualResult = iterator_to_array($collection);

        $this->assertCount(2, $actualResult);
        $this->assertSame($expectedResult, $actualResult);
    }
}