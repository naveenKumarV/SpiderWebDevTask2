<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GithubUser extends Model
{
    protected $table = 'github_users';
    protected $fillable = ['id','username','account_created_at','repos','followers','following','starred_repos','watch_repos'];
    protected  $guarded=[];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function repositories()
    {
        return $this->hasMany('App\Repository');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function searchers()
    {
        return $this->belongsToMany('App\User','github_user_user')->withTimestamps();
    }

}
