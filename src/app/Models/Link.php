<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $identifier
 * @property string $url
 * @property string $hash
 * @method static Builder|Link query()
 */
class Link extends Model
{
    protected $table = 'links';

    protected $fillable = [
        'url',
        'hash',
        'identifier',
    ];
}
