<?php
namespace Zmcms\WebsiteGalleries;
use Illuminate\Support\ServiceProvider;
class ZmcmsWebsiteGalleriesServiceProvider extends ServiceProvider{

	public function register(){
		$this->app->make('Zmcms\WebsiteGalleries\Backend\Controllers\ZmcmsWebsiteGalleriesController');
		$this->app->make('Zmcms\WebsiteGalleries\Frontend\Controllers\ZmcmsWebsiteGalleriesController');
		require_once(__DIR__.'/helpers.php');
	}

	public function boot(){
		$this->loadMigrationsFrom(__DIR__.'/migrations');
		$this->publishes([
			__DIR__.'/config' => base_path('config/'.Config('frontend.theme_name').'/zmcms/website_galleries'),
			__DIR__.'/backend/css' => base_path('public/themes/'.Config('frontend.theme_name').'/backend/css/'),
			__DIR__.'/backend/js' => base_path('public/themes/'.Config('frontend.theme_name').'/backend/js/'),
			__DIR__.'/backend/views' => base_path('resources/views/themes/zmcms/backend'),
			__DIR__.'/frontend/css' => base_path('public/themes/'.Config('frontend.theme_name').'/frontend/css/'),
			__DIR__.'/frontend/js' => base_path('public/themes/'.Config('frontend.theme_name').'/frontend/js/'),
			__DIR__.'/frontend/views' => base_path('resources/views/themes/zmcms/frontend'),
		]);
	}

}
