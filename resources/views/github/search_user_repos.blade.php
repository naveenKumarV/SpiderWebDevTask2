@extends('template')

@section('heading')
    REPOSITORIES OF  A GITHUB USER
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
    @if(isset($repos))
        @if(count($repos))
            <div class="list-group">
                @foreach($repos as $repo)
                    <a class="list-group-item" href="/finder?repo={{ $repo['name'] }}">
                        <h4 class="list-group-item-heading">{{ $repo['name'] }}</h4>
                        <p class="list-group-item-text">{{ $repo['description'] }}</p>
                        <p class="list-group-item-text">{{ $repo['created_at'] }}</p>
                    </a>
                @endforeach
            </div>
        @else
            No results found with the Github username.
        @endif
    @endif
@stop