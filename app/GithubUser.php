<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GithubUser extends Model
{
    protected $table = 'github_users';
    protected $fillable = [];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function repositories()
    {
        return $this->hasMany('App\Repository');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
