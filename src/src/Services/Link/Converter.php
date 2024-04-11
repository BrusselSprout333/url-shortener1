<?php

declare(strict_types=1);

namespace App\Services\Link;

use function sprintf;

readonly class Converter implements EncoderInterface, DecoderInterface
{
    public function __construct(
        protected string $baseUrl,
        protected RepositoryInterface $repository
    ) {
    }

    public function encode(string $value): Container
    {
        $identifier = $this->repository->create($value);

        return new Container($this->baseUrl . '/' . $identifier);
    }

    public function decode(string $encoded): Container
    {
        $url = $this->repository->getByIdentifier($encoded);

        if ($url === null) {
            throw new DecoderException(sprintf('Repository returned an empty result for the token [%s]', $encoded));
        }

        return new Container($url);
    }
}
