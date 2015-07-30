<?php

namespace Vnn\Places\Formatter;

/**
 * Class CountryStripperFormatter
 * @package Vnn\Places\Formatter
 */
class CountryStripperFormatter implements FormatterInterface
{
    /**
     * @var bool|false
     */
    private $singleResult;

    /**
     * @param bool|false $singleResult
     */
    public function __construct($singleResult = false)
    {
        $this->singleResult = $singleResult;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(array $data)
    {
        if ($this->singleResult) {
            return $this->formatResult($data);
        }

        foreach ($data as $index => $row) {
            $data[$index] = $this->formatResult($row);
        }

        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function formatResult(array $data)
    {
        if ($data['formatted_address']) {
            $address = explode(', ', $data['formatted_address']);
            array_pop($address);

            $address = implode(', ', $address);
            $data['formatted_address'] = $address;
        }

        return $data;
    }
}
