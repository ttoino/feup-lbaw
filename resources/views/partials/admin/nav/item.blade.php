<a href="{{ route($route) }}" @class([
    'nav-link',
    'nav-item',
    'active' => Request::route()->getName() === $route,
])>
    {{ $label }}
</a>
