<?php
namespace Pyncer\Source;

use Pyncer\Source\Exception\SourceNotFoundException;
use Pyncer\Source\SourceInterface;
use Pyncer\Source\SourceMapInterface;

abstract class AbstractSourcer implements SourcerInterface
{
    public function __construct(
        protected string $name,
        protected SourceMapInterface $sourceMap
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    protected function getSourceMap(): SourceMapInterface
    {
        return $this->sourceMap;
    }

    public function hasSource(string $sourceName): bool
    {
        return $this->getSourceMap()->has($sourceName);
    }

    public function getSourceNames(): iterable
    {
        return $this->getSourceMap()->getKeys();
    }

    public function has(string $key, ?iterable $sourceNames = null): bool
    {
        try {
            $value = $this->get($key, $sourceNames);
        } catch (SourceNotFoundException $exception) {
            $value = null;
        }

        return ($value !== null);
    }

    public function get(string $key, ?iterable $sourceNames = null): mixed
    {
        // Only throw source not found if source map doesn't have the source
        if ($sourceNames !== null) {
            foreach ($sourceNames as $sourceName) {
                if (!$this->hasSource($sourceName)) {
                    throw new SourceNotFoundException($sourceName);
                }
            }
        }

        $sourceNames ??= $this->getSourceNames();

        $value = null;

        foreach ($sourceNames as $sourceName) {
            $value = $this->getSourceValue($sourceName, $key);

            if ($value !== null) {
                break;
            }
        }

        return $value;
    }

    abstract protected function getSourceValue(
        string $sourceName,
        string $key
    ): mixed;
}
