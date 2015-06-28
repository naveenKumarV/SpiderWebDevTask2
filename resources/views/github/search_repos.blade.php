@extends('template')

@section('heading')
    SEARCH REPOSITORIES BY KEYWORD
@stop

@section('body')

    {!! Form::open() !!}
    <div class="form-group">
        {!! Form::label('repo','Enter the Repository Name') !!}
        {!! Form::text('repo',null,['class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::submit('Display Repositories',['class'=>'btn btn-primary form-control']) !!}
    </div>
    {!! Form::close() !!}

    @if(isset($lava))
        <div id="pop_div"></div>
        <?php echo $lava->render('AreaChart', 'Activity', 'pop_div') ?>
    @endif

@stop