<?php

namespace App\Services\Link;

interface EncoderInterface
{
    public function encode(string $value): Container;
}
