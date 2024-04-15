<?php

namespace Unit;

use App\Services\Link\Container;
use App\Services\Link\Converter;
use App\Services\Link\DecoderException;
use App\Services\Link\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ConverterTest extends KernelTestCase
{
    public function testEncode(): void
    {
        $repositoryMock = $this->createMock(RepositoryInterface::class);

        $repositoryMock->expects($this->once())
            ->method('create')
            ->with('https://www.google.com/')
            ->willReturn('15T718');

        $converter = new Converter('http://127.0.0.1:8080/link', $repositoryMock);

        $container = $converter->encode('https://www.google.com/');
        $this->assertInstanceOf(Container::class, $container);
        $this->assertEquals('http://127.0.0.1:8080/link/15T718', $container->getUrl());
    }

    public function testDecode(): void
    {
        $repositoryMock = $this->createMock(RepositoryInterface::class);

        $repositoryMock->expects($this->once())
            ->method('getByIdentifier')
            ->with('15T718')
            ->willReturn('https://www.google.com/');

        $converter = new Converter('http://127.0.0.1:8080/link', $repositoryMock);

        $container = $converter->decode('15T718');
        $this->assertInstanceOf(Container::class, $container);
        $this->assertEquals('https://www.google.com/', $container->getUrl());
    }

    public function testDecodeWithEmptyResult(): void
    {
        $repositoryMock = $this->createMock(RepositoryInterface::class);

        $repositoryMock->expects($this->once())
            ->method('getByIdentifier')
            ->with('15T718')
            ->willReturn(null);

        $converter = new Converter('http://127.0.0.1:8080/link', $repositoryMock);

        $this->expectException(DecoderException::class);
        $this->expectExceptionMessage('Repository returned an empty result for the token [15T718]');
        $converter->decode('15T718');
    }
}
