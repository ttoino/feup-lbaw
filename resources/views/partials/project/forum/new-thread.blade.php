<form class="needs-validation" novalidate>
    <div class="form-floating">
        <input aria-describedby="new-thread-title-feedback" class="form-control" type="text" name="title"
            id="new-thread-title" placeholder="" minlength=6 maxlength=50 required>
        <label for="new-thread-title" class="form-label">Title</label>
        <div class="invalid-feedback" id="new-thread-title-feedback">
            Invalid title
        </div>
    </div>

    <div class="form-floating">
        <textarea placeholder="" class="form-control auto-resize" id="new-thread-content"
            aria-describedby="new-thread-content-feedback" name="content" minlength=6 maxlength=512 required></textarea>
        <a href="https://www.markdownguide.org/basic-syntax/"><i class="bi bi-markdown"></i> Markdown is supported</a>
        <label for="new-thread-content" class="form-label">Content</label>
        <div class="invalid-feedback" id="new-thread-content-feedback">
            Invalid content
        </div>
    </div>

    <button class="btn btn-primary" type="submit">Create thread</button>
</form>
