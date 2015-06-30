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

    /**
     * Constructor
     *
     * @param \Github\Client $client
     */
    public function __construct(\Github\Client $client)
    {
        $this->client = $client;
    }

    /**
     * adds a language information to the database table
     *
     * @param $language
     */
    public function addLanguage($language)
    {
        \DB::statement("SET SESSION time_zone = '+00:00'");
        $b=\DB::select(\DB::raw("SELECT COUNT(id) FROM `github_languages_statistics` WHERE language = :name"),array('name'=>$language));
        $array = json_decode(json_encode($b), true);
        if($array[0]["COUNT(id)"]>0)
        {
            $lan = GithubLanguagesStatistics::where('language', '=', $language)->first();
            if ((time() - strtotime($lan->updated_at)) > 3600)
            {
                $url = 'search/repositories?q=language:'.$language;
                $response = $this->client->getHttpClient()->get($url);
                $repos = \Github\HttpClient\Message\ResponseMediator::getContent($response);
                $lan->no_of_repositories = $repos['total_count'];
                $lan->save();
                $lan->touch();
            }
        }
        else
        {
            $statistics = new GithubLanguagesStatistics;
            $statistics->language = $language;
            $url = 'search/repositories?q=language:'.$language;
            $response = $this->client->getHttpClient()->get($url);
            $repos = \Github\HttpClient\Message\ResponseMediator::getContent($response);
            $statistics->no_of_repositories = $repos['total_count'];
            $statistics->save();
        }
    }

    /**
     * gives the language statistics
     *
     * @return \Illuminate\View\View
     */

    public function languageStats($language=null)
    {
        $languages=['CSS','Java','JavaScript','PHP','Python','Ruby'];
        if($language!=null)
        {
            array_push($languages,$language);
        }
        $statistics = [];
        foreach($languages as $language)
        {
            $this->addLanguage($language);
            array_push($statistics, GithubLanguagesStatistics::where('language', '=', $language)->get()->toArray());
        }
        array_pop($languages);
        $lava = new Lavacharts;
        $table  = $lava->DataTable();
        $table->addStringColumn('language')->addNumberColumn('Repositories');
        foreach($statistics as $data)
        {
            $table->addRow(array($data[0]['language'],intval($data[0]['no_of_repositories'])));
        }
        $lava->BarChart('Repositories')->setOptions(array('datatable' => $table));
        return $lava;
    }

    /**
     * gives the statistics of top 6 popular languages
     *
     * @return \Illuminate\View\View
     */
    public function showLanguages()
    {
        try {
            $compare = false;
            $lava = $this->languageStats();
            return view('github.statistics',compact('lava','compare'));
        } catch (\RuntimeException $e) {
            $this->handleAPIException($e);
        }
    }

    /**
     * adds a language to compare
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function compareLanguage(Request $request)
    {
        try {
            $compare = true;
            $this->validate($request, ['language' => 'required|in:C,shell,csharp,matlab,perl,objectivec,cpp']);
            $lava = $this->languageStats($request->get('language'));
            return view('github.statistics', compact('lava', 'compare'));
        }catch (\RuntimeException $e) {
            $this->handleAPIException($e);
        }
    }

    /**
     * adds the github user information to 'github_users' table in the database
     *
     *
     * @param $username
     * @param $flag
     */
    public function addGithubUser($username,$flag)
    {
        \DB::statement("SET SESSION time_zone = '+00:00'");
        $array=\DB::select(\DB::raw("SELECT COUNT(username) FROM `github_users` WHERE username = :name"),array('name'=>$username));
        $array = json_decode(json_encode($array), true);
        if($array[0]["COUNT(username)"]>0)
        {
            $user=GithubUser::where('username','=',$username)->first();
            if((time()-strtotime($user->updated_at))>3600)
            {
                $user_info = $this->client->api('user')->show($username);
                $user->repos=$user_info['public_repos'];
                $user->followers=$user_info['followers'];
                $user->following=$user_info['following'];
                $user->starred_repos=count($this->client->api('user')->starred($username));
                $user->watch_repos=count($this->client->api('user')->watched($username));
                $user->save();
                $user->touch();
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
                $result = \DB::table('github_user_user')->where('user_id','=',\Auth::id())
                    ->where('github_user_id','=',$user->id)->first();
                if($result==null)
                {
                    $user->searchers()->attach(\Auth::id());
                }
            }
        }

    }

    /**
     * adds the information of a repository to the 'repositories' table in the database
     *
     * @param $username
     * @param $repo_name
     * @param $flag
     */
    public function addRepo($username,$repo_name,$flag)
    {
        \DB::statement("SET SESSION time_zone = '+00:00'");
        $array=\DB::select(\DB::raw("SELECT COUNT(name) FROM `repositories` WHERE name = :name"),array('name'=>$repo_name));
        $array = json_decode(json_encode($array), true);
        if($array[0]["COUNT(name)"]>0)
        {
            $repository = Repository::where('name', '=', $repo_name)->first();
            if((time()-strtotime($repository->updated_at))>3600)
            {
                $repo = $this->client->api('repo')->show($username,$repo_name);
                $repository->description = $repo['description'];
                $commits=$this->client->api('repo')->commits()->all($username, $repo['name'], array('sha' => 'master'));
                $commit_dates = [];
                foreach($commits as $commit)
                {
                    array_push($commit_dates,date("Y-m-d", strtotime($commit['commit']['author']['date'])));
                }
                $repository->commit_dates = serialize($commit_dates);
                $repository->languages = serialize(array_keys($this->client->api('repo')->languages($username, $repo['name'])));
                if(array_key_exists('source',$repo))
                {
                    $repository->forks_count = $repo['source']['forks_count'];
                    $repository->watchers_count = $repo['source']['watchers_count'];
                }
                $repository->forks_count = $repo['forks_count'];
                $repository->watchers_count = $repo['watchers_count'];
                $repository->subscribers_count=$repo['subscribers_count'];
                $repository->save();
                $repository->touch();
            }
        }
        else
        {
            $repo = $this->client->api('repo')->show($username,$repo_name);
            $repository = new Repository;
            $repository->name = $repo['name'];
            $repository->repo_created_at = date("Y-m-d H:i:s", strtotime($repo['created_at']));
            $repository->description = $repo['description'];
            $commits=$this->client->api('repo')->commits()->all($username, $repo['name'], array('sha' => 'master'));
            $commit_dates = [];
            foreach($commits as $commit)
            {
                array_push($commit_dates,date("Y-m-d", strtotime($commit['commit']['author']['date'])));
            }
            $repository->commit_dates = serialize($commit_dates);
            $repository->languages = serialize(array_keys($this->client->api('repo')->languages($username, $repo['name'])));
            if(array_key_exists('source',$repo))
            {
                $repository->forks_count = $repo['source']['forks_count'];
                $repository->watchers_count = $repo['source']['watchers_count'];
            }
            $repository->forks_count = $repo['forks_count'];
            $repository->watchers_count = $repo['watchers_count'];
            $repository->subscribers_count=$repo['subscribers_count'];
            $repository->github_user_id = intval(GithubUser::where('username', '=', $username)->pluck('id'));
            $repository->save();
        }
        if($flag)
        {
            if (!\Auth::guest())
            {
                $result = \DB::table('repository_user')->where('user_id','=',\Auth::id())
                    ->where('repository_id','=',$repository->id)->first();
                if($result==null)
                {
                    $repository->searchers()->attach(\Auth::id());
                }
            }
        }
    }

    /**
     * gives the repositories of a github user
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function searchUserRepos(Request $request)
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

    /**
     * gives the information about a github user
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
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

    /**
     * Gets the repository information
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function repoInfo(Request $request)
    {
        $this->validate($request,['username'=>'required','repo'=>'required']);
        $username=$request->get('username');
        $repo_name=$request->get('repo');
        try {
            $this->addGithubUser($username,false);
            $this->addRepo($username,$repo_name,true);
            $repo = Repository::where('name','=',$repo_name)->get()->toArray();
            return view('github.repo_info',compact('repo','username'));
        } catch (\RuntimeException $e) {
            $this->handleAPIException($e);
        }
    }

    /**
     * Searches repository by keyword
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function searchRepos(Request $request)
    {
        $this->validate($request,['repo'=>'required|min:4']);
        $repo_name=$request->get('repo');
        try {
            $repos = $this->client->api('repo')->find($repo_name);
            return view('github.search_repos',compact('repos'));
        } catch (\RuntimeException $e) {
            $this->handleAPIException($e);
        }
    }

    /**
     * gives the activity graph of a repository based on number of commits
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function repoStatistics(Request $request)
    {
        $this->validate($request,['username'=>'required','repo'=>'required']);
        $username=$request->get('username');
        $repo_name=$request->get('repo');
        try{
            $this->addGithubUser($username,false);
            $this->addRepo($username,$repo_name,true);
            $repo = Repository::where('name','=',$repo_name)->first();
            $commit_dates = unserialize($repo->commit_dates);
            $frequency = array_count_values($commit_dates);
            $area_chart = new Lavacharts;
            $table = $area_chart->DataTable();
            $table->addDateColumn('Date')->addNumberColumn('Number of Commits');
            foreach($frequency as $key=>$value)
            {
                $table->addRow(array($key,intval($value)));
            }
            $area_chart->AreaChart('Activity')->setOptions(array(
                'datatable' => $table,
                'title' => 'Repository Activity based on number of commits',
                'legend' => $area_chart->Legend(array(
                    'position' => 'in'))));
            return view('github.repo_statistics',compact('area_chart' ));
        }catch (\RuntimeException $e) {
            $this->handleAPIException($e);
        }
    }

    /**
     * Gives the previous searches of the currently logged in user
     *
     * @return \Illuminate\View\View
     */
    public function history()
    {
        if(!\Auth::guest())
        {
            $searched_repos = \Auth::user()->searchedRepos->lists('name')->toArray();
            $searched_users = \Auth::user()->searchedUsers->lists('username')->toArray();
            return view('github.previous_searches',compact('searched_repos','searched_users'));
        }
        return view('github.previous_searches');
    }

    /**
     * handles API exceptions
     *
     * @param $e
     */
    public function handleAPIException($e)
    {
        dd($e->getCode() . ' - ' . $e->getMessage());
    }
}
