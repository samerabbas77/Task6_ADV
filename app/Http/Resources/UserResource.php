<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $role = $this->projects->isNotEmpty()? $this->projects->first()->pivot->p_rule:null;
        return 
        [
            "Name"   => $this->name,
            "E-Mail" => $this->email,
            'Role' => $role,
            "Project"    =>$this->projects->map(function($project)
            {
                return[
                    'Project'  => $project->name,   
                    "Tasks"  => $project->tasks->map(function ($task) {
                        return [
                            'Title' => $task->title,
                        ];
                    })
                ];
            }
        ),

        ];
    }
}
