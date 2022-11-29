<?php
namespace Pyncer\Source\Exception;

use Pyncer\Exception\RuntimeException;
use Throwable;

class SourcerNotFoundException extends RuntimeException
{
    protected string $sourcerName;

    public function __construct(
        string $sourcerName,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $this->sourcerName = $sourcerName;

        parent::__construct(
            'The specified sourcer, ' . $sourcerName . ', was not found.',
            $code,
            $previous
        );
    }

    public function getSourcerName(): string
    {
        return $this->sourcerName;
    }
}
