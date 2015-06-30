@extends('template')

@section('heading')
    REPOSITORY INFORMATION
@stop

@section('body')
    {!! Form::open() !!}
    <div class="form-group">
        {!! Form::label('username','Enter the Github Username') !!}
        {!! Form::text('username',null,['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('repo','Enter the Repository Name') !!}
        {!! Form::text('repo',null,['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::submit('Display information',['class'=>'btn btn-primary form-control']) !!}
    </div>
    {!! Form::close() !!}
    @include('errors.list')

    @if(isset($repo))
        <ul style="text-align: left;">
            <?php $repo=$repo[0]; ?>
            <li>Repository name: {{ $repo['name'] }}</li>
            <li>Owner name: {{ $username }}</li>
            <li>Description:
                @if($repo['description']=='')
                    none
                @else
                    {{ $repo['description'] }}
                @endif
            </li>

            <li>Created at: {{ $repo['repo_created_at'] }} </li>
            <?php $languages = unserialize($repo['languages']); ?>
            <li>Languages:
                <ol>
                    @foreach($languages as $language)
                        <li>{{ $language }}</li>
                    @endforeach
                </ol>
            </li>
            <li>Number of subscribers: {{ $repo['subscribers_count'] }}</li>
            <li>Forks count: {{ $repo['forks_count'] }}</li>
            <li>Number of users watching the repository: {{ $repo['watchers_count'] }}</li>
        </ul>
    @endif
@stop