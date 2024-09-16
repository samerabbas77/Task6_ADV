<?php

namespace App\Http\Requests\User;

use App\Enums\UserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserOnlyUserRequest  extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * admin or the owner of the account
     */
    public function authorize(): bool
    {
        //the user info that we well edit
        $user = $this->route('user');

        if( Auth::check())
        {
            if((Auth::user()->id == $user->id))
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
            'name'        => 'nullable|string|max:255',
            'email'       => 'nullable|email|unique:users|max:255',
            'password'    => 'nullable|string|min:8',
        ];
    }
}