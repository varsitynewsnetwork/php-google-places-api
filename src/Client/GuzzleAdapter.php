<?php

namespace Vnn\Places\Client;

use GuzzleHttp\Client;

/**
 * Class GuzzleAdapter
 * @package Vnn\Places\Client
 */
class GuzzleAdapter implements ClientInterface
{
    /**
     * @var Client
     */
    protected $guzzle;

    /**
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->guzzle = $client ?: new Client();
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($url)
    {
        try {
            $result = $this->guzzle->get($url);
            $response = $result->getBody()->getContents();

            $data = json_decode($response, true);

            if (!$data) {
                throw new \RuntimeException('Failed to parse response');
            }

            if ($data['status'] !== 'OK') {
                throw new \RuntimeException($data['error_message']);
            }

            return $data;
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage(), 0, $e);
        }
    }
}
