<?php
require_once "tracer.php";

/**
* ScPublic1
*
* @param  none
* @return none
*/
class ScPublic1 extends Tracer {

	public $name = 'ScPublic1';
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

		$aryErr = array();		// スクレイピングエラーなどの情報を格納するリターン用配列


//pr($site);
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
//			$aryWd = $Word->find('all', array('conditions'=>array('deleted'=>0, 'search_word <>'=>'')));	// 全てのワードを抽出
			$aryWd = $Word->get_crl_words();	// 全てのワードを抽出

		} else {
//			$time = date("Y-m-d H:i:s",strtotime("-1 hour"));
//			$aryWd = $Word->find('all', array('conditions'=>array('deleted'=>0, 'search_word <>'=>'', 'created >='=>$time )));	// 1時間以内に登録されたワードのみ抽出
			$aryWd = $Word->get_crl_words( true );	// 1時間以内に登録されたワードのみ抽出
		}


//pr($site);
//pr($crlSite);
//pr($aryWd);

		foreach( $aryWd as $wd ){
//pr('$wd : '.$wd['Word']['search_word']);
//print_r( '$aryWd loop : ' . $wd['Word']['search_word'] . "\n");
//print_r( '$aryWd loop ' . memory_get_usage() . "\n");

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


//			$crlSite = ClassRegistry::init('Crlsite');
			$crlSite = new Crlmodels;

			// add 2012.09.24 IllegalSiteは結構アップロードをこまめにしてるので、都度再読み込みしよかー
			$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

			// add 2012.09.27 検索結果が通常ページのタグ構造と異なる場合
			if( $site['IllegalSite']['sc_post_tag_forSearchResult'] != null && $site['IllegalSite']['sc_detail_link_tag_forSearchResult'] != null ) {
				$site['IllegalSite']['sc_post_tag']			= $site['IllegalSite']['sc_post_tag_forSearchResult'];
				$site['IllegalSite']['sc_detail_link_tag']	= $site['IllegalSite']['sc_detail_link_tag_forSearchResult'];
			}


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

				// add 2012.10.05 検索結果が通常ページのタグ構造と異なる場合
				if( $site['IllegalSite']['sc_post_tag_forSearchResult'] != null && $site['IllegalSite']['sc_detail_link_tag_forSearchResult'] != null ) {
					$site['IllegalSite']['sc_post_tag']			= $site['IllegalSite']['sc_post_tag_forSearchResult'];
					$site['IllegalSite']['sc_detail_link_tag']	= $site['IllegalSite']['sc_detail_link_tag_forSearchResult'];
				}
//pr($cnt);
//pr($site['IllegalSite']['sc_detail_link_tag']);
				// add 2012.09.23 notFoundが検出されない検索結果ページに備えて
				if( empty( $val->find( $site['IllegalSite']['sc_detail_link_tag'], 0 )->find('a',0)->href ) ){
					break;
				}
				$href = $val->find( $site['IllegalSite']['sc_detail_link_tag'], 0 )->find('a',0)->href;

				$val->clear();
				unset($val);

				$preg_url = preg_quote( $site['IllegalSite']['site_url'], '/' );
				$preg_url = '/' . $preg_url . '/i';

//				if( preg_match( $site['IllegalSite']['sc_preg_site_url'], $href ) ){
				if( preg_match( $preg_url, $href ) ){

//					$detail_url = preg_replace( '/#more.*/', '', $href );
					$detail_url = $href;

//					if( false === $d_html = &$crlSite->get_html( $detail_url, $site, $site['IllegalSite']['sc_contents_tag'] ) ){	// スクレイピング
//						$err = array( 'url' => $url, 'status' => 'HTML difference' );	// スクレイピングエラーの場合
//						$aryErr[] = $err;
//						continue;
//					}


					// add 2012.09.24 IllegalSiteは結構アップロードをこまめにしてるので、都度再読み込みしよかー
					$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

					$d_html = &$crlSite->get_html( $detail_url, $site, $site['IllegalSite']['sc_contents_tag'], false );	// スクレイピング
					if( !is_object($d_html) ){	// エラーが存在する場合
						if( isset($d_html['MonomiError']) ){	// エラーが存在する場合
							if( 'notUpdate' !== $d_html['MonomiError'] ){
//print_r( ' $d_html: ' . $d_html . "\n");
								$err = array( 'url' => $detail_url, 'status' => $d_html['MonomiError'], 'mode'=>$sendMode );	// スクレイピングエラーの場合
								$aryErr[] = $err;
							}
							continue;
						}
					}
					$content = $d_html->find( $site['IllegalSite']['sc_contents_tag'], 0 );
					$crlSite->check_content( $detail_url, $content, $site_uid, $site['IllegalSite']['site_url'] );

					$d_html->clear();
					unset($d_html);
				}
				$cnt++;

//print_r('2-A: '.memory_get_usage() . "\n");
//				$d_html->clear();
//				unset($d_html);
//print_r('2-B: '.memory_get_usage() . "\n");

//print_r( '$obj loop END ' . memory_get_usage() . "\n");
			}

			// 1件も投稿記事が確認できない場合
			// 検索スクレイピングでは１件もないケースはザラにあるので、これでエラー扱いはしない
//			if( $cnt == 0 ){
//				$err = array( 'url' => $url, 'status' => 'NONE POST' );
//				$aryErr[] = $err;
//			}

			unset($crlSite);

			$html->clear();
			unset($html);

			sleep(rand(3,5)); // 検索wait対策
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

//			if( false === $html = &$crlSite->get_html( $url, $site, 'div.post' ) ){	// スクレイピング
//				$err = array( 'url' => $url, 'status' => 'HTML difference' );	// スクレイピングエラーの場合
//				$aryErr[] = $err;
//				continue;
//			}

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

//			$obj = $html->find('div.post');
			$obj = $html->find( $site['IllegalSite']['sc_post_tag'] );


			$flg = false;
			$cnt = 0;
			foreach( $obj as $val ){		// 1投稿を括るタグを指定


				// add 2012.09.23 notFoundが検出されない検索結果ページに備えて
				if( empty( $val->find( $site['IllegalSite']['sc_detail_link_tag'], 0 )->find('a',0)->href ) ){
					break;
				}
				$href = $val->find( $site['IllegalSite']['sc_detail_link_tag'], 0 )->find('a',0)->href;

				$val->clear();
				unset($val);


				$preg_url = preg_quote( $site['IllegalSite']['site_url'], '/' );
				$preg_url = '/' . $preg_url . '/i';
//pr($preg_url);
//				if( preg_match( $site['IllegalSite']['sc_preg_site_url'], $href ) ){
				if( preg_match( $preg_url, $href ) ){

					$detail_url = $href;
//print_r( ' $detail_url: ' . $detail_url . "\n");

					// add 2012.09.24 IllegalSiteは結構アップロードをこまめにしてるので、都度再読み込みしよかー
					$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

					$d_html = &$crlSite->get_html( $detail_url, $site, $site['IllegalSite']['sc_contents_tag'], false );	// スクレイピング

					if( !is_object($d_html) ){	// エラーが存在する場合
						if( isset($d_html['MonomiError']) ){	// エラーが存在する場合
							if( 'notUpdate' !== $d_html['MonomiError'] ){
								$err = array( 'url' => $detail_url, 'status' => $d_html['MonomiError'], 'mode'=>'normal' );	// スクレイピングエラーの場合
								$aryErr[] = $err;
							}
							continue;
						}
					}

					// 2012.10.18 $tag_orderを追加
//					$content = $d_html->find( $site['IllegalSite']['sc_contents_tag'], 0 );
					$content = $d_html->find( $site['IllegalSite']['sc_contents_tag'], $site['IllegalSite']['sc_contents_tag_order'] );


					if( !is_object($content) ){
print_r('not_object $content: '. $detail_url . "\n");
						$d_html->clear();
						unset($d_html);
						unset($content);
						continue;
					}


//pr($content->plaintext);
					$crlSite->check_content( $detail_url, $content, $site_uid, $site['IllegalSite']['site_url'] );

					$d_html->clear();
					unset($d_html);
				}
				$cnt++;

//break;
			}

			// 1件も投稿記事が確認できない場合
			if( $cnt == 0 ){
				$err = array( 'url' => $url, 'status' => 'NONE POST', 'mode'=>'normal' );
				$aryErr[] = $err;
			}


			unset($crlSite);

			$html->clear();
			unset($html);

			sleep(rand(1,3)); // 検索wait対策
		}

		$crlSite = new Crlmodels;
		$crlSite->updateErr( $aryErr, $site );

		return $aryErr;
	}






}

?>