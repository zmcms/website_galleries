<!DOCTYPE html>
<html lang="pl-PL">
<?php (!isset($head))?$head = zmcms_get_initial_head_data($theme = Config('frontend.theme_name')):null; ?>
	<head>	
	<title>{{$head['title']}}</title>
	<meta name="keywords" content="{{$head['keywords']}}" >
	<meta name="description" content="{{$head['description']}}" >
	<link rel="canonical" href="{{$head['canonical']}}" >
	<meta property="og:title" content="{{$head['og:title']}}" >
	<meta property="og:type" content="{{$head['og:type']}}" >
	<meta property="og:url" content="{{$head['og:url']}}" >
	<meta property="og:image" content="{{$head['og:image']}}" >
	<meta property="og:description" content="{{$head['og:description']}}" >
	<meta property="og:locale" content="pl_PL{{$head['og:locale']}}" >
	<meta name="language" content="{{$head['language']}}" >
	<meta http-equiv="X-UA-Compatible" content="IE=edge" >
	<meta name="viewport" content="width=device-width, initial-scale=1" >
	<meta name="csrf-token" content="{{ csrf_token() }}" >
	<meta charset="UTF-8" >
	{!! zmcms_html_css('themes/'.Config('zmcms.frontend.theme_name').'/frontend/css', $compress = false, $not=['index.css']) !!}
    <link rel="icon" type="image/png" href="{{Config(Config('zmcms.frontend.theme_name').'.media.icon')}}" >
    <script src="https://kit.fontawesome.com/138da2cdc4.js" crossorigin="anonymous"></script>
	<script>window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token(),]); ?></script>
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	@includeIf('themes.'.Config('frontend.theme_name').'.seo.google_script')
	</head>
<body>
<a href="/">
<div class="logo fadein">	
<img src="{{ Config(Config('zmcms.frontend.theme_name').'.media.logo') }}" alt="{{ Config(Config('zmcms.frontend.theme_name').'.contact_data.headquarters.business_name') }}" >
</div>
</a>
<nav id="main">
		<div class="mobile_control">
			<a href="" id="btn_phone" title="{{___('Zadzwoń')}}"><span class="fas fa-phone-square"></span></a>
			<a href="" id="btn_bars" title="{{___('Otwórz menu')}}"><span class="fas fa-bars"></span></a>
			<a href="" id="btn_times" class="hidden" title="{{___('Zamknij menu')}}"><span class="fas fa-times"></span></a>
		</div>
</nav>
<nav id="main_positions" class="mobile_hide">
		<ul id="mnu_main">{{zmcms_website_navigations_frontend($position = 'main', $parent = null, $to_file=false)}}</ul>
</nav>
	<header style="background-image: url({{(json_decode($data['navigation']['data']->images_resized, true)['ilustration']['1400'])}})">

	<div class="color_filter">
		<h1>{{$data['navigation']['data']->name}}</h1>
		@if(strlen($data['navigation']['data']->og_description)>3)
			<div class="intro">{{$data['navigation']['data']->og_description}}</div>
		@endif
	</div>
	</header>
	<content>
		{!! $data['navigation']['data']->content !!}



		<div class="items_lst">
		@foreach($data['galleries'] as $r)
		<a href="{{$data['navigation']['data']->link.'/'.$r->slug}}">
		<div class="item">
			<div class="imgcontainer">
				<img src="{{$r->og_image}}" alt="{{$r->title}}" />
			</div>
			<h2>{{$r->title}}</h2>
		</div>
		</a>
		@endforeach
		</div>




		{{-- <h1>SELF</h1> --}}
		{{-- <pre>{{print_r($data, true)}}</pre> --}}

		{{-- {{print_r($data['articles'], true)}} --}}
	</content>
	@include('themes.'.(Config('zmcms.frontend.theme_name').'.frontend.zmcms_google_map'))
	@include('themes.'.(Config('zmcms.frontend.theme_name').'.frontend.footer'))

{!! zmcms_html_js('themes/'.Config('zmcms.frontend.theme_name').'/frontend/js', false) !!}
@stack('custom_js')
@include('themes.'.Config('zmcms.frontend.theme_name').'.frontend.zmcms_main_ajax_dialog_box')
</body>
</html>