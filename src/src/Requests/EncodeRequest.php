<?php

namespace App\Requests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Validation;

class EncodeRequest extends Request
{
//    public function validate(): void
//    {
//        $validator = Validation::createValidator();
//
//        $url = trim($this->query->get('url'), '/');
//
//        $errors = $validator->validate($url, [new Url()]);
//
//        if (count($errors) > 0) {
//            throw new BadRequestHttpException((string) $errors);
//        }
//    }

    public function getUrl(): string
    {
        return trim($this->query->get('url'), '/');
    }
}
