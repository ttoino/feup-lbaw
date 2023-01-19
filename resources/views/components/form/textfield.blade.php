@props(['name', 'type' => 'text'])
@php($id ??= $name)

<div @class(['form-floating', 'input-group has-validation password-input' => $type == 'password'])>
    <input {{ $attributes->merge([
            'class' => 'form-control',
            'placeholder' => '',
            'name' => $name,
            'id' => $id,
            'type' => $type
        ]) }}
        aria-describedby="{{ $id }}-feedback">
    <label for="{{ $id }}" class="form-label">{{$slot}}</label>
    @if ($type == 'password')
        <x-button outline icon />
    @endif
    <div class="invalid-feedback" id="{{ $id }}-feedback">
    </div>
</div>
