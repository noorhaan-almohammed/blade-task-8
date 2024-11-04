@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <!-- Header -->
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ __('Trashed Tasks') }}</h4>
                    <a href="{{ route('home') }}" class="btn btn-light btn-sm">Home</a>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <!-- Status Messages -->
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- Title -->
                    <h2 class="mt-4 mb-4 text-center">Todo List</h2>

                    <!-- Tasks List -->
                    @if(isset($tasks) && $tasks->isEmpty())
                        <p class="text-center">No tasks trashed yet!</p>
                    @elseif(isset($tasks))
                        <ul class="list-group">
                            @foreach($tasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-primary">{{ $task->title }}</strong>
                                        <p class="mb-0 text-muted">{{ $task->description }}</p>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <!-- Restore Button -->
                                        <form action="{{ route('tasks.restore', $task->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-outline-success btn-sm">Restore</button>
                                        </form>
                                        <!-- Force Delete Button -->
                                        <form action="{{ route('tasks.forceDelete', $task->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
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
