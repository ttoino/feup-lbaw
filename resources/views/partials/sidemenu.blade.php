<aside class="nav flex-column col-2 border-end border-secondary">
    <a class="nav-link" href="{{ url('/project/' . $project->id)}}">{{$project->name}}</a>
    <a class="nav-link disabled" href=>Info</a>
    <a class="nav-link" href="{{ url('/project/' . $project->id)}}">Board</a>
    <a class="nav-link disabled" href=>Timeline</a>
    <a class="nav-link disabled" href=>Forum</a>
</aside>    