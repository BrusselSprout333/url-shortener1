<?php

namespace App\Services\Link;

interface RepositoryInterface
{
    public function getByIdentifier(string $identifier): ?string;

    /**
     * @return string Identifier of a stored link
     */
    public function create(string $url): string;
}
