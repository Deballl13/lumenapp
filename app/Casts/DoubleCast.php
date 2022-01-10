<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class DoubleCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return $value===null?number_format(doubleval(0), 1, '.', ''):number_format(strval(round($value, 1, PHP_ROUND_HALF_UP)), 1, '.', '');        ;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $value===null?number_format(doubleval(0), 1, '.', ''):number_format(strval(round($value, 1, PHP_ROUND_HALF_UP)), 1, '.', '');
    }
}
