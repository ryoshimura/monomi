<?php

class HtmlCache extends AppModel {
	var $name = 'HtmlCache';



	public function __construct() {
		$id = array("id" => false,
					"table" => "html_caches",
				   );
		parent::__construct($id);
		$this->primaryKey = "cache_uid";
	}

	public function beforeFind(&$queryData) {

		return true;
	}

	public function afterFind($results) {

//		if ( !is_null($results) && !is_null($results[0]) ) {
//			if ( array_key_exists('Model', $results[0]) ) {
//
//			}
//		}

		return $results;
	}





	/**
	* キャッシュ照会＆取得
	*
	* @param
	* @return
	*/
	public function loadCache( $url, $strUpdate = false ) {

		$cache = $this->find('first', array('conditions'=>array('url'=>$url, 'deleted'=>0)));

		if( $cache == false ){
			return false;

		} else if( $strUpdate == false ){
			$file_pass = HTML_CACHE_PATH . $cache['HtmlCache']['cache_uid'];
			$data = file_get_contents($file_pass);
			return $data;

		} else {
			if( $strUpdate === $cache['HtmlCache']['update_text'] ){	// 更新日時テキストと同じならキャッシュデータを返す
				$file_pass = HTML_CACHE_PATH . $cache['HtmlCache']['cache_uid'];
				$data = file_get_contents($file_pass);
				return $data;

			} else {
				// 更新が確認されたらキャッシュを返さない
				return false;
			}
		}

	}



	/**
	* キャッシュ登録
	*
	* @param
	* @return
	*/
	public function writeCache( $sorce, $url, $strUpdate, $site_uid = null ) {

		$save = array();
		$cache = $this->find('all', array('conditions'=>array('url'=>$url, 'deleted'=>0)));

		// 存在してない場合のみキャッシュ
		if( $cache == false ){
			$this->create();
//			$save['HtmlCache']['sorce']			= urldecode( str_replace(array("\r\n","\r","\n","\t"), '', $sorce) );
//			$save['HtmlCache']['sorce']			= $sorce;
//			$save['HtmlCache']['file_name']		= $sorce;
			$save['HtmlCache']['url']			= $url;
			$save['HtmlCache']['file_size']		= strlen($sorce);
			$save['HtmlCache']['update_text']	= $strUpdate;

			// 2012.11.14 add
			$save['HtmlCache']['site_uid']			= $site_uid;

			$this->save( $save );

			$save = $this->find('first',array('conditions'=>array('url'=>$url, 'deleted'=>0)));
//			$save['HtmlCache']['file_name']		= $save['HtmlCache']['cache_uid'];
//			$this->save( $save );


			$file_pass = HTML_CACHE_PATH . $save['HtmlCache']['cache_uid'];
			file_put_contents($file_pass, $sorce);

		}

	}






}

?>