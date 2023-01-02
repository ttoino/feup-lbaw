<?php

namespace App\Casts;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Datetime implements CastsAttributes {
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
        $carbon = Carbon::parse($value);

        return [
            'iso' => $carbon->toISOString(),
            'long_diff' => $carbon->diffForHumans(['aUnit' => true]),
            'diff' => $carbon->diffForHumans(['aUnit' => true, 'short' => true, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]),
            'datetime' => $carbon->isoFormat('MMM D Y, H:mm'),
            'date' => $carbon->isoFormat('MMM D Y'),
            'time' => $carbon->isoFormat('H:mm')
        ];
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
        return is_array($value) ? $value['iso'] : ($value instanceof Carbon ? $value->toISOString() : $value);
    }
}