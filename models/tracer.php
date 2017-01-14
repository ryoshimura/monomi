<?php
App::import('Vendor', 'simplehtmldom', array('file' => 'simplehtmldom' . DS . 'simple_html_dom.php'));

class Tracer extends AppModel {

	public $name     = 'Tracer';
	public $useTable = false; // テーブルに対応させない
/*
	function getImageFile(&$crawler) {

		if ( empty($crawler) || empty($crawler['image_url']) ) return;

		$url = $crawler['image_url'];
//print_r($url);
		$HttpSocket = new HttpSocket();
		$results = $HttpSocket->get($url);
		$response = $HttpSocket->response;

		if ($response['status']['code'] === 302) {
			$url = $response['header']['Location'];
			$results = $HttpSocket->get($url);
		}

		$response = $HttpSocket->response;
		if ($response['status']['code'] === 200) {
			//$temp=file_get_contents($url);
			$parse_url  = parse_url($url);
			$path_parts = pathinfo($parse_url['path']);

			$make_dir  = "/home/site_imgs/";
			$make_dir .= $parse_url['host'].$path_parts['dirname']."/";

			if( !is_dir($make_dir) ) {
				umask(0);
				$rc = mkdir($make_dir, 0775, true);
			}

			//$save_name = String::uuid() .".". $path_parts['extension'];
			$save_name = $path_parts['basename'];

			$handle = fopen($make_dir.$save_name, "w");
			fwrite($handle, $results);
			fclose($handle);

			$crawler['image_file_path'] = $make_dir.$save_name;
		}

		$crawler['response_status'] = $response['status']['code'];
	}
*/
}

?>