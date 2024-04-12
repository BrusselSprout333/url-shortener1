<?php

namespace Feature;

use App\Controller\RedirectController;
use App\Repository\ShortLinkRepository;
use App\Services\Link\Converter;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class RedirectTest extends WebTestCase
{
    public function test_correct_redirect(): void
    {
        $repository = $this->createMock(ShortLinkRepository::class);
        $repository->expects($this->once())
            ->method('getByIdentifier')
            ->with('15T718')
            ->willReturn('https://example.com');

        $converter = new Converter('http://127.0.0.1:8081/link', $repository);

        $controller = new RedirectController();

        $response = $controller->redirect_to('15T718', $converter);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('https://example.com', $response->getTargetUrl());
    }

    public function test_not_found_redirect(): void
    {
        $repository = $this->createMock(ShortLinkRepository::class);
        $repository->expects($this->once())
            ->method('getByIdentifier')
            ->with('15T718')
            ->willReturn(null);

        $converter = new Converter('http://127.0.0.1:8081/link', $repository);

        $controller = new RedirectController();

        $response = $controller->redirect_to('15T718', $converter);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}