<?php

namespace Vnn\Places\Formatter;

/**
 * Class SingleResultFormatter
 * @package Vnn\Places\Formatter
 */
class SingleResultFormatter implements FormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(array $data)
    {
        if (!empty($data)) {
            $data = $data[0];
        }

        return $data;
    }
}
