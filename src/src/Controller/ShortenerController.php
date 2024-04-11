<?php

namespace App\Controller;

use App\Services\Link\EncoderInterface;
use App\Validator\LinkValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\SafeBrowsing\Api;

class ShortenerController extends AbstractController
{
    public function __construct(
        private readonly LinkValidator $validator
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function encode(Request $request, Api $safeBrowsingApi, EncoderInterface $encoder): JsonResponse
    {
        $url = $this->validator->validateLink($request);

        $found = $safeBrowsingApi->threatMatchesFind($url);

        if ($found) {
            return new JsonResponse(null, Response::HTTP_FAILED_DEPENDENCY);
        }

        $container = $encoder->encode($url);

        return new JsonResponse($container, Response::HTTP_CREATED);
    }
}
