<?php

declare(strict_types=1);

namespace App\Services\PSR\Http\Client;

use Psr\Http\Client\ClientExceptionInterface;

class ClientException extends \Exception implements ClientExceptionInterface
{
}
