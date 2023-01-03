@php($assignee ??= null)

<li>
    <a href="{{ route('user.profile', ['user' => $assignee ?? 0]) }}"
        data-render-href="id,{{ str_replace('123456789', '{}', route('user.profile', ['user' => 123456789])) }}"
        data-bs-toggle="tooltip" data-bs-title="{{ $assignee?->name ?? '' }}"
        data-render-attr="name,bs-title" data-bs-placement="bottom">
        <img src="{{ asset($assignee?->profile_pic ?? '') }}"
            data-render-src="profile_pic" alt="{{ $assignee?->name ?? '' }}"
            width="24" height="24" class="rounded-circle">
    </a>
</li>
