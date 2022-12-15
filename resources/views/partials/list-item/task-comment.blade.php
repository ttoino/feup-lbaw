<li class="list-group-item position-relative d-flex flex-row justify-content-between" data-task-id="{{ $item->id }}">
    <p> {{$item->content}} </p>
    <a class="align-self-center" href="{{ route('user.profile', ['user' => $item->author]) }}">
        <img src="{{ asset($item->author->getProfilePicture()) }}"
            alt="{{ $item->author->name }}" 
            width="40" height="40" class="rounded-circle">
    </a>
</li>