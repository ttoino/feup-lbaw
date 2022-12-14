@php
    $currentRoute = Request::route();
    $currentRouteName = $currentRoute->getName();
@endphp

<a href="{{ route($route, ['project' => $project]) }}" @class([
    'nav-link',
    'nav-item',
    'active' => $currentRouteName === 'project.thread'
        ? $route === 'project.forum'
        : $route === $currentRouteName,
])>
    {{ $label }}
</a>
