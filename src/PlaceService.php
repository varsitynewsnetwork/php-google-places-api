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
    protected $findPlaceEndpoint = 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json';

    /**
     * @var string
     */
    protected $textSearchEndpoint = 'https://maps.googleapis.com/maps/api/place/textsearch/json';

    /**
     * @var string
     */
    protected $detailEndpint = 'https://maps.googleapis.com/maps/api/place/details/json';

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
     * and returns raw data, which may be formatted by the passed formatter.
     *
     * @param string $place The string to look up as a Google Places location
     * @param callable $resultFormatter Called on the results to format them
     * @return array
     * @throws \RuntimeException
     */
    public function textSearch($place, callable $resultFormatter = null)
    {
        $googleUrl = $this->textSearchEndpoint .
            '?query=' . urlencode($place) .
            '&key=' . urlencode($this->apiKey);

        $data = $this->client->fetch($googleUrl);

        if (!isset($data['results'])) {
            return [];
        }

        $data = $data['results'];

        if (isset($resultFormatter)) {
            $data = $resultFormatter($data);
        }

        return $data;
    }

    /**
     * Looks up the location passed in the Google Places API via Find Place
     * request and returns raw data, which may be formatted by the passed
     * formatter.
     *
     * @param string $place The string to look up as a Google Places location
     * @param callable $resultFormatter Called on the results to format them
     * @param array $fields The output fields you wish to retrieve from the Places API
     * @return array
     * @throws \RuntimeException
     */
    public function findPlace($place, callable $resultFormatter = null, $fields = null)
    {
        $googleUrl = $this->findPlaceEndpoint .
        '?key=' . urlencode($this->apiKey) .
        '&input=' . urlencode($place) .
        '&inputtype=textquery';

        if ($fields !== null) {
            $googleUrl .= '&fields=' . array_reduce($fields, function($carry, $item) {
                if (!$carry) {
                    return $item;
                } else {
                    return $carry . ',' . $item;
                }
            });
        }

        $data = $this->client->fetch($googleUrl);

        if (!isset($data['candidates'])) {
            return [];
        }

        $data = $data['candidates'];

        if (isset($resultFormatter)) {
            $data = $resultFormatter($data);
        }

        return $data;
    }

    /**
     * Retrieve a specific place by placeid and returns raw data, which may be
     * formatted by the passed formatter.
     *
     * @param string $placeId
     * @param callable|null $resultFormatter
     * @return array
     */
    public function detail($placeId, callable $resultFormatter = null)
    {
        $googleUrl = $this->detailEndpint .
            '?placeid=' . urlencode($placeId) .
            '&key=' . urlencode($this->apiKey);

        $data = $this->client->fetch($googleUrl);

        if (!isset($data['result'])) {
            return [];
        }

        $data = $data['result'];

        if (isset($resultFormatter)) {
            $data = $resultFormatter($data);
        }

        return $data;
    }
}
