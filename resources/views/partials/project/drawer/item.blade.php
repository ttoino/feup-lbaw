<a href="{{ route($route, ['project' => $project]) }}" @class([
    'nav-link',
    'nav-item',
    'active' => Request::route()->getName() === $route, # FIXME: handle project forum
])>
    {{ $label }}
</a>
