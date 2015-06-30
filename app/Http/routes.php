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
Route::get('github/statistics', 'GithubController@showLanguages');
Route::post('github/statistics', 'GithubController@compareLanguage');

Route::get('github/repo/search',function()
{
    return view('github.search_repos');
});
Route::post('github/repo/search', 'GithubController@searchRepos');

Route::get('github/repo/info', function()
{
    return view('github.repo_info');
});
Route::post('github/repo/info', 'GithubController@repoInfo');

Route::get('github/user/info', function()
{
    return view('github.user_info');
});
Route::post('github/user/info', 'GithubController@userInfo');

Route::get('github/search_history','GithubController@history');

Route::get('github/repo/statistics',function()
{
    return view('github.repo_statistics');
});
Route::post('github/repo/statistics','GithubController@repoStatistics');

Route::get('github/user/repos/search',function()
{
    return view('github.search_user_repos');
});
Route::post('github/user/repos/search', 'GithubController@searchUserRepos');



