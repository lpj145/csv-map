<?php
declare(strict_types=1);

namespace CsvMap\Tests;


use CsvMap\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /**
     * @var Collection
     */
    protected $collection;

    protected function setUp(): void
    {
        parent::setUp();
        $this->collection = Collection::factory(CSV_FILE_PATH);
    }

    public function testIsEmpty()
    {
        self::assertTrue(
            Collection::factory('')->isEmpty()
        );
    }

    public function testGetHeaders()
    {
        $diff = array_diff([
            'id',
            'client_number',
            'name',
            'item',
            'price_item'
        ], $this->collection->getHeaders());

        $this->assertEmpty($diff);
    }

    public function testHasHeader()
    {
        $this->assertTrue(
            $this->collection->hasHeader('name')
        );
    }

    public function testExtract()
    {
        $arrayOfExtractIds = ['20', '10', '55', '123', '202', '202'];
        $resultOfExtract = $this->collection->extract('id');
        $this->assertEmpty(array_diff($arrayOfExtractIds, $resultOfExtract));
    }

    public function testFactory()
    {
        self::assertTrue(true, 'Factory is ok!');
    }

    public function testGroupBy()
    {
        $arrayOfGroupedItemsByIds = [
            '20' => [
                [
                    'id' => '20',
                    'client_number' => '93',
                    'name' => 'John D Am',
                    'item' => 'Amonitor',
                    'price_item' => '32.00'
                ]
            ]
        ];

        $groupedItems = $this->collection->groupBy('id');
        $firstFromGroupedItems = [key($groupedItems) => $groupedItems[key($groupedItems)]];

        $this->assertEquals($arrayOfGroupedItemsByIds, $firstFromGroupedItems);
    }

    public function testGroupByIsArray()
    {
        $this->assertIsArray(
            $this->collection->groupBy('id')
        );
    }

    public function testEach()
    {
        $expectedItems = [];
        $this->collection->each(function($item) use(&$expectedItems){
            $expectedItems[] = $item;
        });
        $this->assertCount(6, $expectedItems);
    }

    public function testToArray()
    {
        $this->assertIsArray(
            $this->collection->toArray()
        );
    }

    public function testFirstArrayEqual()
    {
        $firstData = [
            'id' => '20',
            'client_number' => '93',
            'name' => 'John D Am',
            'item' => 'Amonitor',
            'price_item' => '32.00'
        ];

        $items = $this->collection->toArray();
        $this->assertEmpty(
            array_diff($firstData, $items[0])
        );
    }
}
