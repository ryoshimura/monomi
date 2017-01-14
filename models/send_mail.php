<?php

class SendMail extends AppModel {
	var $name = 'SendMail';



	public function __construct() {
		$id = array("id" => false,
					"table" => "send_mails",
				   );
		parent::__construct($id);
		$this->primaryKey = "sendmail_uid";
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
	 * SendMailモデルの暗号化
	 *
	 */
	public function encryptedWord($data) {

		if( isset($data['SendMail']) ){
			foreach( $data['SendMail'] as $key=>$val ){
				if( $key === 'from_address' ){
					if( $val !== '' && $val !== null ){
						$data['SendMail'][$key] = $this->encryptedData( $val );
					}
				}
			}
		}

		return $data;
	}


	/*
	 * SendMailモデルの復号化
	 *
	 */
	public function decryptedWord($data) {

		if( isset($data['SendMail']) ){
			foreach( $data['SendMail'] as $key=>$val ){
				if( $key === 'from_address' ){
					if( $val !== '' && $val !== null ){
						$data['SendMail'][$key] = $this->decryptedData( $val );
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



}

?>