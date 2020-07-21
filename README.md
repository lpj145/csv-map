#### CSV-Map
This lib have goal to manage some csv files with a filter methods, it is built top of [box/soap](https://github.com/box/spout).

##### Requirements
    -   >= PHP7.2
    -   box/spout 3.1.0
    
##### Install
````bash
composer require mdantas/csv-map
````

##### Methods
All methods can be found at ``CsvMap\Interfaces\CsvCollectionInterface``

``getHeaders`` - Get array of headers from csv file.

``hasHeader`` - Check if header exists on csv file.

``groupBy`` - group arrays of values, the index of array is based on ``$row[headerName]``.

``extract`` - extract only columns of values.

``isEmpty`` - check if collection is empty.

``each`` - shortcut for simple callable function on each item.

``toArray`` - produces array indexed by header name with row values, like: ``[$headername => $rowValue]``

##### Examples
````php
use CsvMap\Collection;

$fileCsvPath = './filecsv';

$csvCollection = Collection::factory($fileCsvPath);

$csvCollection->getHeaders(): array;

$csvCollection->hasHeader('headerName'): bool; 
    
$csvCollection->groupBy('id')

$csvCollection->extract('id')

$csvCollection->isEmpty(): bool;

$csvCollection->each(function($item) => {
    print_r($item);
});

$csvCollection->toArray();
````

##### Tests
````bash
composer run tests
````