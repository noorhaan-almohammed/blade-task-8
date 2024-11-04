@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ __('Dashboard') }}</h4>
                    <div>
                        <a href="{{ route('tasks.trashed') }}" class="btn btn-dark btn-sm">Trashed Tasks</a>
                        <a href="{{ route('tasks.create') }}" class="btn btn-light btn-sm">+ Add Task</a>
                    </div>

                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h2 class="mt-4 mb-4 text-center"> Todo List </h2>

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(isset($tasks) && $tasks->isEmpty())
                        <p class="text-center"> No tasks added ! add some tasks to do .. </p>
                    @elseif(isset($tasks))
                        <ul class="list-group">
                            @foreach($tasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route('tasks.edit', $task) }}" class="text-decoration-none font-weight-bold">
                                            {{ $task->title }}
                                        </a>
                                        <p class="mb-0 text-muted">{{ $task->description }}</p>
                                    </div>
                                    <div style="display: flex ; column-gap: 10px;">
                                        <form action="{{ route('tasks.updateStatus', $task) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <button type="submit" class="btn btn-outline-primary">
                                                {{ $task->status_id === 1 ? 'Pending' : 'Completed' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button style="height: -webkit-fill-available" type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
