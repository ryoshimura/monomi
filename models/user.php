<?php

class User extends AppModel {
	var $name = 'User';

//	var $validate = array(
//				'email' => array(
//					'rule' => array('email', true),
//					'message' => 'メールアドレスを正しく入力してください。'
//				),
//			    'password' => array(
//					'rule' => array('minLength', '8'),
//					'message' => 'Mimimum 8 characters long'
//				)
//			);

//	var $hasOne = array(
//			'Translator' => array(
//				  'className'   => 'Translator'
//				, 'order'       => ''
//				, 'foreignKey'  => 'user_uid'
//				, 'fields'      => array('Translator.translator_id', 'Translator.name')
//				, 'conditions'  => ''
//				, 'dependent'   => true
//			),
//			'RoleRelation' => array(
//				  'className'   => 'RoleRelation'
//				, 'foreignKey'  => 'user_uid'
//				, 'fields'      => array('RoleRelation.role_id as role_id')
//				, 'conditions'  => ''
//				, 'dependent'   => true
//			)
//	);

	public $validate = array(
			    'passwd' => array(
					array(
						'rule' => array('between', 4, 200),
						'required'	=> true,
						'message' => 'パスワードは4文字以上の半角英数字で入力してください'
					),
					array(
						'rule' => 'alphaNumeric',
						'required'	=> true,
						'message' => 'パスワードは4文字以上の半角英数字で入力してください'
					)
				),
			    'passwd_confirm' => array(
					'rule' => array('checkCompare'),
					'required'	=> true,
					'message' => '同じパスワードを入力してくだい'
				),
				'mail_address' => array(
					array(
						'rule' => array('email', false),
						'required'	=> true,
						'message' => '正しいメールアドレスを入力してくだい'
					),
//					array(
//						'rule' => 'isUnique',
//						'required'	=> true,
//						'message' => 'このメールアドレスは既に使われているため、登録できません'
//					),
					array(
						'rule' => array('checkCompareMail'),
						'required'	=> true,
						'message' => 'このメールアドレスは既に使われているため、登録できません'
					)
				)
			);

	public function __construct() {
		$id = array("id" => false,
					"table" => "users",
				   );
		parent::__construct($id);
		$this->primaryKey = "user_uid";
	}







	public function beforeFind(&$queryData) {

		return true;
	}

	public function afterFind($results) {

		return $results;
	}

	public function hashPasswords($data) {

//pr($data);
		if (is_array($data)) {
//pr($data);

			if(isset($data['User'])) {
				if (isset($data['User']['mail_address']) && !empty($data['User']['mail_address']) ) {
//					$data['User']['mail_address'] = base64_encode($this->encryptedData($data['User']['mail_address'], "cdbcuhxas"));
					$data['User']['mail_address'] = base64_encode($this->encryptedData($data['User']['mail_address'], CERT_KEY));
				}

				if (isset($data['User']['passwd']) && !empty($data['User']['passwd']) ) {
//					$data['User']['passwd'] = base64_encode($this->encryptedData($data['User']['passwd'], "cdbcuhxas"));
					$data['User']['passwd'] = base64_encode($this->encryptedData($data['User']['passwd'], CERT_KEY));
				}
			}

		}

		return $data;
	}



	/*
	 * Userモデルの復号化
	 *
	 */
	public function decryptedUser($data) {

		if (is_array($data)) {

			if(isset($data['User'])) {
				if (isset($data['User']['mail_address']) && !empty($data['User']['mail_address']) ) {
					$data['User']['mail_address'] = $this->decryptedData( $data['User']['mail_address'] );
				}
				if (isset($data['User']['passwd']) && !empty($data['User']['passwd']) ) {
					$data['User']['passwd'] = $this->decryptedData( $data['User']['passwd'] );
				}
			}
		}

		return $data;
	}

	/** 復号化 */
	public function decryptedData($input) {

		$key = CERT_KEY;
		$input = base64_decode( $input );

		/* モジュールをオープンし、IV を作成します */
		$td = mcrypt_module_open('des', '', 'ecb', '');

//		$key = substr($key, 0, mcrypt_enc_get_key_size($td));
		$key = substr(md5($key), 0, mcrypt_enc_get_key_size($td));

		$iv_size = mcrypt_enc_get_iv_size($td);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
//		$iv = substr(md5($key), 0, $iv_size);

		/* 復号のため、バッファを再度初期化します */
		mcrypt_generic_init($td, $key, $iv);
		$p_t = mdecrypt_generic($td, $input);

		/* 後始末をします */
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		return rtrim($p_t,"\0");
	}


	/** 暗号化 */
	public function encryptedData($input, $key) {

    /* モジュールをオープンし、IV を作成します */
    $td = mcrypt_module_open('des', '', 'ecb', '');
//pr('td: ' . $td);
//	$key = substr($key, 0, mcrypt_enc_get_key_size($td));
	$key = substr(md5($key), 0, mcrypt_enc_get_key_size($td));
//pr('key: ' . $key);


    $iv_size = mcrypt_enc_get_iv_size($td);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
//	$iv = substr(md5($key), 0, $iv_size);
//pr('iv: ' . $iv);

    /* 暗号化ハンドルを初期化します */
	mcrypt_generic_init($td, $key, $iv);

	/* データを暗号化します */
	$c_t = mcrypt_generic($td, $input);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);

		return $c_t;
	}



	/** 復号化 */
	public function ___decryptedData($input, $key) {
//pr('復号化！！');
		$td = mcrypt_module_open(MCRYPT_TRIPLEDES,'','cbc','');
		// key（最大キー長に）
		$ks = mcrypt_enc_get_key_size($td);
		$key = substr(md5($key), 0, $ks);

		// iv
		$ivsize = mcrypt_enc_get_iv_size($td);
		$iv = substr(md5($key), 0, $ivsize);



		mcrypt_generic_init($td, $key, $iv);
		$decrypted_data = mdecrypt_generic($td, $input);


		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		//return $decrypted_data;
		return rtrim($decrypted_data,"\0");
	}

	/** 暗号化 */
	public function ___encryptedData($input, $key) {
//pr($input);
		$td = mcrypt_module_open(MCRYPT_TRIPLEDES,'','cbc','');

		// key（最大キー長に）
		$ks = mcrypt_enc_get_key_size($td);
		$key = substr(md5($key), 0, $ks);

		// iv
		$ivsize = mcrypt_enc_get_iv_size($td);
		$iv = substr(md5($key), 0, $ivsize);

		//暗号と復号が同じKeyじゃないとダメみたい。
		//srand();
		//$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

		mcrypt_generic_init($td, $key, $iv);
		$encrypted_data = mcrypt_generic($td, $input);

		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return $encrypted_data;
	}

	/*
	 * 2010.12.29 add
	 * 確認入力用バリデーションメソッド
	 */
	function checkCompare($field) {
		foreach( $field as $key => $value ){
			if (preg_match('/^(.+)_confirm$/', $key, $regs)) {
				return $this->data[$this->name][$regs[1]] == $this->data[$this->name][$key];
		    }
		}
	}



	/*
	 * 2012.10.14 add
	 * 重複メールアドレス確認（暗号化にともない追加）
	 */
	function checkCompareMail($field) {

		foreach( $field as $key => $value ){

			if( $key === 'mail_address' ){

				$user['User']['mail_address'] = $value;
				$user = $this->hashPasswords( $user );

				if( false === $this->find('first',array('conditions'=>array('mail_address'=>$user['User']['mail_address']))) ){
					return true;
				} else {
					return false;
				}
			}


//			if (preg_match('/^(.+)_confirm$/', $key, $regs)) {
//				return $this->data[$this->name][$regs[1]] == $this->data[$this->name][$key];
//		    }
		}

	}







	/*
	 * プロフィール変更画面専用バリデーション
	 *
	 */
	function profile_validate( $ldata, $user ) {

		$res = array();

		// 2012.10.15 add $ldataを復号
		$data = $this->decryptedUser($ldata);


		if( '' === $ldata['User']['mail_address'] && '' === $ldata['User']['passwd'] && '' === $ldata['User']['passwd_confirm'] ){
			return $res;
		}

		// メールアドレス
		if( '' !== $ldata['User']['mail_address'] && $ldata['User']['mail_address'] !== $user['User']['mail_address'] ){

			if ( !preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $data['User']['mail_address'])) {
				$res['mail_address'] = '正しいメールアドレスを入力してください';
			} else {
				if( false !== $this->find('first', array('conditions'=>array('mail_address'=>$ldata['User']['mail_address']))) ){
					$res['mail_address'] = 'このメールアドレスは既に使われているため、登録できません';
				}
			}
		}

		// パスワード
		if( '' !== $ldata['User']['passwd'] ||  '' !== $ldata['User']['passwd_confirm'] ){



			if( !preg_match('/^[\w]+$/', $data['User']['passwd'] ) || 4 > strlen( $data['User']['passwd'] ) ){
					$res['passwd'] = 'パスワードは4文字以上の半角英数字で入力してください';
			}

			if( $data['User']['passwd'] !== $data['User']['passwd_confirm'] ){
					$res['passwd_confirm'] = '同じパスワードを入力してくだい';
			}

		}


		if( empty($res) ){
			return true;
		} else {
			return $res;
		}

	}



	/*
	 * 不正サイト検知用メール送信リスト生成メソッド
	 *
	 */
	public function get_notice_list() {

		$Word = ClassRegistry::init('Word');
		$IllegalResult = ClassRegistry::init('IllegalResult');


		$sql = 'SELECT ';
		$sql.= 'User.user_uid, ';
		$sql.= 'User.mail_address, ';
		$sql.= 'User.payment_status, ';
//		$sql.= 'Word.word_uid, ';
//		$sql.= 'Word.search_word, ';
		$sql.= 'IR.illegal_result_uid ';
//		$sql.= 'IR.illegal_url, ';
//		$sql.= 'IRS.site_name ';
		$sql.= 'FROM ';
		$sql.= 'illegal_results AS IR ';
		$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = IR.word_uid ';
		$sql.= 'LEFT JOIN users AS User ON User.user_uid = Word.user_uid ';
//		$sql.= 'LEFT JOIN illegal_sites AS IRS ON IRS.site_uid = IR.site_uid ';
//		$sql.= 'LEFT JOIN result_relations AS RR ON RR.illegal_result_uid = IR.illegal_result_uid ';
//		$sql.= 'LEFT JOIN download_results AS DR ON RR.download_result_uid = DR.download_result_uid ';
		$sql.= 'WHERE ';
		$sql.= 'IR.trash = 0 ';
		$sql.= 'AND IR.deleted = 0 ';
		$sql.= 'AND IR.notice = 0 ';				// 未通知のリザルト
		$sql.= 'AND Word.deleted = 0 ';
		$sql.= 'AND User.payment_status != "0" ';	// 契約無効以外
		$sql.= 'ORDER BY ';
		$sql.= 'User.user_uid ASC' ;
		$rcd = $this->query( $sql );


		// データ整形
		$data = array();
		$initial_flag = true;
		$i = 0;
		$j = 0;
		$k = 0;
		foreach( $rcd as $key => $val ){

			if( $initial_flag == true ){
				$buf = array();
				$buf['User'] = $val['User'];
				$buf = $this->decryptedUser( $buf );
				$data[$i]['mail_address'] = $buf['User']['mail_address'];
				$data[$i]['payment_status'] = $val['User']['payment_status'];
				$data[$i]['ir_count'] = 1;
				$initial_flag = false;

			} else {
				// user_uidが直前レコードと異なったら
				if( $val['User']['user_uid'] !== $rcd[$key-1]['User']['user_uid'] ){	// 直前の配列のuser_uidと違う場合
					$i++;
					$buf = array();
					$buf['User'] = $val['User'];
					$buf = $this->decryptedUser( $buf );
					$data[$i]['mail_address'] = $buf['User']['mail_address'];
					$data[$i]['payment_status'] = $val['User']['payment_status'];
					$data[$i]['ir_count'] = 1;

				} else {
					$data[$i]['ir_count'] += 1;
				}
			}

			// メール通知フラグを1に
			$save = array();
			$save['IllegalResult']['illegal_result_uid'] = $val['IR']['illegal_result_uid'];
			$save['IllegalResult']['notice'] = 1;

			$IllegalResult->save( $save, false );
		}


		return $data;
	}




	/*
	 * 不正サイト検知用メール送信リスト生成メソッド
	 *
	 */
	public function old_get_notice_list() {

		$Word = ClassRegistry::init('Word');
		$IllegalResult = ClassRegistry::init('IllegalResult');


		$sql = 'SELECT ';

		$sql.= 'User.user_uid, ';
		$sql.= 'User.mail_address, ';
		$sql.= 'User.payment_status, ';
		$sql.= 'Word.word_uid, ';
		$sql.= 'Word.search_word, ';
		$sql.= 'IR.illegal_result_uid, ';
		$sql.= 'IR.illegal_url, ';
		$sql.= 'IRS.site_name ';
		$sql.= 'FROM ';
		$sql.= 'illegal_results AS IR ';
		$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = IR.word_uid ';
		$sql.= 'LEFT JOIN users AS User ON User.user_uid = Word.user_uid ';
		$sql.= 'LEFT JOIN illegal_sites AS IRS ON IRS.site_uid = IR.site_uid ';
//		$sql.= 'LEFT JOIN result_relations AS RR ON RR.illegal_result_uid = IR.illegal_result_uid ';
//		$sql.= 'LEFT JOIN download_results AS DR ON RR.download_result_uid = DR.download_result_uid ';
		$sql.= 'WHERE ';
		$sql.= 'IR.trash = 0 ';
		$sql.= 'AND IR.deleted = 0 ';
		$sql.= 'AND IR.notice = 0 ';				// 未通知のリザルト
		$sql.= 'AND Word.deleted = 0 ';
		$sql.= 'AND User.payment_status != "0" ';	// 契約無効以外
//		$sql.= 'GROUP BY User.user_uid ';	// 契約無効以外
		$sql.= 'ORDER BY ';
		$sql.= 'User.user_uid ASC, Word.word_uid ASC, IRS.site_name ASC, IR.illegal_url ASC' ;

		$rcd = $this->query( $sql );

//pr($rcd);

		// 復号化
		foreach($rcd as $k=>$v){
			$buf = array();
			$buf['Word'] = $v['Word'];
			$buf = $Word->decryptedWord( $buf );
			$rcd[$k]['Word'] = $buf['Word'];

			$buf = array();
			$buf['User'] = $v['User'];
			$buf = $this->decryptedUser( $buf );
			$rcd[$k]['User'] = $buf['User'];
		}

//pr($rcd);



		// データ整形
		$data = array();
		$initial_flag = true;
		$i = 0;
		$j = 0;
		$k = 0;
		foreach( $rcd as $key => $val ){

			if( $initial_flag == true ){
				$data[$i]['mail_address'] = $val['User']['mail_address'];
				$data[$i]['payment_status'] = $val['User']['payment_status'];
				$data[$i][$j]['search_word'] = $val['Word']['search_word'];
				$initial_flag = false;

			} else {
				// user_uidが直前レコードと異なったら
				if( $val['User']['user_uid'] !== $rcd[$key-1]['User']['user_uid'] ){	// 直前の配列のメアドと違う場合
					$i++;
					$j = 0;
					$data[$i]['mail_address'] = $val['User']['mail_address'];
					$data[$i]['payment_status'] = $val['User']['payment_status'];
				}

				// word_uidが直前レコードと異なったら
				if( $val['Word']['word_uid'] !== $rcd[$key-1]['Word']['word_uid'] ){	// 直前の配列のワードと違う場合
					$j++;
					$k = 0;
					$data[$i][$j]['search_word'] = $val['Word']['search_word'];
				}
			}

			$data[$i][$j][$k]['url']	= $val['IR']['illegal_url'];
			$data[$i][$j][$k]['name']	= $val['IRS']['site_name'];

			$k++;

			// メール通知フラグを1に
			$save = array();
			$save['IllegalResult']['illegal_result_uid'] = $val['IR']['illegal_result_uid'];
			$save['IllegalResult']['notice'] = 1;

			$IllegalResult->save( $save, false );
		}


		return $data;

	}




}

?>