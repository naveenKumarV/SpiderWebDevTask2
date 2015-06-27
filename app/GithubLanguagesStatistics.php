<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GithubLanguagesStatistics extends Model
{
    protected $table = 'github_languages_statistics';
    protected $fillable = ['language','no_of_repositories'];
    protected $guarded = [];

}
