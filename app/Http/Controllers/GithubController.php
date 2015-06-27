<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\GithubUser;
use App\Repository;
use App\User;
use App\GithubLanguagesStatistics;
use Khill\Lavacharts\Lavacharts;

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

    public function statistics()
    {
        try
        {
            \DB::statement("SET SESSION time_zone = '+00:00'");
            $b=\DB::select(\DB::raw("SELECT COUNT(id) FROM `github_languages_statistics`"));
            $array = json_decode(json_encode($b), true);
            if($array[0]["COUNT(id)"]>0)
            {

                if((time()-strtotime(GithubLanguagesStatistics::find(1)->updated_at))<3600)
                {
                    $flag=false;
                }
                else
                {
                    $flag=true;
                    GithubLanguagesStatistics::truncate();
                }
            }
            else
            {
                $flag=true;
            }
            if($flag)
            {
                $languages=['C','C++','C#','CSS','Java','JavaScript','PHP','Python','Ruby','Shell'];
                foreach($languages as $language)
                {
                    $statistics = new GithubLanguagesStatistics;
                    $statistics->language = $language;
                    $url='search/repositories?q=language:'.$language;
                    $response = $this->client->getHttpClient()->get($url);
                    $repos    = \Github\HttpClient\Message\ResponseMediator::getContent($response);
                    $statistics->no_of_repositories = $repos['total_count'];
                    $statistics->save();
                }
            }
            $statistics = GithubLanguagesStatistics::get(array('language','no_of_repositories'))->toArray();

            $lava = new Lavacharts;
            $table  = $lava->DataTable();
            $table->addStringColumn('language')->addNumberColumn('Repositories');
            foreach($statistics as $data)
            {
                $table->addRow(array($data['language'],intval($data['no_of_repositories'])));
            }
            $lava->BarChart('Repositories')->setOptions(array('datatable' => $table));

            return view('github.statistics',compact('lava'));

        } catch (\RuntimeException $e) {
            $this->handleAPIException($e);
        }
    }

    public function addGithubUser($username,$flag)
    {
        $b=\DB::select(\DB::raw("SELECT COUNT(username) FROM `github_users` WHERE username = :name"),array('name'=>$username));
        $array = json_decode(json_encode($b), true);
        if($array[0]["COUNT(username)"]>0)
        {
            $user=GithubUser::where('username','=',$username)->get();
            var_dump($user);
            if((time()-strtotime($user->updated_at))>3600)
            {
                $user_info = $this->client->api('user')->show($username);
                $user->repos=$user_info['public_repos'];
                $user->followers=$user_info['followers'];
                $user->following=$user_info['following'];
                $user->starred_repos=count($this->client->api('user')->starred($username));
                $user->watch_repos=count($this->client->api('user')->watched($username));
                $user->save();
            }
        }
        else
        {
            $user=new GithubUser;
            $user_info = $this->client->api('user')->show($username);
            $user->username=$user_info['login'];
            $user->account_created_at=date("Y-m-d H:i:s",strtotime($user_info['created_at']));
            $user->repos=$user_info['public_repos'];
            $user->followers=$user_info['followers'];
            $user->following=$user_info['following'];
            $user->starred_repos=count($this->client->api('user')->starred($username));
            $user->watch_repos=count($this->client->api('user')->watched($username));
            $user->save();
        }
        if($flag)
        {
            if (!\Auth::guest())
            {
                $user->searchers()->attach(\Auth::id());
            }
        }

    }

    public function addRepo($username,$repo_name,$flag)
    {
        $repo = $this->client->api('repo')->show($username,$repo_name);
        $repository = new Repository;
        $repository->name = $repo['name'];
        $repository->repo_created_at = date("Y-m-d H:i:s", strtotime($repo['created_at']));
        $repository->description = $repo['description'];
        $repository->commit_count = count($this->client->api('repo')->commits()->all($username, $repo['name'], array('sha' => 'master')));
        $repository->languages = serialize(array_keys($this->client->api('repo')->languages($username, $repo['name'])));
        $repository->forks_count = $repo['forks_count'];
        $repository->watchers_count = $repo['watchers_count'];
        $repository->subscribers_count=$repo['subscribers_count'];
        $repository->github_user_id = intval(GithubUser::where('username', '=', $username)->get(array('id')));
        $repository->save();
        if($flag)
        {
            if (!\Auth::guest())
            {
                $repository->searchers()->attach(\Auth::id());
            }
        }
    }

    public function searchUserRepos(Request $request)      //what if two users search for the same user
    {
        $this->validate($request,['username'=>'required']);
        $username=$request->get('username');
        try
        {
            $this->addGithubUser($username,true);
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
            $user=GithubUser::where('username','=',$username)->get()->toArray();
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
            $this->addRepo($username,$repo_name,true);
            $repo = Repository::where('name','=',$repo_name)->get()->toArray();
            /*
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
             $repo['issues_count']= $repo_info['open_issues_count'];
            */
            return view('github.repo_info',compact('repo','username','repo_info'));
        } catch (\RuntimeException $e) {
            $this->handleAPIException($e);
        }
    }

    public function searchRepos(Request $request)
    {
        try {
            $this->validate($request,['repo'=>'required|min:4']);
            $repo_name=$request->get('repo');
            $repos = $this->client->api('repo')->find($repo_name);
            return view('github.search_repos',compact('repos'));
        } catch (\RuntimeException $e) {
            $this->handleAPIException($e);
        }
    }

    public function handleAPIException($e)
    {
        dd($e->getCode() . ' - ' . $e->getMessage());
    }
}
