<?php

namespace Driplet\Client;

use Driplet\Exception\DripletException;
use Driplet\Http\HttpClientInterface;
use Driplet\Http\GuzzleHttpClient;
use Driplet\Message\MessageBuilder;

class DripletClient
{
    private string $endpoint;
    private string $secret;
    private HttpClientInterface $httpClient;

    public function __construct(
        string $endpoint,
        string $secret,
        ?HttpClientInterface $httpClient = null
    ) {
        if (empty($endpoint)) {
            throw new DripletException('Endpoint cannot be empty');
        }

        if (empty($secret)) {
            throw new DripletException('Secret cannot be empty');
        }

        $this->endpoint = $endpoint;
        $this->secret = $secret;
        $this->httpClient = $httpClient ?? new GuzzleHttpClient();
    }

    /**
     * Creates a new message builder instance.
     */
    public function createMessage(): MessageBuilder
    {
        return MessageBuilder::create();
    }

    /**
     * Sends a message using the builder.
     *
     * @throws DripletException
     */
    public function sendMessage(MessageBuilder $builder): bool
    {
        $payload = $builder->build();
        $jsonPayload = json_encode($payload);
        
        if ($jsonPayload === false) {
            throw new DripletException('Failed to encode message payload');
        }

        $signature = hash_hmac('sha256', $jsonPayload, $this->secret);

        return $this->httpClient->post($this->endpoint, [
            'json' => $payload,
            'headers' => [
                'X-Driplet-Signature' => $signature,
                'Content-Type' => 'application/json',
            ],
        ]);
    }
}
