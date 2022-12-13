<li class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center gap-2 border p-2"
    data-thread-comment-id="{{ $threadComment->id }}">
    {{-- TODO: improve styling --}}
    <article class="vstack">
        <p>{{ $threadComment->content }}</p>
        <section class="hstack justify-content-between px-5 py-2">
            <a href="{{ route('user.profile', ['user' => $threadComment->author]) }}" role="button" aria-expanded="false"
                style="z-index: 100" class="hstack justify-content-around">
                <img width="40" height="40" alt="Profile picture"
                    src="{{ asset($threadComment->author->getProfilePicture()) }}" class="rounded-circle p-1">
                <span>{{ $threadComment->author->name }}</span>
            </a>
    
            @php
                use Carbon\Carbon;
                
                $creation_date = Carbon::parse($threadComment->creation_date);
            @endphp
            <p><i class="bi bi-calendar mx-1"></i>Created
                {{ $creation_date->diffForHumans(['aUnit' => true]) }}</p>
        </section>
    </article>

</li>
