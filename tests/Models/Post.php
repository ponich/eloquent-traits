<?php

namespace Ponich\Eloquent\Traits\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Ponich\Eloquent\Traits\VirtualAttribute;
use Ponich\Eloquent\Traits\HasAttachment;

class Post extends Model
{
    use VirtualAttribute, HasAttachment;

    protected $table = 'posts';

    protected $guarded = [
        'id'
    ];

    public $virtalAttributes = [
        'tags', 'og_tags'
    ];

    public function getExitsAttribute()
    {
        return 'sample attribute';
    }
}
