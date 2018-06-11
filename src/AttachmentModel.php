<?php

namespace Ponich\Eloquent\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $model_class
 * @property int $model_id
 * @property string $uuid
 * @property string $md5
 * @property string $disk
 * @property string $path
 * @property string $name
 * @property string $type
 * @property int $size
 * @property array $metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AttachmentModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'eloquent_attachments';

    /**
     * @var array
     */
    protected $guarded = [
        'id'
    ];
}
