{{-- This page shows the github user information --}}

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
        <ul style="text-align: left;">
            <?php $user=$user[0]; ?>
            <li>Name:{{ $user['username'] }}</li>

            <li>Github Account created at: {{ $user['account_created_at'] }}</li>

            <li>Number of Public Repos:{{ $user['repos'] }}</li>

            <li>Number of followers: {{ $user['followers'] }}</li>

            <li>Number of other users this user is following:{{ $user['following'] }}</li>

            <li>Number of repositories starred by the user: {{$user['starred_repos']}}</li>

            <li>Number of repositories being watched by the user: {{$user['watch_repos']}}</li>
        </ul>
    @endif
@stop
