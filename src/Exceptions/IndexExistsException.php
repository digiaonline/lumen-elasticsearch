<?php

namespace Nord\Lumen\Elasticsearch\Exceptions;

/**
 * Class IndexExistsException
 * @package Nord\Lumen\Elasticsearch\Exceptions
 */
class IndexExistsException extends Exception
{

    /**
     * @inheritdoc
     */
    public function __construct($index, $code = 0, \Exception $previous = null)
    {
        parent::__construct(sprintf('The index %s already exists', $index), $code, $previous);
    }

}
