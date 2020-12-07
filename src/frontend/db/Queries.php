<?php
namespace Zmcms\WebsiteGalleries\Frontend\Db;
use Illuminate\Support\Facades\DB;
use Session;
use Request;
class Queries{
	public static function get_galleries_list($token_nav, $paginate = 0, $order=[], $filter=[]){
		$gall = (Config('database.prefix')??'').'website_galleries';
		$gall_names = (Config('database.prefix')??'').'website_galleries_names';
		$gall_images = (Config('database.prefix')??'').'website_galleries_images';
		$linker = (Config('database.prefix')??'').'website_navigations_linker';

		$resultset = DB::table($gall)
			->join($gall_names, $gall.'.token', '=', $gall_names.'.token')
			->join($linker, $gall.'.token', '=', $linker.'.obj_token');
		if($filter!=[])
			foreach($filter as $v) {
				$resultset->where($v[0], $v[1], $v[2]);
			}
			$resultset->where('nav_token', $token_nav);
			$resultset->select([
				$gall.'.token as token',
				$gall.'.sort as sort',
				$gall.'.access as access',
				$gall.'.frontend_access as frontend_access',
				$gall_names.'.langs_id as langs_id',
				$gall_names.'.title as title',
				$gall_names.'.slug as slug',
				$gall_names.'.intro as intro',
				$gall_names.'.meta_keywords as meta_keywords',
				$gall_names.'.meta_description as meta_description',
				$gall_names.'.og_title as og_title',
				$gall_names.'.og_type as og_type',
				$gall_names.'.og_url as og_url',
				$gall_names.'.og_image as og_image',
				$gall_names.'.og_description as og_description',
			]);
		if($order!=[])
			foreach ($order as $column => $direction) {
				$resultset->orderBy($column, $direction);
			}
		if($paginate==0)
			return $resultset->get();

		return $resultset->paginate($paginate);
	}

	public static function get_gallery_images($gallery_token, $langs_id, $paginate = 0, $order=[], $filter=[]){
		$gall = (Config('database.prefix')??'').'website_galleries';
		$gall_names = (Config('database.prefix')??'').'website_galleries_names';
		$gall_images = (Config('database.prefix')??'').'website_galleries_images';
		$gall_images_names = (Config('database.prefix')??'').'website_galleries_images_names';
		$resultset = DB::table($gall_images)
			->join($gall_images_names, $gall_images.'.token', '=', $gall_images_names.'.token')
			->where($gall_images_names.'.langs_id', $langs_id)
			->where($gall_images.'.gallery_token', $gallery_token)
			->get();
		return $resultset;
	}

	public static function get_image($token){
		$gall_images = (Config('database.prefix')??'').'website_galleries_images';
		$gall_images_names = (Config('database.prefix')??'').'website_galleries_images_names';
		$resultset = DB::table($gall_images)
			->join($gall_images_names, $gall_images.'.token', '=', $gall_images_names.'.token')
			->where($gall_images_names.'.langs_id', Session::get('language'))
			->where($gall_images.'.token', $token)
			// ->when($offset != null, function ($query) use ($offset){
				// $query->offset($offset);
				// return $query;
			// })
			->first();
		return $resultset;
	}
}