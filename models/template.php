<?php

class Template extends AppModel {
	var $name = 'Template';



	public function __construct() {
		$id = array("id" => false,
					"table" => "templates",
				   );
		parent::__construct($id);
		$this->primaryKey = "template_uid";
	}

	public function beforeFind(&$queryData) {

		return true;
	}

	public function afterFind($results) {

//		if ( !is_null($results) && !is_null($results[0]) ) {
//			if ( array_key_exists('Model', $results[0]) ) {
//
//			}
//		}

		return $results;
	}





	/**
	* inbox templateを置換して取得
	*
	* @param
	* @return
	*/
//	public function getTemplate( $template_uid, $user_uid, $upload_name, $upload_url ) {
	public function getTemplate( $template_uid, $user_uid, $is_site, $ds_site, $notfound_sitename ) {

		// アップロード名及びアップロードURLを整理
		$upload_name = '';
		$upload_url = '';
		$aryUploadUrl = array();		// add 2012.10.04

		if( $is_site !== false ){
			$upload_name = $is_site[0]['IRS']['site_name'];
			$upload_url = $is_site[0]['IR']['illegal_url'];
			$aryUploadUrl[0] = $upload_url;

			// 2012.12.3 add
			$word = $this->getWordData( 'is', $is_site[0]['IR']['illegal_result_uid'], $user_uid );

		} else if( $ds_site !== false ) {
			$upload_name = $ds_site[0]['DRS']['site_name'];
			$cnt = 0;
			foreach( $ds_site as $val ){

				if( $val['DR']['download_result_url'] === '' ){		// notFound経由で作られたダウンロードリザルトの場合
					$upload_url = '【アップロードされているURL】';
					break;
				}

				$upload_url .= $val['DR']['download_result_url'] . "\n";
				$aryUploadUrl[$cnt] = $val['DR']['download_result_url'];
				$cnt++;
			}

			// 2012.12.5 add
			$word = $this->getWordData( 'ds', $val['IR']['illegal_result_uid'], $user_uid );

		} else {		// 両方とも false の場合
			$upload_name = $notfound_sitename['name'];
			$upload_url = '【アップロードされているURL】';
			$aryUploadUrl[0] = $upload_url;

			// 2012.12.5 add
			$word = $this->getWordData( 'is', $notfound_sitename['iruid'], $user_uid );

		}


		$UserProfile = ClassRegistry::init('UserProfile');
		$prf = $UserProfile->find('first',array('conditions'=>array('user_uid'=>$user_uid)));
		$prf = $UserProfile->decryptedProf($prf);	// 復号化

		$template = $this->find('first',array( 'conditions'=>array('template_uid'=>$template_uid) ));


		// 件名置換
		$subject = $template['Template']['subject'];
		$subject = str_replace( '[[upload_site]]', $upload_name, $subject );							// [[$upload_name]]

		// 本文置換
		$body = $template['Template']['body_text'];
		$body = str_replace( '[[date]]', date("F j Y"), $body );										// [[date]]英語
		$body = str_replace( '[[date_jp]]', date("Y年 n月 j日"), $body );								// [[date_jp]]日本語
		$body = str_replace( '[[upload_site]]', $upload_name, $body );									// [[upload_site]]
		$body = str_replace( '[[upload_url]]', $upload_url, $body );									// [[upload_url]]


		// [[work_name]]
		if( $word['Word']['work_name'] === '' || $word['Word']['work_name'] == null ){
			$body = str_replace( '[[work_name_jp]]', '【あなたの作品名（日本語）】', $body );
		} else {
			$body = str_replace( '[[work_name_jp]]', $word['Word']['work_name'], $body );
		}

		// [[work_url_jp]]
		if( $word['Word']['work_url_jp'] === '' || $word['Word']['work_url_jp'] == null ){
			$body = str_replace( '[[work_url_jp]]', '【あなたの作品の詳細ページURL】', $body );
		} else {
			$body = str_replace( '[[work_url_jp]]', $word['Word']['work_url_jp'], $body );
		}

		// [[work_url_en]]	どーする？
		if( $word['Word']['work_url_en'] === '' || $word['Word']['work_url_en'] == null ){
			$body = str_replace( '[[work_url_en]]', '', $body );
		} else {
			$body = str_replace( '[[work_url_en]]', $word['Word']['work_url_en'], $body );
		}

		// [[creator_url]]
		if( $prf['UserProfile']['creator_url'] === '' || $prf['UserProfile']['creator_url'] == null ){
			$body = str_replace( '[[creator_url]]', '【サークル・ブランドのURL（英語）】', $body );
		} else {
			$body = str_replace( '[[creator_url]]', $prf['UserProfile']['creator_url'], $body );
		}

		// [[creator_name_jp]]
		if( $prf['UserProfile']['creator_name_jp'] === '' || $prf['UserProfile']['creator_name_jp'] == null ){
			$body = str_replace( '[[creator_name_jp]]', '【サークル・ブランド名称（日本語）】', $body );
		} else {
			$body = str_replace( '[[creator_name_jp]]', $prf['UserProfile']['creator_name_jp'], $body );
		}

		// [[creator_name_en]]
		if( $prf['UserProfile']['creator_name_en'] === '' || $prf['UserProfile']['creator_name_en'] == null ){
			$body = str_replace( '[[creator_name_en]]', '【サークル・ブランド名称（英語）】', $body );
		} else {
			$body = str_replace( '[[creator_name_en]]', $prf['UserProfile']['creator_name_en'], $body );
		}

		// [[representative_jp]]
		if( $prf['UserProfile']['representative_jp'] === '' || $prf['UserProfile']['representative_jp'] == null ){
			$body = str_replace( '[[representative_jp]]', '【サークル・ブランド代表者名（日本語）】', $body );
		} else {
			$body = str_replace( '[[representative_jp]]', $prf['UserProfile']['representative_jp'], $body );
		}

		// [[representative_en]]
		if( $prf['UserProfile']['representative_en'] === '' || $prf['UserProfile']['representative_en'] == null ){
			$body = str_replace( '[[representative_en]]', '【サークル・ブランド代表者名（英語）】', $body );
		} else {
			$body = str_replace( '[[representative_en]]', $prf['UserProfile']['representative_en'], $body );
		}

		// [[creator_address_jp]]
		if( $prf['UserProfile']['creator_address_jp'] === '' || $prf['UserProfile']['creator_address_jp'] == null ){
			$body = str_replace( '[[creator_address_jp]]', '【サークル・ブランド住所（日本語）】', $body );
		} else {
			$body = str_replace( '[[creator_address_jp]]', $prf['UserProfile']['creator_address_jp'], $body );
		}

		// [[creator_address_en]]
		if( $prf['UserProfile']['creator_address_en'] === '' || $prf['UserProfile']['creator_address_en'] == null ){
			$body = str_replace( '[[creator_address_en]]', '【サークル・ブランド住所（英語）】', $body );
		} else {
			$body = str_replace( '[[creator_address_en]]', $prf['UserProfile']['creator_address_en'], $body );
		}

		// [[mail_address]]
		if( $prf['UserProfile']['creator_mail_address'] === '' || $prf['UserProfile']['creator_mail_address'] == null ){
			$body = str_replace( '[[mail_address]]', '【サークル・ブランドのメールアドレス】', $body );
		} else {
			$body = str_replace( '[[mail_address]]', $prf['UserProfile']['creator_mail_address'], $body );
		}

		// [[tel_jp]]
		if( $prf['UserProfile']['creator_tel_jp'] === '' || $prf['UserProfile']['creator_tel_jp'] == null ){
			$body = str_replace( '[[tel_jp]]', '【サークル・ブランドの電話番号（国内向け）】', $body );
		} else {
			$body = str_replace( '[[tel_jp]]', $prf['UserProfile']['creator_tel_jp'], $body );
		}

		// [[tel_en]]
		if( $prf['UserProfile']['creator_tel_en'] === '' || $prf['UserProfile']['creator_tel_en'] == null ){
			$body = str_replace( '[[tel_en]]', '【サークル・ブランドの電話番号（海外向け）】', $body );
		} else {
			$body = str_replace( '[[tel_en]]', $prf['UserProfile']['creator_tel_en'], $body );
		}

		// [[following_site_en]]
		if( $ds_site !== false ){
			$buf  = "They're linked from following.\n" . $ds_site[0]['IR']['illegal_url'] . "\n";
			$body = str_replace( '[[following_site_en]]', $buf, $body );
		} else {
			$body = str_replace( '[[following_site_en]]', '', $body );
		}



		$template['Template']['subject'] = $subject;
		$template['Template']['body_text'] = $body;
		$template['Template']['aryUploadUrl'] = $aryUploadUrl;

		return $template;
	}





	/**
	* dmca templateのセレクトボックスのアイテムを取得
	*
	* @param
	* @return
	*/
	public function getSelect( $mode = false, $uid = false ) {

		// 将来的には最適なテンプレをアサインする感じにしたい
		$data = $this->find('all', array('fields'=>array('template_uid','template_name'), 'order'=>array('template_uid ASC')));
		return $data;


	}


	/**
	* テンプレートで置換する作品毎の情報を取得（作品名・作品URL）
	*
	* @param
	* @return word
	*/
	private function getWordData( $mode, $result_uid, $user_uid ) {

		$Word = ClassRegistry::init('Word');
		$IllegalResult = ClassRegistry::init('IllegalResult');

//		if( $mode === 'is' ){

			$ir = $IllegalResult->find('first',array('conditions'=>array('illegal_result_uid'=>$result_uid)));

			$sql = 'SELECT ';
			$sql.= 'Word.word_uid,';
			$sql.= 'Word.search_word,';
			$sql.= 'Word.work_name,';
			$sql.= 'Word.work_url_jp,';
			$sql.= 'Word.work_url_en';
			$sql.= ' FROM ';
			$sql.= 'illegal_results AS IR ';
			$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = IR.word_uid ';
			$sql.= 'WHERE ';
			$sql.= 'IR.illegal_url = "'. $ir['IllegalResult']['illegal_url'] .'" ';
			$sql.= 'AND Word.user_uid = "'. $user_uid .'" ';
			$data = $this->query( $sql );

//		} else if( $mode === 'ds' ){
/*
			// 全件取得L
			$sql = 'SELECT ';
			$sql.= 'Word.word_uid,';
			$sql.= 'Word.search_word,';
			$sql.= 'Word.work_name,';
			$sql.= 'Word.work_url_jp,';
			$sql.= 'Word.work_url_en';
			$sql.= ' FROM ';
			$sql.= 'download_results AS DR ';
			$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = DR.word_uid ';
			$sql.= 'WHERE ';
			$sql.= 'DR.download_result_uid = "'. $result_uid .'" ';
			$sql.= 'AND Word.user_uid = "'. $user_uid .'" ';
			$data = $this->query( $sql );
*/
//		}


		// データ整形
		$res = array();
		$icnt = 0;
		foreach( $data as $key=>$val ){

			if( $icnt == 0 ){
				$res['Word']['work_name']	= $val['Word']['work_name'];
				$res['Word']['work_url_jp']	= $val['Word']['work_url_jp'];
				$res['Word']['work_url_en']	= $val['Word']['work_url_en'];
			} else {
				if( ($val['Word']['work_url_jp']!='' && $val['Word']['work_url_jp']!=null) || ($val['Word']['work_url_en']!='' && $val['Word']['work_url_en']!=null) ){
					$res['Word']['work_name']	= $val['Word']['work_name'];
					$res['Word']['work_url_jp']	= $val['Word']['work_url_jp'];
					$res['Word']['work_url_en']	= $val['Word']['work_url_en'];
				}
			}
			$icnt++;
		}

		// 復号化
		$res = $Word->decryptedWord( $res );

		return $res;
	}




}

?>