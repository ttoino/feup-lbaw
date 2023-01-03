@extends('layouts.project')

@section('title', $project->name)

@push('main-classes', 'project-info-main ')

@section('project-content')
    <section class="project-info">
        <div class="left editable">
            <header>
                <h2 class="h1" data-render-text="name">{{ $project->name }}</h2>
                <p>
                    Created on
                    <time data-render-datetime="creation_date.iso"
                        data-render-text="creation_date.datetime"
                        datetime="{{ $project->creation_date['iso'] }}">
                        {{ $project->creation_date['datetime'] }}
                    </time>
                </p>
                <p>
                    Last edited
                    <time data-render-datetime="last_modification_date.iso"
                        data-render-text="last_modification_date.long_diff"
                        datetime="{{ $project->last_modification_date ? $project->last_modification_date['iso'] : '' }}">
                        {{ $project->last_modification_date ? $project->last_modification_date['long_diff'] : 'never' }}
                    </time>
                </p>
            </header>
            <div class="buttons">
                @can('update', $project)
                    <button id="edit-project-button" class="btn btn-outline-primary">
                        <i class="bi bi-pencil"></i>
                        Edit
                    </button>
                @endcan

                @can('archive', $project)
                    <button id="archive-project-button"
                        class="btn btn-outline-secondary">
                        <i class="bi bi-archive"></i>
                        Archive
                    </button>
                @endcan

                @can('unarchive', $project)
                    <button id="unarchive-project-button"
                        class="btn btn-outline-secondary">
                        <i class="bi bi-archive"></i>
                        Unarchive
                    </button>
                @endcan

                @can('delete', $project)
                    <button id="delete-project-button" class="btn btn-outline-danger">
                        <i class="bi bi-trash"></i>
                        Delete
                    </button>
                @endcan

                @can('leaveProject', $project)
                    <button id="leave-project-button" class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i>
                        Leave project
                    </button>
                @endcan

                <a href="{{ route('project.reportproject', ['project' => $project]) }}"
                    class="btn btn-outline-danger">
                    Report Project
                </a>
            </div>
            <div class="description" data-render-html="description.formatted">
                {!! $project->description['formatted'] !!}</div>

            <form id="edit-project-form" class="edit needs-validation" novalidate>
                <div class="form-floating">
                    <input aria-describedby="name-feedback" placeholder=""
                        data-render-value="name" class="form-control" id="name"
                        type="text" name="name" value="{{ $project->name }}"
                        minlength="6" maxlength="512" required autofocus>
                    <label for="name" class="form-label">Name</label>
                    <div class="invalid-feedback" id="name-feedback">
                        Invalid name
                    </div>
                </div>

                <div class="form-floating">
                    <textarea placeholder="" class="form-control auto-resize"
                        data-render-value="description.raw" aria-describedby="description-feedback"
                        id="description" name="description" minlength="6" maxlength="512" required>{{ $project->description['raw'] }}</textarea>
                    <label for="description" class="form-label">Description</label>
                    <div class="invalid-feedback" id="description-feedback">
                        Invalid description
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Save changes
                </button>
            </form>
        </div>
        <div class="right">
            <section class="user-list">
                <h2 class="h2">Project members</h2>
                @include('partials.paginated-list', [
                    'paginator' => $project->users->paginate(8),
                    'itemView' => 'partials.list-item.user',
                ])
            </section>
        </div>
    </section>
@endsection
