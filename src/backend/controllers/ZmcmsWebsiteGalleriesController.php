<?php
namespace Zmcms\WebsiteGalleries\Backend\Controllers;
use Zmcms\WebsiteGalleries\Backend\Db\Queries as Q;
use Illuminate\Http\Request;
class ZmcmsWebsiteGalleriesController extends \App\Http\Controllers\Controller
{
	/**
	 * LISTA RÓŻNYCH GALERII OBRAZKÓW
	 */
	public function galleries_list(){
		$data= [];
		$resultset = Q::gallery_list($paginate = 0, $order=[], $filter=[]);
		return view('themes.'.Config('zmcms.frontend.theme_name').'.backend.zmcms_website_galleries_panel', compact('data', 'resultset'));
		
	}
	/**
	 * WYŚWIETLA FORMULARZ Z PODSTAWOWYMI DANYMI NT. GALERII OBRAZKÓW
	 * $token = null - tryb dodawania nowej galerii zdjęć
	 * $token != null - tryb aktualizacji danych o galerii zdjęć
	 */
	public function gallery_frm($token = null){
		if ($token ==  null)
			return $this->gallery_frm_new();
		else
			return $this->gallery_frm_edit($token);
	}

	/**
	 * WYWOŁYWANE PRZEZ METODĘ gallery_frm($token = null)
	 */
	public function gallery_frm_new(){
		// $data = Q::article_get($token, $langs_id = 'pl', $pageName = 'page', $pageNumber = $page);
		$data = [];
		$settings=[
			'title'	=> ___('Nowa galeria'),
			'action' => 'create',
			'btnsave' => ___('Utwórz'),
			// 'btnsave_and_publish' => 'Zapisz i opublikuj',
		];
		return view('themes.'.Config('zmcms.frontend.theme_name').'.backend.zmcms_website_galleries_frm', compact('data', 'settings'));
		return 'gallery_frm_new1';
	}

	public function gallery_frm_edit($token){
		$data = [];
		$data=Q::gallery_get($token, 'pl');
		$settings=[
			'title'	=> ___('Edycja galerii'),
			'action' => 'update',
			'btnsave' => ___('Zapisz zmiany'),
			// 'btnsave_and_publish' => 'Zapisz i opublikuj',
		];
		return view('themes.'.Config('zmcms.frontend.theme_name').'.backend.zmcms_website_galleries_frm', compact('data', 'settings'));
		return 'gallery_frm_edit2';
	}
	
	/**
	 * ZAPISYWANIE NOWEJ GALERRI LUB ZAPISYWANIE ZMIAN W ISTNIEJĄCEJ GALERII
	 */
	public function gallery_save(Request $request){
		return Q::gallery_save($request->all());
		return json_encode([
				'result'	=>	'ok',
				'code'		=>	'ok',
				'msg' 		=>	'<pre>'.print_r($request->all(), true).'</pre>',
		]);
	}

	/**
	 * OBRAZKI WYBRANEJ GALERII
	 */
	public function gallery_images_list($token){
		$data = [];
		$data['gallery']=Q::gallery_get($token, 'pl');
		$data['gallery_images']=Q::gallery_images_list($token, 'pl');
		return view('themes.'.Config('zmcms.frontend.theme_name').'.backend.zmcms_website_galleries_images_list', compact('data'));
		return 'Lista obrazków galerii '.$token;
	}

	public function gallery_image_save(Request $request){
		$data = $request->all();
		$data['images_resized'] = '';
		$data['mime'] = '';

		$d['image'] = null;
		if(strlen($data['path']) > 4) $d['image'] = zmcms_image_save(
				base_path().DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$data['path'],
				base_path().DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.Config('zmcms.frontend.theme_name').DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'store'.DIRECTORY_SEPARATOR.'wgall',
				str_slug(date('ymdhis').'-'.$data['title']).'.jpg'
		);
		$data['images_resized'] = json_encode($d);

		return Q::gallery_image_save($data);

		return json_encode([
				'result'	=>	'ok',
				'code'		=>	'ok',
				'msg' 		=>	'<pre>'.print_r($data['images_resized'], true).'</pre>',
		]);
	}


	public function gallery_image_delete($token){
		$data = Q::gallery_image_get($token);
		$str = '';
		// $data[] = Q::gallery_image_delete( json_decode($data['images_resized']) );
		foreach( (json_decode($data->images_resized))->image as $r ){
			@unlink(base_path().DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$r);
		}
		return Q::gallery_image_delete($token);
		return json_encode([
			'result'	=>	'ok',
			'code'		=>	'ok',
			'msg' 		=>	'Usuwanie obrazka - token: '.$str.print_r(json_decode($data->images_resized), true),
		]);	
	}
	public function gallery_image_update($token){
		$data = Q::gallery_image_get($token);

		$resultset = [
			'path'			=>	$data->path,
			'token'			=>	$data->token,
			'gallery_token'	=>	$data->gallery_token,
			'title'			=>	$data->title,
			'slug'			=>	$data->slug,
			'alt'			=>	$data->alt,
			'intro'			=>	$data->intro,
		];
		return json_encode($resultset);
	}
	public function gallery_image_list_refresh($token){
		$data['gallery_images']=Q::gallery_images_list($token, 'pl');
		// return 'refresh';
		return view('themes.'.Config('zmcms.frontend.theme_name').'.backend.zmcms_website_galleries_images_list_items', compact('data'));

	}

	public function get_ajax_list(){
		$data= [];
		$data = Q::gallery_list($paginate = 0, $order=[], $filter=[]);
		return view('themes.'.Config('zmcms.frontend.theme_name').'.backend.zmcms_website_galleries_ajax_list', compact('data'));
	}
	public function get_ajax_gallery($token){
		$data= [];
		$data['gallery']=Q::gallery_get($token, 'pl');
		$data['gallery_images']=Q::gallery_images_list($token, 'pl');
		return view('themes.'.Config('zmcms.frontend.theme_name').'.backend.zmcms_website_galleries_ajax_gallery', compact('data'));	
	}
}
