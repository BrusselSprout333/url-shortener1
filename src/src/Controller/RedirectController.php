<?php

namespace App\Controller;

use App\Services\Link\DecoderException;
use App\Services\Link\DecoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class RedirectController extends AbstractController
{
    public function redirectTo(string $identifier, DecoderInterface $decoder): RedirectResponse|Response
    {
        try {
            $container = $decoder->decode($identifier);
        } catch (DecoderException $e) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        return $this->redirect($container->getUrl());
    }
}
