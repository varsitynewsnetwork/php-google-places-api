<?php

namespace Vnn\Places\Formatter;

/**
 * Interface FormatterInterface
 * @package Vnn\Places\Formatter
 */
interface FormatterInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function __invoke(array $data);
}
