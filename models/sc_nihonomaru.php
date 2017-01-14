<?php
require_once "tracer.php";

class ScNihonomaru extends Tracer {

	public $name = 'ScNihonomaru';
	public $useTable = false;

	// Anime-sharing専用スクレイピングリスト
	public $urlList = array(

		'Doujin Soft'		=>		'http://forum.nihonomaru.com/doujin-soft.263/',
		'Hentai Anime'		=>		'http://forum.nihonomaru.com/hentai-anime.284/',
		'Eroge'				=>		'http://forum.nihonomaru.com/eroge.285/',
		'Anime Series'		=>		'http://forum.nihonomaru.com/anime-series.247/',
		'Manga List'		=>		'http://forum.nihonomaru.com/manga-list.175/',
//		'Comedy Manga'		=>		'http://forum.nihonomaru.com/comedy-manga.324/',
//		'Fantasy & Drama Manga'		=>		'http://forum.nihonomaru.com/fantasy-and-drama-manga.50/',
//		'Martial Arts Manga'		=>		'http://forum.nihonomaru.com/martial-arts-manga.318/',
//		'Supernatural Manga'		=>		'http://forum.nihonomaru.com/supernatural-manga.313/',
//		'Shounen Manga'		=>		'http://forum.nihonomaru.com/shounen-manga.49/',
//		'Shoujo Manga'		=>		'http://forum.nihonomaru.com/shoujo-manga.47/',
		'Yaoi & Yuri Manga'	=>		'http://forum.nihonomaru.com/yaoi-and-yuri-manga.48/',
		'Artbooks'			=>		'http://forum.nihonomaru.com/artbooks.40/',
		'Seinen Manga'		=>		'http://forum.nihonomaru.com/seinen-manga.279/',
		'Hentai CG'		=>		'http://forum.nihonomaru.com/hentai-cg.280/',
		'Doujinshi'		=>		'http://forum.nihonomaru.com/doujinshi.281/',
		'Hentai Manga'		=>		'http://forum.nihonomaru.com/hentai-manga.282/',

	);





	public function __construct() {

	}



	/**
	* 新着監視
	*
	*/
	public function ispCrw( $site ) {

		error_reporting(E_ALL ^ E_NOTICE);
		print_r('CRL_START: ' . $site['IllegalSite']['site_name'] ."\n");


		// findで使用するhtmlタグ
//		$sc_post_tag = 'div.nonsticky';
//		$sc_lastupdate_tag = 'dl.threadlastpost';
//		$sc_time_tag = 'em.time';
//		$sc_link_tag = 'h3.threadtitle';
//		$sc_contents_tag = 'li.postcontainer';
		$sc_contents_tag = 'div.message';
//		$sc_detail_post = '';


		App::import('Model','Crlmodels');
		$IllegalSite = ClassRegistry::init('IllegalSite');

		$site_uid = $site['IllegalSite']['site_uid'];
		$aryErr = array();		// スクレイピングエラーなどの情報を格納するリターン用配列


		// リストのURLをスクレイピング
		$before_url = '';
		foreach( $this->urlList as $url ){

print_r( '$url: ' . $url . "\n");
			$crlSite = new Crlmodels;

			$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

			$html = $crlSite->get_html( $url, $site, 'table[id=threadslist]', false );	// スクレイピング
			if( !is_object($html) ){	// エラーが存在する場合
				if( isset($html['MonomiError']) ){	// エラーが存在する場合
					if( 'notUpdate' !== $html['MonomiError'] ){
						$err = array( 'url' => $url, 'status' => $html['MonomiError'], 'mode'=>'normal' );	// スクレイピングエラーの場合
						$aryErr[] = $err;
					}
					continue;
				}
			}

//			$obj = $html->find( 'tbody', 1 )->find('tr');
			$obj = $html->find('tr');
//pr($html->plaintext);

			$flg = false;
			$cnt = 0;
			$update_break_flag = false;
			$flag_topics = false;
			foreach( $obj as $key => $val ){		// 1投稿を括るタグを指定

				// pinned Topicsの次のTRから読み込む
				if( $flag_topics == false && 'Forum Topics' !== $val->find('td', 0)->plaintext ){
					continue;
				} else if( $flag_topics == false && 'Forum Topics' === $val->find('td', 0)->plaintext ) {
					$flag_topics = true;
					continue;
				}

				if( is_object( $val->find('td', 2) )){
					if( !empty($val->find('td', 2)->find('a',0)->href) ){
						$left_url = $url . 'thread/';
						$detail_url = $val->find('td', 2)->find('a',0)->href;
						if( false !== strpos( $detail_url, $left_url ) ){
//							print_r( $val->find('td', 2)->find('a',0)->href . "\n" );
//							print_r( $val->find('td', 3)->plaintext . "\n" );

							// add 2012.09.24 IllegalSiteは結構アップロードをこまめにしてるので、都度再読み込みしよかー
							$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));
							$lastdate	= $val->find('td', 3)->plaintext;
							$detail_url	= $val->find('td', 2)->find('a',0)->href;

							$d_html = $crlSite->get_htmlc( $detail_url, $site, $sc_contents_tag, $lastdate );		// キャッシュ対応版
							if( !is_object($d_html) ){	// エラーが存在する場合
								if( isset($d_html['MonomiError']) ){	// エラーが存在する場合
									if( 'notUpdate' !== $d_html['MonomiError'] ){
										$err = array( 'url' => $detail_url, 'status' => $d_html['MonomiError'], 'mode'=>'normal' );	// スクレイピングエラーの場合
										$aryErr[] = $err;
									}
									continue;
								}
							}
//pr($d_html->find('div.message',0)->plaintext);

							$contents = $d_html->find($sc_contents_tag);
//							if( !is_object($contents) ){
//								continue;
//							}

							foreach( $contents as $content ){
								$crlSite->check_content_uni( $detail_url, $content, $site_uid, $site['IllegalSite']['site_url'], true );
							}


//							$contents->clear();
//							unset($contents);
							$d_html->clear();
							unset($d_html);
						}
					}
				}

				$cnt++;
			}

			// 1件も投稿記事が確認できない場合
			if( $cnt == 0 && $update_break_flag == false ){
				$err = array( 'url' => $url, 'status' => 'NONE POST', 'mode'=>'normal' );
				$aryErr[] = $err;
			}

			unset($crlSite);
			if( is_object($obj) ){
				$obj->clear();
				unset($obj);
			}
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