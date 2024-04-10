<?php

namespace App\Controller;

use App\Services\Link\EncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use App\Services\SafeBrowsing\Api;

class ShortenerController extends AbstractController
{
    public function encode(Request $request, Api $safeBrowsingApi, EncoderInterface $encoder): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $validator = Validation::createValidator();

        $constraints = new Assert\Collection([
            'url' => [
                new Assert\NotBlank(['message' => 'URL is required.']),
                new Assert\Url(['message' => 'Invalid URL format.'])
            ]
        ]);

        $violations = $validator->validate($data, $constraints);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $found = $safeBrowsingApi->threatMatchesFind($data['url']);

        if ($found) {
            return new JsonResponse(null, Response::HTTP_FAILED_DEPENDENCY);
        }

        $container = $encoder->encode($data['url']);

        return new JsonResponse($container, Response::HTTP_CREATED);
    }
}
