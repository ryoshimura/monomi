<?php
require_once "tracer.php";

/**
* ScEhentai http://g.e-hentai.org/ 専用モデル
*
* getもしくはpostでのみスクレイピングし、コンテンツマッチは検索結果で判断（detailページの文字照合は行わない）
*
* @param  none
* @return none
*/
class ScEhentai extends Tracer {

	public $name = 'ScEhentai';
	public $useTable = false;



	public function __construct() {

	}




	/**
	* 検索監視
	*
	*/
	public function ispSrch( $site, $new_flag = false ) {
pr('START');
		// findで使用するhtmlタグ
		$sc_post_tag = 'div.it3';
		$sc_lastupdate_tag = 'dl.threadlastpost';
		$sc_time_tag = 'em.time';
		$sc_link_tag = 'h3.threadtitle';
		$sc_contents_tag = 'div.has_after_content';



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
			$aryWd = $Word->get_crl_words();	// 全てのワードを抽出

		} else {
			$aryWd = $Word->get_crl_words( true );	// 1時間以内に登録されたワードのみ抽出
		}



		foreach( $aryWd as $wd ){

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


			// 2012.09.18 PostとGetで分岐
			if( $site['IllegalSite']['search_url_post'] == null || $site['IllegalSite']['search_url_post'] === '' ){	// GET
				$html = &$crlSite->get_html( $url, $site, $sc_post_tag, false, true );	// スクレイピング
				$sendMode = 'normal';

			} else {		//POST
				$html = &$crlSite->get_html( $url, $site, $sc_post_tag, false, true, mb_convert_encoding($wd['Word']['search_word'], $site['IllegalSite']['encoding'], 'UTF-8' ) );	// スクレイピング
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




			// add 2012.09.23 notFoundが検出されない検索結果ページに備えて
//			if( empty( $html->find( $sc_post_tag ) ){
//				unset($crlSite);
//				$html->clear();
//				unset($html);
//				continue;
//			}
			$obj = $html->find( $sc_post_tag );



			$cnt = 0;
			foreach( $obj as $val ){		// 1投稿を括るタグを指定

				// aタグが複数の場合あるため、不適切なタグは採用しない
				$href = '';
				foreach( $val->find('a') as $v  ){
					if( false === strpos( $v->href, 'gallerytorrents' ) ){
						$href = $v->href;
					}
				}
				if( $href === '' ){
					$val->clear();
					unset($val);
					continue;
				}

//				$crlSite->update_content( $href, $wd['Word']['word_uid'], $site_uid, $sc_contents_tag, true );
				$crlSite->check_content( $href, $val, $site_uid, $site['IllegalSite']['site_url'], true );

				$val->clear();
				unset($val);

				$cnt++;

			}

			unset($crlSite);
			$html->clear();
			unset($html);

			sleep(rand(3,6)); // 検索wait対策
		}


//print_r('loop End '.memory_get_usage() . "\n");
		$crlSite = new Crlmodels;
		$crlSite->updateErr( $aryErr, $site );
		return $aryErr;
	}




}

?>