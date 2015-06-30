@extends('layout')

@section('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}" />
    @yield('include')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    @yield('graph_header')
@stop

@section('content')
    <div id="wrapper" class="box effect container-fluid" >
        <h3>GITHUB SEARCH</h3>
        <hr/>
        <div class="row">
            <div class="navbar-header">
                <a class="navbar-brand">
                    @if(Auth::guest())
                        Hi User, You are not signed in.
                    @else
                        <?php
                        $current_user = Auth::user();
                        echo 'Hi '.$current_user->first_name.' '.$current_user->last_name;
                        ?>
                    @endif
                </a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                @if(Auth::guest())
                    <li><a href="{{ url('/auth/register') }}"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                    <li><a href="{{ url('/auth/login') }}"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                @else
                    <li><a href="{{ url('/auth/logout') }}"><span class="glyphicon glyphicon-log-out"></span> Log out</a></li>
                @endif
            </ul>
        </div>
        <div class="row">
            <div class="col-md-3">
                <ul  id="sidebar" class="nav nav-pills nav-stacked" style="max-width: 200px;">
                    <li><a href="{{ url('/') }}"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                    <li><a href="{{ url('github/repo/search') }}"><span class="glyphicon glyphicon-search"></span> Search Repositories By keyword</a></li>
                    <li><a href="{{ url('github/repo/info') }}"><span class="glyphicon glyphicon-info-sign"></span> Repository Information</a></li>
                    <li><a href="{{ url('github/user/repos/search') }}"><span class="glyphicon glyphicon-search"></span> Search Repositories of a Github User</a></li>
                    <li><a href="{{ url('github/user/info') }}"><span class="glyphicon  glyphicon-user "></span> Github User Information</a></li>
                    <li><a href="{{ url('github/statistics') }}"><span class="glyphicon glyphicon-stats"></span>  Github Statistics by Language</a></li>
                    <li><a href="{{ url('github/repo/statistics') }}"><span class="glyphicon glyphicon-signal"></span>  Statistics for Repository</a></li>
                    <li><a href="{{ url('github/search_history') }}"><span class="glyphicon glyphicon-list"></span>  Your previous searches</a></li>
                </ul>
            </div>
            <div id="content" class="col-md-8">
                <div id="header"><h3>@yield('heading')</h3></div>
                @yield('body')
            </div>
        </div>
        <hr/>
        <footer>
            <h4>COPYRIGHTS</h4>
            <p>This site and the registration form are developed as a task of Spider Web Development Inductions 2015.
                Thank you for visiting my webpage.</p>
        </footer>
    </div>
@stop