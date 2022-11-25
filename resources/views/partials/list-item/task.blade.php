<li class="list-group-item list-group-item-action position-relative hstack justify-content-between"
    data-task-id="{{ $item->id }}">
    @include('partials.task-common', ['task' => $item])
</li>
