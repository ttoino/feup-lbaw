<a href="{{ route($route, ['project' => $project]) }}" @class([
    'nav-link',
    'nav-item',
    'active' => Request::route()->getName() === $route,
])>
    {{ $label }}
</a>
