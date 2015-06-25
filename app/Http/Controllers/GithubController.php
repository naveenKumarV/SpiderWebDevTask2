<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GithubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $client;

    /*
     * Github username
     *
     * @var string
     * */
    private $username;

    public function __construct(\Github\Client $client)
    {
        $this->client = $client;
        $this->username = env('GITHUB_USERNAME');
    }

    public function index()
    {
        $username='dhanajagan91';
        try {
            /*$username='naveenKumarV';
             $request='SpiderWebDevTask2';
             $content_url='repos/'.$username.'/'.$request.'/'.'stats/code_frequency';

             $response = $this->client->getHttpClient()->get($content_url);
             $repos    = \Github\HttpClient\Message\ResponseMediator::getContent($response);*/
            $user = $this->client->api('repo')->show('naveenKumarV','SpiderWebDevTask2');
            return view('github.repos',compact('user'));

        } catch (\RuntimeException $e) {
            $this->handleAPIException($e);
        }
    }

    public function searchUserRepos(Request $request)
    {
        $this->validate($request,['username'=>'required']);
        $username=$request->get('username');
        $repos=$this->client->api('user')->repositories($username);
        return view('github.search_user_repos',compact('repos'));

    }

    public function userInfo(Request $request)
    {
        $this->validate($request,['username'=>'required']);
        $username=$request->get('username');
        $user_info = $this->client->api('user')->show($username);
        $user['id']=$user_info['id'];
        $user['name']=$user_info['login'];
        $user['public_repos']=$user_info['public_repos'];
        $user['followers']=$this->client->api('user')->followers($username);
        $user['followers_count']=$user_info['followers'];
        $user['following']=$this->client->api('user')->following($username);
        $user['following_count']=$user_info['following'];
        $user['created_at']=$user_info['created_at'];
        $user['url']=$user_info['html_url'];
        $user['starred_repos']=$this->client->api('user')->starred($username);
        $user['watched_repos']=$this->client->api('user')->watched($username);

        return view('github.user_info',compact('user'));
    }

    public function repoInfo(Request $request)
    {
        $this->validate($request,['username'=>'required','repo'=>'required']);
        $username=$request->get('username');
        $repo_name=$request->get('repo');
        $repo['languages'] = $this->client->api('repo')->languages($username, $repo_name);
        $commits = $this->client->api('repo')->commits()->all($username, $repo_name, array('sha' => 'master'));
        $repo['commit_count']=count($commits);
        return view('github.repo_info',compact('repo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
