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
            <h3>SEARCH RESULTS</h3>
            <div class="list-group">
                @foreach($repos as $repo)
                    <a class="list-group-item" href="{{$repo['html_url']}}">
                        <h4 class="list-group-item-heading">{{ $repo['name'] }}</h4>
                        <p class="list-group-item-text">{{ $repo['description'] }}</p>
                        <p class="list-group-item-text">{{ date('d M Y H:i:s',strtotime($repo['created_at'])) }}</p>
                    </a>
                @endforeach
            </div>
        @else
            No repositories found with the Github username.
        @endif
    @endif
@stop