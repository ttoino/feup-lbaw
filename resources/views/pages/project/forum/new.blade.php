@extends('pages.project.forum')

@section('form')
    <h2>Create a new Thread</h2>

    <div class="form-floating">
        <input aria-label="Create Thread title" aria-describedby="thread-title"
            @class(['form-control', 'is-invalid' => $errors->has('title')]) id="title" type="text" name="title"
            placeholder="Create Thread" minlength=6 maxlength=50 required>
        <label for="title" class="form-label">Title</label>
        <div class="invalid-feedback" id="title-feedback">
            @error('title')
                {{ $message }}
            @else
                Invalid title
            @enderror
        </div>
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

    <button class="btn btn-primary" type="submit">Create Thread</button>
@endsection

@section('forum-content')
    @include('partials.centered-form')
@endsection
