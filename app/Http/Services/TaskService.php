<?php
namespace App\Http\Services;

use Exception;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskService
{
    public function createTask(array $fieldInputs)
    {

        try {
        $task = Task::create([
            'title' => $fieldInputs['title'],
            'description' => $fieldInputs['description'],
            'status_id' => $fieldInputs['status'],
            'due_date' => $fieldInputs['due_date'],
            'owner_id' => auth()->user()->id,
            ]);
            Cache::forget('tasks_' . Auth::id());
       return $task;
        } catch (Exception $e) {
            Log::error('Error creating Task: ' . $e->getMessage());
            throw new HttpResponseException(response()->json('there is something wrong in server',500), );
        }
    }

    public function updateTask($taskData,$task){
        try{
           $data = [
                'title' => $taskData['title'] ?? $task->title,
                'description' => $taskData['description'] ?? $task->description,
                'due_date' => $taskData['due_date'] ?? $task->due_date,
           ];
           $task->update($data);
           Cache::forget('tasks_' . Auth::id());
        }
        catch(HttpException $e){
            Log::error('Error updating Task: ' . $e->getMessage());
            abort( 403 , 'You are not authorized to update this task.');
        }
        catch (Exception $e) {
            Log::error('Error updating Task: ' . $e->getMessage());
            abort( 500 , 'Server Error');
        }
    }
    public function updateStatus($task){
        try{
         $task->status_id = $task->status_id === 1 ? 2 : 1;
         $task->save();
         Cache::forget('tasks_' . Auth::id());
        }catch(HttpException $e){
            Log::error('Error updating Task: ' . $e->getMessage());
            abort( 403 , 'You are not authorized to update this task.');
        }
        catch (Exception $e) {
            Log::error('Error updating Task: ' . $e->getMessage());
            abort( 500 , 'Server Error');
        }
    }
    public function listTask(){
        try {
            if (!Auth::check()) {
                throw new Exception("User not authenticated.");
            }

            $tasks = Cache::remember('tasks_' . Auth::id(),  10, function () {
                return Auth::user()->tasks;
            });

            return $tasks;
        } catch (Exception $e) {
            Log::error('Error retrieving Task: ' . $e->getMessage());
            abort(500,'There is something wrong on the server');
        }
    }

    /**
     * Delete a specific Task.
     *
     * @param Task $Task
     * @return void
     */
    public function deleteTask($Task)
    {
        try {
            $Task->delete();
            Cache::forget('tasks_' . Auth::id());
        } catch (ModelNotFoundException $e) {
            Log::error('Error finding Task: ' . $e->getMessage());
            abort(404,'Task not found');
        } catch (Exception $e) {
            Log::error('Error deleting Task: ' . $e->getMessage());
            abort( 500 , 'Server Error');
        }
    }

    /**
     * Display a paginated listing of the trashed (soft deleted) resources.
     */
    public function trashedListTask()
    {
        try {
            return Task::onlyTrashed()->simplePaginate(10);
        } catch (Exception $e) {
            Log::error('Error trashed List Task: ' . $e->getMessage());
            abort( 500 , 'Server Error');
        }
    }

    /**
     * Restore a trashed (soft deleted) resource by its ID.
     *
     * @param  int  $id  The ID of the trashed Task to be restored.
     */
    public function restoreTask($id)
    {
        try {
            $Task = Task::onlyTrashed()->findOrFail($id);
            $Task->restore();
            Cache::forget('tasks_' . Auth::id());
         } catch (ModelNotFoundException $e) {
                Log::error('Error finding Task: ' . $e->getMessage());
                abort(404 , 'Not Found');
            }
             catch (Exception $e) {
                Log::error('Error restoring Task: ' . $e->getMessage());
                abort( 500 , 'Server Error');
            }
    }

    /**
     * Permanently delete a trashed (soft deleted) resource by its ID.
     *
     * @param  int  $id  The ID of the trashed Task to be permanently deleted.
     */
    public function forceDeleteTask($id)
    {
        try {
            $trashedTask = Task::onlyTrashed()->findOrFail($id);
            if(auth()->id() !== $trashedTask->owner_id){
                throw new HttpResponseException(response()->json(['message' => 'You can not delete this task, You\'re not creator!'],403), );
            }
            $trashedTask->forceDelete();
            Cache::forget('tasks_' . Auth::id());
        } catch (ModelNotFoundException $e) {
            Log::error('Error finding Task: ' . $e->getMessage());
            abort(404 , 'Not Found');
        }
         catch (Exception $e) {
            Log::error('Error restoring Task: ' . $e->getMessage());
            abort( 500 , 'Server Error');
        }
    }
}
