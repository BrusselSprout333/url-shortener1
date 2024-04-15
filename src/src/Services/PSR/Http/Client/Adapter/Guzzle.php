<?php

namespace App\Services\PSR\Http\Client\Adapter;

use App\Services\PSR\Http\Client\ClientException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

readonly class Guzzle implements ClientInterface
{
    public function __construct(
        private Client $guzzle
    ) {
    }

    /**
     * Sends a PSR-7 request and returns a PSR-7 response.
     *
     * @throws ClientExceptionInterface if an error happens while processing the request
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->guzzle->send($request);
        } catch (BadResponseException $e) {
            return $e->getResponse();
        } catch (\Throwable $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
