<?php

/**
* ホーム関連 コントローラー
*
* @package   パッケージ名
* @author    著作者 <著作者メール>
* @since     PHP 5.0
* @version $Id: DB.php,v 1.58 2004/03/13 16:17:19 danielc Exp $
*/
class RegistController extends AppController {

	var $name       = 'Regist';                     // $nameを設定することでcontroller名を任意に設定することができる。
	var $components = array( 'Email', 'Cookie', 'Session', 'Security' );     // 使用するcomponentを指定する。
	var $layout     = 'base_layout';               // 使用するレイアウト
	var $uses       = array( 'User', 'Message', 'Paypal', 'Regist', 'Payment' );    	 // 使用するmodelを指定する。デフォルトmodelは必ず配列の先頭に記述する。
	var $helpers	= array( 'Form', 'Exform' );		// 拡張formHelper（datetime日本語版）を利用


	/**
	* action,render前実行関数
	*
	* @param  none
	* @return none
	*/
	public function beforeFilter() {
		parent::beforeFilter();

//		$this->Auth->allow('bregist', 'htregist');

//		$this->redirect('/homes/maintenance/');


		// Security(SSL)コンポーネント

		if (preg_match ('|workspace|',ROOT)){ //テスト環境
		} else {
			$this->Security->blackHoleCallback = '_sslFail';
		    $this->Security->requireSecure();	// 強制SSLを中断するには、この行をコメントアウト
		}
	}



	/**
	* _sslFail
	*
	* @param
	* @return none
	*/
	function _sslFail() {
	    $this->redirect("https://".env('SERVER_NAME').$this->here);
	}



	/**
	* index
	*
	* @param  none
	* @return none
	*/
	public function index() {

	}





	/**
	* 監視プラン申込
	*
	* @param  none
	* @return none
	*/
	public function form() {

		Configure::write('debug', 2);	// テストのため一時的に

		$flag_agreement = '';
		$flag_email = '';
		if ( $this->RequestHandler->isPost() ) {

			$data['Regist'] = $this->data['User'];
			$user['User'] = $this->data['User'];
			$user = $this->User->hashPasswords($user);

			$this->Regist->set( $data );
			$this->Regist->validates();


			// 利用規約同意の確認
			if( $user['User']['agreement'] != 1 ){
				$flag_agreement = 'notAgreement';
			}

			// 同じメールアドレスが存在するか確認（同じメールアドレスがあっても、仮会員であればOKとする）
			if( false != $user_buf = $this->User->find('first',array('conditions'=>array('mail_address'=>$user['User']['mail_address']))) ){
				if( $user_buf['User']['user_status'] == 1 ){
					$flag_email = 'isnotUniq';
				}
			}


			if( $this->Regist->validates() && $user['User']['agreement'] == 1 ){

				$this->Session->write( 'form_data', $this->data );	// セッションにデータを保存
				$this->redirect(array('controller' => 'regist', 'action' => 'confirm'));	// 確認画面へリダイレクト

			}

		}

		$this->set('agreement', $flag_agreement );
		$this->set('email', $flag_email );

	}



	/**
	* 監視プラン申込確認
	*
	* @param  none
	* @return none
	*/
	public function confirm() {

		Configure::write('debug', 2);	// テストのため一時的に

		$returnURL = 'https://monomi.info/regist/thanks/';
		$cancelURL = 'https://monomi.info/regist/cancel/';

		if ( $this->RequestHandler->isPost() ) {

			$save_payment = array();
			if( $this->data['User']['plan'] === 'w2p700' ){

				$amount = 700;
				$save_payment['Payment']['product_name']	= '監視30日間ワード数2個700円';
				$save_payment['Payment']['term']			= 30;
				$save_payment['Payment']['current_volume']	= 2;
				$save_payment['Payment']['payment_amount']	= $amount;

			} else if( $this->data['User']['plan'] === 'w3p900' ){

				$amount = 900;
				$save_payment['Payment']['product_name']	= '監視30日間ワード数3個900円';
				$save_payment['Payment']['term']			= 30;
				$save_payment['Payment']['current_volume']	= 3;
				$save_payment['Payment']['payment_amount']	= $amount;

			}


			// PayPal SetExpressCheckoutでtoken取得
			$exChkOut = $this->Paypal->getToken( $amount, $returnURL, $cancelURL );
//pr($exChkOut);
			$ack = strtoupper($exChkOut["ACK"]);
			if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING") {		// チェックアウト成功

				// User登録
				$user = $this->User->hashPasswords($this->data);	// 暗号化
				if( false != $user_save = $this->User->find('first',array('conditions'=>array('mail_address'=>$user['User']['mail_address']))) ){
					$user_save['User']['passwd']		= $user['User']['passwd'];
					$user_save['User']['user_status']	= 0;	// 仮登録
					$user_save['User']['payment_status']= 0;	// 未契約
					$user_save['User']['current_volume']= $save_payment['Payment']['current_volume'];	// ワード数
					$user_save['User']['deleted']		= 0;	// 削除フラグ解除

				} else {
					$user_save = array();
					$user_save['User']['mail_address']	= $user['User']['mail_address'];
					$user_save['User']['passwd']		= $user['User']['passwd'];
					$user_save['User']['user_status']	= 0;	// 仮登録
					$user_save['User']['payment_status']= 0;	// 未契約
					$user_save['User']['current_volume']= $save_payment['Payment']['current_volume'];	// ワード数
					$this->User->create();
				}
//				$this->User->save( $user_save, false );
				$user = $this->User->find('first',array('conditions'=>array('mail_address'=>$user['User']['mail_address'])));

				// Payment登録
				$save_payment['Payment']['user_uid'] = $user['User']['user_uid'];
				$save_payment['Payment']['paypal_token'] = $exChkOut['TOKEN'];
				$save_payment['Payment']['payerid'] = $exChkOut['CORRELATIONID'];
				$this->Payment->create();
//				$this->Payment->save($save_payment, false);

				$this->Paypal->RedirectToPayPal( $exChkOut['TOKEN'] );	// ペイパル決済へリダイレクト

			} else {	// チェックアウト失敗
				$this->redirect(array('controller' => 'regist', 'action' => 'paypal_error'));	// ペイパルエラー画面へリダイレクト
			}

		}


		// セッション
		$data = $this->Session->read( 'form_data' );	// セッション読込
		if( $data === false ){	// セッション切れの場合
			$this->set('session', false );
		} else {
			$this->set('data', $data );
		}

		$this->set('data', $data );

	}



	/**
	* 監視プラン申込完了
	*
	* @param  none
	* @return none
	*/
	public function thanks() {

		Configure::write('debug', 2);	// テストのため一時的に

		// ペイパルから値を取得
		if (isset($_REQUEST['token'])) {
			$token = $_REQUEST['token'];
		} else {
//			$this->redirect(array('controller' => 'regist', 'action' => 'paypal_error'));	// ペイパルエラー画面へリダイレクト
		}

pr($_REQUEST);

		if ( $token != "" ) {

			$resArray = $this->Paypal->GetShippingDetails( $token );
			$ack = strtoupper($resArray["ACK"]);
			if( $ack == "SUCCESS" || $ack == "SUCESSWITHWARNING") {

pr($resArray);
				// User更新
				// Payment更新
				// 自動返信メール


			} else {
pr('false');
				$this->redirect(array('controller' => 'regist', 'action' => 'paypal_error'));	// ペイパルエラー画面へリダイレクト
			}

		} else {
pr('false');
			$this->redirect(array('controller' => 'regist', 'action' => 'paypal_error'));	// ペイパルエラー画面へリダイレクト

		}

	}




	/**
	* 監視プラン申込キャンセル
	*
	* @param  none
	* @return none
	*/
	public function cancel() {

	}






	/**
	* 監視プラン申込 ペイパルエラー
	*
	* @param  none
	* @return none
	*/
	public function paypal_error() {

	}



	/**
	* 仮会員登録 体験版
	*
	* @param  none
	* @return none
	*/
	public function tregist() {

		$flag_agreement = '';

		if (!empty($this->data)) {

			$user = $this->data;
			$this->User->set( $user );

			// 利用規約同意の確認
			if( $user['User']['agreement'] != 1 ){
				$flag_agreement = 'notAgreement';
			}


			if( $this->User->validates() && $user['User']['agreement'] == 1 ){

				$this->User->create();
				$user['User']['payment_status'] = '2';	// ペイメントステータスを体験版「2」を設定
				$user['User']['hash_key'] = md5(uniqid(rand(),1));	// 仮登録用ハッシュキーを生成
				$user['User']['hash_create_time'] = date("Y-m-d H:i:s");	// 仮登録用ハッシュキーを生成
				$user =  $this->User->hashPasswords($user);

				$this->User->save( $user, false );

				$user = $this->User->decryptedUser($user);			// 復号化

				// 仮登録完了メールを送信
				$this->sendMail_client_kari( $user, 'trial' );

				// リダイレクト
				$this->redirect('/regist/pthanks/trial/');
			}
		}

		$this->set('agreement', $flag_agreement );
	}





	/**
	* 仮会員登録 ベータテスト版
	*
	* @param  none
	* @return none
	*/
	public function bregist() {

		$flag_agreement = '';

		if (!empty($this->data)) {

			$user = $this->data;
			$this->User->set( $user );

			// 利用規約同意の確認
			if( $user['User']['agreement'] != 1 ){
				$flag_agreement = 'notAgreement';
			}

			if( $this->User->validates() && $user['User']['agreement'] == 1 ){

				$this->User->create();
				$user['User']['payment_status'] = '9';	// ペイメントステータスを体験版「2」を設定
				$user['User']['hash_key'] = md5(uniqid(rand(),1));	// 仮登録用ハッシュキーを生成
				$user['User']['hash_create_time'] = date("Y-m-d H:i:s");	// 仮登録用ハッシュキーを生成
				$user =  $this->User->hashPasswords($user);			// 暗号化

				$this->User->save( $user, false );

				$user = $this->User->decryptedUser($user);			// 復号化

				// 仮登録完了メールを送信
				$this->sendMail_client_kari( $user, 'beta' );

				// リダイレクト
				$this->redirect('/regist/pthanks/beta/');
			}
		}

		$this->set('agreement', $flag_agreement );
	}




	/**
	* 仮会員登録 完了
	*
	* @param  none
	* @return none
	*/
	public function pthanks() {

		// 第１パラメータ
		$mode = 'standard';
		if( isset($this->params['pass'][0]) ){		// パラメータが存在しない場合、TOPへリダイレクト
			$mode = $this->params['pass'][0];
		}

		$this->set( 'mode',$mode );
	}


	/**
	* 本会員登録＋本登録thanks 体験版・ベータ版
	*
	* @param  none
	* @return none
	*/
	public function htregist() {

		$view_flag = 'limitover';
		$mode = '';

		// 第１パラメータ
		if( isset($this->params['pass'][0]) ){		// パラメータが存在しない場合、TOPへリダイレクト

			$hash_key = $this->params['pass'][0];
			$time = date("Y-m-d H:i:s",strtotime("-24 hour"));
//pr($time);
//			if( false !== $user = $this->User->find('first', array('conditions'=>array('hash_key'=>$hash_key, 'hash_create_time >='=>$time ))) ){
			if( false !== $user = $this->User->find('first', array('conditions'=>array('hash_key'=>$hash_key ))) ){

				if( $user['User']['payment_status'] === '2' ){			// 体験版
					$mode = '体験版';
					$user['User']['current_volume'] = '1';									// 体験版ワード数
					$user['User']['current_period'] = date("Y-m-d",strtotime("+5 day"));	// 体験版5日分プラス

				} else if( $user['User']['payment_status'] === '9' ){	// ベータテスト版
					$mode = 'ベータテスト版';
					$user['User']['current_volume'] = '2';									// ベータテスト版ワード数
					$user['User']['current_period'] = '2030-01-01';	// とりあえず2030年あたりに設定
				}


				if( $user['User']['user_status'] == 0 ){	// 現時点で仮会員の場合は、ステータスを本会員へ変更

					// ステータスを本登録に更新
					$user['User']['user_status'] = '1';
					$user['User']['regist_date'] = date("Y-m-d H:i:s");
					$this->User->save( $user, false );

					// ウェルカムメッセージを登録
					$title	= 'はじめてご利用の方へ';
					$text  = 'モノミインフォにお申込みいただきありがとうございます。<br />';
					$text .= '監視サービスを始めるには、監視ワードの設定が必要です。<br />';
					$text .= '<a href="/users/words/">こちら</a>より設定してください。<br />';
					$text .= 'また<a href="/users/template_prof/">プロフィール</a>を登録することで、削除申請が簡単に行なえます。<br />';
					$this->Message->setMsg( $user, $title, $text );

					$view_flag = 'success';

				} else if( $user['User']['user_status'] == 1 ){		// 既に本会員の場合
					$view_flag = 'finished';
				}



			}
		}

		$this->set( 'mode', $mode );
		$this->set( 'view_flag', $view_flag );
	}






	/**
	* 本会員登録 スタンダード監視プラン（課金フェーズに進む）
	*
	* @param  none
	* @return none
	*/
	public function pystandard() {


/*
		$setOptions = array(
			'NOSHIPPING' => '1',
			'ALLOWNOTE' => '0',
			'L_PAYMENTREQUEST_0_NAME0' => $this->data['Item']['title'],
			'L_PAYMENTREQUEST_0_DESC0' => $this->data['Item']['description'],
			'L_PAYMENTREQUEST_0_AMT0' => '10',
			'RETURNURL' => FULL_BASE_URL.'/mypages/confirm',
			'CANCELURL' => FULL_BASE_URL.'/mypages/cancel'
		);
*/
/*
		$amount = 1;
		$setResult = $this->Paypal->setExpressCheckout($amount, 'Sale');
pr($setResult);
*/

$this->Paypal->testSetExp();

//$this->Paypal->testPear();





		$view_flag = 'timeover';
		$mode = '';

		// 第１パラメータ
		if( isset($this->params['pass'][0]) ){		// パラメータが存在しない場合、TOPへリダイレクト

			$hash_key = $this->params['pass'][0];
			if( false !== $user = $this->User->find('first', array('conditions'=>array('hash_key'=>$hash_key ))) ){

				if( $user['User']['user_status'] == 0 && $user['User']['payment_status'] == 0 ){	// 仮会員で且つ支払いステータスが無効の場合は、プラン選択画面を表示
					$view_flag = 'plan';

				} else {	// 既に支払い済みか、または仮会員でない場合
					$view_flag = 'finished';

				}

			} else {		// ハッシュコードが正しくない場合
				$view_flag = 'timeover';
			}

		}

		$this->set( 'view_flag', $view_flag );
	}




	/**
	* Paypal支払い処理
	*
	* @param  none
	* @return none
	*/
	public function payment() {

		if ( $this->RequestHandler->isPost() ) {



		}
	}





	/**
	* 支払いキャンセル画面
	*
	* @param  none
	* @return none
	*/
	public function pcancel() {

	}





	/**
	* 支払い完了画面
	*
	* @param  none
	* @return none
	*/
	public function pcomp() {

	}





	/**
	* sendMail for client（仮会員→本会員）
	*
	* @param
	* @return none
	*/
	private function sendMail_client_kari( $user, $mode ) {

		// 送信メッセージ本文を生成
		$msg = '';
		$msg .= "\r\n";
		$msg .= $user['User']['mail_address'] . " 様\r\n";
		$msg .= "※このメールにお心当たりのない場合は、URLにアクセスせずメールを破棄してください。\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";

		if( $mode === 'standard' ){
			$msg .= "物見info スタンダード監視プランへの仮登録、誠にありがとうございます。\r\n";
		} else if( $mode === 'trial' ){
			$msg .= "物見info 体験版への仮登録、誠にありがとうございます。\r\n";
		} else if( $mode === 'beta' ){
			$msg .= "物見info ベータテスト版への仮登録、誠にありがとうございます。\r\n";
		}

		$msg .= "以下のURLから本登録をお願いします。\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";

		if( $mode === 'standard' ){
			$msg .= 'http://monomi.info/regist/pystandard/' . $user['User']['hash_key'] . '/' . "\r\n";
		} else {
			$msg .= 'http://monomi.info/regist/htregist/' . $user['User']['hash_key'] . '/' . "\r\n";
		}

		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "上記URLの有効期間は24時間以内のため、お早めに本登録をお願い致します。\r\n";
		$msg .= "URLが改行されている場合は、1行につなげてブラウザのアドレスバーに入力してください。\r\n";
		$msg .= "また携帯電話からはご登録いただけない場合がございます。\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "[お問合せ]\r\n";
		$msg .= "http://monomi.info/inquiry/form/\r\n";
		$msg .= "\r\n";
		$msg .= "*********************************************************\r\n";
		$msg .= "物見info： http://monomi.info/\r\n";
		$msg .= "お問合せ受付： http://monomi.info/inquiry/form/\r\n";
		$msg .= "*********************************************************\r\n";
		$msg .= "\r\n";


		$this->Email->to			= $user['User']['mail_address'];
		$this->Email->from			= '物見infoサポート<support@monomi.info>';
		$this->Email->subject		= '[物見info] 仮登録を受け付けました';

		$this->Email->sendAs		= 'text';
		$this->Email->delivery		= 'mail';
		$this->Email->delivery		= 'smtp';
		$this->Email->lineLength	= 500;
		$this->Email->smtpOptions	= array(
					'port'		=>	25,
					'host'		=>	'localhost',
					'timeout'	=>	30
				);


		return $this->Email->send($msg);

	}


}

?>