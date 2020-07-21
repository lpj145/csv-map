<?php
namespace CsvMap\Interfaces;

interface CsvCollectionInterface
{
    public function getHeaders(): array;

    public function hasHeader(string $name): bool;

    public function extract(string $headerName): array;

    public function groupBy(string $headerName): array;

    public function each(callable $func): void;

    public function isEmpty(): bool;

    public function toArray(): array;

    public static function factory(string $filePath): CsvCollectionInterface;
}