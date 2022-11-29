<?php
namespace Pyncer\Source;

interface SourcerInterface
{
    public function getName(): string;

    public function hasSource(string $sourceName): bool;
    public function getSourceNames(): iterable;

    public function has(string $key, ?iterable $sourceNames = null): bool;
    public function get(string $key, ?iterable $sourceNames = null): mixed;
}
