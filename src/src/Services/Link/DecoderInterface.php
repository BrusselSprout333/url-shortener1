<?php

namespace App\Services\Link;

interface DecoderInterface
{
    /**
     * @throws DecoderException
     */
    public function decode(string $encoded): Container;
}
