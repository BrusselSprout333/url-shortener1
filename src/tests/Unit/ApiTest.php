<?php

namespace Unit;

use App\Services\PSR\Http\Client\ClientException;
use App\Services\SafeBrowsing\Api;
use App\Services\SafeBrowsing\Client;
use GuzzleHttp\Psr7\Response;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use UnexpectedValueException;

class ApiTest extends KernelTestCase
{
    public function test_threat_matches_find_success(): void
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

    public function test_threat_matches_find_client_exception(): void
    {
        $clientMock = $this->createMock(Client::class);
        $api = new Api($clientMock);

        $exceptionMock = $this->createMock(ClientException::class);

        $clientMock->expects($this->once())
            ->method('threatMatchesFind')
            ->willThrowException($exceptionMock);

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Safe browsing API client error');
        $api->threatMatchesFind('https://www.google.com/');
    }

    public function test_threat_matches_find_invalid_status_code(): void
    {
        $clientMock = $this->createMock(Client::class);
        $api = new Api($clientMock);

        $clientMock->expects($this->once())
            ->method('threatMatchesFind')
            ->willReturn(new Response(500));

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Invalid safe browsing api response status code');
        $api->threatMatchesFind('https://www.google.com/');
    }

    public function test_threat_matches_find_invalid_response_data(): void
    {
        $client = $this->createMock(Client::class);
        $api = new Api($client);

        $client->expects($this->once())
            ->method('threatMatchesFind')
            ->with('https://www.google.com/')
            ->willReturn(new Response(body: 'Invalid JSON data'));

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Invalid safe browsing api response structure');
        $api->threatMatchesFind('https://www.google.com/');
    }
}
