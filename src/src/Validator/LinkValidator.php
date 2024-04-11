<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class LinkValidator
{
    /**
     * @throws \JsonException
     */
    public function validateLink(Request $request): string
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
            throw new \JsonException(implode(',', $errors), Response::HTTP_BAD_REQUEST);
        }

        return $data['url'];
    }
}
