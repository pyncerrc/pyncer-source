<?php
namespace Pyncer\Source;

use Pyncer\Source\SourcerInterface;
use Pyncer\Source\SourceResult;

interface SourceDirectorInterface
{
    public function hasSourcer(string $sourcerName): bool;
    public function getSourcer(string $sourcerNamge): SourcerInterface;
    public function addSourcer(SourcerInterface $sourcer): static;

    public function getSourcers(): iterable;
    public function getSourcerNames(): iterable;

    public function hasSource(string $sourceName): bool;
    public function getSources(): iterable;

    public function has(
        string $key,
        ?iterable $sourcerNames = null,
        ?iterable $sourceNames = null,
    ): bool;
    public function get(
        string $key,
        ?iterable $sourcerNames = null,
        ?iterable $sourceNames = null,
    ): ?SourceResult;
}
