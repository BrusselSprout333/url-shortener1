<?php

namespace Unit;

use App\Services\PSR\Http\Client\Adapter\Guzzle as GuzzleAdapter;
use App\Services\PSR\Http\Client\ClientException;
use App\Services\SafeBrowsing\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ClientTest extends KernelTestCase
{
    public function test_send_request(): void
    {
        $guzzle = $this->createMock(GuzzleAdapter::class);

        $client = new Client(
            'AIzaSyBYgjetVnLA82OvjLdPuImMq-1gwCa_dLc',
            'URL Shortener test task',
            $guzzle
        );

        $guzzle->expects($this->once())
            ->method('sendRequest')
            ->with(
                $this->callback(function (Request $request) {
                    $this->assertSame('POST', $request->getMethod());
                    $this->assertSame('https://safebrowsing.googleapis.com/v4/threatMatches:find?key=AIzaSyBYgjetVnLA82OvjLdPuImMq-1gwCa_dLc', $request->getUri()->__toString());
                    return true;
                })
            )->willReturn(new Response());
        $response = $client->threatMatchesFind('https://www.google.com/');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_send_bad_request(): void
    {
        $guzzle = $this->createMock(GuzzleAdapter::class);

        $client = new Client(
            'AIzaSyBYgjetVnLA82OvjLdPuImMq-1gwCa_dLc',
            'URL Shortener test task',
            $guzzle
        );

        $exceptionMock = $this->createMock(ClientException::class);

        $guzzle->expects($this->once())
            ->method('sendRequest')
            ->willThrowException($exceptionMock);

        $this->expectException(ClientException::class);
        $client->threatMatchesFind('bad_link');
    }
}
