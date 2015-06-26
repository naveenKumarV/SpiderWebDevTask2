<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\GithubUser;
use App\Repository;
use App\User;

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
            $username='naveenKumarV';
            $request='SpiderWebDevTask2';
            $content_url='repos/'.$username.'/'.$request.'/'.'stats/participation';

            $response = $this->client->getHttpClient()->get($content_url);
            $repos    = \Github\HttpClient\Message\ResponseMediator::getContent($response);
            //$user = $this->client->api('repo')->show('naveenKumarV','SpiderWebDevTask2');
            return view('github.repos',compact('repos'));

        } catch (\RuntimeException $e) {
            $this->handleAPIException($e);
        }
    }

    public function addGithubUser($username,$flag)
    {
        $repos=compact($this->client->api('user')->repositories($username));
        $user=new GithubUser;
        $user_info = $this->client->api('user')->show($username);
        $username=$user->username=$user_info['login'];
        $user->account_created_at=date("Y-m-d H:i:s",strtotime($user_info['created_at']));
        $user->repos=$user_info['public_repos'];
        $user->followers=serialize($this->client->api('user')->followers($username));
        $user->following=serialize($this->client->api('user')->following($username));
        $user->starred_repos=count($this->client->api('user')->starred($username));
        $user->watch_repos=count($this->client->api('user')->watched($username));
        $user->save();
        if($flag)
        {
            if (!\Auth::guest())
            {
                $user->searchers()->attach(\Auth::id());
            }
        }
        $this->addRepos($username,$repos,!$flag);
    }

    public function addRepos($username,$repos,$flag)
    {
        foreach($repos as $repo) {
            $repository = new Repository;
            $repository->name = $repo['name'];
            $repository->repo_created_at = date("Y-m-d H:i:s", strtotime($repo['created_at']));
            $repository->commit_count = count($this->client->api('repo')->commits()->all($username, $repo['name'], array('sha' => 'master')));
            $repository->description = $repo['description'];
            $repository->url = $repo['html_url'];
            $repository->languages = serialize(array_keys($this->client->api('repo')->languages($username, $repo['name'])));
            $repository->subscribers_count = $repo['subscribers_count'];
            $repository->forks_count = $repo['forks_count'];
            $repository->watchers_count = $repo['watchers_count'];
            $repository->collaborators = serialize($this->client->api('repo')->collaborators()->all($username, $repo['name']));
            $repository->contributors = serialize($this->client->api('repo')->contributors($username, $repo['name']));
            $repository->user_id = GithubUser::where('username', '=', $username)->get('id');
            $repository->save();
            if ($flag)
            {
                if (!\Auth::guest())
                {
                    $repository->searchers()->attach(\Auth::id());
                }
            }
        }
    }

    public function searchUserRepos(Request $request)
    {
        $this->validate($request,['username'=>'required']);
        $username=$request->get('username');
        try {
            //addGithubUser(true);
            $repos=$this->client->api('user')->repositories($username);
            $user_info = $this->client->api('user')->show($username);

            return view('github.search_user_repos',compact('repos','user_info'));
        }
        catch (\RuntimeException $e) {
            $this->handleAPIException($e);
        }

    }

    public function userInfo(Request $request)
    {
        $this->validate($request,['username'=>'required']);
        $username=$request->get('username');
        try {
            $this->addGithubUser($username,true);
            $user=GithubUser::where('username','=',$username)->get();

            //  $user=GithubUser::find(1);
            return view('github.user_info',compact('user'));
        } catch (\RuntimeException $e) {
            $this->handleAPIException($e);
        }
    }

    public function repoInfo(Request $request)
    {
        $this->validate($request,['username'=>'required','repo'=>'required']);
        $username=$request->get('username');
        $repo_name=$request->get('repo');
        try {
            //addGithubUser(false);
            $repo['languages'] = $this->client->api('repo')->languages($username, $repo_name);
            $repo['languages'] = array_keys($repo['languages']);
            $commits = $this->client->api('repo')->commits()->all($username, $repo_name, array('sha' => 'master'));
            $repo['commit_count']=count($commits);
            $repo_info = $this->client->api('repo')->show($username,$repo_name);
            $repo['description']=$repo_info['description'];
            $repo['watchers_count']=$repo_info['watchers_count'];
            $repo['forks_count']=$repo_info['forks_count'];
            $repo['subscribers_count']=$repo_info['subscribers_count'];
            $repo['created_at']=strtotime($repo_info['created_at']);
            $repo['contributors'] = $this->client->api('repo')->contributors($username,$repo_name);
            $repo['collaborators'] = $this->client->api('repo')->collaborators()->all($username,$repo_name);
            $repo['issues_count']= $repo_info['open_issues_count'];
            return view('github.repo_info',compact('repo','username','repo_name','repo_info'));
        } catch (\RuntimeException $e) {
            $this->handleAPIException($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function searchRepos(Request $request)
    {
        try {
            $this->validate($request,['repo'=>'required|min:4']);
            $repo_name=$request->get('repo');
            $repos = $this->client->api('repo')->find($repo_name);
            $username='naveenKumarV';
            $request='naveenKumarV.github.io';
            $content_url='repos/'.$username.'/'.$request.'/'.'stats/participation';

            $response = $this->client->getHttpClient()->get($content_url);
            $reps    = \Github\HttpClient\Message\ResponseMediator::getContent($response);
            return view('github.search_repos',compact('repos','reps'));
        } catch (\RuntimeException $e) {
            $this->handleAPIException($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {

    }

    public function handleAPIException($e)
    {
        dd($e->getCode() . ' - ' . $e->getMessage());
    }
}
