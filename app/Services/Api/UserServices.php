<?php
namespace App\Services\Api;

use Exception;
use App\Models\User;
use App\Traits\ResponseTraint;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserServices
{
    use ResponseTraint;
    protected $trait;
  

    /**
     * Get all the user who have taskes
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAllUsers()
    {
        try {
            $users = User::with('tasks')->get();

           return  UserResource::collection($users);
        } catch (Exception $e) {
            throw new Exception('Somthing went rong : '.$e->getMessage()) ;
        }
    }

//.....................................................................................
//.....................................................................................
    /**
     * Store a new user
     * @param mixed $validated
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function storeUser($validated)
    {
        try {
                $user = User::create([
                    'name'=> $validated['name'],
                    'email'=> $validated['email'],
                    'password'=> Hash::make($validated['password']),
                ]);

                $user->rule = $validated['rule'];
                $user->save();
                return new UserResource($user);
        } catch (Exception $e) {
            throw new Exception('Somthing went rong : '.$e->getMessage()) ;
        }
    }

 //........................................................................................
 //........................................................................................
    /**
     * update a user info by its id
     * @param mixed $user
     * @param mixed $validated
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function updateUser($user, $validated)
    {
       try {
            $validated['name']?? $user->name = $validated['name']  ;
            $validated['email']?? $user->email = $validated['email'];
            $validated['password']?? $user->password = Hash::make($validated['password']);        
            $validated['rule']?? $user->rule = $validated['rule'];
            $user->save();
            

            return new UserResource($user);
    } catch (Exception $e) {
        throw new Exception('Somthing went rong : '.$e->getMessage()) ;
    }
    }
     //........................................................................................
 //........................................................................................
    /**
     * update a user info by its id
     * @param mixed $user
     * @param mixed $validated
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function updateOnlyUser($user, $validated)
    {
       try {
            if($validated['name']!=null) $user->name = $validated['name'];
            if($validated['email']!=null) $user->email = $validated['email'];
            if($validated['password']!=null) $user->password = Hash::make($validated['password']);        
            $user->save();      

            return new UserResource($user);
    } catch (Exception $e) {
        throw new Exception('Somthing went rong : '.$e->getMessage()) ;
    }
    }
    
  //.................................................................................... 
  //....................................................................................
  /**
   * show user info for admin and owner of account only
   * @param mixed $user
   * @return mixed
   */
  public function getUser($user)
  {
    try {
        if( Auth::check())
        {   //check the id of the current user who call the request
            if((Auth::user()->rule == 'admin') || (Auth::user()->id == $user->id))
            {
                return new UserResource($user);
            }else{
                return null;
            }
        }else{
            return null;
        }
    } catch (Exception $e) {
        throw new Exception('Somthing went rong : '.$e->getMessage()) ;
    }
  }

//.........................................................................
//.........................................................................

    /**
     * Summary of deleteUser
     * @param mixed $validated
     * @param mixed $id
     * @throws \Exception
     * @return UserResource|null
     */
    public function deleteUser($validated,$id)
    {
        try {
            if( ($validated['force_Delete'] == null) || ($validated['force_Delete'] == 0))
            {
                $old = User::findOrFail($id);
                //detached all the project from this user
                $old->projects()->detach();
                $old->delete();
                return new UserResource($old);
            }elseif($validated['force_Delete'] == 1 && Auth::user()->rule == 'admin'){
                $old = User::withTrashed()->findOrFail($id);
                $old->forceDelete();
                return new UserResource($old);
            }else{
                return null;
            }

        } catch (Exception $e) {
            throw new Exception('Somthing went rong : '.$e->getMessage()) ;
        }
    }

//..................................................................................
//..................................................................................
    /**
     * retore the user if exsits
     * @param mixed $id
     * @return UserResource|\Illuminate\Http\JsonResponse|null
     */
    public function restoreUser($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            if($user->deleted_at === null)
            {
                return null;
            }else{
                $user->restore();
                return new UserResource($user);
            }

        } catch (Exception $e) {
            throw new Exception('Somthing went rong : '.$e->getMessage()) ;
        }
    }
}