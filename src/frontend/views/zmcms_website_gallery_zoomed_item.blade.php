<div class="gallery_zoomed_item">
<h1>{{$data['item']['current']->title}}</h1>
	<div class="img">
		@if($data['item']['prev']!=[])
		<a href="#" 
			id="open_{{$data['item']['prev']->token}}"  
			data-token="{{$data['item']['prev']->token}}" 
			data-nav_token="{{ $data['nav_token'] }}" 
			data-obj_type="image"
		>
			<div class="prev"><i class="fas fa-chevron-left"></i></div>
		</a>
		@endif
		<img src="{{ (json_decode($data['item']['current']->images_resized, true))['image']['1400'] }}" alt="{{ $data['item']['current']->alt }}" />
		@if($data['item']['next']!=[])
		<a href="#" 
			id="open_{{$data['item']['next']->token}}"  
			data-token="{{$data['item']['next']->token}}" 
			data-nav_token="{{ $data['nav_token'] }}" 
			data-obj_type="image" 
		>
			<div class="next"><i class="fas fa-chevron-right"></i></div>
		</a>
		@endif
	</div>
	<div class="desc">
		{!! $data['item']['current']->intro !!}
	</div>
</div>
<script type="text/javascript">
	$(".gallery_zoomed_item a[id^='open_']").on('click', function(e){
		// alert($(this).attr('id'));
		e.preventDefault();e.stopPropagation();
		var d = document.getElementById($(this).attr('id'));
		// $('#ajax_dialog_box').fadeIn( "slow", function() {});
		$('#ajax_dialog_box_content').html('<div class="msg ok"><div class="loader"></div></div>');
			$.get(
				"/galleries/"+d.dataset.obj_type+"/"+d.dataset.nav_token+"/"+d.dataset.token,
				function (data){
						$('#ajax_dialog_box_content').html(data);
				}
			);
		return false;
	});
</script>