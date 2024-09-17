<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name','description'];

    /**
     * MToM projects have mony users
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
        ->withPivot('p_rule','contribution_hours','last_activity');
    }
    //...................................................................................................
    //...................................................................................................
    /**
     * 1 To M project has mony
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    //...................................................................................................
    //...................................................................................................
        /**
     * get the oldest task by its id
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function oldestTask()
    {
        return $this->hasOne(Task::class)->oldestOfMany();
    }
    //...................................................................................................
    //...................................................................................................
    /**
     * get the newist task by its id
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastTask()
    {
        return $this->hasone(Task::class)->latestOfMany();
    }
    //...................................................................................................
    //...................................................................................................

    public function maxPriority( $title)
    {
        return $this->hasone(Task::class)->ofMany([
            'priority' => 'max'
        ],function($q) use ($title)
        {
           $q ->where('priority','hight')
              ->where('title','LIKE','%'. $title .'%');

        });

    }
}
