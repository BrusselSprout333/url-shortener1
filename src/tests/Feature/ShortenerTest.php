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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShortenerTest extends WebTestCase
{
    public function testEncode(): void
    {
        $repository = $this->createMock(ShortLinkRepository::class);
        $repository->expects($this->once())
            ->method('create')
            ->with('https://example.com')
            ->willReturn('15T718');

        $converter = new Converter('http://127.0.0.1:8080/link', $repository);

        $request = new Request(content: json_encode(['url' => 'https://example.com']));
        $validator = new LinkValidator();

        $controller = new ShortenerController($validator);
        $api = new Api(new Client(
            'AIzaSyBYgjetVnLA82OvjLdPuImMq-1gwCa_dLc',
            'URL Shortener test task',
            new Guzzle(new \GuzzleHttp\Client([
                'timeout' => 5,
                'connect_timeout' => 5,
            ]))
        ));

        $response = $controller->encode($request, $api, $converter);
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals('http://127.0.0.1:8080/link/15T718', $responseData['url']);
    }
}
