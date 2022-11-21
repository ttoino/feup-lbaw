<a href="{{ url($path) }}" @class([
    'nav-link',
    'nav-item',
    'active' => Request::path() === $path,
])>
    {{ $label }}
</a>
