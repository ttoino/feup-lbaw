@props(['name'])
@php($id ??= $name)

<div class="form-check">
    <input type="checkbox" class="form-check-input" name={{ $name }} id={{ $id }} {{ $attributes }}>
    <label class="form-check-label" for={{ $id }}>{{ $slot }}</label>
</div>
