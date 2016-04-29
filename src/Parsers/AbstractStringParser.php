<?php namespace Nord\Lumen\Elasticsearch\Parsers;

use Nord\Lumen\Elasticsearch\Exceptions\InvalidArgument;

class AbstractStringParser
{
    /**
     * @var string
     */
    private $separator = ';';

    /**
     * @var string
     */
    private $delimiter = ':';


    /**
     * Configuration constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->configure($config);
    }


    /**
     * @param $string
     *
     * @return array
     * @throws InvalidArgument
     */
    public function parse($string)
    {
        if (!is_string($string)) {
            throw new InvalidArgument('Cannot parse non-string values.');
        }

        $array = [];

        foreach ($this->splitItems($string) as $item) {
            $array[] = $this->splitItem($item);
        }

        return $array;
    }


    /**
     * @param array $config
     */
    protected function configure(array $config)
    {
        if (isset($config['separator'])) {
            $this->separator = $config['separator'];
        }

        if (isset($config['delimiter'])) {
            $this->delimiter = $config['delimiter'];
        }
    }


    /**
     * @param string $string
     *
     * @return array
     */
    protected function splitItems($string)
    {
        return strpos($string, $this->separator) !== false ? explode($this->separator, $string) : [$string];
    }


    /**
     * @param string $string
     *
     * @return array
     */
    protected function splitItem($string)
    {
        return explode($this->delimiter, $string);
    }
}
