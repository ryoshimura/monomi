<?php
require_once "tracer.php";

/**
* ScU2dbook
*
* キャッシュ＋ユニコード対応版
*
* @param  none
* @return none
*/
class ScU2dbook extends Tracer {

	public $name = 'ScU2dbook';
	public $useTable = false;




	public function __construct() {

	}





	public function ispTest( $site, $crlSite ) {
		$url = $site['IllegalSite']['site_url'];
		if( false === $html = $crlSite->get_html( $url, $site, 'div.post' ) ){	// スクレイピング
		}
	}




	/**
	* 検索監視
	*
	*/
	public function ispSrch( $site, $new_flag = false ) {

//print_r('START_test!!'."\n");
print_r('CRL_START: ' . $site['IllegalSite']['site_name'] ."\n");

		$sc_hantei_tag			= 'div.content_filelist';
		$sc_post_tag			= 'div.item';
		$sc_detail_link_tag		= 'p.filetitle';


		$aryErr = array();		// スクレイピングエラーなどの情報を格納するリターン用配列

		// add 2012.10.11 検索非対応サイトの場合は強制終了
		if( ($site['IllegalSite']['search_url']==null || $site['IllegalSite']['search_url']==='') && ($site['IllegalSite']['search_url_post']==null || $site['IllegalSite']['search_url_post']==='') ) {
			return $aryErr;
		}


		App::import('Model','Crlmodels');
		$IllegalSite = ClassRegistry::init('IllegalSite');

		$site_uid = $site['IllegalSite']['site_uid'];

		// 契約が生きている検索ワード一覧を取得
		$Word = ClassRegistry::init('Word');
		if( $new_flag == false ){
			$aryWd = $Word->get_crl_words();	// 全てのワードを抽出
		} else {
			$aryWd = $Word->get_crl_words( true );	// 1時間以内に登録されたワードのみ抽出
		}

		foreach( $aryWd as $wd ){
//print_r( '$aryWd loop ' . memory_get_usage() . "\n");
			$crlSite = new Crlmodels;

			if( $wd['Word']['search_word'] === '' || $wd['Word']['search_word'] == null ){	// 検索ワードが空白の場合、continue
				continue;
			}

			// POST、GET分岐
			if( $site['IllegalSite']['search_url_post'] == null || $site['IllegalSite']['search_url_post'] === '' ){	// GET
				$url = str_replace( '[[kw]]', urlencode( mb_convert_encoding($wd['Word']['search_word'], $site['IllegalSite']['encoding'], 'UTF-8' )), $site['IllegalSite']['search_url'] );
			} else {		// POST
				$url = $site['IllegalSite']['search_url_post'];
			}

//print_r( $url . "\n");

			// add 2012.09.24 IllegalSiteは結構アップロードをこまめにしてるので、都度再読み込みしよかー
			$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

			// 2012.09.18 PostとGetで分岐
			if( $site['IllegalSite']['search_url_post'] == null || $site['IllegalSite']['search_url_post'] === '' ){	// GET
				$html = &$crlSite->get_html( $url, $site, $site['IllegalSite']['sc_post_tag'], false, true );	// スクレイピング
				$sendMode = 'normal';
			} else {		//POST
				$html = &$crlSite->get_html( $url, $site, $site['IllegalSite']['sc_post_tag'], false, true, mb_convert_encoding($wd['Word']['search_word'], $site['IllegalSite']['encoding'], 'UTF-8' ) );	// スクレイピング
				$sendMode = 'post';
			}

			if( !is_object($html) ){	// エラーが存在する場合
				if( isset($html['MonomiError']) ){	// エラーが存在する場合
					if( 'notFound' === $html['MonomiError'] ){
						// notFoundはエラー扱いにしない
					} else if( 'notUpdate' !== $html['MonomiError'] ){
						$err = array( 'url' => $url, 'status' => $html['MonomiError'], 'mode'=>$sendMode );	// スクレイピングエラーの場合
						$aryErr[] = $err;
					}
					continue;
				}
			}

//pr($html->outertext);
			$obj = $html->find( $sc_post_tag );

			$cnt = 0;
			foreach( $obj as $val ){		// 1投稿を括るタグを指定
//print_r( '$obj loop 1 START ' . memory_get_usage() . "\n");

				// add 2012.09.23 notFoundが検出されない検索結果ページに備えて
				if( empty( $val->find( $sc_detail_link_tag, 0 )->find('a', 0)->href ) ){
					$val->clear();
					unset($val);
					continue;
				}
				$href = $val->find( $sc_detail_link_tag, 0 )->find('a', 0)->href;

//print_r( '$obj loop 2 START ' . memory_get_usage() . "\n");

				if( !preg_match( '/http\:\/\/.*/i', $href ) ){
					$href = $site['IllegalSite']['site_url'] . $href;
				}

				$content = $val->find( 'div.thumb', 0 )->find('img', 0)->outertext;

//print_r( '$obj loop 3 START ' . memory_get_usage() . "\n");

				$arr=preg_split("|<[^>]*alt=[\"\'][^\'\"]*[\"\'][^<]*>|i", $content);
				preg_match_all('|<[^>]*alt=[\"\']([^\'\"]*)[\"\'][^<]*>|i',$content,$matches);
				$txt=strip_tags($arr[0]);
//print_r($matches[1]);
				foreach($matches[1] as $i=>$v){
					$txt.=$v.strip_tags($arr[$i+1]);
				}

				$crlSite->check_content_plaintext( $href, $txt, $site_uid, $site['IllegalSite']['site_url'] );

//print_r( '$obj loop 6 START ' . memory_get_usage() . "\n");

//				$content->clear();
//				unset($content);
				$val->clear();
				unset($val);

				$cnt++;

			}


			unset($crlSite);
			if( is_object( $obj )){
				$obj->clear();
				unset($obj);
			}
			$html->clear();
			unset($html);
			sleep(rand(5,9)); // 検索wait対策
		}

//print_r('loop End '.memory_get_usage() . "\n");
		$crlSite = new Crlmodels;
		$crlSite->updateErr( $aryErr, $site );
		return $aryErr;
	}






	/**
	* 新着監視
	*
	*/
	public function ispCrw( $site ) {

//		error_reporting(E_ALL ^ E_NOTICE);
print_r('CRL_START: ' . $site['IllegalSite']['site_name'] ."\n");


		$sc_hantei_tag			= 'div.content_filelist';
		$sc_post_tag			= 'div.item';
		$sc_detail_link_tag		= 'p.filetitle';


		App::import('Model','Crlmodels');
		$IllegalSite = ClassRegistry::init('IllegalSite');

		$site_uid = $site['IllegalSite']['site_uid'];
		$aryErr = array();		// スクレイピングエラーなどの情報を格納するリターン用配列

		for( $p=1; $p<=5; $p++ ){	// クロールするページ（TOPから３ページ分）

			if( $p == 1 ){			// クロールするURLを選定
				$url = $site['IllegalSite']['site_url'];
			} else {
				$url = str_replace( '[[page]]', $p, $site['IllegalSite']['next_page_url'] );
			}


			$crlSite = new Crlmodels;

print_r( '$url: ' . $url . "\n");
//print_r( '$page loop 1 ' . memory_get_usage() . "\n");

			// add 2012.09.24 IllegalSiteは結構アップロードをこまめにしてるので、都度再読み込みしよかー
			$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

			$html = $crlSite->get_html( $url, $site, $sc_hantei_tag, false );	// スクレイピング
			if( !is_object($html) ){	// エラーが存在する場合
				if( isset($html['MonomiError']) ){	// エラーが存在する場合
					if( 'notUpdate' !== $html['MonomiError'] ){
						$err = array( 'url' => $url, 'status' => $html['MonomiError'], 'mode'=>'normal' );	// スクレイピングエラーの場合
						$aryErr[] = $err;
					}
					continue;
				}
			}
//print_r($html->outertext);
//print_r( '$page loop 2 ' . memory_get_usage() . "\n");
/*
//			$html2 = str_get_html( $html->find( 'div.content_filelist', 0 )->outertext );
			$html2 = str_get_html( $html->find( 'div.itembox', 1 )->outertext );
			$html->clear();
			unset($html);
*/
//print_r( '$page loop 3 ' . memory_get_usage() . "\n");

			$obj = $html->find( $sc_post_tag );
//			$html->clear();
//			unset($html);
//print_r( '$page loop 4 ' . memory_get_usage() . "\n");

			$cnt = 0;
			foreach( $obj as $val ){		// 1投稿を括るタグを指定
//print_r( '$obj loop 1 START ' . memory_get_usage() . "\n");

				// add 2012.09.23 notFoundが検出されない検索結果ページに備えて
				if( empty( $val->find( $sc_detail_link_tag, 0 )->find('a', 0)->href ) ){
					$val->clear();
					unset($val);
					continue;
				}
				$href = $val->find( $sc_detail_link_tag, 0 )->find('a', 0)->href;

//print_r( '$obj loop 2 START ' . memory_get_usage() . "\n");

				if( !preg_match( '/http\:\/\/.*/i', $href ) ){
					$href = $site['IllegalSite']['site_url'] . $href;
				}

				$content = $val->find( 'div.thumb', 0 )->find('img', 0)->outertext;

//print_r( '$obj loop 3 START ' . memory_get_usage() . "\n");

				$arr=preg_split("|<[^>]*alt=[\"\'][^\'\"]*[\"\'][^<]*>|i", $content);
				preg_match_all('|<[^>]*alt=[\"\']([^\'\"]*)[\"\'][^<]*>|i',$content,$matches);
				$txt=strip_tags($arr[0]);
//print_r($matches[1]);
				foreach($matches[1] as $i=>$v){
					$txt.=$v.strip_tags($arr[$i+1]);
				}

				$crlSite->check_content_plaintext( $href, $txt, $site_uid, $site['IllegalSite']['site_url'] );

//print_r( '$obj loop 6 START ' . memory_get_usage() . "\n");

//				$content->clear();
//				unset($content);
				$val->clear();
				unset($val);

				$cnt++;

			}

//print_r( 'page loop b1END ' . memory_get_usage() . "\n");

			unset($crlSite);
			if( is_object( $obj )){
				$obj->clear();
				unset($obj);
			}
//print_r( 'page loop b2END ' . memory_get_usage() . "\n");
			$html->clear();
			unset($html);
//			$html2->clear();
//			unset($html2);
			sleep(rand(5,7)); // 検索wait対策

//print_r( 'page loop END ' . memory_get_usage() . "\n");
		}


		// 1件も投稿記事が確認できない場合
		if( $cnt == 0 ){
			$err = array( 'url' => $url, 'status' => 'NONE POST', 'mode'=>'normal' );
			$aryErr[] = $err;
		}


		$crlSite = new Crlmodels;
		$crlSite->updateErr( $aryErr, $site );
		unset($crlSite);

//print_r( '2DBOOK END ' . memory_get_usage() . "\n");

		return $aryErr;
	}






}

?>