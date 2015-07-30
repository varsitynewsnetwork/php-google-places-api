<?php

namespace Vnn\Places;

use Vnn\Places\Client\ClientInterface;

/**
 * Class PlaceService
 * @package Vnn\Places
 */
class PlaceService
{
    /**
     * @var string
     */
    protected $endpoint = 'https://maps.googleapis.com/maps/api/place/textsearch/json';

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @param ClientInterface $client
     * @param array $config
     */
    public function __construct(ClientInterface $client, array $config = [])
    {
        $this->client = $client;

        if (isset($config['key'])) {
            $this->setApiKey($config['key']);
        }
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Looks up the location passed in the Google Places API via text search
     * and returns raw data, which may be formatted by the past formatter.
     *
     * @param string $place The string to look up as a Google Places location
     * @param callable $resultFormatter Called on the results to format them
     * @return array
     * @throws \RuntimeException
     */
    public function search($place, callable $resultFormatter = null)
    {
        $googleUrl = $this->endpoint .
            '?query=' . urlencode($place) .
            '&key=' . urlencode($this->apiKey);

        $data = $this->client->fetch($googleUrl);

        if (isset($resultFormatter)) {
            $data = $resultFormatter($data);
        }

        return $data;
    }

}
