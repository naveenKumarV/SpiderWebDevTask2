<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    protected $table = 'repositories';
    protected $fillable = ['name','repo_created_at','description','languages','commit_dates','subscribers_count','forks_count','watchers_count','github_user_id'];
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function githubUser()
    {
        return $this->belongsTo('App\GithubUser');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function searchers()
    {
        return $this->belongsToMany('App\User','repository_user')->withTimestamps();
    }
}
