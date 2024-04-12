<?php

namespace Unit;

use App\Services\PSR\Http\Client\Adapter\Guzzle;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GuzzleAdapterTest extends KernelTestCase
{
    public function test_send__guzzle_request(): void
    {
        $guzzleMock = $this->createMock(Client::class);
        $guzzleAdapter = new Guzzle($guzzleMock);

        $requestMock = $this->createMock(RequestInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $guzzleMock->expects($this->once())
            ->method('send')
            ->with($requestMock)
            ->willReturn($responseMock);

        $response = $guzzleAdapter->sendRequest($requestMock);
        $this->assertSame($responseMock, $response);
    }

    public function tests_guzzle_bad_response(): void
    {
        $guzzleMock = $this->createMock(Client::class);
        $guzzleAdapter = new Guzzle($guzzleMock);

        $requestMock = $this->createMock(RequestInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $guzzleMock->method('send')
            ->willThrowException(
                new BadResponseException('Bad response', $requestMock, $responseMock
                ));

        $response = $guzzleAdapter->sendRequest($requestMock);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function tests_guzzle_client_error(): void
    {
        $guzzleMock = $this->createMock(Client::class);
        $guzzleAdapter = new Guzzle($guzzleMock);

        $requestMock = $this->createMock(RequestInterface::class);

        $guzzleMock->method('send')
            ->willThrowException(new \Exception('Some error'));

        $this->expectException(ClientExceptionInterface::class);
        $guzzleAdapter->sendRequest($requestMock);
    }
}
