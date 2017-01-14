<?php
require_once "tracer.php";

class ScPortalnet extends Tracer {

	public $name = 'ScPortalnet';
	public $useTable = false;

	// erojiji専用スクレイピングリスト
	public $urlList = array(
		'Robotblock'	=> 'http://www.portalnet.cl/comunidad/robot-y-bloqueados.46',
		'Hentai-Videos'	=> 'http://www.portalnet.cl/comunidad/videos.297',
		'Hentai'		=> 'http://www.portalnet.cl/comunidad/hentai.153',
		'Hentai-Manga'	=> 'http://www.portalnet.cl/comunidad/mangas.298',
		'Manga & Musica Anime-En proceso de subida'	=> 'http://www.portalnet.cl/comunidad/en-proceso-de-subida.914',
		'Manga & Musica Anime-Terminados'	=> 'http://www.portalnet.cl/comunidad/terminados.154',
		'Consolas-Xbox'		=> 'http://www.portalnet.cl/comunidad/xbox.569',
		'Consolas-Sony'		=> 'http://www.portalnet.cl/comunidad/sony.567',
		'Consolas-Nintendo'	=> 'http://www.portalnet.cl/comunidad/nintendo.568',
		'Juegos PC-Adultxxx Gamez'	=>	'http://www.portalnet.cl/comunidad/adultxxx-gamez.575',
	);




	public function __construct() {

	}



	/**
	* 新着監視
	*
	*/
	public function ispCrw( $site ) {


		error_reporting(E_ALL ^ E_NOTICE);

		// findで使用するhtmlタグ
		$base_url = 'http://www.portalnet.cl/comunidad/';
//		$sc_post_tag = 'div.nonsticky';
		$sc_post_tag = 'li.threadbit';

		$sc_lastupdate_tag = 'dl.threadlastpost';
		$sc_link_tag = 'h3';
		$sc_contents_tag = 'div.postbody';
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

//print_r( $html->outertext );
			$obj = $html->find( $sc_post_tag );

			$flg = false;
			$cnt = 0;
			$update_break_flag = false;
			foreach( $obj as $key => $val ){		// 1投稿を括るタグを指定

				if( !is_object($val) ){
//pr($cnt);
					continue;
				}
				$links = $val->find($sc_link_tag, 0);
				if( !is_object($links) ){
					continue;
				}

				foreach( $links->find('a') as $v ) {
					$detail_url	= $v->href;
print_r( ' $detail_url: ' . $detail_url . "\n");
//print_r($val->outertext);
					$strUpdate	= $val->find($sc_lastupdate_tag, 0)->find('dd', 1)->plaintext;
//print_r($strUpdate);
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










}

?>