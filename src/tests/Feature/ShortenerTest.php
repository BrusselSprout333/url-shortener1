<?php

namespace Feature;

use App\Controller\ShortenerController;
use App\Repository\ShortLinkRepository;
use App\Services\Link\Converter;
use App\Services\PSR\Http\Client\Adapter\Guzzle;
use App\Services\SafeBrowsing\Api;
use App\Services\SafeBrowsing\Client;
use App\Validator\LinkValidator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ShortenerTest extends WebTestCase
{
    public function test_encode_with_safe_url(): void
    {
        $repository = $this->createMock(ShortLinkRepository::class);
        $repository->expects($this->once())
            ->method('create')
            ->with('https://example.com')
            ->willReturn('15T718');

        $converter = new Converter('http://127.0.0.1:8081/link', $repository);

        $request = new Request(content:json_encode(['url' => 'https://example.com']));
        $validator = new LinkValidator();

        $controller = new ShortenerController($validator);
        $api = new Api(new Client(
            'AIzaSyBYgjetVnLA82OvjLdPuImMq-1gwCa_dLc',
            'URL Shortener test task',
            new Guzzle(new \GuzzleHttp\Client([
                'timeout' => 5,
                'connect_timeout' => 5
            ]))
        ));

        $response = $controller->encode($request, $api, $converter);
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals('http://127.0.0.1:8081/link/15T718', $responseData['url']);
    }

    public function test_encode_with_unsafe_url(): void
    {
        $repository = $this->createMock(ShortLinkRepository::class);

        $converter = new Converter('http://127.0.0.1:8081/link', $repository);
        $request = new Request(content:json_encode(['url' => 'http://unsafelink.com']));
        $validator = new LinkValidator();

        $controller = new ShortenerController($validator);
        $api = $this->createMock(Api::class);
        $api->expects($this->once())
            ->method('threatMatchesFind')
            ->with('http://unsafelink.com')
            ->willReturn(true);

        $response = $controller->encode($request, $api, $converter);

        $this->assertEquals(Response::HTTP_FAILED_DEPENDENCY, $response->getStatusCode());
    }
}
