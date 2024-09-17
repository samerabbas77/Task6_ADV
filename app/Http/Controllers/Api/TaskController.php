<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Task;
use App\Models\Project;
use App\Services\Api\TaskService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Task\IndexTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\Task\AddCommentsRequest;
use App\Http\Requests\Task\GetPrioretyTaskRequest;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    //............................................................................................
    //............................................................................................
    /**
     * get all the task with filter
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(IndexTaskRequest $request)
    {  
        $validated = $request->validated();
            // Fetch tasks using the service class
            $tasks = $this->taskService->indexService($validated);

            // Return the tasks as a response
            return response()->json([
                'message' => 'Fetching data successfully',
                'data' => $tasks]);

    }
    //.............................................................................................
    //.............................................................................................

    // Manager can add tasks
    public function store(StoreTaskRequest $request)
    {
        $request->validated() ;
        $validated = $request->only(['title','description','status','priority','project_id','time_spent']);
        $task = $this->taskService->createTask($validated);

        return response()->json([
            'message' => 'Fetching data successfully',
            'data' => $task]);
    }

//..................................................................................................
//..................................................................................................
    /**
     * Developer can only update task status
     * @param \App\Http\Requests\Task\UpdateTaskRequest $request
     * @param \App\Models\Task $task
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update( UpdateTaskRequest $request, Task $task )
    {
        $validated = $request->validated();
        $task = $this->taskService->updateTask($validated,$task);
       

        return response()->json([
            'message' => 'Fetching data successfully',
            'data' => $task]);
    }
    //...................................................................................
    //...................................................................................
    /**
     * show spicific task
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    { 
            // Get the task by ID using the service
            $task = $this->taskService->showService($id);
            if($task!= null)
            {          
                return response()->json($task);
            }else{
                return response()->json(['You are Not Admin']);
            }


    }
//........................................................................................
//........................................................................................
    /**
     *  Delete a specific task by ID
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
            // Delete the task using the service
            $result = $this->taskService->deleteService($id);
            if($result!= null)
            {          
                           // Return a success message
            return response()->json(['message' => 'Task deleted successfully'], 204);
            }else{
                return response()->json(['You are Not Admin']);
            }


    }
//...........................................................................................
//...........................................................................................
    /**
     * Testers can add comments only
     * @param \App\Http\Requests\Task\AddCommentsRequest $request
     * @param \App\Models\Task $task
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function addComment(AddCommentsRequest $request, Task $task)
    {
        $comment = $request->validated();
        $task = $this->taskService->addComment($task, $comment);

        return response()->json([
            'message' => 'new Task',
            'data' => $task]); 
    }
//...........................................................................................
//...........................................................................................
/**
 * Summary of getLatestTask
 * @param mixed $id
 * @return mixed|\Illuminate\Http\JsonResponse
 */
    public function getLatestTask($id)
    {
        $task = $this->taskService->getLatestTask($id);
        return response()->json([
            'message' => 'Lastest Task',
            'data' => $task]);

    }
//...........................................................................................
//...........................................................................................
/**
 * Summary of getOldestTask
 * @param mixed $id
 * @return mixed|\Illuminate\Http\JsonResponse
 */
    public function getOldestTask($id)
    {
        $task = $this->taskService->getOldestTask($id);
        return response()->json([
            'message' => 'oldest Task',
            'data' => $task]);

    }
//.......................................................................................................
//.......................................................................................................
    /**
     * Summary of getHighestTaskPriority
     * @param \App\Models\Project $project
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getHighestTaskPriority(GetPrioretyTaskRequest $request,Project $project)
    {
       $request->validated();
       $validated = $request->only(['title']);
        $task = $this->taskService->getHighestTaskPriorityService($project,$validated);
        return response()->json([
            'message' => 'Task with Highest Priority',
            'data' => $task]);
    }
}
