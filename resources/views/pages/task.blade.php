@extends('layouts.project')

@section('title', $task->name)

@section('project-content')
    <section class="flex-fill vstack p-3" style="max-width: 480px">
        <div class="hstack justify-content-between align-items-center">
            <h2>{{ $task->name }}</h2>
            @if ($task->state === 'completed')
                <i class="h3 bi bi-check-circle-fill"></i>
            @endif
        </div>
        <p>{{ $task->description }}</p>

        <ul class="list-unstyled hstack gap-2">
            @foreach ($task->tags as $tag)
                <li class="p-1 rounded border"
                    style="background: rgba({{ $tag->rgbColor() }}, 30%); --bs-border-color: rgb({{ $tag->rgbColor() }})">
                    {{ $tag->title }}
                </li>
            @endforeach
        </ul>

        <div>Position: {{ $task->position }}</div>

        <form id="iprof" method="POST" action>
            @csrf

            <select name="task_group" required>
                @foreach ($task->project->taskGroups as $g)
                    <option value={{ $g->id }} @if ($g->id == $task->taskGroup->id) selected @endif>{{ $g->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">
                Change
            </button>
        </form>

        @if (!$task->assignees->isEmpty())
            <h3>Assignees</h3>

            @include('partials.list', [
                'paginator' => $task->assignees,
                'itemView' => 'partials.list-item.user',
            ])
        @endif
    </section>
@endsection
