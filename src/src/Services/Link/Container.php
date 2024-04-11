<?php

declare(strict_types=1);

namespace App\Services\Link;

use JsonSerializable;

readonly class Container implements JsonSerializable
{
    public function __construct(
        protected string $url
    ) {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function jsonSerialize(): array
    {
        return ['url' => $this->url];
    }
}
