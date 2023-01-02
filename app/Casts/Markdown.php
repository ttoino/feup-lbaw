<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Spatie\LaravelMarkdown\MarkdownRenderer;

class Markdown implements CastsAttributes {
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
        return [
            'raw' => $value ?? '',
            'formatted' => app(MarkdownRenderer::class)->toHtml($value ?? '')
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
        return is_array($value) ? $value['raw'] : $value ;
    }
}