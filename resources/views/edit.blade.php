<!-- resources/views/tasks/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mt-5 mb-4">Edit Task</h1>

    <form action="{{ route('tasks.update', $task) }}" method="POST" class="bg-light p-5 rounded shadow-sm">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="title" class="form-label">Title:</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $task->title) }}" maxlength="255">
        </div>

        <div class="form-group mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea id="description" name="description" class="form-control" maxlength="255">{{ old('description', $task->description) }}</textarea>
        </div>

        <div class="form-group mb-4">
            <label for="due_date" class="form-label">Due Date:</label>
            <input type="date" id="due_date" name="due_date" class="form-control"
                   value = {{ $task->due_date }}
                   min="{{ \Carbon\Carbon::now()->toDateString() }}">
            <small class="form-text text-muted">Please select a date in the future.</small>
        </div>


        <button type="submit" class="btn btn-primary w-100">Update Task</button>
    </form>
</div>
@endsection
