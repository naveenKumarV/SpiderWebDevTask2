@extends('template')

@section('graph_header')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

@stop

@section('heading')
    LANGUAGES vs NUMBER OF REPOSITORIES
@stop

@section('body')
    This chart shows only the top 10 languages with maximum number of repositories
    <div id="poll_div" style="height:300px;"></div>
    <?php echo $lava->render('BarChart', 'Repositories', 'poll_div') ?>
@stop