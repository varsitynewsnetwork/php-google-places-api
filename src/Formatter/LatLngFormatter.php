<?php

namespace Vnn\Places\Formatter;

/**
 * Class LatLngFormatter
 * @package Vnn\Places\Formatter
 */
class LatLngFormatter implements FormatterInterface
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
        $row = ['address' => null, 'lat' => null, 'lng' => null];
        $row['address'] = $data['formatted_address'];

        if (isset($data['geometry']) && isset($data['geometry']['location'])) {
            $row['lat'] = $data['geometry']['location']['lat'];
            $row['lng'] = $data['geometry']['location']['lng'];
        }

        return $row;
    }
}
