@extends('template')

@section('heading')
    GITHUB USER INFORMATION
@stop

@section('body')
    {!! Form::open() !!}
    <div class="form-group">
        {!! Form::label('username','Enter the Github Username') !!}
        {!! Form::text('username',null,['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::submit('Display Repositories',['class'=>'btn btn-primary form-control']) !!}
    </div>
    {!! Form::close() !!}
    @if(isset($user))
        <ul>
            <li>Name:{{ $user['name'] }}</li>
            <li>Github Id:{{ $user['id'] }}</li>
            <li>Number of Public Repos:{{ $user['public_repos'] }}</li>
            <li>Number of Followers:{{ $user['followers_count'] }}</li>
            @if($user['followers_count']>0)
                <li>Followers:
                    @foreach($user['followers'] as $follower)
                        <ol>
                            <li>{{ $follower }}</li>
                        </ol>
                    @endforeach
                </li>
            @endif
            <li>Number of other users this user is following:{{ $user['following_count'] }}</li>
            @if($user['following_count']>0)
                <li>Users this user is following:
                    @foreach($user['following'] as $following)
                        <ol>
                            <li>{{ $following }}</li>
                        </ol>
                    @endforeach
                </li>
            @endif
            @if(count($user['starred_repos'])>0)
                <li>Repositories the user has starred:
                    @foreach($user['starred_repos'] as $starred)
                        <ol>
                            <li>{{ $starred }}</li>
                        </ol>
                    @endforeach
                </li>
            @else
                <li>Number of repositories starred by the user:0</li>
            @endif
            @if(count($user['watched_repos'])>0)
                <li>Repositories being watched by the user:
                    @foreach($user['watched_repos'] as $watched)
                        <ol>
                            <li>{{ $watched }}</li>
                        </ol>
                    @endforeach
                </li>
            @else
                <li>Number of repositories being watched:0</li>
            @endif
        </ul>
    @endif
@stop
