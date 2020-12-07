<?php
namespace Zmcms\WebsiteGalleries\Backend\Db;
use Illuminate\Support\Facades\DB;
use Session;
use Request;
class Queries{

	public static function gallery_list($paginate = 0, $order=[], $filter=[]){
		$gallery = (Config('database.prefix')??'').'website_galleries';
		$gallery_names = (Config('database.prefix')??'').'website_galleries_names';
		$resultset = DB::table($gallery)
			->join($gallery_names, $gallery.'.token', '=', $gallery_names.'.token');
		if($filter!=[]){
			foreach($filter as $v) {
				$resultset->where($v[0], $v[1], $v[2]);
			}
		}
		$resultset->select([
			$gallery.'.token as token',
			$gallery.'.sort as sort',
			$gallery.'.access as access',
			$gallery.'.frontend_access as frontend_access',
			$gallery.'.date_from as date_from',
			$gallery.'.date_to as date_to',
			$gallery_names.'.langs_id as langs_id',
			$gallery_names.'.title as title',
			$gallery_names.'.slug as slug',
			$gallery_names.'.intro as intro',
			$gallery_names.'.meta_keywords as meta_keywords',
			$gallery_names.'.meta_description as meta_description',
			$gallery_names.'.og_title as og_title',
			$gallery_names.'.og_type as og_type',
			$gallery_names.'.og_url as og_url',
			$gallery_names.'.og_image as og_image',
			$gallery_names.'.og_description as og_description',
		]);
		if($order!=[])
			foreach ($order as $column => $direction) {
				$resultset->orderBy($column, $direction);
			}
		if($paginate==0)
			return $resultset->get();

		return $resultset->paginate($paginate);
	}

	public static function gallery_save($data){
		switch($data['action']){
			case 'create':{return self::gallery_create($data);break;}
			case 'update':{return self::gallery_update($data);break;}
		}
	}


public static function gallery_get($token, $langs_id){
		$gallery = (Config('database.prefix')??'').'website_galleries';
		$gallery_names = (Config('database.prefix')??'').'website_galleries_names';
		$resultset = DB::table($gallery)
			->join($gallery_names, $gallery.'.token', '=', $gallery_names.'.token')
			->where($gallery.'.token', $token)
			->where($gallery_names.'.langs_id', $langs_id)
			->select([
				$gallery.'.token as token',
				$gallery.'.sort as sort',
				$gallery.'.access as access',
				$gallery.'.frontend_access as frontend_access',
				$gallery.'.date_from as date_from',
				$gallery.'.date_to as date_to',
				$gallery_names.'.langs_id as langs_id',
				$gallery_names.'.title as title',
				$gallery_names.'.slug as slug',
				$gallery_names.'.intro as intro',
				$gallery_names.'.meta_keywords as meta_keywords',
				$gallery_names.'.meta_description as meta_description',
				$gallery_names.'.og_title as og_title',
				$gallery_names.'.og_type as og_type',
				$gallery_names.'.og_url as og_url',
				$gallery_names.'.og_image as og_image',
				$gallery_names.'.og_description as og_description',
			])
			->first();
			// ->setBaseUrl('aaa');
			return $resultset;
	}

	public static function gallery_create($data){
		$gallery = (Config('database.prefix')??'').'website_galleries';
		$gallery_names = (Config('database.prefix')??'').'website_galleries_names';
		$token = hash ('sha256', date('Ymd').rand(0,1000));
		try{
			DB::beginTransaction();
			DB::table($gallery)->insert([
				'token'				=> $token,
				'sort'				=> ( $data['sort'] ?? null ),
				'access'			=> $data['access'],
				'frontend_access'	=> $data['frontend_access'],
				'date_from'			=> $data['date_from'],
				'date_to'			=> $data['date_to'],
			]);
			DB::table($gallery_names)->insert([
				'token'				=> $token,
				'langs_id'			=> ( $data['langs_id'] ?? Config(Config('zmcms.frontend.theme_name').'.main.lang_default') ),
				'title'				=> $data['title'],
				'slug'				=> $data['slug'],
				'intro'				=> $data['intro'],
				'meta_keywords'		=> $data['meta_keywords'],
				'meta_description'	=> $data['meta_description'],
				'og_title'			=> $data['og_title'],
				'og_type'			=> $data['og_type'],
				'og_url'			=> $data['og_url'],
				'og_image'			=> $data['og_image'],
				'og_description'	=> $data['og_description'],
			]);
			DB::commit();
			return json_encode([
				'result'	=>	'ok',
				'code'		=>	'ok',
				'msg' 		=>	___('Dodano nową galerię'.' "'.$data['title'].'"'),
			]);
		}catch(\Illuminate\Database\QueryException $e){
			DB::rollBack();
			return json_encode([
				'result'	=>	'error',
				'code'		=>	$e->getMessage(),
				'msg' 		=>	___('Nie można dodać galerii'.' "'.$data['title'].'"'),
			]);
		}
		return false;
	}
	public static function gallery_update($data){
		$gallery = (Config('database.prefix')??'').'website_galleries';
		$gallery_names = (Config('database.prefix')??'').'website_galleries_names';
		try{
			DB::beginTransaction();
			DB::table($gallery)
			->where('token', $data['token'])
			->update([
				'sort'				=> ( $data['sort'] ?? null ),
				'access'			=> $data['access'],
				'frontend_access'	=> $data['frontend_access'],
				'date_from'			=> $data['date_from'],
				'date_to'			=> $data['date_to'],
			]);
			DB::table($gallery_names)
			->where('token', $data['token'])
			->update([
				'langs_id'			=> ( $data['langs_id'] ?? Config(Config('zmcms.frontend.theme_name').'.main.lang_default') ),
				'title'				=> $data['title'],
				'slug'				=> $data['slug'],
				'intro'				=> $data['intro'],
				'meta_keywords'		=> $data['meta_keywords'],
				'meta_description'	=> $data['meta_description'],
				'og_title'			=> $data['og_title'],
				'og_type'			=> $data['og_type'],
				'og_url'			=> $data['og_url'],
				'og_image'			=> $data['og_image'],
				'og_description'	=> $data['og_description'],
			]);
			DB::commit();
			return json_encode([
				'result'	=>	'ok',
				'code'		=>	'ok',
				'msg' 		=>	___('Zaktualizowano galerię'.' "'.$data['title'].'"'),
			]);
		}catch(\Illuminate\Database\QueryException $e){
			DB::rollBack();
			return json_encode([
				'result'	=>	'error',
				'code'		=>	$e->getMessage(),
				'msg' 		=>	___('Nie można zaktualizować galerii'.' "'.$data['title'].'"'),
			]);
		}
		return false;
	}

	/**
	 * ZARZĄDZANIE OBRAZKAMI WYBRANEJ GALERII
	 */
	public static function gallery_images_list($gallery_token = null, $paginate = 0, $order=[], $filter=[]){
		$images = (Config('database.prefix')??'').'website_galleries_images';
		$images_names = (Config('database.prefix')??'').'website_galleries_images_names';
		
		$resultset = DB::table($images)
			->join($images_names, $images.'.token', '=', $images_names.'.token');
		if($gallery_token != null){
			$resultset->where($images.'.gallery_token', $gallery_token);
		}
		if($filter!=[]){
			foreach($filter as $v) {
				$resultset->where($v[0], $v[1], $v[2]);
			}
		}
		$resultset->select([
			$images.'.token as token',
			$images.'.gallery_token as gallery_token',
			$images.'.sort as sort',
			$images.'.access as access',
			$images.'.path as path',
			$images.'.images_resized as images_resized',
			$images.'.mime as mime',
			$images_names.'.langs_id as langs_id',
			$images_names.'.title as title',
			$images_names.'.slug as slug',
			$images_names.'.alt as alt',
			$images_names.'.intro as intro',
			$images_names.'.created_at as created_at',
			$images_names.'.updated_at as updated_at',
		]);
		if($order!=[])
			foreach ($order as $column => $direction) {
				$resultset->orderBy($column, $direction);
			}
		if($paginate==0)
			return $resultset->get();

		return $resultset->paginate($paginate);
		
	}

	public static function gallery_image_save($data){
		switch($data['action']){
			case 'create':{return self::gallery_image_create($data);break;}
			case 'update':{return self::gallery_image_update($data);break;}
		}
	}

	public static function gallery_image_create($data){
		$gallery_image = (Config('database.prefix')??'').'website_galleries_images';
		$gallery_image_names = (Config('database.prefix')??'').'website_galleries_images_names';
		$token = hash ('sha256', date('Ymd').rand(0,1000));
		try{
			DB::beginTransaction();
			DB::table($gallery_image)->insert([
				'token'				=>	$token,
				'gallery_token'		=>	$data['gallery_token'],
				'sort'				=>	( $data['sort'] ?? 0 ),
				'access'			=>	( $data['access'] ?? '*' ),
				'path'				=>	$data['path'],
				'images_resized'	=>	$data['images_resized'],
				'mime'				=>	$data['mime'],
			]);
			DB::table($gallery_image_names)->insert([
				'token'				=>	$token,
				'langs_id'			=>	( $data['langs_id'] ?? Config(Config('zmcms.frontend.theme_name').'.main.lang_default') ),
				'title'				=>	$data['title'],
				'slug'				=>	$data['slug'],
				'alt'				=>	$data['alt'],
				'intro'				=>	$data['intro'],
			]);
			DB::commit();
			return json_encode([
				'result'	=>	'ok',
				'code'		=>	'ok',
				'msg' 		=>	___('Dodano nowy obrazek do galerii'),
			]);
		}catch(\Illuminate\Database\QueryException $e){
			DB::rollBack();
			return json_encode([
				'result'	=>	'error',
				'code'		=>	$e->getMessage(),
				'msg' 		=>	___('Nie można dodać obrazka do galerii'),
			]);
		}
		return false;
	}
	public static function gallery_image_get($token){
		$images = (Config('database.prefix')??'').'website_galleries_images';
		$images_names = (Config('database.prefix')??'').'website_galleries_images_names';
		return $resultset = DB::table($images)
			->join($images_names, $images.'.token', '=', $images_names.'.token')
			->where($images.'.token', $token)
			->select([
				$images.'.token as token',
				$images.'.gallery_token as gallery_token',
				$images.'.sort as sort',
				$images.'.access as access',
				$images.'.path as path',
				$images.'.images_resized as images_resized',
				$images.'.mime as mime',
				$images_names.'.langs_id as langs_id',
				$images_names.'.title as title',
				$images_names.'.slug as slug',
				$images_names.'.alt as alt',
				$images_names.'.intro as intro',
				$images_names.'.created_at as created_at',
				$images_names.'.updated_at as updated_at',
			])
		->first();
	}
	public static function gallery_image_delete($token){
		$images = (Config('database.prefix')??'').'website_galleries_images';
		try{
			DB::beginTransaction();
			$resultset = DB::table($images)
				->where($images.'.token', $token)
				->delete();
			DB::commit();
			return json_encode([
				'result'	=>	'ok',
				'code'		=>	'ok',
				'msg' 		=>	___('Usunięto obrazek z galerii'),
			]);
		}catch(\Illuminate\Database\QueryException $e){
			DB::rollBack();
			return json_encode([
				'result'	=>	'error',
				'code'		=>	$e->getMessage(),
				'msg' 		=>	___('Nie można usunąć obrazka z galerii'),
			]);
		}
		
	}
	public static function gallery_image_update($data){
		$gallery_image = (Config('database.prefix')??'').'website_galleries_images';
		$gallery_image_names = (Config('database.prefix')??'').'website_galleries_images_names';
		$token = hash ('sha256', date('Ymd').rand(0,1000));
		try{
			DB::beginTransaction();
			DB::table($gallery_image)
			->where('token', $data['token'])
			->update([
				'gallery_token'		=>	$data['gallery_token'],
				'sort'				=>	( $data['sort'] ?? 0 ),
				'access'			=>	( $data['access'] ?? '*' ),
				'path'				=>	$data['path'],
				'images_resized'	=>	$data['images_resized'],
				'mime'				=>	$data['mime'],
			]);
			DB::table($gallery_image_names)
			->where('token', $data['token'])
			->update([
				'langs_id'			=>	( $data['langs_id'] ?? Config(Config('zmcms.frontend.theme_name').'.main.lang_default') ),
				'title'				=>	$data['title'],
				'slug'				=>	$data['slug'],
				'alt'				=>	$data['alt'],
				'intro'				=>	$data['intro'],
			]);
			DB::commit();
			return json_encode([
				'result'	=>	'ok',
				'code'		=>	'ok',
				'msg' 		=>	___('Zaktualizowano obrazek w galerii'),
			]);
		}catch(\Illuminate\Database\QueryException $e){
			DB::rollBack();
			return json_encode([
				'result'	=>	'error',
				'code'		=>	$e->getMessage(),
				'msg' 		=>	___('Nie zaktualizowano obrazka w galerii'),
			]);
		}
		return false;


		return json_encode([
				'result'	=>	'ok',
				'code'		=>	'ok',
				'msg' 		=>	'<pre>gallery_image_update</pre>',
		]);
	}
}
