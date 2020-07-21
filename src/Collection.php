<?php
namespace CsvMap;

use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\CSV\RowIterator;
use Box\Spout\Reader\CSV\Sheet;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use CsvMap\Interfaces\CsvCollectionInterface;

final class Collection implements CsvCollectionInterface
{
    /**
     * @var RowIterator
     */
    protected $sheet;

    protected $items = [];

    protected $headers = [];

    public function __construct(string $filePath)
    {
        try {
            $reader = (ReaderEntityFactory::createCSVReader());
            $reader->open($filePath);
            $this->sheet = $reader->getSheetIterator()->current()->getRowIterator();
            $this->setHeaders($this->sheet);
            $this->setItems($this->sheet);
            $reader->close();
        } catch (IOException $exception) {
            // Do something on errors ?
        } catch (ReaderNotOpenedException $exception) {
            // Do something on errors ?
        }
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return array_search($name, $this->headers);
    }

    public function extract(string $headerName, bool $removeDuplicated = false): array
    {
        $extracted = array_reduce($this->items, function($items, $item) use($headerName, $removeDuplicated){
            if (!array_key_exists($headerName, $item)) {
                throw new \InvalidArgumentException("${headerName} not found on items from csv files.");
            }

            if ($removeDuplicated) {
                $items[$item[$headerName]] = $item[$headerName];
            } else {
                $items[] = $item[$headerName];
            }

            return $items;
        }, []);

        if ($removeDuplicated) {
            return array_keys($extracted);
        }

        return $extracted;
    }

    public function groupBy(string $headerName): array
    {
        return array_reduce($this->items, function($items, $item) use($headerName){

            if (!array_key_exists($headerName, $item)) {
                throw new \InvalidArgumentException("${headerName} not found on items from csv files.");
            }

            if (!array_key_exists($item[$headerName], $items)) {
                $items[$item[$headerName]] = [];
            }

            array_push($items[$item[$headerName]], $item);

            return $items;
        }, []);
    }

    public function each(callable $func): void
    {
        array_map(function($item) use($func){
            return $func($item);
        }, $this->items);
    }

    public function isEmpty(): bool
    {
        return !(count($this->items) > 0);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public static function factory(string $filePath): CsvCollectionInterface
    {
        return new static($filePath);
    }

    private function setHeaders(RowIterator $row)
    {
        $row->next();
        $this->headers = $row->current()->toArray();
    }

    private function setItems(RowIterator $row)
    {
        $row->next();
        while ($row->valid()) {
            $this->items[] = array_combine($this->headers, $row->current()->toArray());
            $row->next();
        }
    }

}