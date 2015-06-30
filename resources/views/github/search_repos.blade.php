{{-- This page searches repositories by keyword --}}

@extends('template')

@section('heading')
    SEARCH REPOSITORIES BY KEYWORD
@stop

@section('body')

    {!! Form::open() !!}
    <div class="form-group">
        {!! Form::label('repo','Enter the keyword (minimum four characters) to display the relevant repositories containing the keyword.') !!}
        {!! Form::text('repo',null,['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::submit('Display Repositories',['class'=>'btn btn-primary form-control']) !!}
    </div>
    {!! Form::close() !!}
    @include('errors.list')


    @if(isset($repos))
        <?php  $repos = $repos['repositories']; ?>
        @if(count($repos))
            <h3>SEARCH RESULTS</h3>
            Click the search result to go to the github repository
            <div class="list-group">
                @foreach($repos as $repo)
                    <a class="list-group-item" href="{{$repo['url']}}">
                        <h4 class="list-group-item-heading">{{ $repo['name'] }}</h4>
                        <p class="list-group-item-text">Owner: {{ $repo['owner'] }}</p>
                        <p class="list-group-item-text">{{ $repo['description'] }}</p>
                        <p class="list-group-item-text">{{ date('d M Y H:i:s',strtotime($repo['created_at'])) }}</p>
                    </a>
                @endforeach
            </div>
        @else
            No results found with the specified keyword.
        @endif
    @endif
@stop