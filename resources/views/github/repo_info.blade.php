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

    @if(isset($repo))
        <ul>
            <li>Languages:
                <ol>
                    @foreach($repo['languages'] as $language)
                        <li><?php var_dump($language) ?></li>
                    @endforeach
                </ol>
            </li>
        </ul>
    @endif
@stop