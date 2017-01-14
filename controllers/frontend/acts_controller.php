<?php

/**
* 説明
* @see 関連関数1,関連関数2
*/


/**
* （テスト処理等）アクション関連 コントローラー
*
* @package   パッケージ名
* @author    著作者 <著作者メール>
* @since     PHP 5.0
* @version $Id: DB.php,v 1.58 2004/03/13 16:17:19 danielc Exp $
*/
class ActsController extends AppController {

	var $name       = 'Acts';                     // $nameを設定することでcontroller名を任意に設定することができる。
	var $components = array('Email');     // 使用するcomponentを指定する。
	var $layout     = 'base_layout';               // 使用するレイアウト
	var $uses       = array( 'Crlsite', 'User', 'Word', 'UserProfile', 'HtmlCache', 'Crlmodels', 'IllegalResult', 'DownloadResult', 'IllegalSite', 'Message' );    	 // 使用するmodelを指定する。デフォルトmodelは必ず配列の先頭に記述する。


	/**
	* action,render前実行関数
	*
	* @param  none
	* @return none
	*/
	public function beforeFilter() {
		parent::beforeFilter();

		// Authコンポーネント非適用Action
//		$this->Auth->allow('index', 'delete', 'act_dlsite', 'act_dmm', 'categorize', 'test', 'create_afli', 'test_mail', 'try_test');

		Configure::write('debug', 2);
	}





	/**
	* メッセージ送信
	*/
	public function test_wordlist(){
		$this->layout="";

		$data = $this->Word->get_crl_words();
pr(count($data));
pr($data);


		$this->render('/acts/index');
	}





	/**
	* メッセージ送信
	*/
	public function set_msg(){
		$this->layout="";

		$users = $this->User->find('all', array('conditions'=>array('payment_status'=>9)));


		foreach( $users as $user ){
pr($user);
			$title	= 'β版ご利用のお客様へ特別なプランを紹介';
			$text  = 'β版物見インフォをご利用いただきありがとうございます。<br />';
			$text .= '皆さまからいただいたご意見や情報提供によってサービスの品質が向上し、正式サービス開始を間近に控えるまでとなりました。<br />';
			$text .= 'そこで、ささやかではありますがβ版ご利用者限定の特別なプラン（監視ワード枠２つ増加）を用意しました。<br />';
			$text .= '「β版ご利用者向け監視30日ワード数4個 780円」<br />';
			$text .= 'サービス開始と同時に、こちらの<a href="/users/profile/">アカウント情報</a>からお申込みいただけます。<br />';
			$text .= 'なお、このプランは何度でもお申込みいただけます。<br />';
			$text .= '引き続き物見インフォをよろしくお願い致します。';

			$this->Message->setMsg( $user, $title, $text );

		}

		$this->render('/acts/index');
	}



	/**
	* キャンペーンコード配布
	*/
	public function set_campaign(){
		$this->layout="";

		$user = $this->User->find('all', array('conditions'=>array('campaign_code'=>null)));


		foreach( $user as $user ){
			$user['User']['campaign_code'] = uniqid(rand(100,999));
			$this->User->save($user, false);
			sleep(1);
		}

		$this->render('/acts/index');
	}



	/**
	* 誤検知リザルトを削除
	*/
	public function delete_ehentai(){
		$this->layout="";

		$data = $this->IllegalResult->find('all', array('conditions'=>array('site_uid'=>'00025', 'memo_request'=>0, 'memo_complete'=>0)));

		foreach( $data as $save ){
//			$this->IllegalResult->delete( $save['IllegalResult']['illegal_result_uid'], false);
			$save['IllegalResult']['deleted'] = 1;
			$this->IllegalResult->save( $save, false );
		}



		$this->render('/acts/index');
	}





	/**
	* unicode変換テスト
	*/
	public function test_nyaa(){
		$this->layout="";

		$url = 'http://sukebei.nyaa.eu/?page=search&cats=0_0&filter=0&term=%E7%A4%BE%E4%BC%9A%E4%BA%BA%E3%81%AB%E3%81%AA%E3%81%A3%E3%81%9F%E3%81%B0%E3%81%8B%E3%82%8A%E3%81%AE%E7%BE%8E%E4%BA%BAOL';
		$site = $this->IllegalSite->find('first',array('conditions'=>array('site_uid'=>'00034')));

		$html = &$this->Crlmodels->get_html( $url, $site, null, false, true );	// スクレイピング
pr($html->outertext);
		$html->clear();
		unset($html);

		$this->render('/acts/index');
	}


	/**
	* unicode変換テスト
	*/
	public function conv_uni(){
		$this->layout="";

		$text = 'http://sukebei.nyaa.eu/?page=torrentinfo&#38;tid=187722';
print_r($text . "\n");

		$text = $this->Crlmodels->conv_text_uni($text);
print_r($text . "\n");

		$this->render('/acts/index');
	}

	/**
	* ユーザデータを復号化して表示
	*/
	public function chk_user(){
		$this->layout="";


		$user = $this->User->find('all');
		foreach( $user as $key=>$val ){
			$user[$key] = $this->User->decryptedUser($val);
		}

pr($user);

		$this->render('/acts/index');
	}




	public function get_whois(){
		$this->layout="";

		// whoisを取りたいURL
		$url = "http://rapidgator.net/";

		// ホスト部抜き出し
		$host = $this->split_host($url);

		// ホストを「.」できっておく
		$sep = split("\.", $host);

		for($i=0; $i<count($sep); $i++){
			// LinuxコマンドにてWhois取得
			$whois = shell_exec("whois " . $host);

			// 「No match!!」が含まれてたら取得失敗
			$flag = strstr($whois, "No match!!");

			if($flag){
				// ホストを前から順番に1オクテットずつ消していく
				$host = str_replace($sep[$i] . ".", "", $host);
			}else{
				// ループ終了
				break;
			}
		}

		// 出力
		$this->set('data',$whois);


		$this->render('/acts/index');

	}


	/**
	* URLからホスト部を抜き出す
	*/
	public function split_host($url){
		$host = "";

		$pattern = '/(http|https):\/\/([-._a-z\d]+)/';

		if(preg_match($pattern, $url, $matche)){
			$host = $matche[2];
		}

		return $host;
	}


	public function get_dr(){
		$this->layout="";


		$this->DownloadResult->getDR( '507b3ef7-4bf4-4c2d-bc7f-15e045036250', '508a056c-679c-4795-b2f9-0b7845036250' );;

		$this->render('/acts/index');
	}


	// ShareSex
	public function create_status_results(){
		$this->layout="";

		$user = $this->User->find('all');

		foreach( $user as $val ){

			// イリーガル
			$sql = 'SELECT ';
			$sql.= 'IR.illegal_url,';
			$sql.= 'IR.trash,';
			$sql.= 'IR.memo_request,';
			$sql.= 'IR.memo_complete';
			$sql.= ' FROM ';
			$sql.= 'illegal_results AS IR ';
			$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = IR.word_uid ';
			$sql.= 'WHERE ';
			$sql.= 'Word.user_uid = "'. $val['User']['user_uid'] .'" ';
			$sql.= 'AND IR.deleted = 0 ';
			$sql.= 'AND Word.deleted = 0 ';
			$sql.= 'AND (IR.trash=1 OR IR.memo_request=1 OR IR.memo_complete=1) ';

			$sql.= 'AND IR.illegal_url IN ( SELECT DISTINCT illegal_url FROM illegal_results WHERE deleted = 0 ) ';
			$status = $this->StatusResult->query( $sql );

			foreach( $status as $v ){
				$buf = $this->StatusResult->find('all',array('conditions'=>array('user_uid'=>$val['User']['user_uid'], 'result_url'=>$v['IR']['illegal_url'])));	// 重複チェック
				if( $buf == false ){
					$save['StatusResult']['user_uid']		= $val['User']['user_uid'];
					$save['StatusResult']['result_url']		= $v['IR']['illegal_url'];
					$save['StatusResult']['trash']			= $v['IR']['trash'];
					$save['StatusResult']['memo_request']	= $v['IR']['memo_request'];
					$save['StatusResult']['memo_complete']	= $v['IR']['memo_complete'];
					$this->StatusResult->create();
					$this->StatusResult->save($save, false);
				}
			}


			// ダウンロード
			$sql = 'SELECT ';
			$sql.= 'DR.download_result_url,';
			$sql.= 'DR.trash,';
			$sql.= 'DR.memo_request,';
			$sql.= 'DR.memo_complete';
			$sql.= ' FROM ';
			$sql.= 'download_results AS DR ';
			$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = DR.word_uid ';
			$sql.= 'WHERE ';
			$sql.= 'Word.user_uid = "'. $val['User']['user_uid'] .'" ';
			$sql.= 'AND DR.deleted = 0 ';
			$sql.= 'AND Word.deleted = 0 ';
			$sql.= 'AND (DR.trash=1 OR DR.memo_request=1 OR DR.memo_complete=1) ';
			$sql.= 'AND DR.download_result_url IN ( SELECT DISTINCT download_result_url FROM download_results WHERE deleted = 0 ) ';
			$status = $this->StatusResult->query( $sql );
//pr($status);


			foreach( $status as $v ){
				$buf = $this->StatusResult->find('all',array('conditions'=>array('user_uid'=>$val['User']['user_uid'], 'result_url'=>$v['DR']['download_result_url'])));	// 重複チェック
				if( $buf == false ){
					$save['StatusResult']['user_uid']		= $val['User']['user_uid'];
					$save['StatusResult']['result_url']		= $v['DR']['download_result_url'];
					$save['StatusResult']['trash']			= $v['DR']['trash'];
					$save['StatusResult']['memo_request']	= $v['DR']['memo_request'];
					$save['StatusResult']['memo_complete']	= $v['DR']['memo_complete'];
					$this->StatusResult->create();
					$this->StatusResult->save($save, false);
				}
			}


		}



		$this->render('/acts/index');
	}







	// ShareSex
	public function test_put(){
		$this->layout="";
//		file_put_contents('testtesttest', 'testtesttest');
pr( env('HTTP_HOST') );
		$this->render('/acts/index');
	}


	// ShareSex
	public function chache_test(){
		$this->layout="";

		$hChache = $this->HtmlCache->find('first');

		// キャッシュチェック
//		$sorce = $this->HtmlCache->loadCache( $hChache['HtmlCache']['url'], false );
//		$content = str_get_html( $sorce, "UTF-8", "UTF-8" );


		$file_pass = HTML_CACHE_PATH . 'testcache';
pr($file_pass);
		if( false == file_put_contents($file_pass, 'test!!') ){
			pr('false!!');
		} else {
			pr('ok!!');
		}

//		foreach( $content->find('div.post_wrap', 0)->find('a') as $du ){
//pr( $du->href );
//		}





		$this->render('/acts/index');
	}

	// Uniコードからデコード
	function decUnicode($data, $enc) {
		$arySplitVal = split(';&#', $data);
		$le = count($arySplitVal);
		$arySplitVal[0]	= str_replace('&#', '', $arySplitVal[0]);
		$arySplitVal[$le-1] = str_replace(';', '', $arySplitVal[$le-1]);

		$xout = "";
		for ($i=0;$i<$le;$i++) {
			$xout .= mb_convert_encoding(pack("H*", dechex($arySplitVal[$i]))
				, $enc, "UCS-2");
		}
		return $xout;
	}


	 function utf8_to_unicode_code($utf8_string) {
	 	$expanded = iconv("UTF-8", "UTF-32", $utf8_string);
	 	return unpack("L*", $expanded);
	}

	function unicode_code_to_utf8($unicode_list) {
		$result = "";
		foreach($unicode_list as $key => $value) {
			$one_character = pack("L", $value);
			$result .= iconv("UTF-32", "UTF-8", $one_character);
		}
		return $result;
	}




	// ShareSex
	public function sc2(){
		$this->layout="";

		$tag = 'title';
//		$base_url = 'http://www.henarchive.net';
		$detail_url = 'http://www.elbrollo.com/topic/569786-120929-%26-12399%3B%26-12415%3B%26-37096%3B%26-23627%3B-%26-26032%3B%26-35013%3B%26-38283%3B%26-24215%3B%26-12525%3B%26-12522%3B%26-12387%3B%26-23376%3B%26-12513%3B%26-12452%3B%26-12489%3B/';

			// curl準備
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $detail_url);
			curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
			curl_setopt($ch, CURLOPT_PROXY, '202.195.128.106');
			curl_setopt($ch, CURLOPT_PROXYPORT, '1989');
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);

			$sorce = curl_exec($ch);
			curl_close($ch);



//$sorce = '[120929] [&#12399;&#12415;&#37096;&#23627;] &#26032;&#35013;&#38283;&#24215;&#33;&#12525;&#12522;&#12387;&#23376;&#12513;&#12452;&#12489;';
//$sorce = '[120929] [&#12399;&#12415;&#37096;&#23627;] &#26032;&#35013;&#38283;&#24215;&#33;&#12525;&#12522;&#12387;&#23376;&#12513;&#12452;&#12489; - elbrollo.com    ir a contenido&nbsp;&nbsp;&nbsp;&nbsp;conectar&nbsp;&nbsp;&nbsp;registrese ya!buscaravanzadobuscar la secci: google este tema este foroforumsmembershelp filesgalleryblogsdownloadsstoretutorialsvideosclassifieds&nbsp;nuevos contenidosinicioforoblogsgrupos socialesbrollobankgaler僘videostutorialesla tienditasoportereglasmas elbrollo.com&rarr; rinc multimedia&rarr; anim駛avascript disabled detectedyou currently have javascript disabled. several functions may not work. please re-enable javascript to access full functionality. 1[120929] [&#12399;&#12415;&#37096;&#23627;] &#26032;&#35013;&#38283;&#24215;&#33;&#12525;&#12522;&#12387;&#23376;&#12513;&#12452;&#12489; iniciado por hentaia, ayer, 10:44 amno puedes responder a este temano hay comentarios#1hentaiabrollerobrollero profesional1801 mensajes:escrito ayer, 10:44 am(&#21516;&#20154;cg&#38598;) [120929] [&#12399;&#12415;&#37096;&#23627;] &#26032;&#35013;&#38283;&#24215;&#33;&#12525;&#12522;&#12387;&#23376;&#12513;&#12452;&#12489;&#21931;&#33590;&#65374;&#31192;&#23494;&#12398;&#12513;&#12491;&#12517;&#12540;&#12391;&#22823;&#32966;&#12372;&#22857;&#20181;&#65374;, &#26408;&#26143;&#12398;&#23064; (4cg)[h-cg] [120929] [&#12399;&#12415;&#37096;&#23627;] &#26032;&#35013;&#38283;&#24215;&#33;&#12525;&#12522;&#12387;&#23376;&#12513;&#12452;&#12489;&#21931;&#33590;&#65374;&#31192;&#23494;&#12398;&#12513;&#12491;&#12517;&#12540;&#12391;&#22823;&#32966;&#12372;&#22857;&#20181;&#65374;, &#26408;&#26143;&#12398;&#23064; (4cg)title: &#26032;&#35013;&#38283;&#24215;&#33;&#12525;&#12522;&#12387;&#23376;&#12513;&#12452;&#12489;&#21931;&#33590;&#65374;&#31192;&#23494;&#12398;&#12513;&#12491;&#12517;&#12540;&#12391;&#22823;&#32966;&#12372;&#22857;&#20181;&#65374;[roze] &#26408;&#26143;&#12398;&#23064;[pink&#9734;doragon] sparking&#9733;z &#33; &#33;[&#12450;&#12490;&#12525;&#12464;] &#12500;&#12479;&#12483; &#12367;&#12401;&#12353; hard freshbland: &#12399;&#12415;&#37096;&#23627;release: 2012/09/29file size: 251mbgenre&#65306;cg+&#12494;&#12505;&#12523;informationhttp://www.dlsite.co...d/rj103102.htmlhttp://www.dlsite.co...d/rj101635.htmlhttp://www.dlsite.co...d/rj059284.htmlhttp://www.dlsite.co...d/rj059154.htmldownload links:rapidgator (premium download)http://rapidgator.ne...103102.rar.htmlshareflarehttp://shareflare.ne...103102.rar.htmlfreaksharehttp://freakshare.co...103102.rar.htmlturbobithttp://turbobit.net/9oherh2s94tb.htmluploadedhttp://ul.to/94hsosc...cg-rj103102.rarto enter my blog to get the download links----http://18ranime.net brollo$ : b$ 0volver arribavolver a anim&middot; tema no le冝o &rarr;responder a los mensajes citados&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;limpiar  &nbsp;&nbsp;&nbsp;elbrollo.com&rarr; rinc multimedia&rarr; anim駻eglascambiar tema elbrollo multicolor 3.2.3elbrollo halloween espal (esp)english (usa)espal (esp)marcar foro como le冝oforumsgalleryblogsdownloadsstoretutorialsvideosclassifiedsmarcar todo como le冝oayuda                    community forum software by ip.board';
//$sorce = mb_convert_encoding( $sorce, "UTF-8", "auto" );
//$sorce = html_entity_decode($sorce);
//$sorce = utf8_encode($sorce);
//$sorce = htmlspecialchars($sorce,ENT_QUOTES,"ISO-8859-1");
//$sorce = $this->decUnicode($sorce, "UTF-8");
//print_r($sorce);


$html = str_get_html( $sorce, "UTF-8", "UTF-8" );
$sorce = $html->find( 'div.post_wrap', 0 );
$sorce = $html->plaintext;
$sorce = htmlentities($sorce,ENT_NOQUOTES);
$sorce	= strtolower( str_replace(array("\r\n","\r","\n","\t"), '', $sorce) );
$sorce = str_replace('&amp;#','&#',$sorce);

$r = html_entity_decode($sorce, ENT_NOQUOTES, 'UTF-8');
$s = $this->utf8_to_unicode_code($r);
$t = $this->unicode_code_to_utf8($s);
//print "$r\n";
//print_r($s);
print "$t\n";


//print_r( $sorce );


//		$html = str_get_html( $sorce, "UTF-8", "UTF-8" );
//		$content = $html->find( 'a' );		// 正しくアクセスできているか確認
//		foreach( $content as $val ){
//			pr($val->plaintext);
//		}

//pr($html->plaintext);

/*
//		$sorce = file_get_contents ( $detail_url );

		$html = str_get_html( $sorce, "UTF-8", "UTF-8" );
		$content = $html->find( $tag, 1 );		// 正しくアクセスできているか確認

		if( empty( $content ) ){
			pr('ERROR');
		}

		// マッチング対象外アンカーテキストを除去
		$body_text = $content->outertext;
pr($body_text);
		$body_text = str_replace(array("\r\n","\r","\n"), '', $body_text);
		foreach( $content->find('a') as $ma ){

			$href = $ma->href;
			// 自ドメインの場合
			if( false !== strpos( $href, $base_url ) ){
				// 自詳細ページでない場合はマッチングの邪魔なので削除
				if( false === strpos( $href, $detail_url ) ){
					$body_text = str_replace( $ma->outertext, '', $body_text );
				}
			} else if( preg_match( '/^\/.*?/', $href ) ){	//  スラッシュ（/）スタートのURLのaタグもマッチングの邪魔なので削除
pr('スタート／');
				$body_text = str_replace( $ma->outertext, '', $body_text );
			}
		}

		// 全てのHTMLタグを除去
		$body_text = preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/','',$body_text);

//pr('■■■■■■■');
pr($body_text);

*/

		$this->render('/acts/index');
	}


	// ShareSex
	public function sc(){
		$this->layout="";

		$tag = 'div.entry';
		$base_url = 'http://hentai-girl.net';
		$detail_url = 'http://hthrone.com';




		$sorce = @file_get_contents ( $detail_url );
pr($sorce);
		$html = str_get_html( $sorce, "UTF-8", "UTF-8" );
/*		$content = $html->find( $tag, 0 );		// 正しくアクセスできているか確認

		if( empty( $content ) ){
			pr('ERROR');
		}

		$i = 0;
//		foreach($obj as $v){
//			pr($i);
//			$i++;
//			pr($v->outertext);
//		}


		//		$body_text = $content->plaintext;
		$body_text = $content->outertext;
		$after_detail_url = str_replace( $base_url, '', $detail_url );
		$pattern  = '/(href=.*?';
		$pattern .= preg_quote( $after_detail_url, '/' );
		$pattern .= ')/is';

//$pattern = '/(href=.*?\/hentai\/game\/19364\-090201shiningstar\.html)/is';
		$body_text = preg_replace($pattern, "", $body_text );


		// 自ドメインURLは<a>～</a>を空白で置換
		$pattern  = '/href=[\"\']';
		$pattern .= preg_quote( $base_url, '/' );
		$pattern .= '.*?>(.*?)<\/a>/is';
$pattern = '/<a.*href=\"http\:\/\/hentai\-girl\.net.*?<\/a>/is';
		$body_text = preg_replace($pattern, "", $body_text );
pr($pattern);

		// href=""でhttp://で始まらないものも空白で置換
		$body_text = preg_replace('/(href=[\"\']\/.*?<\/a>)/is', '', $body_text );

		// 全てのHTMLタグを除去
		$body_text = preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/','',$body_text);


		foreach( $content->find('a') as $du ){
			pr($du->href);
		}



if($body_text == null){
	pr('preg_error!!!');
} else {
	pr($body_text);
}

*/

		$this->render('/acts/index');
	}




	// 特定アカウントのパスワードを取る管理者専用
	public function check_user(){
		$this->layout="";

		$user['User']['mail_address'] = 'mitumaro@gmail.com';
		$user = $this->User->hashPasswords($user);

		$user = $this->User->find('first', array('conditions'=>array('mail_address'=>$user['User']['mail_address'])));
		$user = $this->User->decryptedUser($user);


//pr($user);
		$this->set('data',$user);

		$this->render('/acts/index');
	}



	// 非暗号化UserProfileデータを一括暗号化するバッチ
	public function chage_prof(){
		$this->layout="";

		$prof = $this->UserProfile->find('all');

		foreach( $prof as $save ){

			$save = $this->UserProfile->encryptedProf($save);
pr($save);
			$this->UserProfile->save($save, false);

		}


		$this->render('/acts/index');
	}


	// 非暗号化Wordデータを一括暗号化するバッチ
	public function chage_word(){
		$this->layout="";

		$word = $this->Word->find('all', array('conditions'=>array('search_word <>'=>'')));

		foreach( $word as $save ){

			$save = $this->Word->encryptedWord($save);
pr($save);
			$this->Word->save($save, false);

		}


		$this->render('/acts/index');
	}


	// 非暗号化Userデータを一括暗号化するバッチ
	public function chage_mail(){
		$this->layout="";

		$user = $this->User->find('all');

		foreach( $user as $save ){

			$save = $this->User->hashPasswords($save);
//pr($save);
			$this->User->save($save, false);

		}


		$this->render('/acts/index');
	}





	// 暗号復号化テスト
	public function test_mdec(){
		$this->layout="";

//		pr( $this->Word->get_crl_words() );

		pr( $this->User->get_notice_list() );


		$this->render('/acts/index');
	}


	// Anime-Sharing スクレイピングテスト
	public function test_mcrypt( $input, $key ){
		/* モジュールをオープンし、IV を作成します */
		$td = mcrypt_module_open('des', '', 'ecb', '');
pr('td: ' . $td);
		$key = substr($key, 0, mcrypt_enc_get_key_size($td));
pr('key: ' . $key);
//    $key = substr(md5($key), 0, mcrypt_enc_get_key_size($td));
		$iv_size = mcrypt_enc_get_iv_size($td);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
pr('iv: ' . $iv);
//	$iv = substr(md5($key), 0, $iv_size);
	/* 復号のため、バッファを再度初期化します */
		mcrypt_generic_init($td, $key, $iv);
		$p_t = mdecrypt_generic($td, $c_t);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
	}

	// Anime-Sharing スクレイピングテスト
	public function test_anishar(){
		$this->layout="";

		$str = '<p>http://www.uploadstation.com/file/dNVFXKf http://www.uploadstation.com/file/97m4XR6' . "\n";
		$str.= 'あああ http://www.uploadstation.com/file/97m4XR6' . "\n";
		$str.= 'faf src="http://www.uploadstation.com/file/BfQCYXc"' . "\n";
		$str.= 'http://www.uploadstation.com/file/B6BF2Fe</p>';
pr($str);

		preg_match_all( '/http:\/\/www.uploadstation.com\/file\/.*?( |\n|\r|\r\n|<|\")/i', $str, $aryBuf, PREG_SET_ORDER );
pr($aryBuf);

		$data = array();
		foreach( $aryBuf as $val ){
			$val[0] = str_replace(array('<', "\"", "\r\n","\r","\n"), '', $val[0]);	// 改行トリミング
			$val[0] = str_replace('<','',$val[0]);		// タグ < 削除
			$val[0] = trim($val[0]);					// スペース削除

			$data[] = $val[0];
		}
pr($data);


		$this->render('/acts/index');
	}




	// 文字マッチングテスト
	public function test_match(){
		$this->layout="";


		$url = 'http://swishentai.com/hentai-cg/12123';
		$base_url = 'http://swishentai.com';
		$detail_url = $url;

		$sorce = @file_get_contents($url);
		$html = str_get_html( $sorce, "UTF-8", 'UTF-8' );


		$content = $html->find( 'div.entry-content', 0 );
		$body_text = $content->outertext;





		$after_detail_url = str_replace( $base_url, '', $detail_url );
		$pattern  = '/<a.*?href=.*?';
		$pattern .= preg_quote( $after_detail_url, '/' );
		$pattern .= '.*?>/i';
		$body_text = preg_replace($pattern, "", $body_text );

		// 自ドメインURLは<a>～</a>を空白で置換
		$pattern  = '/<a.*?href=.*?';
		$pattern .= preg_quote( $base_url, '/' );
		$pattern .= '.*?>(.*?)<\/a>/i';
		$body_text = preg_replace($pattern, "", $body_text );

		// href=""でhttp://で始まらないものも空白で置換
		$body_text = preg_replace('/<a.*?href=.*?\/(.*?)>(.*?)<\/a>/i', '', $body_text );

		// 全てのHTMLタグを除去
		$body_text = preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/','',$body_text);

pr($body_text);



		if( false !== mb_strpos( strtolower($body_text), strtolower( 'SWEET☆SWEET☆RAPE' ) ) ){
			pr('OK');
		} else {
			pr('NG');
		}

		$this->render('/acts/index');
	}








	// POST送信テスト
	public function test_obj(){
		$this->layout="";

		$url = 'http://www.vivahentai4u.net/?s=%E5%AB%81%E3%81%AE%E5%A7%89%E3%81%8C%E5%B7%A8%E4%B9%B3%E9%81%8E%E3%81%8E%E3%81%A6%E6%88%91%E6%85%A2%E3%81%A7%E3%81%8D%E3%81%AA%E3%81%84';

		$proxy_ip = '183.95.132.76:80';
		$aryProxy = explode( ":", $proxy_ip );
		$port = $aryProxy[1];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);
		curl_setopt($ch, CURLOPT_PROXYPORT, $port);
		$sorce = curl_exec($ch);

		$html = str_get_html( $sorce, "UTF-8", "UTF-8" );
		$obj = $html->find( 'div.post' );

		$cnt = 0;
		foreach( $obj as $val ){		// 1投稿を括るタグを指定

			if( empty( $val->find( 'h2', 0 )->find('a',0)->href ) ){
				pr('ERROR');
			}
//			$href = $val->find( 'h2', 0 )->find('a',0)->href;
//pr($href);
		}


		$this->render('/acts/index');
	}


	// POST送信テスト
	public function test_curl(){
		$this->layout="";

		$url = 'http://www.agoraaasgga.com';

		$proxy_ip = '183.95.132.76:80';
		$aryProxy = explode( ":", $proxy_ip );
		$port = $aryProxy[1];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);
		curl_setopt($ch, CURLOPT_PROXYPORT, $port);

		$html = curl_exec($ch);
		if( false === $html || '' === $html ){

		}


		$respons = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if(preg_match("/^(404|403|500)$/",$respons)){
pr('ERROR!');
		}

//pr('err: ' . curl_error($ch) );
//pr('info: ', curl_getinfo($ch, CURLINFO_HTTP_CODE) );

		curl_close($ch);

pr($html);

		$this->render('/acts/index');
	}



	// POST送信テスト
	public function test_tcw(){
		$this->layout="";

$context = stream_context_create(
  array('http' => array(
    'method' => 'GET',
    'header' => 'User-Agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)',
  )));
		$url = 'http://www.henarchive.net';
$data = @file_get_contents($url, false, $context);

pr( $http_response_header );
pr($data);
		$this->render('/acts/index');
	}


	// POST送信テスト
	public function test_scw(){

		$this->layout="";

//ini_set('user_agent', 'User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)');
ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.13 (KHTML, like Gecko) Chrome/9.0.597.107 Safari/534.13');

				$headers = array(
					"Content-type:text/html;charset=UTF-8",
					'Accept-Encoding:identity',
					'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.13 (KHTML, like Gecko) Chrome/9.0.597.107 Safari/534.13'
				);

				$proxy = array(
					"http" => array(
						"proxy" => '61.55.141.10:80',
						"request_fulluri" => true,
						"timeout" => '10',
						'header' => implode("\r\n", $headers),
					)
				);




//		$url = 'http://www.vivahentai4u.net/?s=%E3%81%82%E3%82%93%E3%81%93%E3%81%B1%E3%82%93%E3%81%98%E3%83%BC';
		$url = 'http://www.henarchive.net';
//		$url = 'http://www.vivahentai4u.net/archives/5910.html';
//		$url = 'http://www.vivahentai4u.net/?s=';


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//プロキシ経由フラグ
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		//プロキシアドレス設定（プロキシのアドレス:ポート名）
		curl_setopt($ch, CURLOPT_PROXY, '183.95.132.76:80');
		//念のためプロキシのポートを指定
		curl_setopt($ch, CURLOPT_PROXYPORT, '80');
		//プロキシのID,PASSの設定（ID:PASS）
//		curl_setopt($ch, CURLOPT_PROXYUSERPWD, "anonymous:");
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.13 (KHTML, like Gecko) Chrome/9.0.597.107 Safari/534.13');

		$html = curl_exec($ch);
		curl_close($ch);





pr($html);

pr($url);
		$sc = stream_context_create($proxy);
		$sorce = @file_get_contents ( $url, false, $sc );
//		$sorce = @file_get_contents ( $url );


pr( $http_response_header );


//		mb_language('Japanese');
//		mb_internal_encoding("UTF-8");
//pr(mb_internal_encoding());

		$sorce = mb_convert_encoding($sorce, 'UTF-8', 'UTF-8');
pr($sorce);

		$html = str_get_html( $sorce, "UTF-8", "UTF-8" );
//pr($html);
		$obj = $html->find( 'div.post' );		// 正しくアクセスできているか確認

//pr($html->outertext);

		$this->render('/acts/index');
	}




	// POST送信テスト
	public function test_post(){

		$this->layout="";

//		$url = 'http://www.php.net/search.php';
//		$url = 'http://www.hentai-hot.com/doujin/doujin-anime/8454-110629-gggyig-gggg-pggggggggggggvvgpgvgggg-p.html';
//		$url = 'http://dlhentai.com/%E7%90%B4%E5%90%B9%E3%81%8B%E3%81%A5%E3%81%8D-%E7%94%98%E9%9C%B2-kotobuki-kazuki-kanro/';
//		$url = 'http://share-films.net/hentai/hentai-game/game-doujin/%e5%a4%89%e6%85%8b%e5%8f%ac%e4%bd%bf%e3%82%a2%e3%83%86%e3%83%8a-%e3%81%8a%e3%81%95%e3%82%8f%e3%82%8a%e5%80%b6%e6%a5%bd%e9%83%a8-vol-2-hazi%e2%97%8boex-%e7%97%b4%e6%bc%a2%e9%81%8a%e6%88%af/';
//		$url = 'http://www.hentai-hot.com/index.php?do=search';
		$url = 'http://www.doujinblog.org/120921-miel-%E4%BF%BA%E3%81%9F%E3%81%A1%E3%81%AF%E7%A8%AE%E4%BB%98%E3%81%91%E6%8D%9C%E6%9F%BB%E5%AE%98%EF%BD%9E%E3%81%82%E3%81%AA%E3%81%9F%E5%A6%8A%E5%A8%A0%E3%81%97%E3%81%A6%E3%81%BE%E3%81%9B/';

		$data = array(
			'story' => 'マスクロ',
			'do' => 'search',
			'subaction' => 'search',
/*			'search_start' => '0',
			'full_search' => '0',
			'result_from' => '1'
*/
		);

		$data = http_build_query($data, "", "&");

		$headers = array(
//			"Content-Type: application/x-www-form-urlencoded",
//			"Content-Length: " . strlen($data)
		);


		$options = array('http' => array(
//			'method' => 'POST',
//			'content' => $data,
//			'header' => implode("\r\n", $headers),
		));
//		$contents = file_get_contents($url, false, stream_context_create($options));
		$contents = file_get_contents( $url );

//pr($contents);




$body_text = $contents;

$detail_url = $url;
		$base_url = 'http://www.doujinblog.org';

		$after_detail_url = str_replace( $base_url, '', $detail_url );
pr($after_detail_url);
		$pattern  = '/<a.*?href=\".*?';
		$pattern .= preg_quote( $after_detail_url, '/' );
		$pattern .= '.*?\".*?>/i';
pr('pattern: ' . $pattern);
//		$body_text = preg_replace($pattern, "", $body_text );


		// 自ドメインURLは<a>～</a>を空白で置換
		$pattern  = '/<a.*?href=.*?';
		$pattern .= preg_quote( $base_url, '/' );
		$pattern .= '.*?.*?>(.*?)<\/a>/i';
		$body_text = preg_replace($pattern, "", $body_text );
pr('pattern: ' . $pattern);
pr($body_text);

		// href=""でhttp://で始まらないものも空白で置換
		$body_text = preg_replace('/<a.*?href=\"\/(.*?)\".*?>(.*?)<\/a>/i', '', $body_text );

		// 全てのHTMLタグを除去
		$body_text = preg_replace('/<("[^"]*"|\'[^\']*\'|[^\'">])*>/','',$body_text);




		// href=""でhttp://で始まらないものも空白で置換



//$html = preg_replace('#(<a\s[^>]*?href\s*=["])(?!http)#i', '$1http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI']), $html) ; // 相対アドレス
//  return preg_replace('!(<a\s[^>]*?href\s*=["\']?)!i', "$1$appendix", $html) ;

		$ary = array();

		preg_match_all("/<a.*?href=\"(.*?)\".*?>(.*?)<\/a>/i", $contents, $ary );
//pr($ary);


		$this->render('/acts/index');
	}



	// 文字列テスト
	public function test_notice(){
		$this->layout="";

		$data = $this->User->get_notice_list();
//		$data = $this->Word->get_crl_words();
pr($data);


		foreach( $data as $user ){

			$mail_address = $user['mail_address'];
			unset( $user['mail_address'] );
			$text = '';

			foreach( $user as $word ){

				$search_word = $word['search_word'];
				unset( $word['search_word'] );

				$text .= "\r\n[" . $search_word . "]\r\n";

				foreach( $word as $site ){
					$text .= ' ' . $site['url'] . "\r\n";
				}
			}

pr($mail_address);
pr($text);
		}



		$this->render('/acts/index');
	}









	// prefecture_id生成
	public function test_clrw(){
		$this->layout="";

		$url = 'http://hentaiupdate.com/';
		$proxy = array(
			"http" => array(
				"proxy" => '106.187.37.110:3128',
				"request_fulluri" => true,
				"timeout" => '10',
			)
		);

		$sc = stream_context_create($proxy);
		$sorce = @file_get_contents ( $url, false, $sc );


		if( !empty($sorce) ){
pr($sorce);
		} else {
pr('NG!!!');
		}


		$this->render('/acts/index');
	}








	// prefecture_id生成
	public function test_c(){
		$this->layout="";


		$aryErr = $this->Crlsite->crawler_site( 'search' );
pr($aryErr);


		$this->render('/acts/index');
	}


	// prefecture_id生成
	public function test_s(){
		$this->layout="";

		$aryErr = $this->Crlsite->crawler_site();
pr($aryErr);

		$this->render('/acts/index');
	}


	// prefecture_id生成
	public function test_search(){
		$this->layout="";

		$aryErr = $this->Crlsite->crawler_searchbox();
pr($aryErr);

		$this->render('/acts/index');
	}



	// prefecture_id生成
	public function test_preg(){
		$this->layout="";

//		$this->Crlsite->crawler_site_atag(null);

		$val = 'http://dlhentai.com/%E3%81%BC%E3%81%A3%E3%81%97%E3%81%83-radical-gogo-baby/#mor';

		if( preg_match( "/http:\/\/dlhentai.com\/.*\/#more.*/", $val ) ){
			pr( $val );
		}

		$this->render('/acts/index');
	}


	// prefecture_id生成
	public function test_rpl(){
		$this->layout="";

		$url = 'http://dlhentai.com/%E3%81%BC%E3%81%A3%E3%81%97%E3%81%83-radical-gogo-baby/';
		$html = @file_get_contents( $url );
		$html = ereg_replace("\r|\n","",$html);
//str_get_html
		// 自身のアドレスは置換しない
		$html = str_replace( '<a href="http://dlhentai.com/%e3%81%bc%e3%81%a3%e3%81%97%e3%81%83-radical-gogo-baby/', '', $html );
//		$html = preg_replace( "/<a.*href=\"http:\/\/dlhentai\.com\/.*<\/a>?/", '', $html );	// 他詳細ページのアドレスを削除（成否判定のため）
		$html = preg_replace( "/<a.*?<\/a>/", '', $html );	// 他詳細ページのアドレスを削除（成否判定のため）
//		$html = preg_replace( "/<a(.*)<\/a>?/", '', $html );	// 他詳細ページのアドレスを削除（成否判定のため）


pr( $html );

		$this->render('/acts/index');
	}


}

?>