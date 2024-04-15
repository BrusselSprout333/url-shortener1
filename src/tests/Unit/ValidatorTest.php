<?php

namespace Unit;

use App\Validator\LinkValidator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ValidatorTest extends KernelTestCase
{
    public function testValidateLinkWithValidUrl(): void
    {
        $validator = new LinkValidator();

        $request = new Request(
            content: json_encode(['url' => 'https://www.google.com/'])
        );

        $this->assertEquals('https://www.google.com/', $validator->validateLink($request));
    }

    public function testValidateLinkWithMissingUrl(): void
    {
        $validator = new LinkValidator();

        $request = new Request(
            content: json_encode(['url' => ''])
        );

        $this->expectException(\JsonException::class);
        $this->expectExceptionCode(ResponseCodes::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage('URL is required.');

        $validator->validateLink($request);
    }

    public function testValidateLinkWithInvalidUrlFormat(): void
    {
        $validator = new LinkValidator();

        $request = new Request(
            content: json_encode(['url' => 'invalid_url'])
        );

        $this->expectException(\JsonException::class);
        $this->expectExceptionCode(ResponseCodes::HTTP_BAD_REQUEST);
        $this->expectExceptionMessage('Invalid URL format.');

        $validator->validateLink($request);
    }
}
