<?php

namespace Driplet\Http;

use Driplet\Exception\DripletException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GuzzleHttpClient implements HttpClientInterface
{
    private Client $client;

    public function __construct(?Client $client = null)
    {
        $this->client = $client ?? new Client();
    }

    public function post(string $url, array $options = []): bool
    {
        try {
            $response = $this->client->post($url, $options);
            return $response->getStatusCode() === 200;
        } catch (GuzzleException $e) {
            throw new DripletException('HTTP request failed: ' . $e->getMessage(), 0, $e);
        }
    }
}
