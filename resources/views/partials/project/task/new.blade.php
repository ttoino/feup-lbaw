<div class="m-auto vstack gap-4">
    <div class="form-floating">
        <input aria-describedby="name-feedback" placeholder="" @class(['form-control', 'is-invalid' => $errors->has('name')]) id="name" type="text"
            name="name" value="{{ old('name') }}" minlength=4 maxlength=255 required autofocus>
        <label for="name" class="form-label">Name</label>
        @error('name')
            {{ $message }}
        @enderror
    </div>

    <div class="m-auto hstack gap-4 p-1">
        <div class="form-floating">
            <textarea placeholder="" style="height: 120px" class="form-control" aria-describedby="description-feedback"
                id="description" name="description" minlength=6 maxlength=512></textarea>
            <label for="description" class="form-label">Description</label>
        </div>
        <div class="m-auto vstack gap-4 p-3">
            <div class="form-floating">
                <select class="form-select" aria-describedby="task-group-feedback" name="task_group" id="task-group"
                    required>
                    <option selected disabled value="">Select a group to place this task in...</option>
                    @foreach ($project->taskGroups as $taskGroup)
                        <option value="{{ $taskGroup->id }}">{{ $taskGroup->name }}</option>
                    @endforeach
                </select>
                <label for="description" class="form-label">Task Group</label>
                <div id="task-group-feedback" class="invalid-feedback">
                    Please select a valid task group.
                </div>
            </div>
        </div>
    </div>
</div>
