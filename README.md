#### CSV-Map
This lib have goal to manage some csv files with a filter methods, it is built top of [box/soap](https://github.com/box/spout),
for more detailed examples, see: [Tests Examples](https://github.com/lpj145/csv-map/tree/main/tests).

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

``combine`` - mount array by value of index by row and intersect values from some headers.

``extract`` - extract only columns of values, with or without duplicated values.

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
 
$csvCollection->combine('id', 'name', 'id', ...): array;
    
$csvCollection->groupBy('id'): array;

$csvCollection->extract('id', true): array // Last param remove duplicated registers

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