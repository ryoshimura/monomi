<?php
require_once "tracer.php";

/**
* ScMangalovemake
*
* @param  none
* @return none
*/
class ScMangalovemake extends Tracer {

	public $name = 'ScMangalovemake';
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

print_r( $url . "\n");


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

			$flg = false;
			$cnt = 0;
			$obj = $html->find( $site['IllegalSite']['sc_post_tag'] );
			foreach( $obj as $val ){		// 1投稿を括るタグを指定


				// add 2012.09.23 notFoundが検出されない検索結果ページに備えて
				if( is_object( $val->find( $site['IllegalSite']['sc_detail_link_tag'], $site['IllegalSite']['sc_detail_link_tag_order'] ) ) ){
					if( empty( $val->find( $site['IllegalSite']['sc_detail_link_tag'], $site['IllegalSite']['sc_detail_link_tag_order'] )->href ) ){
						$val->clear();
						unset($val);
						continue;
					}
					$href = $val->find( $site['IllegalSite']['sc_detail_link_tag'], $site['IllegalSite']['sc_detail_link_tag_order'] )->href;
				} else {
					$val->clear();
					unset($val);
					continue;
				}
				$val->clear();
				unset($val);

				// http://で始まらない場合は補完する
				if( false === strpos( $href, 'http://' ) ){
					$href = $site['IllegalSite']['site_url'] . $href;
				}

				$preg_url = preg_quote( $site['IllegalSite']['site_url'], '/' );
				$preg_url = '/' . $preg_url . '/i';

				if( preg_match( $preg_url, $href ) ){

					$detail_url = $href;
print_r( ' $detail_url: ' . $detail_url . "\n");

					// add 2012.09.24 IllegalSiteは結構アップロードをこまめにしてるので、都度再読み込みしよかー
					$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

					$d_html = &$crlSite->get_htmlc( $detail_url, $site, $site['IllegalSite']['sc_contents_tag'], false );		// キャッシュ対応版
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
					$content = $d_html->find( $site['IllegalSite']['sc_contents_tag'], $site['IllegalSite']['sc_contents_tag_order'] );
					if( !is_object($content) ){
						$d_html->clear();
						unset($d_html);
						unset($content);
						continue;
					}

//					$crlSite->check_content( $detail_url, $content, $site_uid, $site['IllegalSite']['site_url'] );
					$flg_matching = false;
					$matching_text = $d_html->find('h2',0)->plaintext;

					$buf_text = $d_html->find('table',0)->innertext;
					$buf_text = str_replace( '<b>Circle</b>', '', $buf_text );
					$buf_text = str_replace( '<b>Author</b>', '', $buf_text );
					$buf_text = str_replace( '<b>Parody</b>', '', $buf_text );
					$buf_text = str_replace( '<b>Release</b>', '', $buf_text );
					$buf_text = str_replace( '<b>Create</b>', '', $buf_text );
					$buf_text = str_replace( '<b>Update</b>', '', $buf_text );
					$buf_text = str_replace( '<b>Description</b>', '', $buf_text );
					$buf_text = str_replace( '_undefined', '', $buf_text );
					$matching_text .= preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/','',$buf_text);

/*					foreach( $d_html->find('td') as $dkey => $dval ){
						if( $flg_matching == true ){
							$matching_text .= ' ' . $dval->plaintext;
							$flg_matching = false;
						}

//print_r($dval->innertext . "\n");
						if( $dval->innertext === "<b>Circle</b>\n" || $dval->innertext === '<b>Author</b>\n'  ){
							$flg_matching = true;
						}
					}
*/
					$crlSite->check_content_2st( $detail_url, $content, $matching_text, $site_uid, $site['IllegalSite']['site_url'] );

					$d_html->clear();
					unset($d_html);
				}
				$cnt++;
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

print_r('CRL_START: ' . $site['IllegalSite']['site_name'] ."\n");

		App::import('Model','Crlmodels');
		$IllegalSite = ClassRegistry::init('IllegalSite');

		$site_uid = $site['IllegalSite']['site_uid'];
		$aryErr = array();		// スクレイピングエラーなどの情報を格納するリターン用配列

		for( $p=1; $p<=1; $p++ ){	// クロールするページ（TOPから３ページ分）

			if( $p == 1 ){			// クロールするURLを選定
				$url = $site['IllegalSite']['site_url'];
			} else {

				// add 2013.01.11 $site['IllegalSite']['next_page_url']がnullの場合、2ページ目以降のスクレイピングは行なわない
				if( $site['IllegalSite']['next_page_url'] == null || $site['IllegalSite']['next_page_url'] === '' ){
					continue;
				} else {
					$url = str_replace( '[[page]]', $p, $site['IllegalSite']['next_page_url'] );
				}
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


			$flg = false;
			$cnt = 0;
			$obj = $html->find( $site['IllegalSite']['sc_post_tag'] );
			foreach( $obj as $val ){		// 1投稿を括るタグを指定


				// add 2012.09.23 notFoundが検出されない検索結果ページに備えて
				if( is_object( $val->find( $site['IllegalSite']['sc_detail_link_tag'], $site['IllegalSite']['sc_detail_link_tag_order'] ) ) ){
					if( empty( $val->find( $site['IllegalSite']['sc_detail_link_tag'], $site['IllegalSite']['sc_detail_link_tag_order'] )->href ) ){
						$val->clear();
						unset($val);
						continue;
					}
					$href = $val->find( $site['IllegalSite']['sc_detail_link_tag'], $site['IllegalSite']['sc_detail_link_tag_order'] )->href;
				} else {
					$val->clear();
					unset($val);
					continue;
				}
				$val->clear();
				unset($val);

				// http://で始まらない場合は補完する
				if( false === strpos( $href, 'http://' ) ){
					$href = $site['IllegalSite']['site_url'] . $href;
				}

				$preg_url = preg_quote( $site['IllegalSite']['site_url'], '/' );
				$preg_url = '/' . $preg_url . '/i';

				if( preg_match( $preg_url, $href ) ){

					$detail_url = $href;
print_r( ' $detail_url: ' . $detail_url . "\n");

					// add 2012.09.24 IllegalSiteは結構アップロードをこまめにしてるので、都度再読み込みしよかー
					$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

					$d_html = &$crlSite->get_htmlc( $detail_url, $site, $site['IllegalSite']['sc_contents_tag'], false );		// キャッシュ対応版
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
					$content = $d_html->find( $site['IllegalSite']['sc_contents_tag'], $site['IllegalSite']['sc_contents_tag_order'] );
					if( !is_object($content) ){
						$d_html->clear();
						unset($d_html);
						unset($content);
						continue;
					}

//					$crlSite->check_content( $detail_url, $content, $site_uid, $site['IllegalSite']['site_url'] );
					$flg_matching = false;
					$matching_text = $d_html->find('h2',0)->plaintext;

					$buf_text = $d_html->find('table',0)->innertext;
					$buf_text = str_replace( '<b>Circle</b>', '', $buf_text );
					$buf_text = str_replace( '<b>Author</b>', '', $buf_text );
					$buf_text = str_replace( '<b>Parody</b>', '', $buf_text );
					$buf_text = str_replace( '<b>Release</b>', '', $buf_text );
					$buf_text = str_replace( '<b>Create</b>', '', $buf_text );
					$buf_text = str_replace( '<b>Update</b>', '', $buf_text );
					$buf_text = str_replace( '<b>Description</b>', '', $buf_text );
					$buf_text = str_replace( '_undefined', '', $buf_text );
					$matching_text .= preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/','',$buf_text);

/*					foreach( $d_html->find('td') as $dkey => $dval ){
						if( $flg_matching == true ){
							$matching_text .= ' ' . $dval->plaintext;
							$flg_matching = false;
						}

//print_r($dval->innertext . "\n");
						if( $dval->innertext === "<b>Circle</b>\n" || $dval->innertext === '<b>Author</b>\n'  ){
							$flg_matching = true;
						}
					}
*/
					$crlSite->check_content_2st( $detail_url, $content, $matching_text, $site_uid, $site['IllegalSite']['site_url'] );

					$d_html->clear();
					unset($d_html);
				}
				$cnt++;
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