@extends('layouts.project')

@section('title', $project->name)

@section('project-content')
    <section style="overflow-x: auto;" class="flex-fill d-flex flex-row gap-3 p-5 align-items-start flex-fill">
        <div class="col l-8">
            <section class="project-banner row">
                <h2 class="h2">{{ $project->name }}</h2>
                <p>{{ $project->description }}</p>
            </section>
            <section class="flex-fill d-flex flex-row gap-3">
                @php
                    use Carbon\Carbon;
                    
                    $creation_date = Carbon::parse($project->creation_date);
                    $last_modification_date = Carbon::parse($project->last_modification_date);
                @endphp
                <p><i class="bi bi-calendar mx-1"></i>Created {{ $creation_date->diffForHumans(['aUnit' => true]) }}
                </p>
                @if ($project->last_modification_date !== null)
                    <span>-</span>
                    <p>Last edited {{ $last_modification_date->diffForHumans(['aUnit' => true]) }}</p>
                @endif
            </section>
            <section class="flex-fill d-flex flex-row gap-3" style="max-width: 50%">
                @if (Request::user()->id === $project->coordinator_id)
                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-project-modal">
                        Delete project
                    </button>

                    <div class="modal fade" id="delete-project-modal" tabindex="-1"
                        aria-labelledby="delete-project-modal-label" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content modal-body gap-3 align-items-center">
                                <h3 class="modal-title fs-5" id="delete-project-modal-label">
                                    Are you sure you want to delete this project?
                                </h3>
                                <div class="hstack gap-2 align-self-center">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form method="POST" action="{{ route('project.delete', ['project' => $project]) }}">
                                        @csrf
                                        <button class="submit col btn btn-outline-danger">Confirm</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-outline-secondary" data-bs-toggle="modal"
                        data-bs-target="#archive-unarchive-project-modal">
                        @if ($project->archived)
                            Unarchive
                        @else
                            Archive
                        @endif
                        project
                    </button>

                    <div class="modal fade" id="archive-unarchive-project-modal" tabindex="-1"
                        aria-labelledby="archive-unarchive-project-modal-label" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content modal-body gap-3 align-items-center">
                                <h3 class="modal-title fs-5" id="archive-unarchive-project-modal-label">
                                    Are you sure you want to
                                    @if ($project->archived)
                                        unarchive
                                    @else
                                        archive
                                    @endif
                                    this project?
                                </h3>
                                <div class="hstack gap-2 align-self-center">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                                    @php
                                        if ($project->archived) {
                                            $route = 'project.unarchive';
                                        } else {
                                            $route = 'project.archive';
                                        }
                                    @endphp
                                    <form method="POST" action="{{ route($route, ['project' => $project]) }}">
                                        @csrf
                                        <button class="submit col btn btn-outline-danger">Confirm</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#leave-project-modal">
                        Leave project
                    </button>

                    <div class="modal fade" id="leave-project-modal" tabindex="-1"
                        aria-labelledby="leave-project-modal-label" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content modal-body gap-3 align-items-center">
                                <h3 class="modal-title fs-5" id="leave-project-modal-label">
                                    Are you sure you want to leave this project?
                                </h3>
                                <div class="hstack gap-2 align-self-center">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form method="POST" action="{{ route('project.leave', ['project' => $project]) }}">
                                        @csrf
                                        <button class="submit col btn btn-outline-danger">Confirm</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </section>
        </div>
        <div class="col l-4">
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
