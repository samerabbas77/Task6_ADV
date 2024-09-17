<?php
namespace App\Services\Api;

use Exception;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class TaskService
{
        /**
         * Method to fetch filtered tasks by status and priority(only admin)
         * @param mixed $status
         * @param mixed $priority
         * @throws \Exception
         * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
         */
        public function indexService($validated)
        {
            try {
                    // Start the task query
                    $query = Task::query();
        
                    // Filter by status if provided
                    if ($validated['status']) {
                        $query->whereRelation('project', 'tasks.status', $validated['status']);
                    }
        
                    // Filter by priority if provided
                    if ($validated['priority']) {
                        $query->whereRelation('project', 'tasks.priority', $validated['priority']);
                    }
        
                    // Return the filtered tasks
                    return $query->get();
            } catch (Exception $e) {
                // Throw exception for any errors
                throw new Exception('Error fetching tasks: ' . $e->getMessage());
            }
        }
            //.....................................................................................
            //.....................................................................................
            /**
             * store a task in dataBase (only manager)
             * @param mixed $validated
             * @throws \Exception
             * @return Task|\Illuminate\Database\Eloquent\Model
             */
            public function createTask($validated)
            {
                try {
                    $user = Auth::user();

                    // Create the task with validated data
                    return Task::create([
                        'title'=> $validated['title'],
                        'description' => $validated['description'],
                        'status' => $validated['status'],
                        'priority' => $validated['priority'],
                        'time_spent' => $validated['time_spent'],
                        'project_id' => $validated['project_id'],
                    ]);
                } catch (Exception $e) {
                    throw new Exception('Error creating task: ' . $e->getMessage());
                }
            }
        
    //.....................................................................................
    //.....................................................................................
    /**
     * update a task(only manager)
     * @param mixed $validated
     * @param mixed $task
     * @throws \Exception
     * @return mixed
     */
    public function updateTask($validated,$task)
    {
        try {
            // Update the task with validated data
            $task->title =  $validated['title']??$task->title;
            $task->description  = $validated['description']??$task->description;
            $task->status =  $validated['status']??$task->status;
            $task->priority =  $validated['priority']??$task->priority;
            $task->time_spent  = $validated['time_spent']?? $task->time_spent;
            $task->project_id  = $validated['project_id']??$task->project_id;

            return $task;
        } catch (Exception $e) {
            throw new Exception('Error updating task: ' . $e->getMessage());
        }
    }
    
     //.....................................................................................
    //.....................................................................................
    /**
     * show a task
     * @param mixed $taskId
     * @throws \Exception
     * @return Task|Task[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function showService($taskId)
    {
        try {
               if( Auth::user()->rule == 'admin')
               {
                // Find the task by ID, if not found it throws a ModelNotFoundException
                $task = Task::findOrFail($taskId);
                return $task;
                }else{
                    return null;
                }

           
        } catch (Exception $e) {
            // Throw exception with custom message
            throw new Exception('Error fetching task: ' . $e->getMessage());
        }
    }

    //.....................................................................................
    //.....................................................................................

    /**
     * Summary of deleteService
     * @param mixed $taskId
     * @throws \Exception
     * @return bool|null
     */
    public function deleteService($taskId)
    {
        try {
           if(Auth::user()->rule == 'admin')
           {
                         // Find the task by ID, if not found it throws a ModelNotFoundException
            $task = Task::findOrFail($taskId);
            // Delete the task
            $task->delete();
           }else{
            return null;
           }
   

        } catch (Exception $e) {
            // Throw exception with custom message
            throw new Exception('Error deleting task: ' . $e->getMessage());
        }
    }
    //...........................................................................
   //...........................................................................
    /**
     * add comment to a spicfic task (only by tester)
     * @param mixed $task
     * @param mixed $comment
     * @throws \Exception
     * @return Task|Task[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function addComment($task, $comment)
    {
        try {
            $task = Task::findOrFail($task->id);

            $task->comment = $comment['comment'];
            $task->save();

            return $task;
        } catch (Exception $e) {
            throw new Exception('Error while add comment: ' . $e->getMessage());
        }
    }
    //...........................................................................
    //...........................................................................
    /**
     * Summary of getLatestTask
     * @param mixed $projectId
     * @throws \Exception
     * @return mixed
     */
    public function getLatestTask($projectId)
    {
        try {
            $project = Project::findOrFail($projectId);

            // Using latestOfMany to get the latest task
            return $project->lastTask()->first();
        } catch (Exception $e) {
            throw new Exception('Error fetching latest task: ' . $e->getMessage());
        }
    }
//.............................................................................
//.............................................................................
    // Fetch the oldest task for a project
    public function getOldestTask($projectId)
    {
        try {
             $project = Project::findOrFail($projectId);
           
            // Using oldestOfMany to get the oldest task
           return $project->oldestTask()->first();
        } catch (Exception $e) {
            throw new Exception('Error fetching oldest task: ' . $e->getMessage());
        }
    }
//..............................................................................
//..............................................................................
    /**
     * Summary of getHighestTaskPriorityService
     * @param mixed $project
     * @throws \Exception
     * @return mixed
     */
    public function getHighestTaskPriorityService($project,$validated)
    {
        try {
           $task = $project->maxPriority($validated['title'])->first();
         
           return $task;
        } catch (Exception $e) {
            throw new Exception('Error while add comment: ' . $e->getMessage());
        }
    }




            
}