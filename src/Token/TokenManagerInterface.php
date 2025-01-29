<?php

namespace Driplet\Token;

interface TokenManagerInterface
{
    /**
     * Generates a token with custom claims.
     *
     * @param array $customClaims
     * @return string
     */
    public function generateToken(array $customClaims = []): string;
}
