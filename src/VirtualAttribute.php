<?php

namespace Ponich\Eloquent\Traits;

use Illuminate\Database\QueryException;

trait VirtualAttribute
{
    /**
     * Override getAttribute method
     *
     * @param $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        // load from storage
        if (array_key_exists($key, array_flip($this->virtalAttributes)) !== false) {
            $this->loadVirtualAttributes();

            return array_get($this->getAttributes(), $key, null);
        }

        return parent::getAttribute($key);
    }

    /**
     * Load attributes from relation and set to model
     *
     * @return void
     */
    public function loadVirtualAttributes()
    {
        foreach ($this->relationVirtualAttributes as $attribute) {
            $this->setAttribute($attribute->key, $attribute->value);
        }
    }

    /**
     * Relation for attributes table
     *
     * @return mixed
     */
    public function relationVirtualAttributes()
    {
        $type = get_class($this);

        return $this->hasMany(
            VirtualAttributeModel::class,
            'model_id',
            $this->primaryKey
        )->where('model_class', $type);
    }

    /**
     * Override save method
     *
     * @param array $options
     * @return mixed
     */
    public function save(array $options = [])
    {
        try {
            return parent::save($options);
        } catch (QueryException $e) {
            // skip handle
            if (array_get($options, 'skip_query_exception')) {
                throw $e;
            }

            // mis attribute key detected
            if (!$vattr = $this::getMissAttributeKey($e)) {
                throw $e;
            }

            //
            if (array_key_exists($vattr, array_flip($this->virtalAttributes)) === false) {
                throw $e;
            }

            // save attributes
            return $this->setVirtualAttributes($options);
        }
    }

    /**
     * Set virtual attribute values
     *
     * @param array $options
     * @return mixed
     */
    protected function setVirtualAttributes(array $options = [])
    {
        $vattrs = (is_array($this->virtalAttributes)) ? $this->virtalAttributes : [];
        $virtalAttributes = [];

        foreach ($vattrs as $vattr) {
            if (array_key_exists($vattr, $this->attributes)) {
                $virtalAttributes[$vattr] = array_get($this->attributes, $vattr);
                unset($this->{$vattr});
            }
        }

        $options['skip_query_exception'] = true;
        $result = $this->save($options);

        // save attributes
        $this->saveVirtualAttributes($virtalAttributes);

        return $result;
    }

    /**
     * Save attributes in database
     *
     * @param array $vattrs
     * @return bool
     */
    public function saveVirtualAttributes(array $vattrs = [])
    {
        $model = get_class($this);

        // model
        if (!$id = array_get($this, $this->primaryKey)) {
            return false;
        }

        foreach ($vattrs as $vattrKey => $vattrValue) {
            $data = [
                'model_class' => $model,
                'model_id' => $id,
                'type' => gettype($vattrValue),
                'key' => $vattrKey,
                'value' => $vattrValue
            ];

            VirtualAttributeModel::updateOrCreate(
                array_except($data, ['value','type']),
                $data
            );

            $this->setAttribute($vattrKey, $vattrValue);
        }

        return true;
    }

    /**
     * Return RegExp pattern for found miss attributes in QueryException message
     * Test in: mysql, sqlite, pgsql
     *
     * @return string
     */
    public static function getRegExpMissAttribute()
    {
        return '/(?:(?:\w+\[.*\])(?:\W.*?\:){1}|\w\W).*column(?:\s[a-z]+|\W+)(?:\W(?<attribute>[\w-.]+)\W)/m';
    }

    /**
     * Get miss attribute key from QueryException message
     *
     * @param QueryException $exception
     * @return string|null
     */
    public static function getMissAttributeKey(QueryException $exception)
    {
        preg_match_all(
            self::getRegExpMissAttribute(),
            $exception->getMessage(),
            $matches,
            PREG_SET_ORDER,
            0
        );

        return array_get($matches, '0.attribute');
    }
}
