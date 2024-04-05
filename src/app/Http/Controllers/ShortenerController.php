<?php

namespace App\Http\Controllers;

use App\Http\Requests\EncodeRequest;
use App\Services\Link\EncoderInterface;
use App\Services\SafeBrowsing\Api;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ShortenerController extends Controller
{
    public function encode(EncodeRequest $request, Api $safeBrowsingApi, EncoderInterface $encoder): JsonResponse
    {
        $found = $safeBrowsingApi->threatMatchesFind($request->getUrl());

        if ($found) {
            return new JsonResponse(null, Response::HTTP_FAILED_DEPENDENCY);
        }

        $container = $encoder->encode($request->getUrl());

        return new JsonResponse($container, Response::HTTP_CREATED);
    }
}
