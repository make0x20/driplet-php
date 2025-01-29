<?php

namespace Driplet\Http;

interface HttpClientInterface
{
    /**
     * Sends a POST request.
     *
     * @param string $url The URL to send the request to
     * @param array $options Request options including headers and body
     * @return bool True if the request was successful
     * @throws \Driplet\Exception\DripletException
     */
    public function post(string $url, array $options = []): bool;
}
