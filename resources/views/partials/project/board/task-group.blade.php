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
            <a href="{{ route('project.task.info', ['id' => $group->project, 'taskId' => $t->id]) }}"
                class="shadow-sm rounded p-1 bg-white">{{ $t->name }}</a>
        @endforeach
        <form method="@yield('method', 'POST')" action="{{ url('/project/' . $group->project . '/task/new')}}">
            @csrf
            <div class="input-group p-1 mb-3">
                <input aria-label="Create Task Name" aria-describedby="task-name" class="form-control" id="name" type="text" name="name" placeholder="New Task" required>
                <button class="btn btn-outline-secondary" type="submit">&plus;</button>
            </div>  
            <input type="hidden" class="form-control" id="position" name="position" value="{{ count($group->tasks) + 1 }}">
            <input type="hidden" class="form-control" id="task_group" name="task_group" value="{{$group->id}}">
        </form> 
    @else
        <button href="{{ route('project.home', ['id' => $project->id]) }}" class="btn btn-primary">
            <i class="bi-add"></i>Create group
        </button>
    @endisset

</div>
