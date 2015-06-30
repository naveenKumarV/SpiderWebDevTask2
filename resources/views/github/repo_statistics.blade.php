{{--This page shows the activity graph of a repository --}}

@extends('template')

@section('heading')
    REPOSITORY ACTIVITY STATISTICS
@stop

@section('body')
    This page shows the activity of a github repository based on number of commits.
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
        {!! Form::submit('Display Activity',['class'=>'btn btn-primary form-control']) !!}
    </div>
    {!! Form::close() !!}
    @include('errors.list')

    @if(isset($area_chart))
        Hover over the line in the graph to find the number of commits on a particular day.
        <div id="pop_div" style="height: 300px;"></div>
        <?php echo $area_chart->render('AreaChart', 'Activity', 'pop_div') ?>
    @endif

@stop