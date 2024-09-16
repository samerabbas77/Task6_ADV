<?php

namespace App\Http\Requests\Project;

use App\Models\User;
use App\Services\Api\ProjectService;
use Illuminate\Foundation\Http\FormRequest;

use function PHPUnit\Framework\isEmpty;

class assignProjectRequst extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|exists:users,name',
            'p_rule' => 'required|string|max:255',
            'last_activity' => 'nullable|date',
            'contribution_hours' => 'nullable|numeric|min:0',
        ];
    }

    public function passedValidation()
    {
        $user = User::where('name',$this->input('name'))->first();

        $project = $this->route('project');
        $Contribut = ProjectService::calculateContributionHours($project->id);

        $this->merge([
            'user_id'   => $user->id,
            'contribut_time'  =>$Contribut
        ]);
    }
}
