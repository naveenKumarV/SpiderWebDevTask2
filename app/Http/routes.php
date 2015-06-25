<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// github routes

Route::get('github', 'GithubController@index');

Route::get('github/search_repos','GithubController@finder');

Route::get('github/repo_info', function()
{
    return view('github.repo_info');
});
Route::post('github/repo_info', 'GithubController@repoInfo');

Route::get('github/user_info', function()
{
    return view('github.user_info');
});
Route::post('github/user_info', 'GithubController@userInfo');

Route::get('github/user_stats', 'GithubController@commits');

Route::get('github/repo_stats','GithubController@commits' );

Route::get('github/search_user_repos',function()
{
    return view('github.search_user_repos');
});
Route::post('github/search_user_repos', 'GithubController@searchUserRepos');



