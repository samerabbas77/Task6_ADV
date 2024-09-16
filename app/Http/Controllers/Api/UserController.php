<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

use App\Traits\ResponseTraint;

use App\Services\Api\UserServices;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\DeleteUserRequest;

use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UpdateUserOnlyUserRequest;
use Monolog\Formatter\ElasticsearchFormatter;

class UserController extends Controller
{
    use ResponseTraint;
    protected $userService;
    public function __construct(UserServices $userService)
    {
        $this->userService = $userService;
    }
    /**
     * get all user with thier tasks
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = $this->userService->getAllUsers();

        return $this->succeesTrait($users,'Fetching data successfully',200);
    }
    
    //.......................................................................................................
    //.......................................................................................................
    /**
     * store a user (only admin can do this)
     * @param \App\Http\Requests\User\StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user  = $this->userService->storeUser($validated);
        return $this->succeesTrait($user,'Store user successfully',200);
    }


    //....................................................................................
    //....................................................................................
    /**
     * update user(can doby admin or the user with its account)
     * @param \App\Http\Requests\User\UpdateUserRequest $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        
         $user  = $this->userService->updateUser($user,$validated);

        return $this->succeesTrait($user,'Update user successfully',200);
    }
    //     //....................................................................................
    // //....................................................................................
     /**
     * update user(can doby admin or the user with its account)
     * @param \App\Http\Requests\User\UpdateUserOnlyUserRequest  $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateForUser( UpdateUserOnlyUserRequest  $request, User $user)
    {
        $validated = $request->validated();

        //$validated = $request->only[['name','email','password']];
         $user  = $this->userService->updateOnlyUser($user,$validated);

        return $this->succeesTrait($user,'Update user successfully',200);
    }
    //..................................................................................
    //..................................................................................
    /**
     *show user info
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        $user = $this->userService->getUser($user);

        if($user == null)
        {
            return $this->faildTrait('This is Not Your Accont');
        }else{
            return $this->succeesTrait($user,'Shoing user info successfully',200);
        }
    }

    // //.........................................................................
    // //.........................................................................
    /**
     * force or soft deleting an user
     * @param \App\Http\Requests\User\DeleteUserRequest $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteUserRequest $request,$id)
    {
 
        $validated = $request->validated();

        $user = $this->userService->deleteUser($validated,$id);

        if( $user == null )
        {
            return $this->faildTrait('Only admin can do this');
        }else{
            if($validated['force_Delete'] == 1 )
            { 
               return $this->succeesTrait($user,'Force Deleting user info successfully',200);
            }else{
               return $this->succeesTrait($user,'Soft Deleting user info successfully',200);
            }
           
        }


    }

    //..................................................................................
    //..................................................................................
    /**
     * Restore the deleted user by its id
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {      
            $user = $this->userService->restoreUser($id);

            if($user == null)
            {
                return $this->faildTrait('There are no deleted record for this id');
            }else{
                return $this->succeesTrait($user,'Restore user account successfully',200);
            }      

    }

}
