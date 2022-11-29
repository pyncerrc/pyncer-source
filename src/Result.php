<?php
namespace Pyncer\Source;

class SourceResult
{
    public function __construct(
        protected mixed $value,
        protected ?string $sourcerName,
        protected ?string $sourceName
    ) {}

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getSourcerName(): ?string
    {
        return $this->SourcerName;
    }

    public function getSourceName(): ?string
    {
        return $this->sourceName;
    }
}
