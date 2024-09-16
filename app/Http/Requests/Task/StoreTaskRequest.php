<?php

namespace App\Http\Requests\Task;

use App\Models\Project;
use App\Enum\Tasks\TaskStatus;
use Illuminate\Validation\Rule;
use App\Enum\Tasks\TaskPriority;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize()
    {
        $user = Auth::user();
        $project = Project::where('name', $this->input('project_name'))->first();
        // Retrieve the specific project the user is associated with
        $project_final = $user->projects->find($project->id);
    
       
        if ($project_final) {
            // Access the role from the pivot table
            if( $project_final->pivot->p_rule == 'manager')
            {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
        
    }

    public function rules()
    {
        return [
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'status'          => ['required', 'string', new Enum(TaskStatus::class)],
            'priority'        => ['required', new Enum(TaskPriority::class)],
            'time_spent'      => 'nullable|integer|min:0',
            'project_name'    => 'nullable|string|exists:projects,name',
        ];
    }

    protected function prepareForValidation()
    {

        // If project_name is provided, try to fetch its ID
        if ($this->input('project_name'))
         {
            $project = Project::where('name', $this->input('project_name'))->first();

            if ($project) {
                $this->merge([
                    'project_id' => $project->id,
                ]);
            }
        }
    }
}
