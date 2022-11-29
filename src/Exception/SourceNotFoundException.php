<?php
namespace Pyncer\Source\Exception;

use Pyncer\Exception\RuntimeException;
use Throwable;

class SourceNotFoundException extends RuntimeException
{
    protected string $sourceName;

    public function __construct(
        string $sourceName,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $this->sourceName = $sourceName;

        parent::__construct(
            'The specified source, ' . $sourceName . ', was not found.',
            $code,
            $previous
        );
    }

    public function getSourceName(): string
    {
        return $this->sourceName;
    }
}
