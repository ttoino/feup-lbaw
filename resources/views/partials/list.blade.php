<ul class="list-group">
    @foreach ($paginator as $item)
        @include($itemView, ['item' => $item])
    @endforeach
</ul>
