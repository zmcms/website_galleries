<?php
namespace Zmcms\WebsiteGalleries\Frontend\Middleware;
use Closure;use Session;use URL;class ZmcmsWebsiteGalleries
{
	public function handle($request, Closure $next){
		return $next($request);
	}
}
