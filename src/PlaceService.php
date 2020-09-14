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
    protected $detailEndpoint = 'https://maps.googleapis.com/maps/api/place/details/json';

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $googleApiKey;

    /**
     * @param ClientInterface $client
     * @param string $googleApiKey
     */
    public function __construct(ClientInterface $client, string $googleApiKey)
    {
        $this->client = $client;
        $this->googleApiKey = $googleApiKey;
    }

    /**
     * Looks up the location passed in the Google Places API via text search
     * and returns raw data, which may be formatted by the passed formatter.
     *
     * @param string $place The string to look up as a Google Places location
     * @param callable $resultFormatter Called on the results to format them
     * @param array $optionalParams An associative array of optional parameters to text search
     * @return array
     * @throws \RuntimeException
     */
    public function textSearch($place, callable $resultFormatter = null, array $optionalParams = [])
    {
        $queryString = http_build_query(
            [
                'query' => $place,
                'key' => $this->googleApiKey
            ] + $optionalParams
        );
        $googleUrl = $this->textSearchEndpoint . '?' . $queryString;

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
     * @param array $optionalParams An associative array of optional parameters.
     * @return array
     * @throws \RuntimeException
     */
    public function findPlace($place, callable $resultFormatter = null, $fields = null, array $optionalParams = [])
    {
        $queryString = http_build_query(
            [
                'key' => $this->googleApiKey,
                'input' => $place,
                'inputtype' => 'textquery'
            ] + $optionalParams
        );

        $googleUrl = $this->findPlaceEndpoint . '?' . $queryString;

        if ($fields !== null) {
            $googleUrl .= '&fields=' . array_reduce($fields, function ($carry, $item) {
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
    public function detail($placeId, callable $resultFormatter = null, $fields = null, array $optionalParams = [])
    {
        $queryString = http_build_query(
            [
                'placeid' => $placeId,
                'key' => $this->googleApiKey
            ] + $optionalParams
        );

        $googleUrl = $this->detailEndpoint . '?' . $queryString;

        if ($fields !== null) {
            $googleUrl .= '&fields=' . array_reduce($fields, function ($carry, $item) {
                if (!$carry) {
                    return $item;
                } else {
                    return $carry . ',' . $item;
                }
            });
        }

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
