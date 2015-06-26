<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    protected $table = 'repositories';
    protected $fillable = [];

    public function githubUser()
    {
        return $this->belongsTo('App\GithubUser');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

}
