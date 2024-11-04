<!DOCTYPE html>
<html>
<head>
    <title>Pending Tasks</title>
</head>
<body>
    <h1>Hello, {{ $user->name }}</h1>
    <p>You have the following pending tasks:</p>
    <ul>
        @foreach ($tasks as $task)
            <li>{{ $task->title }} - Due on: {{ $task->due_date}}</li>
        @endforeach
    </ul>
    <p>Please complete them as soon as possible.</p>
    <p>Thank you!</p>
</body>
</html>
