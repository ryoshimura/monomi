<?php
require_once "tracer.php";

class ScAnimeSharing extends Tracer {

	public $name = 'ScAnimeSharing';
	public $useTable = false;

	// Anime-sharing専用スクレイピングリスト
	public $urlList = array(
		'New Releases'					=> 'http://www.anime-sharing.com/forum/new-releases-77/',
//		'Ongoing Series'				=> 'http://www.anime-sharing.com/forum/ongoing-series-16/',
//		'Completed Series'				=> 'http://www.anime-sharing.com/forum/completed-series-17/',
		'Movies & OVAs'					=> 'http://www.anime-sharing.com/forum/movies-ovas-18/',
		'Hentai Artbooks'				=> 'http://www.anime-sharing.com/forum/hentai-artbooks-21/',
		'Hentai CG Packs'				=> 'http://www.anime-sharing.com/forum/hentai-cg-packs-24/',
		'Non-hentai Artbooks'			=> 'http://www.anime-sharing.com/forum/non-hentai-artbooks-22/',
		'Non-hentai CG Packs'			=> 'http://www.anime-sharing.com/forum/non-hentai-cg-packs-25/',
		'Hentai Games'					=> 'http://www.anime-sharing.com/forum/hentai-games-38/',
		'NDS/Wii'						=> 'http://www.anime-sharing.com/forum/nds-wii-68/',
		'PSP/PS/PS2/PS3'				=> 'http://www.anime-sharing.com/forum/psp-ps-ps2-ps3-69/',
		'Xbox/Xbox360'					=> 'http://www.anime-sharing.com/forum/xbox-xbox360-72/',
		'Other Systems'					=> 'http://www.anime-sharing.com/forum/other-systems-75/',
		'Hentai OVAs'					=> 'http://www.anime-sharing.com/forum/hentai-ovas-36/',
		'Currently Scanlated Manga'		=> 'http://www.anime-sharing.com/forum/currently-scanlated-manga-28/',
		'Completely Scanlated Manga'	=> 'http://www.anime-sharing.com/forum/completely-scanlated-manga-29/',
		'Raw Manga'						=> 'http://www.anime-sharing.com/forum/raw-manga-30/',
		'Hentai Manga'					=> 'http://www.anime-sharing.com/forum/hentai-manga-32/',
		'Hentai Doujinshi'				=> 'http://www.anime-sharing.com/forum/hentai-doujinshi-34/',
		'Non-hentai Doujinshi'			=> 'http://www.anime-sharing.com/forum/non-hentai-doujinshi-35/',
		'Light Novels'					=> 'http://www.anime-sharing.com/forum/light-novels-61/',
		'Anime OSTs'					=> 'http://www.anime-sharing.com/forum/anime-osts-41/',
		'Game OSTs'						=> 'http://www.anime-sharing.com/forum/game-osts-42/',
		'Non-OST music'					=> 'http://www.anime-sharing.com/forum/non-ost-music-43/',
		'Miscellaneous'					=> 'http://www.anime-sharing.com/forum/miscellaneous-45/',
		'Extreme Content'				=> 'http://www.anime-sharing.com/forum/extreme-content-83/',
//		'Graveyard'						=> 'http://www.anime-sharing.com/forum/graveyard-63/',
	);





	public function __construct() {

	}



	/**
	* 新着監視
	*
	*/
	public function ispCrw( $site ) {

print_r('CRL_START: ' . $site['IllegalSite']['site_name'] ."\n");

		// findで使用するhtmlタグ
		$sc_post_tag = 'div.nonsticky';
		$sc_lastupdate_tag = 'dl.threadlastpost';
		$sc_time_tag = 'em.time';
		$sc_link_tag = 'h3.threadtitle';
//		$sc_contents_tag = 'li.postcontainer';
		$sc_contents_tag = 'div.has_after_content';
//		$sc_content_body_tag = 'div.has_after_content';


		App::import('Model','Crlmodels');
		$IllegalSite = ClassRegistry::init('IllegalSite');

		$site_uid = $site['IllegalSite']['site_uid'];
		$aryErr = array();		// スクレイピングエラーなどの情報を格納するリターン用配列


		// page2分まで成形
		$aryUrlList = array();
		foreach( $this->urlList as $val ){
			$aryUrlList[] = $val;
			$aryUrlList[] = $val . 'index2.html';
		}



		// リストのURLをスクレイピング
		$before_url = '';
		foreach( $aryUrlList as $url ){

			// 1ページ目でbreakした場合、2ページは即breakする
			if( $before_url !== '' ){
				if( false !== strpos( $url, $before_url )){	// 直前にbreakしURLが1ページ目の場合、continue
					continue;
				}
			}


print_r( '$url: ' . $url . "\n");

			$crlSite = new Crlmodels;
			$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

			$html = &$crlSite->get_html( $url, $site, $sc_post_tag, false );	// スクレイピング
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
//pr($html->plaintext);

			$flg = false;
			$cnt = 0;
			$update_break_flag = false;
			foreach( $obj as $key => $val ){		// 1投稿を括るタグを指定

				if( empty( $val->find( $sc_lastupdate_tag, 0 )->plaintext ) ){ break; }
				$lastdate = $val->find( $sc_lastupdate_tag, 0 )->plaintext;	// 更新日周辺をくくってるhtmlタグ

				// 更新日チェック（該当時間以外は全てbreak		時差9時間あるくせぇｗ
			if ( strtotime(date("Y-m-d 3:00:00", strtotime("-9 hour"))) >= strtotime("-9 hour") ) {	// Yesterdayもチェック
					if( false != preg_match( '/.*?(Today|Yesterday).*/i', $lastdate ) ){	// 現在時刻が 0:00～3:00 の場合、TodayとYesterdayをチェック
						if( false != preg_match( '/.*?Yesterday.*/i', $lastdate ) ){
							$time = $val->find( $sc_lastupdate_tag, 0 )->find( $sc_time_tag, 0 )->plaintext;
							$aryTime = explode(':',$time);
							if( $aryTime[0] < 20 ){		// 前日22時以前はbreak
								$update_break_flag = true;
								$before_url = $url;
								break;
							}
						}
					} else {
						$update_break_flag = true;
						$before_url = $url;
						break;
					}
				} else {	// Todayのみチェック
					if( false != preg_match( '/.*?Today.*/i', $lastdate ) ) {	// 現在時刻が
						// 直近2時間以内かチェック
						$time = $val->find( $sc_lastupdate_tag, 0 )->find( $sc_time_tag, 0 )->plaintext;
						if( !$this->check_update($time, 3) ){	// 2時間以内の更新されていない場合はbreak
							$update_break_flag = true;
							$before_url = $url;
							break;
						}
					} else {
						$update_break_flag = true;
						$before_url = $url;
						break;
					}
				}

				// キャッシュ化にともない時刻は見ずに、Todayとyesterday全てチェック
				// ボリューム大杉だったので中止
//				if( false == preg_match( '/.*?(Today|Yesterday).*/i', $lastdate ) ){
//					$update_break_flag = true;
//					$before_url = $url;
//					break;
//				}


				// 詳細スレッドへのリンク抽出
				if( empty( $val->find( $sc_link_tag, 0 )->find('a',0)->href ) ){
					continue;
				}
				$detail_url = $val->find( $sc_link_tag, 0 )->find('a',0)->href;

//print_r( ' $detail_url: ' . $detail_url . "\n");

				$val->clear();
				unset($val);

				// add 2012.09.24 IllegalSiteは結構アップロードをこまめにしてるので、都度再読み込みしよかー
				$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

				// 詳細スレッドをスクレイピング
//				$d_html = &$crlSite->get_html( $detail_url, $site, $sc_contents_tag, false );
				$d_html = &$crlSite->get_htmlc( $detail_url, $site, $sc_contents_tag, $lastdate );		// キャッシュ対応版


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
					$crlSite->check_content( $detail_url, $content, $site_uid, $site['IllegalSite']['site_url'], true );
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



	/**
	* 指定の時間に更新されたかチェック
	*
	*/
	public function check_update( $time, $diff ) {

		$now_hour = date('G', strtotime("-9 hour"));

		$aryTime = explode(':',$time);	// hour抽出
		$hour = $aryTime[0];
		if( false !== strpos( $time, 'PM' ) ){	// PMの場合は$hour+12とする
			$hour += 12;
		}

		if( $hour > $now_hour - $diff ){
			return true;
		} else {
			return false;
		}
	}



	/**
	* ２時間前分のみ（簡単に昨日今日だけスクレイピングするので廃棄）
	*
	*/
	public function __before2hour( $time, $diff ) {
/*
		if( empty( $val->find( $sc_lastupdate_tag, 0 )->plaintext ) ){ break; }
				$lastdate = $val->find( $sc_lastupdate_tag, 0 )->plaintext;	// 更新日周辺をくくってるhtmlタグ

				// 更新日チェック（該当時間以外は全てbreak		時差9時間あるくせぇｗ
				if ( strtotime(date("Y-m-d 2:00:00", strtotime("-9 hour"))) >= strtotime("-9 hour") ) {	// Yesterdayもチェック
					if( false != preg_match( '?(Today|Yesterday).i', $lastdate ) ){	// 現在時刻が 0;00～2;00 の場合、TodayとYesterdayをチェック
						if( false != preg_match( '/.?Yesterday./i', $lastdate ) ){
							$aryTime = explode(':',$time);
							if( $aryTime[0] < 22 ){		// 前日22時以前はbreak
								$update_break_flag = true;
								break;
							}
						}
					} else {
						$update_break_flag = true;
						break;
					}

				} else {	// Todayのみチェック
//print_r( 'today' . "\n");
					if( false != preg_match( '/.?Today./i', $lastdate ) ) {	// 現在時刻が
						// 直近2時間以内かチェック
						$time = $val->find( $sc_lastupdate_tag, 0 )->find( $sc_time_tag, 0 )->plaintext;
						if( !$this->check_update($time, 2) ){	// 2時間以内の更新されていない場合はbreak（テストのため、暫定8とする）
							$update_break_flag = true;
							break;
						}
					} else {
						$update_break_flag = true;
						break;
					}
				}
print_r( ' $time: ' . $time . "\n");
*/
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