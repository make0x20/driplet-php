<?php

namespace Driplet\Token;

use Firebase\JWT\JWT;
use Driplet\Exception\DripletException;

class JwtManager implements TokenManagerInterface
{
    private string $secret;
    private int $expiration;

    public function __construct(string $secret, int $expiration = 60)
    {
        if (empty($secret)) {
            throw new DripletException('JWT secret cannot be empty');
        }
        
        $this->secret = $secret;
        $this->expiration = $expiration;
    }

    public function generateToken(array $customClaims = []): string
    {
        $payload = [
            'exp' => time() + $this->expiration,
            'iat' => time(),
            'custom' => $customClaims,
        ];

        return JWT::encode($payload, $this->secret, 'HS256');
    }
}
