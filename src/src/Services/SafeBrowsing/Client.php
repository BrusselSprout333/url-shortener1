<?php

declare(strict_types=1);

namespace App\Services\SafeBrowsing;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client
{
    protected string $apiUrl = '';

    public function __construct(
        protected readonly string $apiKey,
        protected readonly string $clientId,
        protected ClientInterface $httpClient
    ) {
        $this->apiUrl = sprintf(
            'https://safebrowsing.googleapis.com/v4/threatMatches:find?key=%s',
            $this->apiKey
        );
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function threatMatchesFind(string ...$urls): ResponseInterface
    {
        $request = $this
            ->createRequest(
                'POST',
                $this->apiUrl,
                [
                    'client'     => [
                        'clientId'      => $this->clientId,
                        'clientVersion' => '1.0.0',
                    ],
                    'threatInfo' => [
                        'threatTypes'      => [
                            'MALWARE',
                            'SOCIAL_ENGINEERING',
                            'UNWANTED_SOFTWARE',
                            'POTENTIALLY_HARMFUL_APPLICATION'
                        ],
                        'platformTypes'    => ['PLATFORM_TYPE_UNSPECIFIED'],
                        'threatEntryTypes' => ['URL'],
                        'threatEntries'    => array_map(static fn ($url) => ['url' => $url], $urls),
                    ],
                ]
            );

        return $this->httpClient->sendRequest($request);
    }

    /**
     * Creates PSR-7 Request
     *
     * @param string $method
     * @param string $url
     * @param array $data
     * @return RequestInterface
     */
    protected function createRequest(string $method, string $url, array $data = []): RequestInterface
    {
        /** @noinspection JsonEncodingApiUsageInspection */
        return new Request(
            strtoupper($method),
            $url,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );
    }
}
