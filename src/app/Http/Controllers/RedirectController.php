<?php

namespace App\Http\Controllers;

use App\Services\Link\DecoderException;
use App\Services\Link\DecoderInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class RedirectController extends Controller
{
    public function redirect(string $identifier, DecoderInterface $decoder): Response|RedirectResponse
    {
        try {
            $container = $decoder->decode($identifier);
        } catch (DecoderException $e) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        return Redirect::to($container->getUrl());
    }
}
