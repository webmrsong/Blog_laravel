<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    @yield('info')
    <link href="{{asset('home/css/base.css')}}" rel="stylesheet">
    <link href="{{asset('home/css/index.css')}}" rel="stylesheet">
    <link href="{{asset('home/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('home/css/new.css')}}" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="js/modernizr.js"></script>
    <![endif]-->
</head>
<body>
<header>
    <div id="logo"><a href="{{url('/')}}"></a></div>
    <nav class="topnav" id="topnav">
        @foreach($navs as $k =>$v)<a href="{{$v->nav_url}}"><span>{{$v->nav_name}}</span><span class="en">{{$v->nav_alias}}</span></a>@endforeach

    </nav>
</header>
@section('content')
    <div class="news" style="float: left">
    <h3 >
        <p>最新<span>文章</span></p>
    </h3>
    <ul class="rank">
        @foreach($new as $ne)
            <li><a href="{{url('element/'.$ne->art_id)}}" title="{{$ne->art_title}}" target="_blank">{{$ne->art_title}}</a></li>
        @endforeach
    </ul>
    <h3 class="ph">
        <p>点击<span>排行</span></p>
    </h3>
    <ul class="paih">
        @foreach($hot as $h)
            <li><a href="{{url('element/'.$h->art_id)}}" title="{{$h->art_title}}" target="_blank">{{$h->art_title}}</a></li>
        @endforeach
    </ul>
    </div>
    @show
<footer>
    <p>{!! Config::get('web.copyright') !!} <a href="/">{!! Config::get('web.web_count') !!}</a></p>
</footer>
</body>
</html>