<?php
require_once "tracer.php";

class ScHentaiOtaku extends Tracer {

	public $name = 'ScHentaiOtaku';
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
	public function ispSrch( $site ) {

		App::import('Model','Crlmodels');

		$site_uid = $site['IllegalSite']['site_uid'];
		$aryErr = array();		// スクレイピングエラーなどの情報を格納するリターン用配列

		// 契約が生きている検索ワード一覧を取得
		$Word = ClassRegistry::init('Word');
		$aryWd = $Word->find('all', array('conditions'=>array('deleted'=>0)));
print_r('Word->find() ' . memory_get_usage() . "\n");

//pr($site);
//pr($crlSite);


		foreach( $aryWd as $wd ){
print_r( '$aryWd loop ' . memory_get_usage() . "\n");

			$url = str_replace( '[[kw]]', urlencode( mb_convert_encoding($wd['Word']['search_word'], $site['IllegalSite']['encoding'], 'UTF-8' )), $site['IllegalSite']['search_url'] );
//pr($url);


//			$crlSite = ClassRegistry::init('Crlsite');
			$crlSite = new Crlmodels;


			if( false === $html = &$crlSite->get_html( $url, $site, 'div.entry' ) ){	// スクレイピング
				$err = array( 'url' => $url, 'status' => 'HTML difference' );	// スクレイピングエラーの場合
				$aryErr[] = $err;
				continue;
			}
			$obj = $html->find('div.entry');


			$cnt = 0;
			foreach( $obj as $val ){		// 1投稿を括るタグを指定
print_r( '$obj loop START ' . memory_get_usage() . "\n");

				$href = $val->find('h3',0)->find('a',0)->href;

print_r('1-A: '.memory_get_usage() . "\n");
				$val->clear();
				unset($val);
print_r('1-B: '.memory_get_usage() . "\n");


print_r( ' url : ' . $href . "\n" );
				if( preg_match( "/http:\/\/hentai-otaku.com\/.*/", $href ) ){

//					$detail_url = preg_replace( '/#more.*/', '', $href );
					$detail_url = $href;
					if( false === $d_html = &$crlSite->get_html( $detail_url, $site, 'div.itemtext' ) ){	// スクレイピング
						$err = array( 'url' => $url, 'status' => 'HTML difference' );	// スクレイピングエラーの場合
						$aryErr[] = $err;
						continue;
					}
					$content = $d_html->find('div.itemtext', 0);
					$crlSite->check_content( $detail_url, $content, $site_uid );

				}
				$cnt++;

//print_r('2-A: '.memory_get_usage() . "\n");
				$d_html->clear();
				unset($d_html);
//print_r('2-B: '.memory_get_usage() . "\n");

//print_r( '$obj loop END ' . memory_get_usage() . "\n");
			}

			// 1件も投稿記事が確認できない場合
			if( $cnt == 0 ){
				$err = array( 'url' => $url, 'status' => 'NONE POST' );
				$aryErr[] = $err;
			}

			unset($crlSite);

			$html->clear();
			unset($html);

			//sleep(1);
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

		$site_uid = $site['IllegalSite']['site_uid'];
		$aryErr = array();		// スクレイピングエラーなどの情報を格納するリターン用配列

		for( $p=1; $p<=2; $p++ ){	// クロールするページ（TOPから３ページ分）

			if( $p == 1 ){			// クロールするURLを選定
				$url = $site['IllegalSite']['site_url'];
			} else {
				$url = str_replace( '[[page]]', $p, $site['IllegalSite']['next_page_url'] );
			}


			$crlSite = new Crlmodels;

			if( false === $html = &$crlSite->get_html( $url, $site, 'div.post' ) ){	// スクレイピング
				$err = array( 'url' => $url, 'status' => 'HTML difference' );	// スクレイピングエラーの場合
				$aryErr[] = $err;
				continue;
			}
			$obj = $html->find('div.post');

			$flg = false;
			$cnt = 0;

			foreach( $obj as $val ){		// 1投稿を括るタグを指定
				$href = $val->find('h2',0)->find('a',0)->href;

				if( preg_match( "/http:\/\/dlhentai.com\/.*/", $href ) ){
					$detail_url = preg_replace( '/#more.*/', '', $href );

					if( false === $d_html = &$crlSite->get_html( $detail_url, $site, 'div.post-single' ) ){	// スクレイピング
						$err = array( 'url' => $url, 'status' => 'HTML difference' );	// スクレイピングエラーの場合
						$aryErr[] = $err;
						continue;
					}
					$content = $d_html->find('div.post-single', 0);
					$crlSite->check_content( $detail_url, $content, $site_uid );
				}
				$cnt++;

				$d_html->clear();
				unset($d_html);
//break;
			}

			// 1件も投稿記事が確認できない場合
			if( $cnt == 0 ){
				$err = array( 'url' => $url, 'status' => 'NONE POST' );
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