<?php
$prefix = Config('zmcms.main.backend_prefix');
Route::middleware(['BackendUser'])->group(function () use ($prefix){
	$prefix = Config('zmcms.main.backend_prefix');
	Route::get($prefix.'/galleries/list', 
		'Zmcms\WebsiteGalleries\Backend\Controllers\ZmcmsWebsiteGalleriesController@galleries_list')
		->name('website_galleries');

	Route::get($prefix.'/galleries/gallery_frm/{token}', 
		'Zmcms\WebsiteGalleries\Backend\Controllers\ZmcmsWebsiteGalleriesController@gallery_frm')
		->name('website_galleries');

	Route::get($prefix.'/galleries/gallery_frm', 
		'Zmcms\WebsiteGalleries\Backend\Controllers\ZmcmsWebsiteGalleriesController@gallery_frm')
		->name('website_galleries');

	Route::post($prefix.'/galleries/save', 
		'Zmcms\WebsiteGalleries\Backend\Controllers\ZmcmsWebsiteGalleriesController@gallery_save')
		->name('website_galleries');

	Route::get($prefix.'/gallery/images/{token}', 
		'Zmcms\WebsiteGalleries\Backend\Controllers\ZmcmsWebsiteGalleriesController@gallery_images_list')
		->name('website_galleries');
	Route::post($prefix.'/galleries/image/save', 
		'Zmcms\WebsiteGalleries\Backend\Controllers\ZmcmsWebsiteGalleriesController@gallery_image_save')
		->name('website_galleries');
	
	Route::get($prefix.'/galleries/image/update/{token}', 
		'Zmcms\WebsiteGalleries\Backend\Controllers\ZmcmsWebsiteGalleriesController@gallery_image_update')
		->name('website_galleries');

	Route::get($prefix.'/galleries/image/delete/{token}', 
		'Zmcms\WebsiteGalleries\Backend\Controllers\ZmcmsWebsiteGalleriesController@gallery_image_delete')
		->name('website_galleries');
	Route::get($prefix.'/galleries/image/refresh/{token}', 
		'Zmcms\WebsiteGalleries\Backend\Controllers\ZmcmsWebsiteGalleriesController@gallery_image_list_refresh')
		->name('website_galleries');
	Route::get($prefix.'/galleries/get_ajax_list', 
		'Zmcms\WebsiteGalleries\Backend\Controllers\ZmcmsWebsiteGalleriesController@get_ajax_list')
		->name('website_galleries');
	Route::get($prefix.'/galleries/get_ajax_gallery/{token}', 
		'Zmcms\WebsiteGalleries\Backend\Controllers\ZmcmsWebsiteGalleriesController@get_ajax_gallery')
		->name('website_galleries');
	

		
	
});


/**
 * 

galleries/new_frm
 */
