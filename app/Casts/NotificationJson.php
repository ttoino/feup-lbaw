<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class NotificationJson implements CastsAttributes {
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes) {
        return array_map(function ($val) {
            if (is_string($val) && str_starts_with($val, 'model:')) {
                [$_, $model, $id] = explode(':', $val);
                return $model::findOrFail($id);
            }

            return $val;
        }, json_decode($value, true));
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
    public function set($model, string $key, $value, array $attributes) {
        return json_encode(array_map(function ($val) {
            if ($val instanceof Model)
                return 'model:' . get_class($val) . ':' . $val->id;

            return $val;
        }, $value));
    }
}