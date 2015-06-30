{{-- This page shows the github statistics by language --}}

@extends('template')

@section('heading')
    LANGUAGES vs NUMBER OF REPOSITORIES
@stop

@section('body')
    <div style="margin-top: 30px;">
        @if(isset($lava))
            This chart shows  the top 6 languages with maximum number of repositories
            @if($compare==true)
                along with the language you have added to compare
            @endif
            .Add a language to compare it's popularity with these languages.
            Hover over the bars to check out total number of repositories (both active and non-active)
            in each language.
            <div id="poll_div" style="height:300px;"></div>
            <?php echo $lava->render('BarChart', 'Repositories', 'poll_div') ?>
        @endif
    </div>
    {!! Form::open() !!}
    <div class="form-group">
        <label class="radio-inline"><input type="radio" name="language" value="C">C</label>
        <label class="radio-inline"><input type="radio" name="language" value="shell">Shell</label>
        <label class="radio-inline"><input type="radio" name="language" value="csharp">C#</label>
        <label class="radio-inline"><input type="radio" name="language" value="matlab">MATLAB</label>
        <label class="radio-inline"><input type="radio" name="language" value="perl">Perl</label>
        <label class="radio-inline"><input type="radio" name="language" value="objectivec">Objective-C</label>
        <label class="radio-inline"><input type="radio" name="language" value="cpp">C++</label>
    </div>
    <div style="width: 130px;margin: 10px auto;">
        {!! Form::submit('Add Language',['class'=>'btn btn-primary form-control']) !!}
    </div>
    {!! Form::close() !!}
    @include('errors.list')
@stop