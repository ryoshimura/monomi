<?php
require_once "tracer.php";

class ScElbrollo extends Tracer {

	public $name = 'ScElbrollo';
	public $useTable = false;

	// Anime-sharing専用スクレイピングリスト
	public $urlList = array(
		'Anime'			=> 'http://www.elbrollo.com/forum/139-anime/',
		'Comic Book'	=> 'http://www.elbrollo.com/forum/140-comic-book/',
		'Manga'			=> 'http://www.elbrollo.com/forum/141-manga/',
		'Hentai'		=> 'http://www.elbrollo.com/forum/187-hentai/',
	);





	public function __construct() {

	}



	/**
	* 新着監視
	*
	*/
	public function ispCrw( $site ) {

		// findで使用するhtmlタグ
		$sc_post_tag = 'tr.unread';
		$sc_lastupdate_tag = 'ul.last_post';
		$sc_link_tag = 'h4';

		$sc_contents_tag = 'div.post_wrap';
//		$sc_content_body_tag = 'div.has_after_content';


		App::import('Model','Crlmodels');
		$IllegalSite = ClassRegistry::init('IllegalSite');
		$Cache = ClassRegistry::init('Cache');

		$site_uid = $site['IllegalSite']['site_uid'];
		$aryErr = array();		// スクレイピングエラーなどの情報を格納するリターン用配列



		// リストのURLをスクレイピング
		$before_url = '';
		foreach( $this->urlList as $url ){

//print_r( '$url: ' . $url . "\n");

			$crlSite = new Crlmodels;
			$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

			$html = &$crlSite->get_html( $url, $site, $sc_post_tag, false );	// ランディングページをスクレイピング
			if( !is_object($html) ){	// エラーが存在する場合
				if( isset($html['MonomiError']) ){	// エラーが存在する場合
					if( 'notUpdate' !== $html['MonomiError'] ){
						$err = array( 'url' => $url, 'status' => $html['MonomiError'], 'mode'=>'normal' );	// スクレイピングエラーの場合
						$aryErr[] = $err;
					}
					continue;
				}
			}

			$obj = $html->find( $sc_post_tag );


			$flg = false;
			$cnt = 0;
			$update_break_flag = false;
			foreach( $obj as $key => $val ){		// 1投稿を括るタグを指定

				// 更新日付を $sc_lastupdate_tag から取得し、キャッシュ情報に照会
				$detail_url	= $val->find($sc_link_tag, 0)->find('a', 0)->href;
				$strUpdate	= $val->find($sc_lastupdate_tag, 0)->find('li', 1)->find('a',0)->plaintext;

				$val->clear();
				unset($val);

				// $this->urlList と重複した場合は continue
print_r( ' $detail_url: ' . $detail_url . "\n");
				$loop_flag = true;
				foreach( $this->urlList as $vUrl ){
					if( $vUrl === $detail_url ){
						$loop_flag = false;
						break;
					}
				}
				if( $loop_flag == false ){ continue; }


				// add 2012.09.24 IllegalSiteは結構アップロードをこまめにしてるので、都度再読み込みしよかー
				$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));


				// キャッシュ対応版
				$d_html = &$crlSite->get_htmlc( $detail_url, $site, $sc_contents_tag, $strUpdate );

				if( !is_object($d_html) ){	// エラーが存在する場合
					if( isset($d_html['MonomiError']) ){	// エラーが存在する場合
						if( 'notUpdate' !== $d_html['MonomiError'] ){
							$err = array( 'url' => $detail_url, 'status' => $d_html['MonomiError'], 'mode'=>'normal' );	// スクレイピングエラーの場合
							$aryErr[] = $err;
						}
						continue;
					}
				}

				$contents = $d_html->find( $sc_contents_tag );
				foreach( $contents as $content ){	// 詳細スレッド内の１投稿ずつ check_content を行なう
//pr($content->plaintext);
//					$crlSite->check_content( $detail_url, $content, $site_uid, $site['IllegalSite']['site_url'], true );
					$crlSite->check_content_uni( $detail_url, $content, $site_uid, $site['IllegalSite']['site_url'], false );
				}


				$d_html->clear();
				unset($d_html);

				$cnt++;

			}

			// 1件も投稿記事が確認できない場合
			if( $cnt == 0 && $update_break_flag == false ){
				$err = array( 'url' => $url, 'status' => 'NONE POST', 'mode'=>'normal' );
				$aryErr[] = $err;
			}

			unset($crlSite);
			$html->clear();
			unset($html);

		}

		$crlSite = new Crlmodels;
		$crlSite->updateErr( $aryErr, $site );


		return $aryErr;
	}






	public function ispCache( $site ){

		$sc_contents_tag = 'div.post_wrap';

		$crlSite = new Crlmodels;
		$HtmlCache = ClassRegistry::init('HtmlCache');
		$caches = $HtmlCache->find('all', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

		$aryErr = array();		// スクレイピングエラーなどの情報を格納するリターン用配列

		foreach( $caches as $cache ){

			$file_pass = HTML_CACHE_PATH . $cache['HtmlCache']['cache_uid'];
//print_r( $cache['HtmlCache']['url'] . "\n");
			$data = file_get_contents($file_pass);
			$html = str_get_html( $data );

			$contents = $html->find( $sc_contents_tag );
			foreach( $contents as $content ){
//				$crlSite->check_content( $cache['HtmlCache']['url'], $content, $site['IllegalSite']['site_uid'], $site['IllegalSite']['site_url'], true );
				$crlSite->check_content_uni( $cache['HtmlCache']['url'], $content, $site['IllegalSite']['site_uid'], $site['IllegalSite']['site_url'], false );
			}

			$html->clear();
			unset($html);
		}


		$crlSite = new Crlmodels;
		$crlSite->updateErr( $aryErr, $site );

		return $aryErr;

	}





}

?>