<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Http\Services\TaskService;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateStatusTaskRequest;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;  // inject taskService to the controller
    }
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $tasks = $this->taskService->listTask();
        return view('home', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = Status::all(); // Assuming you have a Status model
        return view('create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $taskData = $request->validated();
        $this->taskService->createTask($taskData);
        Cache::forget('tasks');
        return redirect()->route('home')->with('success', 'Task created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {

        // return parent::successResponse('Task', new TaskResource($task), 'Task retrived Successfully', 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        if($task->owner_id == auth()->user()->id){
            return view('edit', compact('task'));
        }
        abort(403, 'You are not the owner of this task.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $taskData = $request->validated();
        $task = $this->taskService->updateTask($taskData, $task);
        Cache::forget('tasks');
        return redirect()->route('home')->with('success', 'Task updated successfully');

    }
    public function updateStatus(Task $task)
    {

        $this->taskService->updateStatus($task);

        return redirect()->route('home')->with('success', 'Task Status Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $Task)
    {
        $this->taskService->deleteTask($Task);
        Cache::forget('tasks');
        return redirect()->route('home')->with('success', 'Task deleted successfully');

    }

    /**
     * Display a paginated listing of the trashed (soft deleted) resources.
     */
    public function trashed(Request $request)
    {
        $tasks = $this->taskService->trashedListTask();
        return view('trashedTasks', compact('tasks'));
    }

    /**
     * Restore a trashed (soft deleted) resource by its ID.
     */
    public function restore($id)
    {
        $this->taskService->restoreTask($id);
        return redirect()->back()->with('success', 'Task restored Successfully');
    }

    /**
     * Permanently delete a trashed (soft deleted) resource by its ID.
     */
    public function forceDelete($id)
    {
        $this->taskService->forceDeleteTask($id);
        return redirect()->back()->with('success', 'Tasks Deleted Successfully');
    }
}
