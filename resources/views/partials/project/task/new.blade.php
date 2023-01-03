<form id="new-task-form" class="needs-validation" novalidate>
    <div class="form-floating">
        <input aria-describedby="new-task-name-feedback" placeholder=""
            class="form-control" type="text" name="name" id="new-task-name"
            value="{{ old('name') }}" minlength=4 maxlength=255 required>
        <label for="new-task-name" class="form-label">Name</label>
        <div id="new-task-name-feedback" class="invalid-feedback">
            Please enter a valid name.
        </div>
    </div>

    <div class="form-floating">
        <textarea placeholder="" class="form-control auto-resize"
            aria-describedby="new-task-description-feedback" name="description"
            id="new-task-description" minlength=6 maxlength=512></textarea>
        <label for="new-task-description" class="form-label">Description</label>
        <div id="new-task-description-feedback" class="invalid-feedback">
            Please enter a valid description.
        </div>
    </div>

    <div class="form-floating">
        <select @class(['form-select', 'is-invalid' => $errors->has('task-group')])
            aria-describedby="task-group-feedback" name="task_group_id"
            id="new-task-task-group" required>
            @foreach ($project->taskGroups as $taskGroup)
                <option value="{{ $taskGroup->id }}">
                    {{ $taskGroup->name }}</option>
            @endforeach
        </select>
        <label for="new-task-task-group" class="form-label">Task Group</label>
        <div id="new-task-task-group-feedback" class="invalid-feedback">
            Please select a valid task group.
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Create task</button>

</form>
