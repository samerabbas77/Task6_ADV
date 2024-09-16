<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;

use App\Services\Api\ProjectService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Requests\Project\assignProjectRequst;
use App\Http\Requests\Project\ProjectStoreRequest;
use App\Http\Requests\Project\ProjectUpdateRequest;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }
 //...............................................................................................................
//...............................................................................................................
    /**
     * Fetch all projects
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {  
            $projects = $this->projectService->getAllProjects();
            return response()->json(['projects' => $projects], 200);
    }
//...............................................................................................................
//...............................................................................................................
   
    /**
     * Create a new project
     * @param \App\Http\Requests\Project\ProjectStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProjectStoreRequest $request): JsonResponse
    {
        $project = $this->projectService->createProject($request->validated());
         return response()->json(['project' => $project], 201);
    }
          
//...............................................................................................................
//...............................................................................................................
    /**
     *  Show a single project
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Project $project): JsonResponse
    { 
        $project = $this->projectService->getProjectById($project);
   
        return response()->json(['project' => $project], 404);
             
    }
//...............................................................................................................
//...............................................................................................................
    /**
     * Update a project
     * @param \App\Http\Requests\Project\ProjectUpdateRequest $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( ProjectUpdateRequest $request, Project $project): JsonResponse
    {
    
        $project = $this->projectService->updateProject($project, $request->validated());
        return response()->json(['project' => $project], 200);

    }
//...........................................................................................................
//...........................................................................................................
    /**
     * Delete a project
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
      
        $this->projectService->deleteProject($id);
        return response()->json(['message' => 'Project deleted successfully'], 200);

    }

//................................................................................................................    
//................................................................................................................ 
    public function assign_user(assignProjectRequst $request,Project $project)
    {
        $request->validated();
        $validated = $request->only(['p_rule','last_activity','contribution_hours','user_id','contribut_time']);

        $project = $this->projectService->assignProjectToUser($project,$validated);
        return response()->json(['message' => 'Project Attached successfully' ], 200);
    }



}
