<?php
require_once "tracer.php";

/**
* ScNyaa
*
* キャッシュ＋ユニコード対応版
*
* @param  none
* @return none
*/
class ScNyaa extends Tracer {

	public $name = 'ScNyaa';
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

			$obj = $html->find( $site['IllegalSite']['sc_post_tag'] );


			$cnt = 0;
			foreach( $obj as $val ){		// 1投稿を括るタグを指定
//print_r( '$obj loop START ' . memory_get_usage() . "\n");
//pr($cnt);
				// add 2012.09.23 notFoundが検出されない検索結果ページに備えて
				if( empty($site['IllegalSite']['sc_detail_link_tag']) ){
					if( empty( $val->find('a', $site['IllegalSite']['sc_detail_link_tag_order'])->href ) ){
						$val->clear();
						unset($val);
						continue;
					}
					$href = $val->find('a', $site['IllegalSite']['sc_detail_link_tag_order'])->href;

				} else {
					if( empty( $val->find( $site['IllegalSite']['sc_detail_link_tag'], 0 )->find('a', $site['IllegalSite']['sc_detail_link_tag_order'])->href ) ){
						$val->clear();
						unset($val);
						continue;
					}
					$href = $val->find( $site['IllegalSite']['sc_detail_link_tag'], 0 )->find('a', $site['IllegalSite']['sc_detail_link_tag_order'])->href;
				}




				if( !preg_match( '/http\:\/\/.*/i', $href ) ){
					$href = $site['IllegalSite']['site_url'] . $href;
				}

				$content = $val->find( $site['IllegalSite']['sc_contents_tag'], 0 );
				if( !is_object($content) ){
					$val->clear();
					unset($val);
					unset($content);
					continue;
				}

				$body_text = '';
				$content = str_get_html( preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/','',$content->outertext) );
//print_r('$href: ' . $href . "\n");
//print_r('BODYTEXT: ' . $content->outertext . "\n\n");
//				$crlSite->check_content( $href, $content, $site_uid, $site['IllegalSite']['site_url'] );
				$crlSite->check_content_uni( $href, $content, $site_uid, $site['IllegalSite']['site_url'], false );

				$content->clear();
				unset($content);
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
			sleep(rand(5,7)); // 検索wait対策
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
print_r('CRL_START(nyaa): ' . $site['IllegalSite']['site_name'] ."\n");

		App::import('Model','Crlmodels');
		$IllegalSite = ClassRegistry::init('IllegalSite');

		$site_uid = $site['IllegalSite']['site_uid'];
		$aryErr = array();		// スクレイピングエラーなどの情報を格納するリターン用配列

		for( $p=1; $p<=2; $p++ ){	// クロールするページ（TOPから３ページ分）
//		for( $p=1; $p<=1; $p++ ){	// クロールするページ（TOPから３ページ分）

			if( $p == 1 ){			// クロールするURLを選定
				$url = $site['IllegalSite']['site_url'];
			} else {
				$url = str_replace( '[[page]]', $p, $site['IllegalSite']['next_page_url'] );
			}


			$crlSite = new Crlmodels;

print_r( '$url: ' . $url . "\n");

			// add 2012.09.24 IllegalSiteは結構アップロードをこまめにしてるので、都度再読み込みしよかー
			$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

			$html = &$crlSite->get_html( $url, $site, $site['IllegalSite']['sc_post_tag'], false );	// スクレイピング
			if( !is_object($html) ){	// エラーが存在する場合
				if( isset($html['MonomiError']) ){	// エラーが存在する場合
					if( 'notUpdate' !== $html['MonomiError'] ){
						$err = array( 'url' => $url, 'status' => $html['MonomiError'], 'mode'=>'normal' );	// スクレイピングエラーの場合
						$aryErr[] = $err;
					}
					continue;
				}
			}

			if( $site['IllegalSite']['sc_post_tag_forSearchResult'] != null ) {
				if( !is_object($html->find( $site['IllegalSite']['sc_post_tag_forSearchResult'], 0 )) ){
					$html->clear();
					unset($html);
					continue;
				}
				$obj = $html->find( $site['IllegalSite']['sc_post_tag_forSearchResult'], 0 )->find( $site['IllegalSite']['sc_post_tag'] );
			} else {
				$obj = $html->find( $site['IllegalSite']['sc_post_tag'] );
			}



			$cnt = 0;
			foreach( $obj as $val ){		// 1投稿を括るタグを指定
//print_r( '$obj loop START ' . memory_get_usage() . "\n");
//pr($cnt);
				// add 2012.09.23 notFoundが検出されない検索結果ページに備えて
				if( empty($site['IllegalSite']['sc_detail_link_tag']) ){
					if( empty( $val->find('a', $site['IllegalSite']['sc_detail_link_tag_order'])->href ) ){
						$val->clear();
						unset($val);
						continue;
					}
					$href = $val->find('a', $site['IllegalSite']['sc_detail_link_tag_order'])->href;

				} else {
					if( empty( $val->find( $site['IllegalSite']['sc_detail_link_tag'], 0 )->find('a', $site['IllegalSite']['sc_detail_link_tag_order'])->href ) ){
						$val->clear();
						unset($val);
						continue;
					}
					$href = $val->find( $site['IllegalSite']['sc_detail_link_tag'], 0 )->find('a', $site['IllegalSite']['sc_detail_link_tag_order'])->href;
				}



				if( !preg_match( '/http\:\/\/.*/i', $href ) ){
					$href = $site['IllegalSite']['site_url'] . $href;
				}

				if( empty($site['IllegalSite']['sc_contents_tag']) ){
					$content = $val;
				} else {
					$content = $val->find( $site['IllegalSite']['sc_contents_tag'], 0 );
				}

				if( !is_object($content) ){
					$val->clear();
					unset($val);
					unset($content);
					continue;
				}

				$body_text = '';
				$content = str_get_html( preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/','',$content->outertext) );
print_r('$href: ' . $href . "\n");
//print_r('BODYTEXT: ' . $content->outertext . "\n\n");
//				$crlSite->check_content( $href, $content, $site_uid, $site['IllegalSite']['site_url'] );
				$crlSite->check_content_uni( $href, $content, $site_uid, $site['IllegalSite']['site_url'], false );

				$content->clear();
				unset($content);
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
			sleep(rand(5,7)); // 検索wait対策

		}


		// 1件も投稿記事が確認できない場合
		if( $cnt == 0 ){
			$err = array( 'url' => $url, 'status' => 'NONE POST', 'mode'=>'normal' );
			$aryErr[] = $err;
		}


		$crlSite = new Crlmodels;
		$crlSite->updateErr( $aryErr, $site );

		return $aryErr;
	}






}

?>