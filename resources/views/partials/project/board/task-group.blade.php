<div @class([
    'shadow-sm',
    'rounded',
    'p-2',
    'text-center',
    'd-flex',
    'flex-column',
    'gap-2',
    'mh-100',
    'bg-light',
    'flex-shrink-0',
]) style="width: 270px; overflow-y: auto">

    @isset($group)
        <p class="m-0">{{ $group->name }}</p>
        @foreach ($group->tasks as $t)
            <a href="{{ url("/project/$group->project/task/$t->id") }}"
                class="shadow-sm rounded p-1 bg-white">{{ $t->name }}</a>
        @endforeach
        <button href="{{ route('project.home', ['id' => $group->project]) }}"
            class="btn btn-primary">
            <i class="bi-add"></i>Create task
        </button>
    @else
        <button href="{{ url('/project/' . $project->id) }}" class="btn btn-primary">
            <i class="bi-add"></i>Create group
        </button>
    @endisset

</div>
