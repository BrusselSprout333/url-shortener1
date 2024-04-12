<?php

namespace Unit;

use App\Entity\Link;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $link = new Link();

        $link->setUrl('http://example.com');
        $link->setIdentifier('15T718');
        $link->setHash('d75277cdffef995a46ae59bdaef1db86');

        $this->assertSame('http://example.com', $link->getUrl());
        $this->assertSame('15T718', $link->getIdentifier());
        $this->assertSame('d75277cdffef995a46ae59bdaef1db86', $link->getHash());
    }
}
