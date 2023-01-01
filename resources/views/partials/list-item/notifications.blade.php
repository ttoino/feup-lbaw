<li class="list-group-item list-group-item-action position-relative d-flex flex-row align-items-center gap-2">
    @php
        use App\Models\Project;
        use App\Models\Task;
        use App\Models\TaskComment;
        use App\Models\Thread;
        use App\Models\ThreadComment;
        $json = json_decode($item->json, true);
        if(isset($json['project'])){
            $p = Project::where('id', $json['project'])->firstOrFail();
        }
        if(isset($json['task'])){
            $task = Task::where('id', $json['task'])->firstOrFail();
        }
        if(isset($json['comment'])){
            $comment = TaskComment::where('id', $json['comment'])->firstOrFail();
        }
        if(isset($json['thread'])){
            $thread = Thread::where('id', $json['thread'])->firstOrFail();
        }
        if(isset($json['thread_comment'])){
            $thread_comment = ThreadComment::where('id', $json['thread_comment'])->firstOrFail();
        }
    @endphp
    <div class="hstack flex-fill justify-content-between">
        @if ($item->type === 'App\Notifications\ProjectInvite')
            <a href={{url($json['url'])}}>You've been invited to join project <strong>{{$p->name }}</strong>. Click here to join.</a>
        @elseif($item->type === 'App\Notifications\ProjectRemoved')
            <a href={{route('project.list')}}> You were removed from project <strong>{{$p->name }}</strong>. </a>
        @elseif($item->type === 'App\Notifications\ProjectArchived')
            <a href={{route('project.board', ['project' => $p])}}> Project <strong>{{$p->name }}</strong> has been archived. </a>    
        @elseif($item->type === 'App\Notifications\TaskCommented')
            <a href={{route('project.task.info', ['project' => $comment->task->project, 'task' => $comment->task])}}> {{$comment->author->name}} has commented on a task you're assigned to: "{{$comment->content}}" </a>           
        @elseif($item->type === 'App\Notifications\TaskCompleted')
            <a href={{route('project.task.info', ['project' => $task->project, 'task' => $task])}}> Task <strong>{{$task->name}}</strong> has been completed. </a>   
        @elseif($item->type === 'App\Notifications\ThreadNew')
            <a href={{route('project.thread', ['project' => $thread->project, 'thread' => $thread])}}> A new thread "{{$thread->title}}" has been opened by {{$thread->author->name}} on project <strong>{{$thread->project->name}}</strong>. </a>
        @elseif($item->type === 'App\Notifications\ThreadCommented')
            <a href={{route('project.thread', ['project' => $thread_comment->thread->project, 'thread' => $thread_comment->thread])}}> {{$thread_comment->author->name}} has commented on your thread {{$thread_comment->thread->title}}: "{{$thread_comment->content}}" </strong>. </a>    
        @endif
        <span class='align-self-center'>
            <i class="bi bi-calendar mx-1"></i>
            @include('partials.datediff', [
                'date' => $item->creation_date,
            ])
        </span>
    </div>
</li>