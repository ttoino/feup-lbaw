<li class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center gap-2 editable"
    data-tag-id="{{ $item->id }}">

    <div style="background-color: rgb(var(--tag-color)); --tag-color: {{ $item->rgb_color }}"
        data-render-css-var="color,tag-color" class="p-2 rounded-circle">
    </div>

    <span class="fw-bold flex-fill" data-render-text="title">
        {{ $item->title }}
    </span>

    @if (!$item->project->archived)
        @can('update', $item)
            <button class="btn btn-outline-primary edit-tag" style="z-index: 5">
                <i class="bi bi-pencil"></i>
            </button>

            <form class="edit edit-tag-form input-group">
                <input class="form-control form-control-color" type="color"
                    data-render-value="color" name="color" required
                    value="{{ $item->color }}" style="max-width: 38px">
                <input type="text" minlength="6" maxlength="50" required
                    name="title" value="{{ $item->title }}" data-render-value="title"
                    placeholder="Tag title" class="form-control">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-check-lg"></i>
                </button>
            </form>
        @endcan
    @endif

    @can('delete', $item)
        <button class="btn btn-outline-danger delete-tag" style="z-index: 5"><i
                class="bi bi-trash"></i></button>
    @endcan
</li>
