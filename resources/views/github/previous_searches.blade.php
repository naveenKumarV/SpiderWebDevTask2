{{--This page shows the search history of the currently logged in user --}}

@extends('template')

@section('heading')
    PREVIOUS SEARCHES
@stop

@section('body')
    <div style="text-align: left;">
        @if(Auth::guest())
            Please login to save and see your previous searches.
        @else
            @if(isset($searched_repos) && isset($searched_users))
                <h4>Previously searched repositories</h4>
                @if(count($searched_repos)>0)
                    <ul>
                        @foreach($searched_repos as $repo)
                            <li>{{ $repo }}</li>
                        @endforeach
                    </ul>
                @else
                    No previous recorded searches.
                @endif

                <h4>Previously searched Github Users</h4>
                @if(count($searched_users)>0)
                    <ul>
                        @foreach($searched_users as $user)
                            <li>{{ $user }}</li>
                        @endforeach
                    </ul>
                @else
                    No previous recorded searches.
                @endif
            @endif
        @endif
    </div>
@stop