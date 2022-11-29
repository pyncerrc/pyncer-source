<?php
namespace Pyncer\Source;

use Pyncer\Source\Exception\SourceNotFoundException;
use Pyncer\Source\Exception\SourcerNotFoundException;
use Pyncer\Source\SourceDirectorInterface;
use Pyncer\Source\SourcerInterface;
use Pyncer\Source\SourceResult;

use function array_key_exists;
use function array_keys;
use function array_merge;
use function Pyncer\Array\ensure_array as pyncer_ensure_array;

class SourceDirector implements SourceDirectorInterface
{
    private array $sourcers = [];

    public function hasSourcer(string $sourcerName): bool
    {
        return array_key_exists($sourcerName, $this->sourcers);
    }
    public function getSourcer(string $sourcerName): SourcerInterface
    {
        if (!$this->hasSourcer($sourcerName)) {
            throw new SourcerNotFoundException($sourcerName);
        }

        return $this->sourcers[$sourcerName];
    }
    public function addSourcer(SourcerInterface $sourcer): static
    {
        $this->sourcers[$sourcer->getName()] = $sourcer;

        return $this;
    }

    public function getSourcers(): iterable
    {
        return $this->sourcers;
    }
    public function getSourcerNames(): iterable
    {
        return array_keys($this->sourcers);
    }

    public function hasSource(string $sourceName): bool
    {
        foreach ($this->sourcers as $sourcer) {
            if ($sourcer->hasSource($sourceName)) {
                return true;
            }
        }

        return false;
    }
    public function getSources(): iterable
    {
        $sources = [];

        foreach ($this->sourcers as $sourcer) {
            $sources = array_merge(
                $sources,
                pyncer_ensure_array($sourcer->getSources())
            );
        }

        return $sources;
    }

    public function has(
        string $key,
        ?iterable $sourcerNames = null,
        ?iterable $sourceNames = null,
    ): bool
    {
        $sourcerNames ??= $this->getSourcerNames();

        $hasValue = false;

        foreach ($sourcerNames as $sourcerName) {
            if (!array_key_exists($sourcerName, $this->sourcers)) {
                continue;
            }

            $hasValue = $this->sourcers[$sourcerName]->has(
                $key,
                $sourceNames
            );

            if ($hasValue) {
                break;
            }
        }

        return $hasValue;
    }
    public function get(
        string $key,
        ?iterable $sourcerNames = null,
        ?iterable $sourceNames = null,
    ): ?SourceResult
    {
        // Only throw source not found if no sourcers have the source
        if ($sourceNames !== null) {
            foreach ($sourceNames as $sourceName) {
                if (!$this->hasSource($sourceName)) {
                    throw new SourceNotFoundException($sourceName);
                }
            }
        }

        $sourcerNames ??= $this->getSourcerNames();
        $sourceNames ??= $this->getSourceNames();

        $value = null;
        $resultSourcerName = null;
        $resultSourceName = null;

        foreach ($sourcerNames as $sourcerName) {
            if (!array_key_exists($sourcerName, $this->sourcers)) {
                continue;
            }

            // We do one source at a time to handle excpetions
            foreach ($sourceNames as $sourceName) {
                try {
                    $value = $this->sourcers[$sourcerName]->get(
                        $key,
                        [$sourceName]
                    );
                } catch (SourceNotFoundException $exception) {
                    $value = null;
                }

                if ($value !== null) {
                    $resultSourcerName = $sourcerName;
                    $resultSourceName = $sourceName;
                    break;
                }
            }

            if ($value !== null) {
                break;
            }
        }

        if ($value === null) {
            return null;
        }

        return new SourceResult(
            $value,
            $resultSourcerName,
            $resultSourceName
        );
    }
}
