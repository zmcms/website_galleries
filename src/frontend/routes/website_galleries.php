<?php
Route::middleware(['FrontendUser'])->group(function () {
	Route::get(
		'galleries/{obj_type}/{nav_token}/{token}',
		'Zmcms\WebsiteGalleries\Frontend\Controllers\ZmcmsWebsiteGalleriesController@get_item'
	)->name('gallery');
});
