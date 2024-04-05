<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Link;
use App\Services\Link\RepositoryInterface;
use Illuminate\Support\Facades\DB;

use function gmp_init;
use function gmp_strval;

class ShortLinkRepository implements RepositoryInterface
{
    public function getByIdentifier(string $identifier): ?string
    {
        return Link::query()->where('identifier', $identifier)->first()?->url;
    }

    public function create(string $url): string
    {
        $urlHash = md5($url);

        $link = Link::query()->where('hash', $urlHash)->first();

        if ($link) {
            return $link->identifier;
        }

        $identifier = null;

        DB::transaction(static function () use ($url, $urlHash, &$identifier) {
            $link = Link::query()->create([
                'url'  => $url,
                'hash' => $urlHash,
            ]);

            $identifier = gmp_strval(gmp_init($link->id), 62);

            $link->update(['identifier' => $identifier]);
        });

        return $identifier;
    }
}
