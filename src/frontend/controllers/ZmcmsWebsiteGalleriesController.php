<?php
namespace Zmcms\WebsiteGalleries\Frontend\Controllers;
use Illuminate\Http\Request;
use Zmcms\WebsiteNavigations\Frontend\Model\WebsiteNavigationJoined as QN;
use Zmcms\WebsiteGalleries\Frontend\Db\Queries as QG;
use Session;
class ZmcmsWebsiteGalleriesController extends \App\Http\Controllers\Controller
{
	// public function run(){
		// return 'Wyświetlam galerie';
	// }
	public function run($token_nav, $token_obj, $type){
		$str='';
		$data = [];
		$data['navigation']['data'] = QN::get_navigation($token_nav);
		$data['galleries'] = QG::get_galleries_list($token_nav, $paginate = 0, $order=[], $filter=[]);
		$count = count($data['galleries']);
		// GDY NAWIGACJA PROWADZI DO TRYBU LISTY
		if($count==1){
			$head = [
			'title' => $data['navigation']['data']->name.' - '.Config((Config('zmcms.frontend.theme_name') ?? 'zmcms').'.contact_data.headquarters.business_name'),
			'keywords' => $data['navigation']['data']->meta_keywords,
			'description' => $data['navigation']['data']->meta_description,
			'canonical' => $data['navigation']['data']->canonical ?? null,
			'og:title' => $data['navigation']['data']->og_title,
			'og:type' => $data['navigation']['data']->og_type,
			'og:url' => $data['navigation']['data']->og_url,
			'og:image' => $data['navigation']['data']->og_image,
			'og:description' => $data['navigation']['data']->og_description,
			'og:locale' => $data['navigation']['data']->langs_id,
			'language' => $data['navigation']['data']->langs_id,
			];
			 $token_obj = $data['galleries'][0]->token;
			 $data['galleries'] = $data['galleries'][0];
			 $data['content'] = QG::get_gallery_images($token_obj, Session::get('language'));
			return view('themes.'.(Config('zmcms.frontend.theme_name') ?? 'zmcms').'.frontend.zmcms_website_gallery', compact('head', 'data'));
		}
		if($token_nav == $token_obj){
		// if($count>1){
			$head = [
				'title' => $data['navigation']['data']->name.' - '.Config((Config('zmcms.frontend.theme_name') ?? 'zmcms').'.contact_data.headquarters.business_name'),
				'keywords' => $data['navigation']['data']->meta_keywords,
				'description' => $data['navigation']['data']->meta_description,
				'canonical' => $data['navigation']['data']->canonical ?? null,
				'og:title' => $data['navigation']['data']->og_title,
				'og:type' => $data['navigation']['data']->og_type,
				'og:url' => $data['navigation']['data']->og_url,
				'og:image' => $data['navigation']['data']->og_image,
				'og:description' => $data['navigation']['data']->og_description,
				'og:locale' => $data['navigation']['data']->langs_id,
				'language' => $data['navigation']['data']->langs_id,
			];
			return view('themes.'.(Config('zmcms.frontend.theme_name') ?? 'zmcms').'.frontend.zmcms_website_galleries_list', compact('data'));
		}else{
			foreach ($data['galleries'] as $d) {
				if($d->token == $token_obj) $data['galleries'] = $d;
				$data['content'] = QG::get_gallery_images($token_obj, Session::get('language'));
			}
			// $token_obj = $data['galleries'][0]->token;
			// $data['galleries'] = $data['galleries'][0];
			// $data['content'] = QG::get_gallery_images($token_obj, Session::get('language'));
			// return print_r($data['content'], true);
			return view('themes.'.(Config('zmcms.frontend.theme_name') ?? 'zmcms').'.frontend.zmcms_website_gallery', compact('data'));
		}
	
	}

	public function get_item($obj_type, $nav_token, $token, $prev = null, $next = null){
		switch ($obj_type){
			case 'image':{return $this->get_item_image($nav_token, $token, $prev, $next);break;}
		}
		return json_encode([
				'result'	=>	'ok',
				'code'		=>	'ok',
				'msg' 		=>	___('
					obj_type: '.$obj_type.'<br />
					nav_token: '.$nav_token.'<br />
					token: '.$token.'<br />
				'),
			]);
	}

	public function get_item_image($nav_token, $token, $prev, $next){
		$data['navigation'] = QN::get_navigation($nav_token);
		$d['i'] = QG::get_image($token);
		$data['items'] = QG::get_gallery_images($d['i']->gallery_token,Session('language'), $paginate = 0, $order=[], $filter=[]);
		$i=$p=$n=$current=0;
		foreach($data['items'] as $item){
			if($item->token==$token){
				$p=$i-1;
				$current=$i;
				$n=$i+1;
			}
			$i++;
		}
		if($p>=0){
			$data['item']['prev']=$data['items'][$p];
		}else{
			$data['item']['prev']=[];
		}
		$data['item']['current']=$data['items'][$current];
		if($n<(count($data['items']))){
			$data['item']['next']=$data['items'][$n];
		}else{
			$data['item']['next']=[];
		}
		$data['nav_token']= $nav_token;
		return view('themes.'.(Config('zmcms.frontend.theme_name') ?? 'zmcms').'.frontend.zmcms_website_gallery_zoomed_item', compact('data'));
		return $current.'<br>'.$p.'<br>'.$n.'<br>'.print_r($data['item'], true);
		return json_encode([
			'result'	=>	'ok',
			'code'		=>	'ok',
			'msg' 		=>	$current.'<br>'.$p.'<br>'.$n.'<br>'.print_r($data['item'], true),
		]);
	}
}
