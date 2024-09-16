<?php
namespace App\Services\Auth;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Api\ProjectService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthServices
{
    /**
     * register a user
     * @param array $data
     * @return User
     */
    public function register(array $data)
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        
        $user->save();
        return   $user;
    }
        /*
        @param array $credentials
        @return $token
        */
        public function login(array $credentials): ?string
        {
            if ($token = Auth::attempt($credentials)) {
            return $token;
            }
            return null;
        }


        public function logout()
        {
            $user = Auth::user();
            if ($user) 
            {
                // Call a service method to update last activity for the user's projects
                ProjectService::updateLastActivityOnLogout($user->id);
            }
            auth()->logout();

        }

}