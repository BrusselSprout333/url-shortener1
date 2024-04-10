<?php

namespace App\Services\SafeBrowsing;

use Psr\Http\Client\ClientExceptionInterface;
use UnexpectedValueException;

class Api
{
    public function __construct(
        protected Client $client
    ) {
    }

    public function threatMatchesFind(string $url): bool
    {
        try {
            $response = $this->client->threatMatchesFind($url);
        } catch (ClientExceptionInterface $e) {
            throw new UnexpectedValueException('Safe browsing API client error', $e->getCode(), $e);
        }

        if ($response->getStatusCode() !== 200) {
            throw new UnexpectedValueException('Invalid safe browsing api response status code');
        }

        try {
            $responseData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new UnexpectedValueException('Invalid safe browsing api response structure');
        }

        return !empty($responseData);
    }
}
