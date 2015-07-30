<?php

namespace Vnn\Places\Client;

/**
 * Interface ClientInterface
 * @package Vnn\Places\Client
 */
interface ClientInterface
{
    /**
     * @param string $url
     * @return array
     * @throws \RuntimeException
     */
    public function fetch($url);
}
