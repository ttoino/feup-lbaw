<form action="{{ route('project.task.new', ['project' => $project]) }}"
    method="post" class="needs-validation" novalidate>
    @csrf

    <div class="form-floating">
        <input aria-label="Create Thread title" aria-describedby="thread-title"
            @class(['form-control', 'is-invalid' => $errors->has('title')]) id="title" type="text"
            name="title" placeholder="Create Thread" minlength=6 maxlength=50
            required>
        <label for="title" class="form-label">Title</label>
        <div class="invalid-feedback" id="title-feedback">
            @error('title')
                {{ $message }}
            @else
                Invalid title
            @enderror
        </div>
    </div>

    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="write-tab" data-bs-toggle="tab"
                data-bs-target="#write-tab-pane" type="button" role="tab"
                aria-controls="write-tab-pane"
                aria-selected="true">Write</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="write-tab" data-bs-toggle="tab"
                data-bs-target="#preview-tab-pane" type="button" role="tab"
                aria-controls="preview-tab-pane"
                aria-selected="false">Preview</button>
        </li>
    </ul>
    <div class="tab-content">
        <div class="form-floating tab-pane fade show active" id="write-tab-pane"
            role="tabpanel" aria-labelledby="write-tab" tabindex="0">
            <textarea placeholder="" style="height: 240px" @class(['form-control', 'is-invalid' => $errors->has('content')])
                aria-describedby="write-content-feedback" id="write-content" name="content"
                minlength=6 maxlength=512 required></textarea>
            <label for="write-content" class="form-label">Content</label>
            <div class="invalid-feedback" id="write-content-feedback">
                @error('content')
                    {{ $message }}
                @else
                    Invalid content
                @enderror
            </div>
        </div>
        <div class="form-floating tab-pane fade" id="preview-tab-pane"
            role="tabpanel" aria-labelledby="preview-tab" tabindex="0">
            <textarea placeholder="" style="height: 240px" @class(['form-control', 'is-invalid' => $errors->has('content')])
                aria-describedby="preview-feedback" id="preview-content" name="content"
                minlength=6 maxlength=512 required readonly></textarea>
            <label for="preview-content" class="form-label"></label>
        </div>
        <a href="https://www.markdownguide.org/basic-syntax/"><i
                class="bi bi-markdown"></i> Markdown is supported</a>
    </div>

    <div class="form-floating">
        <textarea placeholder="" style="height: 240px" @class(['form-control', 'is-invalid' => $errors->has('content')])
            aria-describedby="content-feedback" id="content" name="content"
            minlength=6 maxlength=512 required></textarea>
        <a href="https://www.markdownguide.org/basic-syntax/"><i
                class="bi bi-markdown"></i> Markdown is supported</a>
        <label for="content" class="form-label">Content</label>
        <div class="invalid-feedback" id="content-feedback">
            @error('content')
                {{ $message }}
            @else
                Invalid content
            @enderror
        </div>
    </div>

    <button class="btn btn-primary" type="submit">Create thread</button>
</form>
