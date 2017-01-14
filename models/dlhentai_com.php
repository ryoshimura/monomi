<?php
require_once "tracer.php";

class Crlsite extends Tracer {

	public $name = 'Crlsite';
	public $useTable = false;

//	public $data = array( 'Crawler'=>array() );

	public function __construct() {
/*
		$id = array("id" => false,
					"table" => "site_image_datas",
				   );
		parent::__construct($id);
		$this->primaryKey = "uuid";

		$data['Crawler']['site_uid'] = String::uuid();
*/
	}




	/**
	* イリーガルサイトをクロール
	*
	* @param
	* @return none
	*/
	public function crawler_site( $site ) {

		$Content = ClassRegistry::init('Content');

		// 最新の3ページ分をクロール
		for( $i=0; $i<3; $i++ ){

			// クロールするURLを選定
			if( $i == 0 ){
				$url = $site['Site']['site_url'];
			} else {
				$url = str_replace( '[[page]]', $i, $site['Site']['next_page_url'] );
			}


			$aryTag = $this->get_detail_tag( $url );			// 詳細ページのURLを取得

			foreach( $aryTag as $tag ){

				$html = $this->get_html( $tag );

				if( false === $html ){
					$res['Error'][]['url'] = $tag;
					continue;
				}

				if( 'UTF-8' !== $site['Site']['encoding'] ){
					$html = mb_convert_encoding( $html, "UTF-8", $site['Site']['encoding'] );
				}
				$html = ereg_replace("\r|\n","",$html);


				// 詳細ページにあるダウンロードサイトのURLを全て取得




			}

		}


		return true;
	}


	/**
	* アンカータグを取得
	*
	* @param
	* @return none
	*/
	public function get_detail_tag( $url ) {

		$DownloadSite = ClassRegistry::init('DownloadSite');
		$download = $DownloadSite->find('all', array('conditions'=>array('deleted'=>0)));



//		$url = 'http://dlhentai.com/';
//		$url = 'http://dlhentai.com/120529-%e3%81%b2%e3%81%98%e3%81%8d%e5%b1%8b-%e3%81%8a%e3%81%a1%e2%97%8b%e3%81%a1%e2%97%8b%e5%a4%a7%e5%a5%bd%e3%81%8d-%e4%ba%ba%e5%a6%bb-3hcg/#more-13749';



		$html = @file_get_html( $url );
//pr( $html->find('a',0) );

		foreach( $html->find('div.postRight a') as $val ){
			pr( $val->href );




//		$val = 'http://ryushare.com/427beb8879ee/3HCG-096484-120601.rar';

//		if( preg_match( "/http:\/\/ryushare.com\/.*\..*/", $val ) ){
//			pr( $val );
//		}


		}

		return $aryTag;

	}




	/**
	* プロキシ経由でHTMLを取得
	*
	* @param 過去１ページ分
	* @return none
	*/
	public function get_html( $url ) {

		$proxy = array(
			"http" => array(
				"proxy" => "49.212.148.222:80",
				"request_fulluri" => true,
			)
		);
		$sc = stream_context_create($proxy);

		return @file_get_contents( $url, false, $sc );

	}


}

?>