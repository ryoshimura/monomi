<?php
require_once "tracer.php";

class ScUsnzone extends Tracer {

	public $name = 'ScUsnzone';
	public $useTable = false;

	// erojiji専用スクレイピングリスト
	public $urlList = array(
		'漫画同人1'	=> 'http://www.usnzone.com/forum/forum-28-1.html',
		'漫画同人2'	=> 'http://www.usnzone.com/forum/forum-28-2.html',
		'PCゲーム'	=> 'http://www.usnzone.com/forum/forum-71-1.html',
	);





	public function __construct() {

	}



	/**
	* 新着監視
	*
	*/
	public function ispCrw( $site ) {

		// findで使用するhtmlタグ
		$base_url = 'http://www.usnzone.com/forum/';
		$sc_post_tag = 'th.new';
//		$sc_lastupdate_tag = 'ul.last_post';
//		$sc_link_tag = 'h4';

//		$sc_contents_tag = 'div.post_wrap';
		$sc_contents_tag = 'td.t_msgfont';
//		$sc_content_body_tag = 'div.has_after_content';


		App::import('Model','Crlmodels');
		$IllegalSite = ClassRegistry::init('IllegalSite');
		$Cache = ClassRegistry::init('Cache');

		$site_uid = $site['IllegalSite']['site_uid'];
		$aryErr = array();		// スクレイピングエラーなどの情報を格納するリターン用配列



		// リストのURLをスクレイピング
		$before_url = '';
		foreach( $this->urlList as $url ){

print_r( '$url: ' . $url . "\n");

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

				foreach( $val->find('a') as $v ) {
					$detail_url	= $base_url . $v->href;
print_r( ' $detail_url: ' . $detail_url . "\n");

					$v->clear();
					unset($v);

					// $this->urlList と重複した場合は continue
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
					$d_html = &$crlSite->get_htmlc( $detail_url, $site, $sc_contents_tag, false );

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
						$crlSite->check_content_uni( $detail_url, $content, $site_uid, $site['IllegalSite']['site_url'], false );
					}

					$d_html->clear();
					unset($d_html);

					$cnt++;

				}

				$val->clear();
				unset($val);


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

		$sc_contents_tag = 'div.has_after_content';

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
				$crlSite->check_content( $cache['HtmlCache']['url'], $content, $site['IllegalSite']['site_uid'], $site['IllegalSite']['site_url'], true );
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