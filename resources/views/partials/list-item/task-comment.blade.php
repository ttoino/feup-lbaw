<li class="list-group-item position-relative d-flex flex-row justify-content-between" data-task-id="{{ $item->id }}">
    <div class='align-self-center gap-2 hstack'>
        <a href="{{ route('user.profile', ['user' => $item->author]) }}">
            <img src="{{ asset($item->author->getProfilePicture()) }}"
                alt="{{ $item->author->name }}" 
                width="40" height="40" class="rounded-circle">
        </a>
        <span> - {{$item->content}} </span>
    </div>
    <span class='align-self-center'>
        <i class="bi bi-calendar mx-1"></i>
        @include('partials.datediff', [
            'date' => $item->creation_date,
        ])
    </span>
</li>