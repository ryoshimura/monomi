<?php

class Word extends AppModel {
	var $name = 'Word';

	public function __construct() {
		$id = array("id" => false,
					"table" => "words",
				   );
		parent::__construct($id);
		$this->primaryKey = "word_uid";
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



	/*
	 * Wordモデルの暗号化
	 *
	 */
	public function encryptedWord($data) {

		if( isset($data['Word']) ){
			foreach( $data['Word'] as $key=>$val ){
				if( $key === 'search_word' || $key === 'work_name' || $key === 'work_url_jp' || $key === 'work_url_en' ){
					if( $val !== '' && $val !== null ){
						$data['Word'][$key] = $this->encryptedData( $val );
					}
				}
			}
		}

		return $data;
	}


	/*
	 * Wordモデルの復号化
	 *
	 */
	public function decryptedWord($data) {

		if( isset($data['Word']) ){
			foreach( $data['Word'] as $key=>$val ){
				if( $key === 'search_word' || $key === 'work_name' || $key === 'work_url_jp' || $key === 'work_url_en' ){
					if( $val !== '' && $val !== null ){
						$data['Word'][$key] = $this->decryptedData( $val );
					}
				}
			}
		}

		return $data;
	}


	/** 復号化 */
	public function decryptedData($input) {
//pr($input);
		$key = CERT_KEY;
		$input = base64_decode( $input );

		/* モジュールをオープンし、IV を作成します */
		$td = mcrypt_module_open('des', '', 'ecb', '');

		$key = substr(md5($key), 0, mcrypt_enc_get_key_size($td));

		$iv_size = mcrypt_enc_get_iv_size($td);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

		/* 復号のため、バッファを再度初期化します */
		mcrypt_generic_init($td, $key, $iv);
		$p_t = mdecrypt_generic($td, $input);

		/* 後始末をします */
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		return rtrim($p_t,"\0");
	}


	/** 暗号化 */
	public function encryptedData($input) {

		$key = CERT_KEY;

		/* モジュールをオープンし、IV を作成します */
		$td = mcrypt_module_open('des', '', 'ecb', '');
		$key = substr(md5($key), 0, mcrypt_enc_get_key_size($td));

		$iv_size = mcrypt_enc_get_iv_size($td);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

		/* 暗号化ハンドルを初期化します */
		mcrypt_generic_init($td, $key, $iv);

		/* データを暗号化します */
		$c_t = mcrypt_generic($td, $input);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		return base64_encode( $c_t );
	}














	public function alltrim($str){
//		$str = mb_ereg_replace("^[[:space:]]+", "", $str);
//		$str = mb_ereg_replace("[[:space:]]+$", "", $str);

		$str = preg_replace("/^[ 　]+/u","",$str);
		$str = preg_replace("/[ 　]+$/u","",$str);
		return $str;
	}


	public function get_crl_words( $newWordFlag = false ){

		// スクレイピングするワードを抽出
		$sql = 'SELECT * ';
		$sql.= 'FROM ';
		$sql.= 'words AS Word ';
		$sql.= 'LEFT JOIN users AS User ON User.user_uid = Word.user_uid ';
		$sql.= 'WHERE ';
		$sql.= 'Word.deleted = 0 ';
		$sql.= 'AND Word.search_word != "" ';
		$sql.= 'AND Word.search_word IS NOT NULL ';		// 2012.12.05 add
		$sql.= 'AND User.payment_status != "0" ';	// 契約無効以外
		$sql.= 'AND User.period_status = "1" ';	// 契約期間内
		$sql.= 'AND User.deleted = 0 ';	// 契約無効以外

		if( $newWordFlag == true ){
			$time = date("Y-m-d H:i:s",strtotime("-90 minute"));	// 1時間前に登録されたワードのみ抽出
			$sql.= 'AND Word.created >= "'. $time .'" ';
		}

		$res = $this->query( $sql );


		// 復号化
		$data = array();
		foreach( $res as $val ){
//			$data[] = $this->decryptedWord($val);
			$word = $this->decryptedWord($val);
			if( 2 < strlen($word['Word']['search_word']) && 1 < mb_strlen($word['Word']['search_word']) ){		// 文字数チェック 2byte（半角2文字）以内ならスルー
				$data[] = $word;
			}
		}



		return $data;
	}


}

?>