<?php

namespace Vnn\Places\Formatter;

/**
 * Class CompositeFormatter
 * @package Vnn\Places\Formatter
 */
class CompositeFormatter implements FormatterInterface
{
    /**
     * @var array
     */
    private $formatters;

    /**
     * @param array $formatters
     */
    public function __construct(array $formatters = [])
    {
        $this->formatters = $formatters;
    }

    /**
     * @param callable $formatter
     * @return $this
     */
    public function addFormatter(callable $formatter)
    {
        $this->formatters[] = $formatter;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(array $data)
    {
        foreach ($this->formatters as $formatter) {
            $data = $formatter($data);
        }

        return $data;
    }
}
