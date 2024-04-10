<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Link;
use App\Services\Link\DecoderException;
use App\Services\Link\RepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use Symfony\Component\HttpFoundation\Response;

use function gmp_init;
use function gmp_strval;

class ShortLinkRepository extends ServiceEntityRepository implements RepositoryInterface
{
    public function __construct(
        private readonly ManagerRegistry $doctrine
    ) {
        parent::__construct($doctrine, Link::class);
    }

    public function getByIdentifier(string $identifier): ?string
    {
//        return Link::query()->where('identifier', $identifier)->first()?->url;
        return $this->findOneBy(['identifier' => $identifier])?->getUrl();
    }

    public function create(string $url): string
    {
        $entityManager = $this->doctrine->getManager();

        $urlHash = md5($url);

        $link = $this->findOneBy(['hash' => $urlHash]);

        if ($link) {
            return $link->getIdentifier();
        }

        $identifier = null;

        $entityManager->beginTransaction();
        try {
            $link = new Link();
            $link->setUrl($url);
            $link->setHash($urlHash);

            $entityManager->persist($link);
            $entityManager->flush();

            $identifier = gmp_strval(gmp_init($link->getId()), 62);
            $link->setIdentifier($identifier);

            $entityManager->persist($link);
            $entityManager->flush();

            $entityManager->commit();
        } catch (\Throwable) {
            $entityManager->rollback();
        }

        return $identifier;
    }
}
