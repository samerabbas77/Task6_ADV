<?php

namespace App\Http\Requests\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class DeleteUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->route("user");
        
      if((Auth::user()->id == $user) ||(Auth::user()->rule == 'admin' ))
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
            'force_Delete'  => ['nullable' ,'boolean']
        ];
    }
}