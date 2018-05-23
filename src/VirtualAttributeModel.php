<?php

namespace Ponich\Eloquent\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $model_class
 * @property int $model_id
 * @property string $key
 * @property mixed $value
 */
class VirtualAttributeModel extends Model
{
    /**
     * @var string
     */
    public $table = 'eloquent_attributes';

    /**
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * Set serialize attribute value
     *
     * @param mixed|null $value
     */
    public function setValueAttribute($value = null)
    {
        $this->attributes['value'] = serialize($value);
    }

    /**
     * Get unserialize attribute value
     *
     * @return mixed
     */
    public function getValueAttribute()
    {
        $value = array_get($this->attributes, 'value');

        return ($value) ? unserialize($value) : null;
    }
}
