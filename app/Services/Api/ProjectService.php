<?php

namespace App\Services\Api;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Project;

class ProjectService
{
    // Fetch all projects
    public function getAllProjects()
    {
        try {
            return Project::with('users', 'tasks')->get();
        } catch (Exception $e) {
            throw new Exception('Error fetching projects: ' . $e->getMessage());
        }
    }
//........................................................................................
//........................................................................................
    /**
     * Create a new project
     * @param mixed $data
     * @throws \Exception
     * @return Project|\Illuminate\Database\Eloquent\Model
     */
    public function createProject($data)
    {
        try {
            return Project::create([
                'name'=> $data['name'],
                'description' => $data['description'],
            ]);
        } catch (Exception $e) {
            throw new Exception('Error creating project: ' . $e->getMessage());
        }
    }
//....................................................................................
//....................................................................................
    /**
     * Fetch a project by ID
     * @param mixed $id
     * @throws \Exception
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function getProjectById($project)
    {
        try {
            $project1 = Project::with('users', 'tasks')->find($project->id);
           
            if (!$project1)

            {
                throw new Exception('Project not found');
            }
            return $project1;
        } catch (Exception $e) {
            throw new Exception('Error fetching project: ' . $e->getMessage());
        }
    }
//.....................................................................................
//.....................................................................................
    /**
     *  Update a project
     * @param mixed $id
     * @param mixed $data
     * @throws \Exception
     * @return Project|Project[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function updateProject($project, $data)
    {
        try {
            $project = Project::find($project->id);
            if (!$project) {
                throw new Exception('Project not found');
            }
            $project->name = $data['name']?? $project->name ;
            $project->description = $data['description']?? $project->description ;
            $project->save();

            return $project;
        } catch (Exception $e) {
            throw new Exception('Error updating project: ' . $e->getMessage());
        }
    }

    
    //.........................................................................................
    //.........................................................................................
    /**
     * // Delete a project
     * @param mixed $id
     * @throws \Exception
     * @return bool
     */
    public function deleteProject($id)
    {
        try {
            $project = Project::find($id);
            if (!$project) {
                throw new Exception('Project not found');
            }
            $project->delete();
            return true;
        } catch (Exception $e) {
            throw new Exception('Error deleting project: ' . $e->getMessage());
        }
    }

    //.........................................................................................
    //.........................................................................................


    /**
     * Assign a project to a user with additional pivot data
     * @param mixed $projectId
     * @param mixed $userId
     * @throws \Exception
     * @return bool
     */
    public function assignProjectToUser($project, $validated)
    {
        try {
            $project = Project::findOrFail($project->id);
            $user = User::findOrFail($validated['user_id']);
            $pivotData = [
                'p_rule' => $validated['p_rule'],
                'contribution_hours' => $validated['contribut_time'],
            ];
            // Attach the user to the project with pivot data
            $project->users()->attach($validated['user_id'], $pivotData);
            return true;
        } catch (Exception $e) {
            throw new Exception('Error assigning project to user: ' . $e->getMessage());
        }
    }
//........................................................................................................
//........................................................................................................

    /**
     *  Update last activity for all projects the user is part of when they logout
     * @param mixed $userId
     * @throws \Exception
     * @return bool
     */
    public static function updateLastActivityOnLogout($userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            // Get all projects the user is associated with
            $projects = $user->projects;

            foreach ($projects as $project) {
                // Update the last_activity in the pivot table
                $user->projects()->updateExistingPivot($project->id, [
                    'last_activity' => Carbon::now()
                ]);
            }

            return true;
        } catch (Exception $e) {
            throw new Exception('Error updating last activity on logout: ' . $e->getMessage());
        }
    }
//......................................................................................................
//......................................................................................................
// Calculate total contribution hours based on time spent on tasks for a user in a project
/**
 * calucate the contrbuting hours by sum all the time_spent in every task 
 * @param mixed $projectId
 * @param mixed $userId
 * @return mixed
 */
public  static function calculateContributionHours($projectId)
{
    $project = Project::findOrFail($projectId);

    // Assuming there's a 'time_spent' field in tasks or pivot table with users
    $totalHours = $project->tasks()
                          ->where('project_id', $projectId)  // Filter by user
                          ->sum('time_spent');         // Sum the time spent field

    return $totalHours;
}

}
