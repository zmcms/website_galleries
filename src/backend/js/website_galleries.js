$(document).ready(function(){
	/**
	 * OTWIERA listę istniejących galerii.
	 **/
	var backend_prefix = $('meta[name="backend-prefix"]').attr('content');
	$("#btn_zwgall").on('click', function(e, backend_prefix){
		location.href = "/"+backend_prefix+"/galleries/list";
		return false;
	});	
	/**
	 * OTWIERA Formularz w trybie dodawania nowej galerii.
	 **/
	$("#btn_zwgaln").on('click', function(e, backend_prefix){
		location.href = "/"+backend_prefix+"/galleries/new_frm";
		return false;
	});	
});





