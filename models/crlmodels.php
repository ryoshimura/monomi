<?php
require_once "tracer.php";

class Crlmodels extends Tracer {

	public $name = 'Crlmodels';
	public $useTable = false;

//	public $data = array( 'Crawler'=>array() );

	public function __construct() {
/*
		$id = array("id" => false,
					"table" => "site_image_datas",
				   );
		parent::__construct($id);
		$this->primaryKey = "uuid";

		$data['Crawler']['site_uid'] = String::uuid();
*/
	}





	/**
	* コンテンツ内のワードチェック（各サイト共通メソッド）
	*
	* @param
	* @return none
	*/
	public function check_content( $detail_url, $content, $site_uid, $base_url, $uploader_flag = false ) {

		$IllegalResult = ClassRegistry::init('IllegalResult');		// 違法サイト クロール結果
		$DownloadResult = ClassRegistry::init('DownloadResult');		// ダウンロードサイト クロール結果
		$ResultRelation = ClassRegistry::init('ResultRelation');		// リレーション用

		// 違法DLサイト一覧を取得
		$DownloadSite = ClassRegistry::init('DownloadSite');
		$aryDl = $DownloadSite->find('all', array('conditions'=>array('deleted'=>0)));

		// 契約が生きている検索ワード一覧を取得
		$Word = ClassRegistry::init('Word');
//		$aryWd = $Word->find('all', array('conditions'=>array('deleted'=>0)));
//		$aryWd = $Word->find('all', array('conditions'=>array('deleted'=>0, 'search_word <>'=>'')));
		$aryWd = $Word->get_crl_words();	// 全てのワードを抽出
//pr('!!check!!');
//pr($aryWd);



		// add 2012.09.21 同ドメインの<a>～</a>を除去し、正確にマッチできるテキストを生成
		// 自URLは<a>タグだけトリミング
/**
		$body_text = $content->outertext;
		$after_detail_url = str_replace( $base_url, '', $detail_url );
		$pattern  = '/(href=.*?';
		$pattern .= preg_quote( $after_detail_url, '/' );
		$pattern .= ')/is';
		$body_text = preg_replace($pattern, "", $body_text );


		// 自ドメインURLは<a>～</a>を空白で置換
		$pattern  = '/href=[\"\']';
		$pattern .= preg_quote( $base_url, '/' );
		$pattern .= '.*?>(.*?)<\/a>/is';
		$body_text = preg_replace($pattern, "", $body_text );

		// href=""でhttp://で始まらないものも空白で置換
		$body_text = preg_replace('/(href=[\"\']\/.*?<\/a>)/is', '', $body_text );

		// 全てのHTMLタグを除去
		$body_text = preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/','',$body_text);

		// 2012.10.17 正規表現パターンがエラーの場合に表示
		if($body_text == null){
			print_r('preg_error' . "\n");
			return false;
		}
**/
		$body_text = $content->outertext;

		foreach( $content->find('a') as $ma ){
			$href = $ma->href;
			// 自ドメインの場合
			if( false !== strpos( $href, $base_url ) ){
				// 自詳細ページでない場合はマッチングの邪魔なので削除
				if( false === strpos( $href, $detail_url ) ){
					$body_text = str_replace( $ma->outertext, '', $body_text );
				}
			} else if( preg_match( '/^\/.*?/', $href ) ){	//  スラッシュ（/）スタートのURLのaタグもマッチングの邪魔なので削除
				$body_text = str_replace( $ma->outertext, '', $body_text );
			}
		}

		// 全てのHTMLタグを除去
		$body_text = preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/','',$body_text);


//print_r("\n" . 'BODYTEXT: ' . $body_text . "\n\n");




		foreach( $aryWd as $wd ){
//			if( false !== mb_strpos( $content->plaintext, $wd['Word']['search_word'] ) ){	// キーワードがヒットしたらIllegalResultにレコード格納
			// 2012.09.13 小文字大文字両方Hitするよう全て小文字化する
//			if( false !== mb_strpos( strtolower($content->plaintext), strtolower($wd['Word']['search_word']) ) ){	// キーワードがヒットしたらIllegalResultにレコード格納
			if( false !== mb_strpos( strtolower($body_text), strtolower($wd['Word']['search_word']) ) ){	// キーワードがヒットしたらIllegalResultにレコード格納
//pr($pattern);
//pr($body_text);
				$save = array();
				if( false !== $ilgResult = $IllegalResult->find('first', array('conditions'=>array( 'deleted'=>0, 'site_uid'=>$site_uid, 'word_uid'=>$wd['Word']['word_uid'], 'illegal_url'=>$detail_url ))) ){	// 重複レコードチェック
					$save = $ilgResult;
					$save['IllegalResult']['updated'] = date('Y-m-d H:i:s');
				} else {
					$IllegalResult->create();
					$save['IllegalResult']['site_uid'] = $site_uid;
					$save['IllegalResult']['word_uid'] = $wd['Word']['word_uid'];
					$save['IllegalResult']['illegal_url'] = $detail_url;
					$save['IllegalResult']['regist_date'] = date('Y-m-d');
				}
				$IllegalResult->save( $save, false );


				$aryIllegalResult = $IllegalResult->find('first',array('conditions'=>array('site_uid'=>$site_uid, 'word_uid'=>$wd['Word']['word_uid'], 'illegal_url'=>$detail_url)));	// リレーション用

				// add 2012.09.15 レアケースだが、$aryIllegalResult == false の場合は、強制continueとする
				if( $aryIllegalResult == false ){
					pr( 'ERROR_DETAIL_URL: ' . $detail_url );
					continue;
				}


				// 2012.10.10 anime-sharing等のフォーラム系で１投稿内にパーテーションタグの判別がつかない複数作品を取り扱ったpostに対応。アップローダの検出を意図的に行なわない
				if( $uploader_flag == true ){
					continue;
				}

				// 違法アップロード先のURLをチェック
				$cntUplorder = 0;
				foreach( $content->find('a') as $du ){
					foreach( $aryDl as $dls ){
						if( false !== strpos( $du->href, $dls['DownloadSite']['download_url'] ) ){

							$cntUplorder++;

							$save = array();
							if( false !== $dlResult = $DownloadResult->find('first', array('conditions'=>array( 'deleted'=>0, 'download_site_uid'=>$dls['DownloadSite']['download_site_uid'], 'word_uid'=>$wd['Word']['word_uid'], 'download_result_url'=>$du->href ))) ){	// 重複レコードチェック
								$save = $dlResult;
								$save['DownloadResult']['updated'] = date('Y-m-d H:i:s');
							} else {
								$DownloadResult->create();
								$save['DownloadResult']['download_site_uid'] = $dls['DownloadSite']['download_site_uid'];
								$save['DownloadResult']['word_uid'] = $wd['Word']['word_uid'];
								$save['DownloadResult']['download_result_url'] = $du->href;
							}
							$DownloadResult->save( $save, false );


							// 2012.08.22 add リレーション用
							$aryDownloadResult = $DownloadResult->find('first',array('conditions'=>array('download_site_uid'=>$dls['DownloadSite']['download_site_uid'], 'word_uid'=>$wd['Word']['word_uid'], 'download_result_url'=>$du->href)));	// リレーション用
							if( false === $ResultRelation->find('first', array('conditions'=>array('download_result_uid'=>$aryDownloadResult['DownloadResult']['download_result_uid'], 'illegal_result_uid'=>$aryIllegalResult['IllegalResult']['illegal_result_uid'] ))) ){
								$save_relation = array(
									'download_result_uid'=>$aryDownloadResult['DownloadResult']['download_result_uid'],
									'illegal_result_uid'=>$aryIllegalResult['IllegalResult']['illegal_result_uid']
								);
								$ResultRelation->create();
								$ResultRelation->save( $save_relation, false );
							}
						}

					}
				}


				// 上記でアップローダが特定できない場合、<a>タグでくくられていない違法アップロードURLをチェックし登録
				if( $cntUplorder == 0 ){

					$this->updateUrl( $content->outertext, $aryDl, $wd, $aryIllegalResult );	// 正しく抽出できないので保留

				}
			}
		}

		return true;
	}







	/**
	* コンテンツ内のワードチェック（unicode変換版。主に欧州・ロシア語対策
	*
	* @param
	* @return none
	*/
	public function check_content_uni( $detail_url, $content, $site_uid, $base_url, $uploader_flag = false ) {

		$IllegalResult = ClassRegistry::init('IllegalResult');		// 違法サイト クロール結果
		$DownloadResult = ClassRegistry::init('DownloadResult');		// ダウンロードサイト クロール結果
		$ResultRelation = ClassRegistry::init('ResultRelation');		// リレーション用

		// 違法DLサイト一覧を取得
		$DownloadSite = ClassRegistry::init('DownloadSite');
		$aryDl = $DownloadSite->find('all', array('conditions'=>array('deleted'=>0)));

		// 契約が生きている検索ワード一覧を取得
		$Word = ClassRegistry::init('Word');
		$aryWd = $Word->get_crl_words();	// 全てのワードを抽出

		$body_text = $content->outertext;
//pr($body_text);
		foreach( $content->find('a') as $ma ){
			$href = $ma->href;
			// 自ドメインの場合
			if( false !== strpos( $href, $base_url ) ){
				// 自詳細ページでない場合はマッチングの邪魔なので削除
				if( false === strpos( $href, $detail_url ) ){
					$body_text = str_replace( $ma->outertext, '', $body_text );
				}
			} else if( preg_match( '/^\/.*?/', $href ) ){	//  スラッシュ（/）スタートのURLのaタグもマッチングの邪魔なので削除
				$body_text = str_replace( $ma->outertext, '', $body_text );
			}
		}

		// 全てのHTMLタグを除去
		$body_text = preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/','',$body_text);
		// unicode変換
//		$body_text = htmlentities($body_text,ENT_NOQUOTES);
		$body_text	= strtolower( str_replace(array("\r\n","\r","\n","\t"), '', $body_text) );
		$body_text = str_replace('&amp;#','&#',$body_text);
		$r = html_entity_decode($body_text, ENT_NOQUOTES, 'UTF-8');
//print_r('check_content_uni: ' . $detail_url . "\n");
		$s = $this->utf8_to_unicode_code($r);
		$body_text = $this->unicode_code_to_utf8($s);
//pr($body_text);
/*
$r = html_entity_decode($body_text, ENT_NOQUOTES, 'UTF-8');
$s = $this->utf8_to_unicode_code($r);
$body_text = $this->unicode_code_to_utf8($s);
*/
//pr($body_text);
/*		// unicode変換
		$r = html_entity_decode($body_text, ENT_NOQUOTES, 'UTF-8');
		$s = $this->utf8_to_unicode_code($r);
		$body_text = $this->unicode_code_to_utf8($s);
		// 全てのHTMLタグを除去
		$body_text = preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/','',$body_text);
//		$body_text = htmlentities($body_text,ENT_NOQUOTES);
		$body_text	= strtolower( str_replace(array("\r\n","\r","\n","\t"), '', $body_text) );
*/


		foreach( $aryWd as $wd ){
			// 2012.09.13 小文字大文字両方Hitするよう全て小文字化する
			if( false !== mb_strpos( strtolower($body_text), strtolower($wd['Word']['search_word']) ) ){	// キーワードがヒットしたらIllegalResultにレコード格納
//print_r('IN!');

				$save = array();
				if( false !== $ilgResult = $IllegalResult->find('first', array('conditions'=>array( 'deleted'=>0, 'site_uid'=>$site_uid, 'word_uid'=>$wd['Word']['word_uid'], 'illegal_url'=>$detail_url ))) ){	// 重複レコードチェック
					$save = $ilgResult;
					$save['IllegalResult']['updated'] = date('Y-m-d H:i:s');
				} else {
					$IllegalResult->create();
					$save['IllegalResult']['site_uid'] = $site_uid;
					$save['IllegalResult']['word_uid'] = $wd['Word']['word_uid'];
					$save['IllegalResult']['illegal_url'] = $detail_url;
					$save['IllegalResult']['regist_date'] = date('Y-m-d');
				}
				$IllegalResult->save( $save, false );


				$aryIllegalResult = $IllegalResult->find('first',array('conditions'=>array('site_uid'=>$site_uid, 'word_uid'=>$wd['Word']['word_uid'], 'illegal_url'=>$detail_url)));	// リレーション用

				// add 2012.09.15 レアケースだが、$aryIllegalResult == false の場合は、強制continueとする
				if( $aryIllegalResult == false ){
					pr( 'ERROR_DETAIL_URL: ' . $detail_url );
					continue;
				}


				// 2012.10.10 anime-sharing等のフォーラム系で１投稿内にパーテーションタグの判別がつかない複数作品を取り扱ったpostに対応。アップローダの検出を意図的に行なわない
				if( $uploader_flag == true ){
					continue;
				}

				// 違法アップロード先のURLをチェック
				foreach( $content->find('a') as $du ){
					foreach( $aryDl as $dls ){
						if( false !== strpos( $du->href, $dls['DownloadSite']['download_url'] ) ){
//pr( $du->href );
							$save = array();
							if( false !== $dlResult = $DownloadResult->find('first', array('conditions'=>array( 'deleted'=>0, 'download_site_uid'=>$dls['DownloadSite']['download_site_uid'], 'word_uid'=>$wd['Word']['word_uid'], 'download_result_url'=>$du->href ))) ){	// 重複レコードチェック
								$save = $dlResult;
								$save['DownloadResult']['updated'] = date('Y-m-d H:i:s');
							} else {
								$DownloadResult->create();
								$save['DownloadResult']['download_site_uid'] = $dls['DownloadSite']['download_site_uid'];
								$save['DownloadResult']['word_uid'] = $wd['Word']['word_uid'];
								$save['DownloadResult']['download_result_url'] = $du->href;
							}
							$DownloadResult->save( $save, false );

							// 2012.08.22 add リレーション用
							$aryDownloadResult = $DownloadResult->find('first',array('conditions'=>array('download_site_uid'=>$dls['DownloadSite']['download_site_uid'], 'word_uid'=>$wd['Word']['word_uid'], 'download_result_url'=>$du->href)));	// リレーション用
							if( false === $ResultRelation->find('first', array('conditions'=>array('download_result_uid'=>$aryDownloadResult['DownloadResult']['download_result_uid'], 'illegal_result_uid'=>$aryIllegalResult['IllegalResult']['illegal_result_uid'] ))) ){
//pr('relations!!');
								$save_relation = array(
									'download_result_uid'=>$aryDownloadResult['DownloadResult']['download_result_uid'],
									'illegal_result_uid'=>$aryIllegalResult['IllegalResult']['illegal_result_uid']
								);
								$ResultRelation->create();
								$ResultRelation->save( $save_relation, false );
							}

						}
					}
				}

			}
		}

		return true;
	}




	/**
	* コンテンツ内のワードチェック（plaintext） テキスト判定のみのため、アップローダの検出は行なわない
	*
	* @param
	* @return none
	*/
	public function check_content_plaintext( $detail_url, $body_text, $site_uid, $base_url ) {

		$IllegalResult = ClassRegistry::init('IllegalResult');		// 違法サイト クロール結果
		$DownloadResult = ClassRegistry::init('DownloadResult');		// ダウンロードサイト クロール結果
		$ResultRelation = ClassRegistry::init('ResultRelation');		// リレーション用

		// 違法DLサイト一覧を取得
		$DownloadSite = ClassRegistry::init('DownloadSite');
		$aryDl = $DownloadSite->find('all', array('conditions'=>array('deleted'=>0)));

		// 契約が生きている検索ワード一覧を取得
		$Word = ClassRegistry::init('Word');
		$aryWd = $Word->get_crl_words();	// 全てのワードを抽出


		foreach( $aryWd as $wd ){
			if( false !== mb_strpos( strtolower($body_text), strtolower($wd['Word']['search_word']) ) ){	// キーワードがヒットしたらIllegalResultにレコード格納

				$save = array();
				if( false !== $ilgResult = $IllegalResult->find('first', array('conditions'=>array( 'deleted'=>0, 'site_uid'=>$site_uid, 'word_uid'=>$wd['Word']['word_uid'], 'illegal_url'=>$detail_url ))) ){	// 重複レコードチェック
					$save = $ilgResult;
					$save['IllegalResult']['updated'] = date('Y-m-d H:i:s');
				} else {
					$IllegalResult->create();
					$save['IllegalResult']['site_uid'] = $site_uid;
					$save['IllegalResult']['word_uid'] = $wd['Word']['word_uid'];
					$save['IllegalResult']['illegal_url'] = $detail_url;
					$save['IllegalResult']['regist_date'] = date('Y-m-d');
				}
				$IllegalResult->save( $save, false );

/*
				$aryIllegalResult = $IllegalResult->find('first',array('conditions'=>array('site_uid'=>$site_uid, 'word_uid'=>$wd['Word']['word_uid'], 'illegal_url'=>$detail_url)));	// リレーション用

				// add 2012.09.15 レアケースだが、$aryIllegalResult == false の場合は、強制continueとする
				if( $aryIllegalResult == false ){
					pr( 'ERROR_DETAIL_URL: ' . $detail_url );
					continue;
				}


				// 2012.10.10 anime-sharing等のフォーラム系で１投稿内にパーテーションタグの判別がつかない複数作品を取り扱ったpostに対応。アップローダの検出を意図的に行なわない
				if( $uploader_flag == true ){
					continue;
				}
*/
/*
				// 違法アップロード先のURLをチェック
				foreach( $content->find('a') as $du ){
//pr( 'Atag: ' . $du->href );

					foreach( $aryDl as $dls ){
						if( false !== strpos( $du->href, $dls['DownloadSite']['download_url'] ) ){
//pr( $du->href );
							$save = array();
							if( false !== $dlResult = $DownloadResult->find('first', array('conditions'=>array( 'deleted'=>0, 'download_site_uid'=>$dls['DownloadSite']['download_site_uid'], 'word_uid'=>$wd['Word']['word_uid'], 'download_result_url'=>$du->href ))) ){	// 重複レコードチェック
								$save = $dlResult;
								$save['DownloadResult']['updated'] = date('Y-m-d H:i:s');
							} else {
								$DownloadResult->create();
								$save['DownloadResult']['download_site_uid'] = $dls['DownloadSite']['download_site_uid'];
								$save['DownloadResult']['word_uid'] = $wd['Word']['word_uid'];
								$save['DownloadResult']['download_result_url'] = $du->href;
							}
							$DownloadResult->save( $save, false );


							// 2012.08.22 add リレーション用
							$aryDownloadResult = $DownloadResult->find('first',array('conditions'=>array('download_site_uid'=>$dls['DownloadSite']['download_site_uid'], 'word_uid'=>$wd['Word']['word_uid'], 'download_result_url'=>$du->href)));	// リレーション用
							if( false === $ResultRelation->find('first', array('conditions'=>array('download_result_uid'=>$aryDownloadResult['DownloadResult']['download_result_uid'], 'illegal_result_uid'=>$aryIllegalResult['IllegalResult']['illegal_result_uid'] ))) ){
//pr('relations!!');
								$save_relation = array(
									'download_result_uid'=>$aryDownloadResult['DownloadResult']['download_result_uid'],
									'illegal_result_uid'=>$aryIllegalResult['IllegalResult']['illegal_result_uid']
								);
								$ResultRelation->create();
								$ResultRelation->save( $save_relation, false );
							}

						}
					}
				}
*/
			}
		}

		return true;
	}











	/**
	* コンテンツ内のワードチェック（<a>なしアップローダの検出版）
	*
	* @param
	* @return none
	*/
	public function check_content_atag( $detail_url, $content, $site_uid, $base_url, $uploader_flag = false ) {

//print_r("IN!\n");

		$IllegalResult = ClassRegistry::init('IllegalResult');		// 違法サイト クロール結果
		$DownloadResult = ClassRegistry::init('DownloadResult');		// ダウンロードサイト クロール結果
		$ResultRelation = ClassRegistry::init('ResultRelation');		// リレーション用

		// 違法DLサイト一覧を取得
		$DownloadSite = ClassRegistry::init('DownloadSite');
		$aryDl = $DownloadSite->find('all', array('conditions'=>array('deleted'=>0)));

		// 契約が生きている検索ワード一覧を取得
		$Word = ClassRegistry::init('Word');
//		$aryWd = $Word->find('all', array('conditions'=>array('deleted'=>0)));
//		$aryWd = $Word->find('all', array('conditions'=>array('deleted'=>0, 'search_word <>'=>'')));
		$aryWd = $Word->get_crl_words();	// 全てのワードを抽出



		$body_text = $content->outertext;

		foreach( $content->find('a') as $ma ){
			$href = $ma->href;
			// 自ドメインの場合
			if( false !== strpos( $href, $base_url ) ){
				// 自詳細ページでない場合はマッチングの邪魔なので削除
				if( false === strpos( $href, $detail_url ) ){
					$body_text = str_replace( $ma->outertext, '', $body_text );
				}
			} else if( preg_match( '/^\/.*?/', $href ) ){	//  スラッシュ（/）スタートのURLのaタグもマッチングの邪魔なので削除
				$body_text = str_replace( $ma->outertext, '', $body_text );
			}
		}

		// 全てのHTMLタグを除去
		$body_text = preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/','',$body_text);


//print_r("\n" . 'BODYTEXT: ' . $body_text . "\n\n");


		foreach( $aryWd as $wd ){
//			if( false !== mb_strpos( $content->plaintext, $wd['Word']['search_word'] ) ){	// キーワードがヒットしたらIllegalResultにレコード格納
			// 2012.09.13 小文字大文字両方Hitするよう全て小文字化する
//			if( false !== mb_strpos( strtolower($content->plaintext), strtolower($wd['Word']['search_word']) ) ){	// キーワードがヒットしたらIllegalResultにレコード格納
			if( false !== mb_strpos( strtolower($body_text), strtolower($wd['Word']['search_word']) ) ){	// キーワードがヒットしたらIllegalResultにレコード格納
//pr($pattern);
//pr($body_text);
				$save = array();
				if( false !== $ilgResult = $IllegalResult->find('first', array('conditions'=>array( 'deleted'=>0, 'site_uid'=>$site_uid, 'word_uid'=>$wd['Word']['word_uid'], 'illegal_url'=>$detail_url ))) ){	// 重複レコードチェック
					$save = $ilgResult;
					$save['IllegalResult']['updated'] = date('Y-m-d H:i:s');
				} else {
					$IllegalResult->create();
					$save['IllegalResult']['site_uid'] = $site_uid;
					$save['IllegalResult']['word_uid'] = $wd['Word']['word_uid'];
					$save['IllegalResult']['illegal_url'] = $detail_url;
					$save['IllegalResult']['regist_date'] = date('Y-m-d');
				}
				$IllegalResult->save( $save, false );


				$aryIllegalResult = $IllegalResult->find('first',array('conditions'=>array('site_uid'=>$site_uid, 'word_uid'=>$wd['Word']['word_uid'], 'illegal_url'=>$detail_url)));	// リレーション用

				// add 2012.09.15 レアケースだが、$aryIllegalResult == false の場合は、強制continueとする
				if( $aryIllegalResult == false ){
					pr( 'ERROR_DETAIL_URL: ' . $detail_url );
					continue;
				}


				// 2012.10.10 anime-sharing等のフォーラム系で１投稿内にパーテーションタグの判別がつかない複数作品を取り扱ったpostに対応。アップローダの検出を意図的に行なわない
				if( $uploader_flag == true ){
					continue;
				}

				// 違法アップロード先のURLをチェック
				$cntUplorder = 0;
				foreach( $content->find('a') as $du ){
					foreach( $aryDl as $dls ){
						if( false !== strpos( $du->href, $dls['DownloadSite']['download_url'] ) ){

							$cntUplorder++;

							$save = array();
							if( false !== $dlResult = $DownloadResult->find('first', array('conditions'=>array( 'deleted'=>0, 'download_site_uid'=>$dls['DownloadSite']['download_site_uid'], 'word_uid'=>$wd['Word']['word_uid'], 'download_result_url'=>$du->href ))) ){	// 重複レコードチェック
								$save = $dlResult;
								$save['DownloadResult']['updated'] = date('Y-m-d H:i:s');
							} else {
								$DownloadResult->create();
								$save['DownloadResult']['download_site_uid'] = $dls['DownloadSite']['download_site_uid'];
								$save['DownloadResult']['word_uid'] = $wd['Word']['word_uid'];
								$save['DownloadResult']['download_result_url'] = $du->href;
							}
							$DownloadResult->save( $save, false );


							// 2012.08.22 add リレーション用
							$aryDownloadResult = $DownloadResult->find('first',array('conditions'=>array('download_site_uid'=>$dls['DownloadSite']['download_site_uid'], 'word_uid'=>$wd['Word']['word_uid'], 'download_result_url'=>$du->href)));	// リレーション用
							if( false === $ResultRelation->find('first', array('conditions'=>array('download_result_uid'=>$aryDownloadResult['DownloadResult']['download_result_uid'], 'illegal_result_uid'=>$aryIllegalResult['IllegalResult']['illegal_result_uid'] ))) ){
								$save_relation = array(
									'download_result_uid'=>$aryDownloadResult['DownloadResult']['download_result_uid'],
									'illegal_result_uid'=>$aryIllegalResult['IllegalResult']['illegal_result_uid']
								);
								$ResultRelation->create();
								$ResultRelation->save( $save_relation, false );
							}
						}

					}
				}

//print_r( '$cntUplorder : ' . $cntUplorder . "\n");

				// 上記でアップローダが特定できない場合、<a>タグでくくられていない違法アップロードURLをチェックし登録
				if( $cntUplorder == 0 ){

					$this->updateUrl( $content->outertext, $aryDl, $wd, $aryIllegalResult );	// 正しく抽出できないので保留

				}
			}
		}

		return true;
	}








	/**
	* アップローダ検出＋DB登録（コンテンツ内のワードチェックなし
	*
	* @param
	* @return none
	*/
	public function check_content_noMatching( $detail_url, $content, $site_uid, $base_url, $wd, $uploader_flag = false ) {

//print_r("IN!\n");

		$IllegalResult = ClassRegistry::init('IllegalResult');		// 違法サイト クロール結果
		$DownloadResult = ClassRegistry::init('DownloadResult');		// ダウンロードサイト クロール結果
		$ResultRelation = ClassRegistry::init('ResultRelation');		// リレーション用

		// 違法DLサイト一覧を取得
		$DownloadSite = ClassRegistry::init('DownloadSite');
		$aryDl = $DownloadSite->find('all', array('conditions'=>array('deleted'=>0)));

		$body_text = $content->outertext;
		foreach( $content->find('a') as $ma ){
			$href = $ma->href;
			// 自ドメインの場合
			if( false !== strpos( $href, $base_url ) ){
				// 自詳細ページでない場合はマッチングの邪魔なので削除
				if( false === strpos( $href, $detail_url ) ){
					$body_text = str_replace( $ma->outertext, '', $body_text );
				}
			} else if( preg_match( '/^\/.*?/', $href ) ){	//  スラッシュ（/）スタートのURLのaタグもマッチングの邪魔なので削除
				$body_text = str_replace( $ma->outertext, '', $body_text );
			}
		}

		// 全てのHTMLタグを除去
		$body_text = preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/','',$body_text);



		$save = array();
		if( false !== $ilgResult = $IllegalResult->find('first', array('conditions'=>array( 'deleted'=>0, 'site_uid'=>$site_uid, 'word_uid'=>$wd['Word']['word_uid'], 'illegal_url'=>$detail_url ))) ){	// 重複レコードチェック
			$save = $ilgResult;
			$save['IllegalResult']['updated'] = date('Y-m-d H:i:s');
		} else {
			$IllegalResult->create();
			$save['IllegalResult']['site_uid'] = $site_uid;
			$save['IllegalResult']['word_uid'] = $wd['Word']['word_uid'];
			$save['IllegalResult']['illegal_url'] = $detail_url;
			$save['IllegalResult']['regist_date'] = date('Y-m-d');
		}
		$IllegalResult->save( $save, false );


		$aryIllegalResult = $IllegalResult->find('first',array('conditions'=>array('site_uid'=>$site_uid, 'word_uid'=>$wd['Word']['word_uid'], 'illegal_url'=>$detail_url)));	// リレーション用

		// add 2012.09.15 レアケースだが、$aryIllegalResult == false の場合は、強制continueとする
		if( $aryIllegalResult == false ){
			pr( 'ERROR_DETAIL_URL: ' . $detail_url );
			continue;
		}

		// 2012.10.10 anime-sharing等のフォーラム系で１投稿内にパーテーションタグの判別がつかない複数作品を取り扱ったpostに対応。アップローダの検出を意図的に行なわない
		if( $uploader_flag == true ){
			continue;
		}

		// 違法アップロード先のURLをチェック
		$cntUplorder = 0;
		foreach( $content->find('a') as $du ){
			foreach( $aryDl as $dls ){
				if( false !== strpos( $du->href, $dls['DownloadSite']['download_url'] ) ){

					$cntUplorder++;
					$save = array();
					if( false !== $dlResult = $DownloadResult->find('first', array('conditions'=>array( 'deleted'=>0, 'download_site_uid'=>$dls['DownloadSite']['download_site_uid'], 'word_uid'=>$wd['Word']['word_uid'], 'download_result_url'=>$du->href ))) ){	// 重複レコードチェック
						$save = $dlResult;
						$save['DownloadResult']['updated'] = date('Y-m-d H:i:s');
					} else {
						$DownloadResult->create();
						$save['DownloadResult']['download_site_uid'] = $dls['DownloadSite']['download_site_uid'];
						$save['DownloadResult']['word_uid'] = $wd['Word']['word_uid'];
						$save['DownloadResult']['download_result_url'] = $du->href;
					}
					$DownloadResult->save( $save, false );

					// 2012.08.22 add リレーション用
					$aryDownloadResult = $DownloadResult->find('first',array('conditions'=>array('download_site_uid'=>$dls['DownloadSite']['download_site_uid'], 'word_uid'=>$wd['Word']['word_uid'], 'download_result_url'=>$du->href)));	// リレーション用
					if( false === $ResultRelation->find('first', array('conditions'=>array('download_result_uid'=>$aryDownloadResult['DownloadResult']['download_result_uid'], 'illegal_result_uid'=>$aryIllegalResult['IllegalResult']['illegal_result_uid'] ))) ){
						$save_relation = array(
							'download_result_uid'=>$aryDownloadResult['DownloadResult']['download_result_uid'],
							'illegal_result_uid'=>$aryIllegalResult['IllegalResult']['illegal_result_uid']
						);
						$ResultRelation->create();
						$ResultRelation->save( $save_relation, false );
					}
				}
			}
		}

//print_r( '$cntUplorder : ' . $cntUplorder . "\n");

		// 上記でアップローダが特定できない場合、<a>タグでくくられていない違法アップロードURLをチェックし登録
		if( $cntUplorder == 0 ){
			$this->updateUrl( $content->outertext, $aryDl, $wd, $aryIllegalResult );	// 正しく抽出できないので保留
		}


		return true;
	}






	/**
	* コンテンツ内のワードチェック <a>タグなし、検索エリアとアップローダーエリアの分離
	*
	* @param
	* @return none
	*/
	public function check_content_2st( $detail_url, $content, $matching_text, $site_uid, $base_url, $uploader_flag = false ) {

//print_r("IN!\n");

		$IllegalResult = ClassRegistry::init('IllegalResult');		// 違法サイト クロール結果
		$DownloadResult = ClassRegistry::init('DownloadResult');		// ダウンロードサイト クロール結果
		$ResultRelation = ClassRegistry::init('ResultRelation');		// リレーション用

		// 違法DLサイト一覧を取得
		$DownloadSite = ClassRegistry::init('DownloadSite');
		$aryDl = $DownloadSite->find('all', array('conditions'=>array('deleted'=>0)));

		// 契約が生きている検索ワード一覧を取得
		$Word = ClassRegistry::init('Word');
//		$aryWd = $Word->find('all', array('conditions'=>array('deleted'=>0)));
//		$aryWd = $Word->find('all', array('conditions'=>array('deleted'=>0, 'search_word <>'=>'')));
		$aryWd = $Word->get_crl_words();	// 全てのワードを抽出


		// マッチング対象テキストを生成
		if( $matching_text !== '' ){
			$body_text = $matching_text;
		} else {
			$body_text = $content->outertext;
			foreach( $content->find('a') as $ma ){
				$href = $ma->href;
				// 自ドメインの場合
				if( false !== strpos( $href, $base_url ) ){
					// 自詳細ページでない場合はマッチングの邪魔なので削除
					if( false === strpos( $href, $detail_url ) ){
						$body_text = str_replace( $ma->outertext, '', $body_text );
					}
				} else if( preg_match( '/^\/.*?/', $href ) ){	//  スラッシュ（/）スタートのURLのaタグもマッチングの邪魔なので削除
					$body_text = str_replace( $ma->outertext, '', $body_text );
				}
			}

			// 全てのHTMLタグを除去
			$body_text = preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/','',$body_text);
		}


		foreach( $aryWd as $wd ){
			if( false !== mb_strpos( strtolower($body_text), strtolower($wd['Word']['search_word']) ) ){	// キーワードがヒットしたらIllegalResultにレコード格納
//pr($pattern);
//pr($body_text);
				$save = array();
				if( false !== $ilgResult = $IllegalResult->find('first', array('conditions'=>array( 'deleted'=>0, 'site_uid'=>$site_uid, 'word_uid'=>$wd['Word']['word_uid'], 'illegal_url'=>$detail_url ))) ){	// 重複レコードチェック
					$save = $ilgResult;
					$save['IllegalResult']['updated'] = date('Y-m-d H:i:s');
				} else {
					$IllegalResult->create();
					$save['IllegalResult']['site_uid'] = $site_uid;
					$save['IllegalResult']['word_uid'] = $wd['Word']['word_uid'];
					$save['IllegalResult']['illegal_url'] = $detail_url;
					$save['IllegalResult']['regist_date'] = date('Y-m-d');
				}
				$IllegalResult->save( $save, false );


				$aryIllegalResult = $IllegalResult->find('first',array('conditions'=>array('site_uid'=>$site_uid, 'word_uid'=>$wd['Word']['word_uid'], 'illegal_url'=>$detail_url)));	// リレーション用

				// add 2012.09.15 レアケースだが、$aryIllegalResult == false の場合は、強制continueとする
				if( $aryIllegalResult == false ){
					pr( 'ERROR_DETAIL_URL: ' . $detail_url );
					continue;
				}


				// 2012.10.10 anime-sharing等のフォーラム系で１投稿内にパーテーションタグの判別がつかない複数作品を取り扱ったpostに対応。アップローダの検出を意図的に行なわない
				if( $uploader_flag == true ){
					continue;
				}

				// 違法アップロード先のURLをチェック
				$cntUplorder = 0;
				foreach( $content->find('a') as $du ){
					foreach( $aryDl as $dls ){
						if( false !== strpos( $du->href, $dls['DownloadSite']['download_url'] ) ){

							$cntUplorder++;

							$save = array();
							if( false !== $dlResult = $DownloadResult->find('first', array('conditions'=>array( 'deleted'=>0, 'download_site_uid'=>$dls['DownloadSite']['download_site_uid'], 'word_uid'=>$wd['Word']['word_uid'], 'download_result_url'=>$du->href ))) ){	// 重複レコードチェック
								$save = $dlResult;
								$save['DownloadResult']['updated'] = date('Y-m-d H:i:s');
							} else {
								$DownloadResult->create();
								$save['DownloadResult']['download_site_uid'] = $dls['DownloadSite']['download_site_uid'];
								$save['DownloadResult']['word_uid'] = $wd['Word']['word_uid'];
								$save['DownloadResult']['download_result_url'] = $du->href;
							}
							$DownloadResult->save( $save, false );


							// 2012.08.22 add リレーション用
							$aryDownloadResult = $DownloadResult->find('first',array('conditions'=>array('download_site_uid'=>$dls['DownloadSite']['download_site_uid'], 'word_uid'=>$wd['Word']['word_uid'], 'download_result_url'=>$du->href)));	// リレーション用
							if( false === $ResultRelation->find('first', array('conditions'=>array('download_result_uid'=>$aryDownloadResult['DownloadResult']['download_result_uid'], 'illegal_result_uid'=>$aryIllegalResult['IllegalResult']['illegal_result_uid'] ))) ){
								$save_relation = array(
									'download_result_uid'=>$aryDownloadResult['DownloadResult']['download_result_uid'],
									'illegal_result_uid'=>$aryIllegalResult['IllegalResult']['illegal_result_uid']
								);
								$ResultRelation->create();
								$ResultRelation->save( $save_relation, false );
							}
						}

					}
				}

//print_r( '$cntUplorder : ' . $cntUplorder . "\n");

				// 上記でアップローダが特定できない場合、<a>タグでくくられていない違法アップロードURLをチェックし登録
				if( $cntUplorder == 0 ){

					$this->updateUrl( $content->outertext, $aryDl, $wd, $aryIllegalResult );	// 正しく抽出できないので保留

				}
			}
		}

		return true;
	}










	/**
	* update_content
	*
	* check_contentのresultアップデートのみ版
	*
	* @param
	* @return none
	*/
	public function update_content( $detail_url, $word_uid, $site_uid, $sc_contents_tag, $uploader_flag = false ) {

print_r( ' -' . $detail_url . "\n");

		$IllegalResult = ClassRegistry::init('IllegalResult');		// 違法サイト クロール結果
		$DownloadResult = ClassRegistry::init('DownloadResult');		// ダウンロードサイト クロール結果
		$ResultRelation = ClassRegistry::init('ResultRelation');		// リレーション用

		// 違法DLサイト一覧を取得
		$DownloadSite = ClassRegistry::init('DownloadSite');
		$aryDl = $DownloadSite->find('all', array('conditions'=>array('deleted'=>0)));

		// 契約が生きている検索ワード一覧を取得
		$Word = ClassRegistry::init('Word');
		$aryWd = $Word->get_crl_words();	// 全てのワードを抽出


		$save = array();
		if( false !== $ilgResult = $IllegalResult->find('first', array('conditions'=>array( 'deleted'=>0, 'site_uid'=>$site_uid, 'word_uid'=>$word_uid, 'illegal_url'=>$detail_url ))) ){	// 重複レコードチェック
			$save = $ilgResult;
			$save['IllegalResult']['updated'] = date('Y-m-d H:i:s');
		} else {
			$IllegalResult->create();
			$save['IllegalResult']['site_uid'] = $site_uid;
			$save['IllegalResult']['word_uid'] = $word_uid;
			$save['IllegalResult']['illegal_url'] = $detail_url;
			$save['IllegalResult']['regist_date'] = date('Y-m-d');
		}
		$IllegalResult->save( $save, false );

		$aryIllegalResult = $IllegalResult->find('first',array('conditions'=>array('site_uid'=>$site_uid, 'word_uid'=>$word_uid, 'illegal_url'=>$detail_url)));	// リレーション用

		// add 2012.09.15 レアケースだが、$aryIllegalResult == false の場合は、強制continueとする
		if( $aryIllegalResult == false ){
			pr( 'ERROR_DETAIL_URL: ' . $detail_url );
			return false;
		}


		// アップローダチェックを行なわない場合は $uploader_flag = true とし、ここでcontinue
		if( $uploader_flag == true ){
			return true;
		}


		// detailのHTMLオブジェクトを取得
		$html = &$this->get_html( $detail_url, $site, $sc_contents_tag, false, false );
		if( !is_object($html) ){
			$html->clear();
			unset($html);
			return false;
		}



		// 違法アップロード先のURLをチェック
		foreach( $html->find('a') as $du ){
			foreach( $aryDl as $dls ){
				if( false !== strpos( $du->href, $dls['DownloadSite']['download_url'] ) ){
					$save = array();
					if( false !== $dlResult = $DownloadResult->find('first', array('conditions'=>array( 'deleted'=>0, 'download_site_uid'=>$dls['DownloadSite']['download_site_uid'], 'word_uid'=>$word_uid, 'download_result_url'=>$du->href ))) ){	// 重複レコードチェック
						$save = $dlResult;
						$save['DownloadResult']['updated'] = date('Y-m-d H:i:s');
					} else {
						$DownloadResult->create();
						$save['DownloadResult']['download_site_uid'] = $dls['DownloadSite']['download_site_uid'];
						$save['DownloadResult']['word_uid'] = $word_uid;
						$save['DownloadResult']['download_result_url'] = $du->href;
					}
					$DownloadResult->save( $save, false );


					// 2012.08.22 add リレーション用
					$aryDownloadResult = $DownloadResult->find('first',array('conditions'=>array('download_site_uid'=>$dls['DownloadSite']['download_site_uid'], 'word_uid'=>$word_uid, 'download_result_url'=>$du->href)));	// リレーション用
					if( false === $ResultRelation->find('first', array('conditions'=>array('download_result_uid'=>$aryDownloadResult['DownloadResult']['download_result_uid'], 'illegal_result_uid'=>$aryIllegalResult['IllegalResult']['illegal_result_uid'] ))) ){
//pr('relations!!');
						$save_relation = array(
							'download_result_uid'=>$aryDownloadResult['DownloadResult']['download_result_uid'],
							'illegal_result_uid'=>$aryIllegalResult['IllegalResult']['illegal_result_uid']
						);
						$ResultRelation->create();
						$ResultRelation->save( $save_relation, false );
					}
				}
			}
		}

		$html->clear();
		unset($html);

		return true;
	}






	/**
	* アップローダurlを抽出
	*
	* @param
	* @return none
	*/
	public function updateUrl( $text, $aryDl, $wd, $aryIllegalResult ) {

//print_r('IN!' . "\n");

		$DownloadResult = ClassRegistry::init('DownloadResult');		// ダウンロードサイト クロール結果
		$ResultRelation = ClassRegistry::init('ResultRelation');		// リレーション用

		$data = array();
		$i = 0;
		foreach( $aryDl as $dls ){


			// add 2013.01.09 http://で始まらないdownload_urlの場合、ここで挿入
			if( FALSE === strpos( $dls['DownloadSite']['download_url'], 'http://' ) ){
				$pattern  = '/http:\/\/.*?';
			} else {
				$pattern  = '/';
			}


			$pattern .= preg_quote( $dls['DownloadSite']['download_url'], '/' );
			$pattern .= '.*?( |\n|\r|\r\n|<|\")/i';

//pr($pattern);
			preg_match_all( $pattern, $text, $aryBuf, PREG_SET_ORDER );

			foreach( $aryBuf as $val ){
				$val[0] = str_replace(array('<', "\"", "\r\n","\r","\n"), '', $val[0]);	// 改行トリミング
				$val[0] = str_replace('<','',$val[0]);		// タグ < 削除
				$val[0] = trim($val[0]);					// スペース削除
				$data[$i]['url'] = $val[0];
				$data[$i]['dls_uid'] = $dls['DownloadSite']['download_site_uid'];
				$i++;
			}
		}


		// DBへ登録
		foreach( $data as $val ){

			$save = array();
			if( false == $dlResult = $DownloadResult->find('first', array('conditions'=>array( 'deleted'=>0, 'download_site_uid'=>$val['dls_uid'], 'word_uid'=>$wd['Word']['word_uid'], 'download_result_url'=>$val['url'] ))) ){	// 重複レコードチェック
				$DownloadResult->create();
				$save['DownloadResult']['download_site_uid'] = $val['dls_uid'];
				$save['DownloadResult']['word_uid'] = $wd['Word']['word_uid'];
				$save['DownloadResult']['download_result_url'] = $val['url'];
				$DownloadResult->save( $save, false );

				// リレーション用
				$aryDownloadResult = $DownloadResult->find('first',array('conditions'=>array('download_site_uid'=>$val['dls_uid'], 'word_uid'=>$wd['Word']['word_uid'], 'download_result_url'=>$val['url'])));	// リレーション用
				if( false === $ResultRelation->find('first', array('conditions'=>array('download_result_uid'=>$aryDownloadResult['DownloadResult']['download_result_uid'], 'illegal_result_uid'=>$aryIllegalResult['IllegalResult']['illegal_result_uid'] ))) ){
					$save_relation = array(
						'download_result_uid'=>$aryDownloadResult['DownloadResult']['download_result_uid'],
						'illegal_result_uid'=>$aryIllegalResult['IllegalResult']['illegal_result_uid']
					);
					$ResultRelation->create();
					$ResultRelation->save( $save_relation, false );

				}
			}


		}


	}






	/**
	* エラーログ処理
	*
	* @param
	* @return none
	*/
	public function updateErr( $aryErr, $site ) {

//pr('updateErr Start');

		$IllegalSite = ClassRegistry::init('IllegalSite');
		$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));
		$ErrorList = ClassRegistry::init('ErrorList');

		if( empty($aryErr) ){	// エラーログが存在しない場合
			$site['IllegalSite']['error_status'] = 0;
			$site['IllegalSite']['error_date'] = null;
			$IllegalSite->save( $site, false );

		} else {	// エラーログが存在する場合
//			if( $site['IllegalSite']['error_status'] === '0' ){
				$site['IllegalSite']['error_status'] = 1;
				$site['IllegalSite']['error_date'] = date('Y-m-d H:i:s');
				$IllegalSite->save( $site, false );
//pr($aryErr);
				foreach( $aryErr as $val ){
					$save = array();
					$save['ErrorList']['site_uid']		= $site['IllegalSite']['site_uid'];
					$save['ErrorList']['url']			= $val['url'];
					$save['ErrorList']['error_status']	= $val['status'];
					$ErrorList->create();
					$ErrorList->save( $save, false );
				}
//			}

		}
	}




	/**
	* プロキシ経由でHTMLを取得
	*
	* @param
	* @return htmlデータオブジェクト（エラーの場合は配列）
	*/
	public function get_html( $url, $site, $tag, $update_flag = false, $search_flag = false, $search_word = false ) {	// $search_wordはPost検索時のみ使用
//print_r(' tag0: '.$tag . "\n");
		$IllegalSite = ClassRegistry::init('IllegalSite');
		$site = $IllegalSite->find('first',array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

		// スクレイピングモード分岐
		if( $site['IllegalSite']['scraping_mode'] === 'curl' || $site['IllegalSite']['scraping_mode'] == null ) {

			$res = $this->scr_curl( $url, $site, $tag, $update_flag, $search_flag, $search_word );
			$site = $IllegalSite->find('first',array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

			if( !is_object($res) ){	// エラーが存在する場合、モードを切り替える

				if( $search_flag !== false && $res['MonomiError'] === 'notFound' ){	// 検索モードでエラーがnotFoundの場合は、スイッチしない
					$site['IllegalSite']['scraping_mode'] = 'curl';
				} else {
//print_r('switch!'."\n");
					$res = $this->scr_fgc( $url, $site, $tag, $update_flag, $search_flag, $search_word );
					$site = $IllegalSite->find('first',array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));
					$site['IllegalSite']['scraping_mode'] = 'fgc';
				}

			} else {
				$site['IllegalSite']['scraping_mode'] = 'curl';
			}

			$IllegalSite->save( $site, false );


		} else if ( $site['IllegalSite']['scraping_mode'] === 'fgc' ) {

			$res = $this->scr_fgc( $url, $site, $tag, $update_flag, $search_flag, $search_word );
			$site = $IllegalSite->find('first',array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

			if( !is_object($res) ){	// エラーが存在する場合、モードを切り替える

				if( $search_flag !== false && $res['MonomiError'] === 'notFound' ){	// 検索モードでエラーがnotFoundの場合は、スイッチしない
					$site['IllegalSite']['scraping_mode'] = 'fgc';
				} else {
//print_r('switch!'."\n");
					$res = $this->scr_curl( $url, $site, $tag, $update_flag, $search_flag, $search_word );
					$site = $IllegalSite->find('first',array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));
					$site['IllegalSite']['scraping_mode'] = 'curl';
				}

			} else {
				$site['IllegalSite']['scraping_mode'] = 'fgc';
			}

			$IllegalSite->save( $site, false );

		}

		return $res;
	}









	/**
	* プロキシ経由でHTMLを取得（キャッシュ対応版）
	*
	* @param
	* @return htmlデータオブジェクト（エラーの場合は配列）
	*/
	public function get_htmlc( $url, $site, $tag, $cacheStrUpdate = false, $search_flag = false, $search_word = false ) {	// $search_wordはPost検索時のみ使用

//print_r( ' get_htmlc: ' . $url . "\n");

		// キャッシュチェック
		$HtmlCache = ClassRegistry::init('HtmlCache');
		$sorce = $HtmlCache->loadCache( $url, $cacheStrUpdate );
		if( $sorce != false ){		// キャッシュを利用しreturn
			$res = str_get_html( $sorce, "UTF-8", $site['IllegalSite']['encoding'] );
			return $res;
		}


		$IllegalSite = ClassRegistry::init('IllegalSite');
		$site = $IllegalSite->find('first',array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

		// スクレイピングモード分岐
		if( $site['IllegalSite']['scraping_mode'] === 'curl' || $site['IllegalSite']['scraping_mode'] == null ) {

			$res = $this->scr_curl( $url, $site, $tag, false, $search_flag, $search_word );
			$site = $IllegalSite->find('first',array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

			if( !is_object($res) ){	// エラーが存在する場合、モードを切り替える

				if( $search_flag !== false && $res['MonomiError'] === 'notFound' ){	// 検索モードでエラーがnotFoundの場合は、スイッチしない
					$site['IllegalSite']['scraping_mode'] = 'curl';
				} else {
//print_r('switch!'."\n");
					$res = $this->scr_fgc( $url, $site, $tag, false, $search_flag, $search_word );
					$site = $IllegalSite->find('first',array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));
					$site['IllegalSite']['scraping_mode'] = 'fgc';
				}

			} else {
				$site['IllegalSite']['scraping_mode'] = 'curl';
			}

			$IllegalSite->save( $site, false );


		} else if ( $site['IllegalSite']['scraping_mode'] === 'fgc' ) {

			$res = $this->scr_fgc( $url, $site, $tag, false, $search_flag, $search_word );
			$site = $IllegalSite->find('first',array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));

			if( !is_object($res) ){	// エラーが存在する場合、モードを切り替える

				if( $search_flag !== false && $res['MonomiError'] === 'notFound' ){	// 検索モードでエラーがnotFoundの場合は、スイッチしない
					$site['IllegalSite']['scraping_mode'] = 'fgc';
				} else {
//print_r('switch!'."\n");
					$res = $this->scr_curl( $url, $site, $tag, false, $search_flag, $search_word );
					$site = $IllegalSite->find('first',array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));
					$site['IllegalSite']['scraping_mode'] = 'curl';
				}

			} else {
				$site['IllegalSite']['scraping_mode'] = 'fgc';
			}

			$IllegalSite->save( $site, false );

		}


		// スクレイピングに成功してたらキャッシュとして保存
		if( is_object($res) ){	// エラーが存在する場合
			$HtmlCache->writeCache( $res->outertext, $url, $cacheStrUpdate, $site['IllegalSite']['site_uid'] );
			sleep(1);
		}


		return $res;
	}








	/**
	* curlモードスクレイピング
	*
	* @param
	* @return none
	*/
	private function scr_curl( $url, $site, $tag, $update_flag, $search_flag, $search_word ) {	// $search_wordはPost検索時のみ使用

		$aryError = array();
		$IllegalSite = ClassRegistry::init('IllegalSite');

		// 最新の優良プロキシを取得を5つ取得
		$ProxyList = ClassRegistry::init('ProxyList');		// プロキシリスト
		$prox = $ProxyList->find('all', array('conditions'=>array('deleted'=>0), 'limit'=>5, 'order'=>array('error_count ASC')));

		// スクレイピング開始
		$initial_flag = true;
		for( $i=0; $i<5; $i++ ){	// 成功するまで5回繰り返す

			if( $i == 1 && $initial_flag == true ){
				$i = 0;
				$initial_flag = false;
			}

			$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));	// add 2012.09.14
			if( $i == 0 && $initial_flag == true && $site['IllegalSite']['proxy_ip'] != null && $site['IllegalSite']['proxy_ip'] !== '' ){	// 最初は前回のプロキシを優先
				$proxy_ip = $site['IllegalSite']['proxy_ip'];
			} else {
				$proxy_ip = $prox[$i]['ProxyList']['ip'];
			}
			$aryProxy = explode( ":", $proxy_ip );
			$port = $aryProxy[1];

//print_r( 'mode_curl: ' . $url . ' count: ' . $i . ' proxy: ' . $proxy_ip . "\n");

			// curl準備
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
			curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);
			curl_setopt($ch, CURLOPT_PROXYPORT, $port);
			if( $site['IllegalSite']['user_agent'] != null && user_agent !== '' ){
				curl_setopt($ch, CURLOPT_USERAGENT, $site['IllegalSite']['user_agent'] );
			} else {
				curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
			}
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);

clearstatcache();
			$time_start = microtime(true);	// 測定開始

			if( $search_word != false  ){		// POSTの場合
				$post_prm = str_replace( '[[kw]]', $search_word, $site['IllegalSite']['post_param'] );
				curl_setopt ($ch,CURLOPT_POST,true);
				curl_setopt ($ch,CURLOPT_POSTFIELDS,$post_prm);
			}
			$sorce = curl_exec($ch);

//print_r($sorce."\n");
//print_r("*************************************************\n");

			$time_end = microtime(true);	// 測定終了
			$time = $time_end - $time_start;	// 所要時間算出

			// エラーチェック第１弾
			// curl_execで値が正常に取得できなかった場合
			if( false === $sorce || '' === $sorce || empty($sorce) ){
				$save = $prox[$i];
				$save['ProxyList']['error_count'] += 1;
				$ProxyList->save( $save, false );

				$curlExecError = curl_error($ch);
				if( !empty($curlExecError) && '' !== $curlExecError ){	// curl_errorでエラーの詳細理由が判明した場合
					$aryError['MonomiError'] = $curlExecError;
				} else {
					$aryError['MonomiError'] = 'curl_exec false';
				}
				curl_close($ch);

				sleep(5);
				continue;
			}

			// エラーチェック第２弾
			// curlで検出されたエラーの場合
			$respons = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if(preg_match("/^(404|403|500)$/",$respons)){
				$save = $prox[$i];
				$save['ProxyList']['error_count'] += 1;
				$ProxyList->save( $save, false );
				$aryError['MonomiError'] = 'ERROR 404,403,500';
				curl_close($ch);

				sleep(3);
				continue;
			}

			// エラーチェック第３弾
			// 遅いプロキシの評価を下げる
			if( $time > 20 ){	// 10秒以上かかっている場合は、プロキシのスコアを+1（マイナス評価）する
				$save = $prox[$i];
				$save['ProxyList']['error_count'] += 1;
				$ProxyList->save( $save, false );
				curl_close($ch);
				continue;
			}

			// エラーチェック第４弾
			// タグ相違
			curl_close($ch);
			$html = str_get_html( $sorce, "UTF-8", $site['IllegalSite']['encoding'] );

			// 2012.10.19 $tag=null タグ判定しない場合を追加
			if( $tag == null ){
				$obj = $html;
			} else {
				$obj = $html->find( $tag );		// 正しくアクセスできているか確認
			}

//			$obj = $html->find( $tag );		// 正しくアクセスできているか確認
			if( empty( $obj ) ){	// HTML相違の場合はエラーとしてcontinueする

				$html->clear();
				unset($html);

				if( $search_flag == true ){		// 検索スクレイピングの場合、検索結果なしだとこのErrになるため、notFound とし、強制終了
					$aryError['MonomiError'] = 'notFound';	//
					return $aryError;
				} else {
					$aryError['MonomiError'] = 'htmlDifference';
				}

			} else {
				// add 2012.09.10 成功したプロキシを次回も使うため、レコードにプロキシIDを保存
				$site['IllegalSite']['proxy_ip'] = $proxy_ip;
				$IllegalSite->save( $site, false );

				// 成功したプロキシのスコアを上げる（-1）
				if( $prox[$i]['ProxyList']['error_count'] > 0 ){
					$save = $prox[$i];
					$save['ProxyList']['error_count'] -= 1;
//pr('proxy_score: ' . $proxy_ip . '  ' . $prox[$i]['ProxyList']['error_count'] . ' >>> ' . $save['ProxyList']['error_count'] );
					$ProxyList->save( $save, false );
				}

				return $html;
			}

//			sleep(2);
		}

		return $aryError;

	}



	/**
	* fgcモードスクレイピング
	*
	* @param
	* @return none
	*/
	public function scr_fgc( $url, $site, $tag, $update_flag, $search_flag, $search_word ) {	// $search_wordはPost検索時のみ使用
//print_r(' tag1: '.$tag . "\n");
		$aryError = array();
		$IllegalSite = ClassRegistry::init('IllegalSite');		// プロキシリスト

		// 最新の優良プロキシを取得を３つ取得
		$ProxyList = ClassRegistry::init('ProxyList');		// プロキシリスト
		$prox = $ProxyList->find('all', array('conditions'=>array('deleted'=>0), 'limit'=>5, 'order'=>array('error_count ASC')));


		$initial_flag = true;
		for( $i=0; $i<5; $i++ ){	// バリデーションタグが設定されている場合、成功するまで5回繰り返す


			if( $i == 1 && $initial_flag == true ){
				$i = 0;
				$initial_flag = false;
			}

			$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));	// add 2012.09.14
			if( $i == 0 && $initial_flag == true && $site['IllegalSite']['proxy_ip'] != null && $site['IllegalSite']['proxy_ip'] !== '' ){	// 最初は前回のプロキシを優先
				$proxy_ip = $site['IllegalSite']['proxy_ip'];
			} else {
				$proxy_ip = $prox[$i]['ProxyList']['ip'];
			}


//print_r( 'mode_fgc: ' . $url . ' count: ' . $i . ' proxy: ' . $proxy_ip . "\n");


			// add 2012.09.18 検索Post型サイトに対応
			if( $search_word != false  ){		// POSTの場合

				// Postパラメータ生成
				$data = array();
				$prm = explode( "&", $site['IllegalSite']['post_param'] );
				foreach( $prm as $val ){
					$pm = explode( "=", $val );
					if( '[[kw]]' === $pm[1] ){	// キーワード置換用パラメータの場合
						$data[$pm[0]] = $search_word;
					} else {
						$data[$pm[0]] = $pm[1];
					}
				}
				$data = http_build_query($data, "", "&");

				$headers = array(
					"Content-Type: application/x-www-form-urlencoded",
//					'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; ja; rv:1.9.1.7) Gecko/20091221 Firefox/3.5.7',
					"Content-Length: " . strlen($data),
					'Accept-Encoding:identity'
				);

				$proxy = array(
					'http' => array(
						"proxy" => $proxy_ip,
						"request_fulluri" => true,
						"timeout" => '10',
						'method' => 'POST',
						'content' => $data,
						'header' => implode("\r\n", $headers),
					)
				);

			} else {	// POST以外

				if( $site['IllegalSite']['user_agent'] != null && $site['IllegalSite']['user_agent'] !== '' ){
					$headers = array(
						"Content-type:text/html;charset=UTF-8",
						$site['IllegalSite']['user_agent'],
						'Accept-Encoding:identity'
					);
				} else {
					$headers = array(
						"Content-type:text/html;charset=UTF-8",
						'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; ja; rv:1.9.1.7) Gecko/20091221 Firefox/3.5.7',
						'Accept-Encoding:identity'
					);
				}


				$proxy = array(
					"http" => array(
						"proxy" => $proxy_ip,
						"request_fulluri" => true,
						"timeout" => 10,
						'header' => implode("\r\n", $headers),
					)
				);

			}


//			$http_response_header = array();
			$time_start = microtime(true);

			$sc = stream_context_create($proxy);
clearstatcache();
			$sorce = @file_get_contents ( $url, false, $sc );

			$time_end = microtime(true);
			$time = $time_end - $time_start;

			// 遅いプロキシの評価を下げる
			if( $time > 15 ){	// 10秒以上かかっている場合は、プロキシのスコアを+1（マイナス評価）する
				$save = $prox[$i];
				$save['ProxyList']['error_count'] += 1;
				$ProxyList->save( $save, false );
				continue;
			}

			// add 2012.09.21 gzip系に備えて
			if( isset($http_response_header) ){
				foreach( $http_response_header as $rhd ){
					if( $rhd === 'Content-Encoding: gzip' ){	// gzipで圧縮されている場合
						$sorce = $this->get_html_curl( $url, $proxy_ip );
						break;
					}
				}
			}


			if( !empty($sorce) && $sorce != false ){

				$html = str_get_html( $sorce, "UTF-8", $site['IllegalSite']['encoding'] );
//print_r(' tag: '.$tag . "\n");

				// 2012.10.19 $tag=null タグ判定しない場合を追加
				if( $tag == null ){
					$obj = $html;
				} else {
					$obj = $html->find( $tag );		// 正しくアクセスできているか確認
				}
//				$obj = $html->find( $tag );		// 正しくアクセスできているか確認

//pr($html);

				if( empty( $obj ) ){	// HTML相違の場合はエラーとしてcontinueする
//pr('empty_object!!');
					$html->clear();
					unset($html);

					if( $search_flag == true ){		// 検索スクレイピングの場合、検索結果なしだとこのErrになるため、notFound とし、強制終了
//pr('notFound!!');
						$aryError['MonomiError'] = 'notFound';	//
						return $aryError;

					} else {
//print_r(' tag: '.$tag . "\n");
						$aryError['MonomiError'] = 'htmlDifference';
					}

				} else {
					// add 2012.09.10 成功したプロキシを次回も使うため、レコードにプロキシIDを保存
					$site['IllegalSite']['proxy_ip'] = $proxy_ip;
					$IllegalSite->save( $site, false );

					// 成功したプロキシのスコアを上げる（-1）
					if( $prox[$i]['ProxyList']['error_count'] > 0 ){
						$save = $prox[$i];
						$save['ProxyList']['error_count'] -= 1;
						$ProxyList->save( $save, false );
					}

					// 2012.10.20 HTMLソースをキャッシュ


					return $html;
				}

			} else {
				if( isset($http_response_header) ){

//pr($http_response_header);

					// エラー判別
					foreach( $http_response_header as $err ){
						if( 'HTTP/1.0 502 Bad Gateway' === $err ){	// Bad Gatewayの場合、プロキシを変更することで改善の可能性あり
							$save = $prox[$i];
							$save['ProxyList']['error_count'] += 1;
							$ProxyList->save( $save, false );
							$aryError['MonomiError'] = 'BadGateway';
						}else if( 'HTTP/1.1 400 Bad Request' === $err ){	// Bad Requestの場合、プロキシを変更することで改善の可能性あり
							$save = $prox[$i];
							$save['ProxyList']['error_count'] += 1;
							$ProxyList->save( $save, false );
							$aryError['MonomiError'] = 'BadRequest';
						}else if( 'HTTP/1.0 503 Service Unavailable' === $err ){	// アクセス集中のため、強制sleep
							sleep(5);
						} else {
							// クロール先サイトのエラー
							$aryError['MonomiError'] = 'notResponse';
						}
					}

				} else {

					//pr('プロキシエラー');
					$aryError['MonomiError'] = 'ProxyError';
				}

				// プロキシtableの対象レコードにエラーカウントレコードを上書きして保存
				$save = $prox[$i];
				$save['ProxyList']['error_count'] += 1;
				$ProxyList->save( $save, false );

			}

//			sleep(2);
		}

//print_r('  get_html END : '.memory_get_usage() . "\n");

//		$aryError['MonomiError'] = 'htmlDifference';
		return $aryError;

	}





	/**
	* プロキシ経由でHTMLを取得
	*
	* @param 過去１ページ分
	* @return none
	*/
	public function _old_get_html( $url, $site, $tag, $update_flag = false, $search_flag = false, $search_word = false ) {	// $search_wordはPost検索時のみ使用

		$aryError = array();
		$IllegalSite = ClassRegistry::init('IllegalSite');		// プロキシリスト

		// 最新の優良プロキシを取得を5つ取得
		$ProxyList = ClassRegistry::init('ProxyList');		// プロキシリスト
		$prox = $ProxyList->find('all', array('conditions'=>array('deleted'=>0), 'limit'=>5, 'order'=>array('error_count ASC')));


		// スクレイピングモード分岐
		if( $site['IllegalSite']['scraping_mode'] === 'curl' || $site['IllegalSite']['scraping_mode'] == null ) {
			$scraping_mode = 'curl';
		} else if ( $site['IllegalSite']['scraping_mode'] === 'fgc' ) {
			$scraping_mode = 'fgc';
		}

		// スクレイピング開始
		$initial_flag = true;
		for( $i=0; $i<5; $i++ ){	// バリデーションタグが設定されている場合、成功するまで5回繰り返す

			if( $i == 1 && $initial_flag == true ){
				$i = 0;
				$initial_flag = false;
			}

			$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));	// add 2012.09.14
			if( $i == 0 && $initial_flag == true && $site['IllegalSite']['proxy_ip'] != null && $site['IllegalSite']['proxy_ip'] !== '' ){	// 最初は前回のプロキシを優先
				$proxy_ip = $site['IllegalSite']['proxy_ip'];
			} else {
				$proxy_ip = $prox[$i]['ProxyList']['ip'];
			}
			$aryProxy = explode( ":", $proxy_ip );
			$port = $aryProxy[1];

print_r( '$url: ' . $url . ' count: ' . $i . ' proxy: ' . $proxy_ip . "\n");

			// curl準備
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
			curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);
			curl_setopt($ch, CURLOPT_PROXYPORT, $port);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);


			$time_start = microtime(true);	// 測定開始

			if( $search_word != false  ){		// POSTの場合
				$post_prm = str_replace( '[[kw]]', $search_word, $site['IllegalSite']['post_param'] );
				curl_setopt ($ch,CURLOPT_POST,true);
				curl_setopt ($ch,CURLOPT_POSTFIELDS,$post_prm);
			}
			$sorce = curl_exec($ch);

			$time_end = microtime(true);	// 測定終了
			$time = $time_end - $time_start;	// 所要時間算出

			// エラーチェック第１弾
			// curl_execで値が正常に取得できなかった場合
			if( false === $sorce || '' === $sorce || empty($sorce) ){
				$save = $prox[$i];
				$save['ProxyList']['error_count'] += 1;
				$ProxyList->save( $save, false );

				$curlExecError = curl_error($ch);
				if( !empty($curlExecError) && '' !== $curlExecError ){	// curl_errorでエラーの詳細理由が判明した場合
					$aryError['MonomiError'] = $curlExecError;
				} else {
					$aryError['MonomiError'] = 'curl_exec false';
				}
				curl_close($ch);

				sleep(5);
				continue;
			}

			// エラーチェック第２弾
			// curlで検出されたエラーの場合
			$respons = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if(preg_match("/^(404|403|500)$/",$respons)){
				$save = $prox[$i];
				$save['ProxyList']['error_count'] += 1;
				$ProxyList->save( $save, false );
				$aryError['MonomiError'] = 'ERROR 404,403,500';
				curl_close($ch);

				sleep(3);
				continue;
			}

			// エラーチェック第３弾
			// 遅いプロキシの評価を下げる
			if( $time > 20 ){	// 10秒以上かかっている場合は、プロキシのスコアを+1（マイナス評価）する
				$save = $prox[$i];
				$save['ProxyList']['error_count'] += 1;
				$ProxyList->save( $save, false );
				curl_close($ch);
				continue;
			}

			// エラーチェック第４弾
			// タグ相違
			curl_close($ch);
			$html = str_get_html( $sorce, "UTF-8", $site['IllegalSite']['encoding'] );
			$obj = $html->find( $tag );		// 正しくアクセスできているか確認
			if( empty( $obj ) ){	// HTML相違の場合はエラーとしてcontinueする

				$html->clear();
				unset($html);

				if( $search_flag == true ){		// 検索スクレイピングの場合、検索結果なしだとこのErrになるため、notFound とし、強制終了
					$aryError['MonomiError'] = 'notFound';	//
					return $aryError;
				} else {
					$aryError['MonomiError'] = 'htmlDifference';
				}

			} else {
				// add 2012.09.10 成功したプロキシを次回も使うため、レコードにプロキシIDを保存
				$site['IllegalSite']['proxy_ip'] = $proxy_ip;
				$IllegalSite->save( $site, false );

				// 成功したプロキシのスコアを上げる（-1）
				if( $prox[$i]['ProxyList']['error_count'] > 0 ){
					$save = $prox[$i];
					$save['ProxyList']['error_count'] -= 1;
//pr('proxy_score: ' . $proxy_ip . '  ' . $prox[$i]['ProxyList']['error_count'] . ' >>> ' . $save['ProxyList']['error_count'] );
					$ProxyList->save( $save, false );
				}

				return $html;
			}

//			sleep(2);
		}

		return $aryError;
	}








	/**
	* プロキシ経由でHTMLを取得
	*
	* @param 過去１ページ分
	* @return none
	*/
	public function __get_html( $url, $site, $tag = false, $update_flag = false, $search_flag = false, $search_word = false ) {	// $search_wordはPost検索時のみ使用

//print_r( '$url: ' . $url . "\n");

		$aryError = array();
		$IllegalSite = ClassRegistry::init('IllegalSite');		// プロキシリスト

		// 最新の優良プロキシを取得を３つ取得
		$ProxyList = ClassRegistry::init('ProxyList');		// プロキシリスト
		$prox = $ProxyList->find('all', array('conditions'=>array('deleted'=>0), 'limit'=>5, 'order'=>array('error_count ASC')));
//pr($prox);

		if( $tag === false ){	// バリデーションタグが存在しない場合、シンプルにhtmlオブジェクトを返す
			$html = @file_get_html( $url, false, $sc );
			return $html;
		}


//		$flg = false;
		$initial_flag = true;
		for( $i=0; $i<5; $i++ ){	// バリデーションタグが設定されている場合、成功するまで5回繰り返す
print_r( '$url: ' . $url . ' count: ' . $i . "\n");

			if( $i == 1 && $initial_flag == true ){
				$i = 0;
				$initial_flag = false;
			}

			$site = $IllegalSite->find('first', array('conditions'=>array('site_uid'=>$site['IllegalSite']['site_uid'])));	// add 2012.09.14
			if( $i == 0 && $initial_flag == true && $site['IllegalSite']['proxy_ip'] != null && $site['IllegalSite']['proxy_ip'] !== '' ){	// 最初は前回のプロキシを優先
//				$bProxy = $ProxyList->find('first', array('conditions'=>array( 'proxy_uid'=>$site['IllegalSite']['proxy_uid'] ) ));
//				$proxy_ip = $bProxy['ProxyList']['ip'];
				$proxy_ip = $site['IllegalSite']['proxy_ip'];
			} else {
				$proxy_ip = $prox[$i]['ProxyList']['ip'];
			}

//print_r('COUNT: '. $i . '  Proxy: ' . $proxy_ip . "\n");

//pr($search_word);
			// add 2012.09.18 検索Post型サイトに対応
			if( $search_word != false  ){		// POSTの場合

				// Postパラメータ生成
				$data = array();
				$prm = explode( ",", $site['IllegalSite']['post_param'] );
				foreach( $prm as $val ){
					$pm = explode( "=", $val );
					if( '[[kw]]' === $pm[1] ){	// キーワード置換用パラメータの場合
						$data[$pm[0]] = $search_word;
					} else {
						$data[$pm[0]] = $pm[1];
					}
				}
//pr($data);
				$data = http_build_query($data, "", "&");

				$headers = array(
					"Content-Type: application/x-www-form-urlencoded",
					"Content-Length: " . strlen($data),
					'Accept-Encoding:identity'
				);

				$proxy = array(
					'http' => array(
						"proxy" => $proxy_ip,
						"request_fulluri" => true,
						"timeout" => '10',
						'method' => 'POST',
						'content' => $data,
						'header' => implode("\r\n", $headers),
					)
				);

			} else {	// POST以外

				$headers = array(
					"Content-type:text/html;charset=UTF-8",
					'Accept-Encoding:identity'
				);

				$proxy = array(
					"http" => array(
						"proxy" => $proxy_ip,
						"request_fulluri" => true,
						"timeout" => 10,
						'header' => implode("\r\n", $headers),
					)
				);

			}

//pr($proxy);
//pr($url);

//			$http_response_header = array();
			$time_start = microtime(true);

			$sc = stream_context_create($proxy);
			$sorce = @file_get_contents ( $url, false, $sc );

			$time_end = microtime(true);
			$time = $time_end - $time_start;

			// 遅いプロキシの評価を下げる
			if( $time > 15 ){	// 10秒以上かかっている場合は、プロキシのスコアを+1（マイナス評価）する
print_r('slow proxy'."\n");
				$save = $prox[$i];
				$save['ProxyList']['error_count'] += 1;
				$ProxyList->save( $save, false );
				continue;
			}




			// add 2012.09.21 gzip系に備えて
			if( isset($http_response_header) ){
				foreach( $http_response_header as $rhd ){
					if( $rhd === 'Content-Encoding: gzip' ){	// gzipで圧縮されている場合
print_r('gzip'."\n");
						$sorce = $this->get_html_curl( $url, $proxy_ip );
						break;
					}
				}
			}


			if( !empty($sorce) && $sorce != false ){

				// add 2012.09.13 ryoshimura サイトが更新されたか確認
//				if( $update_flag == true ){
//					if( false == $this->check_updateSite( $site, $sorce ) ){
//						$aryError['MonomiError'] = 'notUpdate';
//						return $aryError;
//					}
//				}

//print_r('url: ' . $url . "\n");
//print_r(' Proxy: '.$proxy_ip . "\n");
				$html = str_get_html( $sorce, "UTF-8", $site['IllegalSite']['encoding'] );
				$obj = $html->find( $tag );		// 正しくアクセスできているか確認

//pr($html);

				if( empty( $obj ) ){	// HTML相違の場合はエラーとしてcontinueする
//pr('empty_object!!');
					$html->clear();
					unset($html);

					if( $search_flag == true ){		// 検索スクレイピングの場合、検索結果なしだとこのErrになるため、notFound とし、強制終了
						$aryError['MonomiError'] = 'notFound';	//
						return $aryError;

					} else {
//print_r(' tag: '.$tag . "\n");
						$aryError['MonomiError'] = 'htmlDifference';
					}

				} else {
					// add 2012.09.10 成功したプロキシを次回も使うため、レコードにプロキシIDを保存
					$site['IllegalSite']['proxy_ip'] = $proxy_ip;
					$IllegalSite->save( $site, false );
//print_r(' Proxy: '.$proxy_ip . "\n");

					return $html;
				}

			} else {
				if( isset($http_response_header) ){

//pr($http_response_header);

					// エラー判別
					foreach( $http_response_header as $err ){
						if( 'HTTP/1.0 502 Bad Gateway' === $err ){	// Bad Gatewayの場合、プロキシを変更することで改善の可能性あり
							$save = $prox[$i];
							$save['ProxyList']['error_count'] += 1;
							$ProxyList->save( $save, false );
							$aryError['MonomiError'] = 'BadGateway';
						}else if( 'HTTP/1.1 400 Bad Request' === $err ){	// Bad Requestの場合、プロキシを変更することで改善の可能性あり
							$save = $prox[$i];
							$save['ProxyList']['error_count'] += 1;
							$ProxyList->save( $save, false );
							$aryError['MonomiError'] = 'BadRequest';
						}else if( 'HTTP/1.0 503 Service Unavailable' === $err ){	// アクセス集中のため、強制sleep
							sleep(5);
						} else {
							// クロール先サイトのエラー
							$aryError['MonomiError'] = 'notResponse';
						}
					}

				} else {

					//pr('プロキシエラー');
					$aryError['MonomiError'] = 'ProxyError';
				}

				// プロキシtableの対象レコードにエラーカウントレコードを上書きして保存
				$save = $prox[$i];
				$save['ProxyList']['error_count'] += 1;
				$ProxyList->save( $save, false );

			}

//			sleep(2);
		}

//print_r('  get_html END : '.memory_get_usage() . "\n");

//		$aryError['MonomiError'] = 'htmlDifference';
		return $aryError;

	}

















	/**
	* プロキシList更新
	*
	* @param
	* @return none
	*/
	public function update_proxy_list() {

		$ProxyList = ClassRegistry::init('ProxyList');		// プロキシリスト

		// error_count==0 のプロキシが３つ以上残っている場合、新規リスト取得は行なわない
		$error_count = $ProxyList->find('count', array('conditions'=>array('error_count'=>0)));
		if( $error_count >= 5 ){
			return;
		}


		// 日本のプロキシ
		$url = 'http://www.getproxy.jp/proxyapi?ApiKey=9557433efc3ea9389d00dddab6b3e40221f4cb74&area=JP&sort=requesttime&orderby=asc';
		$this->get_proxy( $url );
		sleep(2);

		// アメリカのプロキシ
		$url = 'http://www.getproxy.jp/proxyapi?ApiKey=9557433efc3ea9389d00dddab6b3e40221f4cb74&area=US&sort=requesttime&orderby=asc';
		$this->get_proxy( $url );
		sleep(2);

		// 中国のプロキシ
		$url = 'http://www.getproxy.jp/proxyapi?ApiKey=9557433efc3ea9389d00dddab6b3e40221f4cb74&area=CN&sort=requesttime&orderby=asc';
		$this->get_proxy( $url );
		sleep(2);

		// カナダのプロキシ
		$url = 'http://www.getproxy.jp/proxyapi?ApiKey=9557433efc3ea9389d00dddab6b3e40221f4cb74&area=CA&sort=requesttime&orderby=asc';
		$this->get_proxy( $url );
		sleep(2);

		// フランスのプロキシ
		$url = 'http://www.getproxy.jp/proxyapi?ApiKey=9557433efc3ea9389d00dddab6b3e40221f4cb74&area=FR&sort=requesttime&orderby=asc';
		$this->get_proxy( $url );


	}


	/**
	* プロキシXML取得
	*
	* @param
	* @return none
	*/
	private function get_proxy( $url ) {

		$ProxyList = ClassRegistry::init('ProxyList');		// プロキシリスト
clearstatcache();
		$plist = file_get_contents( $url );
		if( false === $plist ){
			// エラー場合

		} else {
			$xml = simplexml_load_string( $plist, 'SimpleXMLElement', LIBXML_NOCDATA );

			$i = 0;
			foreach( $xml->item as $val ){
				if( $i >= 2 ){ break; }	// 新プロキシを２つ登録したらbreak
				$requesttime = (String)$val->requesttime;
				$type = (String)$val->type;
				if( $requesttime < 1000 && ( $type==='S' || $type==='A' || $type==='B' || $type==='C' || $type==='D' ) ){
					$ip = (String)$val->ip;
					$prx = $ProxyList->find('first', array('conditions'=>array('ip'=>$ip)));
					if( empty($prx) ){
						$save = array();
						$save['ProxyList']['ip']		= $ip;
						$save['ProxyList']['area']		= (String)$val->area;
						$save['ProxyList']['proxy_type']= $type;
						$save['ProxyList']['anonymous']	= (String)$val->anonymous;
						$ProxyList->create();
						$ProxyList->save( $save, false );
						$i++;
					}
				}
			}
		}
	}






	/**
	* サイトが更新したか確認
	*
	* @param
	* @return none
	*/
	private function check_updateSite( $site, $sorce ) {

		$length = strlen( $sorce );
//pr($length);
		if( $site['IllegalSite']['top_page_size'] == $length ){		// 更新していない

			return false;

		} else {													// 更新している

			$IllegalSite = ClassRegistry::init('IllegalSite');
			$site['IllegalSite']['top_page_size'] = $length;
			$IllegalSite->save( $site, false );
			return true;

		}
	}






	/**
	* CURLによるHTML取得
	*
	* @param
	* @return none
	*/
	private function get_html_curl( $url, $proxy_ip, $mode = false ) {

		$aryProxy = explode( ":", $proxy_ip );
		$port = $aryProxy[1];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//プロキシ経由フラグ
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		//プロキシアドレス設定（プロキシのアドレス:ポート名）
		curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);
		//念のためプロキシのポートを指定
		curl_setopt($ch, CURLOPT_PROXYPORT, $port);
		//プロキシのID,PASSの設定（ID:PASS）
//		curl_setopt($ch, CURLOPT_PROXYUSERPWD, "anonymous:");

		$html = curl_exec($ch);
		curl_close($ch);

		return $html;
	}






	/**
	* file_get_contentsによるHTML取得
	*
	* @param
	* @return $sorce
	*/
	private function get_html_fileContents( $url, $proxy_ip, $post_prm, $search_word ) {

		// Postパラメータ生成
		if( $site['IllegalSite']['post_param'] !== '' && $site['IllegalSite']['post_param'] !=null ){	// POSTの場合

			$data = array();
			$prm = explode( ",", $site['IllegalSite']['post_param'] );
			foreach( $prm as $val ){
				$pm = explode( "=", $val );
				if( '[[kw]]' === $pm[1] ){	// キーワード置換用パラメータの場合
					$data[$pm[0]] = $search_word;
				} else {
					$data[$pm[0]] = $pm[1];
				}
			}
			$data = http_build_query($data, "", "&");
			$headers = array(
				"Content-type:text/html;charset=UTF-8",
				"Content-Length: " . strlen($data),
				'Accept-Encoding:identity',
				'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; ja; rv:1.9.1.7) Gecko/20091221 Firefox/3.5.7'
			);

		} else {

			$headers = array(
				"Content-type:text/html;charset=UTF-8",
				'Accept-Encoding:identity',
				'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; ja; rv:1.9.1.7) Gecko/20091221 Firefox/3.5.7'
			);

		}

		$proxy = array(
			"http" => array(
				"proxy" => $proxy_ip,
				"request_fulluri" => true,
				"timeout" => 20,
				'header' => implode("\r\n", $headers),
			)
		);

		$sc = stream_context_create( $proxy );
clearstatcache();
		$sorce = @file_get_contents ( $url, false, $sc );

		return $sorce;
	}






	private function utf8_to_unicode_code($utf8_string) {
//	 	$expanded = iconv("UTF-8", "UTF-32", $utf8_string);

		// Notice出ても強行。検出は行なわれる
	 	$expanded = iconv("UTF-8", "UTF-32//TRANSLIT", $utf8_string);
	 	return unpack("L*", $expanded);
	}

	private function unicode_code_to_utf8($unicode_list) {
		$result = "";
		foreach($unicode_list as $key => $value) {
			$one_character = pack("L", $value);
			$result .= iconv("UTF-32", "UTF-8", $one_character);
		}
		return $result;
	}



	public function conv_text_uni( $text ){

		// unicode変換
//		$text = htmlentities($text,ENT_NOQUOTES);
		$text	= strtolower( str_replace(array("\r\n","\r","\n","\t"), '', $text) );
		$text = str_replace('&amp;#','&#',$text);
		$r = html_entity_decode($text, ENT_NOQUOTES, 'UTF-8');
		$s = $this->utf8_to_unicode_code($r);
		$text = $this->unicode_code_to_utf8($s);

		return $text;
	}




}

?>