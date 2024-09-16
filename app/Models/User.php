<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable implements JWTSubject
{
    use  HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    protected $guarded = ['rule'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          =>'hashed'
    ];



         /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * M To M  user has mony projects
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class)
        ->withPivot('p_rule','contribution_hours','last_activity');
    }
    /**
  
*With the hasManyThrough relationship, you need to specify the foreign keys required to link the tables:

*Target model (Task::class):
* This is the final model you want to access, which is tasks in this case.

*Intermediate model (Team::class): 
*This is the intermediate model that connects the user to the project or team.

*Foreign key in the intermediate model (third parameter):
* The foreign key in the teams table that refers to the base table (User).

*Foreign key in the target model (fourth parameter): 
*The foreign key in the tasks table that refers to the intermediate model (Team).

*Local key in the base table (fifth parameter):
    * The primary key in the users table.
    
*Local key in the intermediate table (sixth parameter):
 *The primary key in the teams table.
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tasks()
    {
        return $this->hasManyThrough(Task::class,Team::class,'user_id','project_id','id','id');
    }
}
