<?php

namespace App\Http\Requests\Task;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class AddCommentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        $task = $this->route('task');
        $project = $task->project;
        $project = $user->projects->find($project->id);
        if ($project) {
            // Access the role from the pivot table
            if( $project->pivot->p_rule == 'tester')
            {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'comment' => ['required','string','max:100']
        ];
    }
}
