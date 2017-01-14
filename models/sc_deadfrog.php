<?php
require_once "tracer.php";

class ScDeadfrog extends Tracer {

	public $name = 'ScDeadfrog';
	public $useTable = false;

	// erojiji専用スクレイピングリスト
	public $urlList = array(
//		'Series'				=> 'http://deadfrog.fapis.com/category.html?id=43',
//		'Ecchi/Moe'				=> 'http://deadfrog.fapis.com/category.html?id=31',
//		'Video'					=> 'http://deadfrog.fapis.com/category.html?id=24',
//		'Manga'					=> 'http://deadfrog.fapis.com/category.html?id=25',
//		'Audio'					=> 'http://deadfrog.fapis.com/category.html?id=26',
//		'Other Crap'			=> 'http://deadfrog.fapis.com/category.html?id=27',
//		'Games'					=> 'http://deadfrog.fapis.com/category.html?id=30',
//		'zOMG its not Anime!!1'	=> 'http://deadfrog.fapis.com/category.html?id=29',
//		'H-Manga'				=> 'http://deadfrog.fapis.com/category.html?id=34',
//		'H-Video'				=> 'http://deadfrog.fapis.com/category.html?id=33',
//		'H-Games'				=> 'http://deadfrog.fapis.com/category.html?id=32',
//		'HCG/Images'			=> 'http://deadfrog.fapis.com/category.html?id=35',
//		'H-Audio'				=> 'http://deadfrog.fapis.com/category.html?id=44',
//		'Lolicon'				=> 'http://deadfrog.fapis.com/category.html?id=22',
//		'Shota'					=> 'http://deadfrog.fapis.com/category.html?id=23',
//		'Yaoi'					=> 'http://deadfrog.fapis.com/category.html?id=20',
//		'Yuri'					=> 'http://deadfrog.fapis.com/category.html?id=21',
//		'Porn'					=> 'http://deadfrog.fapis.com/category.html?id=28',
//		'Not porn'				=> 'http://deadfrog.fapis.com/category.html?id=41',
		''	=> '',
		''	=> '',
		''	=> '',
		''	=> '',
	);




	public function __construct() {

	}



	/**
	* 新着監視
	*
	*/
	public function ispCrw( $site ) {

//		error_reporting(E_ALL ^ E_NOTICE);

		// findで使用するhtmlタグ
		$base_url		= 'http://deadfrog.fapis.com/';
		$sc_url			= 'http://deadfrog.fapis.com/search.html?query=&page=[[page]]&sort=';

		$sc_post_tag = 'tr.entryrow';
		$sc_contents_tag = 'table[id=entrytable]';
//		$sc_content_body_tag = 'div.has_after_content';


		App::import('Model','Crlmodels');
		$IllegalSite = ClassRegistry::init('IllegalSite');
		$Cache = ClassRegistry::init('Cache');

		$site_uid = $site['IllegalSite']['site_uid'];
		$aryErr = array();		// スクレイピングエラーなどの情報を格納するリターン用配列


		for( $lcnt=1; $lcnt<=3; $lcnt++ ){


			$url = str_replace( '[[page]]', $lcnt, $sc_url );
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
					continue;
				}


				$link = $val->find('td', 0)->find('a', 1);
				$detail_url	= $base_url . $link->href;
print_r( ' $detail_url: ' . $detail_url . "\n");

				$strUpdate	= $val->find('td', 2)->plaintext;
//print_r($strUpdate."\n");

				$val->clear();
				unset($val);


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

				$head			= $d_html->find( $sc_contents_tag, 0 )->find('th.title', 0)->outertext;
				$description	= $d_html->find( $sc_contents_tag, 0 )->find('tr', 1)->find('td', 0)->outertext;

				$content = str_get_html( $head . $description );
				$crlSite->check_content( $detail_url, $content, $site_uid, $site['IllegalSite']['site_url'] );

				$content->clear();
				unset($content);
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




}

?>