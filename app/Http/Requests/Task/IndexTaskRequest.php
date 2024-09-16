<?php

namespace App\Http\Requests\Task;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class IndexTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(Auth::user()->rule== 'admin')
        {
            return true;
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
            'status' => 'nullable|string|in:pending,completed,in_progress',
            'priority' => 'nullable|integer|min:1|max:5',         
        ];
    }


}
