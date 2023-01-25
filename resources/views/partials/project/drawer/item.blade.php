@php
    $currentRoute = Request::route();
    $currentRouteName = $currentRoute->getName();
@endphp

<a href="{{ route(is_array($route) ? $route[0] : $route, ['project' => $project]) }}" @class([
    'active' => is_array($route)
        ? in_array($currentRouteName, $route)
        : $route === $currentRouteName,
])>
    <i class="bi bi-{{ $icon }}"></i>
    {{ $label }}
</a>
