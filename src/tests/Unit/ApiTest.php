<?php

namespace Unit;

use App\Services\PSR\Http\Client\ClientException;
use App\Services\SafeBrowsing\Api;
use App\Services\SafeBrowsing\Client;
use GuzzleHttp\Psr7\Response;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ApiTest extends KernelTestCase
{
    public function testThreatMatchesFindSuccess(): void
    {
        $client = $this->createMock(Client::class);
        $api = new Api($client);

        $client->expects($this->once())
            ->method('threatMatchesFind')
            ->with('https://www.google.com/')
            ->willReturn(
                new Response(body: json_encode(['example_response_data']))
            );

        $this->assertTrue($api->threatMatchesFind('https://www.google.com/'));
    }

    public function testThreatMatchesFindClientException(): void
    {
        $clientMock = $this->createMock(Client::class);
        $api = new Api($clientMock);

        $exceptionMock = $this->createMock(ClientException::class);

        $clientMock->expects($this->once())
            ->method('threatMatchesFind')
            ->willThrowException($exceptionMock);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Safe browsing API client error');
        $api->threatMatchesFind('https://www.google.com/');
    }

    public function testThreatMatchesFindInvalidStatusCode(): void
    {
        $clientMock = $this->createMock(Client::class);
        $api = new Api($clientMock);

        $clientMock->expects($this->once())
            ->method('threatMatchesFind')
            ->willReturn(new Response(500));

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Invalid safe browsing api response status code');
        $api->threatMatchesFind('https://www.google.com/');
    }

    public function testThreatMatchesFindInvalidResponseData(): void
    {
        $client = $this->createMock(Client::class);
        $api = new Api($client);

        $client->expects($this->once())
            ->method('threatMatchesFind')
            ->with('https://www.google.com/')
            ->willReturn(new Response(body: 'Invalid JSON data'));

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Invalid safe browsing api response structure');
        $api->threatMatchesFind('https://www.google.com/');
    }
}
