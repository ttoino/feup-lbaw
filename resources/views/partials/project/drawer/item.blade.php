<a href="{{ route($route, ['id' => $project->id]) }}" @class([
    'nav-link',
    'nav-item',
    'active' => Request::route()->getName() === $route,
])>
    {{ $label }}
</a>
