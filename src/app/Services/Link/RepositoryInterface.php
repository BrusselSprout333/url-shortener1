<?php

namespace App\Services\Link;

interface RepositoryInterface
{
    public function getByIdentifier(string $identifier): ?string;

    /**
     * @param string $url
     * @return string Identifier of a stored link
     */
    public function create(string $url): string;
}
