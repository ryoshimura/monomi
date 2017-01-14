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
	var $uses       = array( 'User', 'Message', 'Paypal', 'Regist', 'Payment', 'Product', 'Campaign' );    	 // 使用するmodelを指定する。デフォルトmodelは必ず配列の先頭に記述する。
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

// メンテナンス
/*		$ip = $_SERVER['REMOTE_ADDR'];
		if ( '114.22.246.230' !== $ip ){
			$this->redirect('/pgs/maintenance/');
		}
*/

		// Security(SSL)コンポーネント

		if (preg_match ('|workspace|',ROOT)){ //テスト環境
		} else {
			$this->Security->blackHoleCallback = '_sslFail';
		    $this->Security->requireSecure();	// 強制SSLを中断するには、この行をコメントアウト
		}

//		if( $this->action === 'thanks' || $this->action === 'confirm' ) {
//Configure::write('debug', 2);	// テストのため一時的に
//pr("false_IN");
//			$this->Security->validatePost = false;
//		}


//		Configure::write('debug', 2);	// テストのため一時的に

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
	* 監視プラン申込（テストajax
	*
	* @param  none
	* @return none
	*/
	public function tform() {

//		Configure::write('debug', 2);	// テストのため一時的に

		$returnURL = 'https://monomi.info/regist/confirm/';
		$cancelURL = 'https://monomi.info/regist/cancel/';



		// PayPal SetExpressCheckoutでtoken取得
		$exChkOut = $this->Paypal->getToken( 700, $returnURL, $cancelURL, '監視30日間', 'xxxxxx-user_uid' );
		$ack = strtoupper($exChkOut["ACK"]);
		if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING") {		// チェックアウト成功
		}

//pr($exChkOut);

		$this->set('token', $exChkOut['TOKEN'] );

	}
















	/**
	* 監視プラン申込（デジタルコンテンツち
	*
	* @param  none
	* @return none
	*/
	public function iform() {

//		Configure::write('debug', 2);	// テストのため一時的に

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


			if( $this->Regist->validates() && $user['User']['agreement'] == 1 && $flag_email === '' ){

				$this->Session->write( 'buy_data', $this->data );	// セッションにデータを保存
				$this->redirect(array('controller' => 'regist', 'action' => 'iconfirm'));	// ペイパルエラー画面へリダイレクト

			}

		}

		$this->set('agreement', $flag_agreement );
		$this->set('email', $flag_email );

	}









	/**
	* 監視プラン申込確認（デジタルコンテンツ対応版）
	*
	* @param  none
	* @return none
	*/
	public function iconfirm() {

//		Configure::write('debug', 2);	// テストのため一時的に

		$returnURL = 'https://monomi.info/regist/confirm/';
		$cancelURL = 'https://monomi.info/regist/bcancel/';
//		$cancelURL = 'https://monomi.info/cancel_return_digi.php';



		$byData = $this->Session->read( 'buy_data' );

		if( $byData == false ){
			$this->redirect(array('controller' => 'regist', 'action' => 'paypal_error'));	// ペイパルエラー画面へリダイレクト
		}


		if( $byData['User']['plan'] === 'w2p700' ){
			$amount = 700;
			$itemName = '監視30日間ワード数2個';
			$save_payment['Payment']['product_name']	= '監視30日間 ワード数 2個';
			$save_payment['Payment']['current_volume']	= 2;

		} else if( $byData['User']['plan'] === 'w3p900' ){
			$amount = 900;
			$itemName = '監視30日間ワード数3個';
			$save_payment['Payment']['product_name']	= '監視30日間 ワード数 3個';
			$save_payment['Payment']['current_volume']	= 3;

		}
		$save_payment['Payment']['term']			= 30;
		$save_payment['Payment']['payment_amount']	= $amount;
		$save_payment['Payment']['start_date']		= date("Y-m-d H:i:s");
		$save_payment['Payment']['payment_date']	= date("Y-m-d H:i:s");
		$save_payment['Payment']['payment_status']	= 0;


		// User登録
		$user = $this->User->hashPasswords($byData);	// 暗号化
		if( false != $user_save = $this->User->find('first',array('conditions'=>array('mail_address'=>$user['User']['mail_address']))) ){
//					$user_save['User']['deleted']		= 0;	// 削除フラグ解除
		} else {
			$user_save = array();
			$user_save['User']['mail_address']	= $user['User']['mail_address'];
			$this->User->create();
		}
		$user_save['User']['passwd']		= $user['User']['passwd'];
		$user_save['User']['user_status']	= 0;	// 仮登録
		$user_save['User']['payment_status']= 3;	// 未決済
		$user_save['User']['current_volume']= $save_payment['Payment']['current_volume'];	// ワード数

		$this->User->save( $user_save, false );
		$user = $this->User->find('first',array('conditions'=>array('mail_address'=>$user['User']['mail_address'])));

		// PayPal SetExpressCheckoutでtoken取得
		$exChkOut = $this->Paypal->getToken( $amount, $returnURL, $cancelURL, $itemName, $user['User']['user_uid'] );
		$ack = strtoupper($exChkOut["ACK"]);
		if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING") {		// チェックアウト成功

			// Payment登録
			if( false != $payment = $this->Payment->find('first',array('conditions'=>array('user_uid'=>$user['User']['user_uid'], 'paypal_token'=>$exChkOut['TOKEN'] ))) ){
//				$save_payment['Payment']['payment_uid'] = $payment['Payment']['payment_uid'];
				$save_payment['Payment']['deleted'] = 0;

			} else {
				$this->Payment->create();
				$save_payment['Payment']['user_uid'] = $user['User']['user_uid'];
				$save_payment['Payment']['paypal_token'] = $exChkOut['TOKEN'];
			}

			$this->Payment->save($save_payment, false);

		} else {	// チェックアウト失敗
			$this->redirect(array('controller' => 'regist', 'action' => 'paypal_error'));	// ペイパルエラー画面へリダイレクト
		}
pr($byData);
		$this->set('payment', $save_payment );
		$this->set('byData', $byData );
		$this->set('token', $exChkOut['TOKEN'] );

	}







	/**
	* 監視プラン申込
	*
	* @param  none
	* @return none
	*/
	public function t_form() {

		App::import('Sanitize');

		$returnURL = 'https://monomi.info/regist/confirm/';
		$cancelURL = 'https://monomi.info/regist/cancel/';

		$flag_agreement = '';
		$flag_email = '';
		$flag_campaign = '';
		if ( $this->RequestHandler->isPost() ) {


//pr($this->data);
			$this->redirect(array(
				 'controller' => 'test'
				,'action' => 'index'
				,$data
			));
//https://beta.epsilon.jp/cgi-bin/order/receive_order3.cgi

/*
			$data['Regist'] = $this->data['User'];
			$user['User'] = $this->data['User'];
			$user = $this->User->hashPasswords($user);

			$this->Regist->set( $data );
			$this->Regist->validates();


			// 利用規約同意の確認
			if( $user['User']['agreement'] != 1 ){
				$flag_agreement = 'notAgreement';
			}

			// 同じメールアドレスが存在するか確認（同じメールアドレスがあっても、仮会員や体験版ユーザはOKとする）
			if( false != $user_buf = $this->User->find('first',array('conditions'=>array('mail_address'=>$user['User']['mail_address']))) ){
//				if( $user_buf['User']['user_status'] == 1 ){
				if( $user_buf['User']['user_status'] == 1 && $user_buf['User']['payment_status'] != 2  ){
					$flag_email = 'isnotUniq';
				}
			}

			// 選択プラン情報を取得
			$product_buf = $this->Product->find('first',array('conditions'=>array('product_uid'=>$user['User']['plan'])));

			// 招待キャンペーン
			if( $product_buf['Product']['genre']==='campaign' ){		// 招待キャンペーンプランを選択していて、且つ正しい招待コードが入力されていない場合

				if( $user['User']['campaign'] === '' || $user['User']['campaign'] === null ){

					$flag_campaign = 'illegal_code';

				} else {

					$introducer = $this->User->find('first',array('conditions'=>array('campaign_code'=>$user['User']['campaign'])));
					if( $introducer == false ){
						$flag_campaign = 'illegal_code';
					}

				}
			}



			if( $this->Regist->validates() && $user['User']['agreement'] == 1 && $flag_email === '' && $flag_campaign === '' ){

//pr($product_buf);
				if( $product_buf == false ){	// 改ざんの可能性があるので、とりあえずTOPへリダイレクト
					$this->redirect('/');	// TOPへリダイレクト
				}

				$amount = $product_buf['Product']['amount'];
				$itemName = $product_buf['Product']['product_name'];
				$save_payment['Payment']['product_name']	= $product_buf['Product']['product_name'];
				$save_payment['Payment']['current_volume']	= $product_buf['Product']['current_volume'];
				$save_payment['Payment']['term']			= 30;
				$save_payment['Payment']['payment_amount']	= $amount;
				$save_payment['Payment']['start_date']		= date("Y-m-d H:i:s");
				$save_payment['Payment']['payment_date']	= date("Y-m-d H:i:s");
				$save_payment['Payment']['payment_status']	= 0;
				$save_payment['Payment']['product_uid']		= $product_buf['Product']['product_uid'];

				// User登録
				$user = $this->User->hashPasswords($this->data);	// 暗号化
				if( false != $user_save = $this->User->find('first',array('conditions'=>array('mail_address'=>$user['User']['mail_address']))) ){
//					$user_save['User']['deleted']		= 0;	// 削除フラグ解除

				} else {
					$user_save = array();
					$user_save['User']['mail_address']	= $user['User']['mail_address'];
					$this->User->create();
					$user_save['User']['payment_status']= 3;	// 未決済

				}
				$user_save['User']['passwd']		= $user['User']['passwd'];
//				$user_save['User']['user_status']	= 0;	// 仮登録
//				$user_save['User']['payment_status']= 3;	// 未決済
				if( isset($introducer) ){
					if( $introducer != false ){
						$user_save['User']['introducer_user_uid'] = $introducer['User']['user_uid'];		// 紹介者のuser_uid
					}
				}
//				$user_save['User']['current_volume']= $save_payment['Payment']['current_volume'];	// ワード数

				$this->User->save( $user_save, false );
				$user = $this->User->find('first',array('conditions'=>array('mail_address'=>$user['User']['mail_address'])));


				// PayPal SetExpressCheckoutでtoken取得
				$exChkOut = $this->Paypal->getToken( $amount, $returnURL, $cancelURL, $itemName, $user['User']['user_uid'] );
				$ack = strtoupper($exChkOut["ACK"]);
				if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING") {		// チェックアウト成功

					// Payment登録
					if( false != $payment = $this->Payment->find('first',array('conditions'=>array('user_uid'=>$user['User']['user_uid'], 'paypal_token'=>$exChkOut['TOKEN'] ))) ){
						$save_payment['Payment']['payment_uid'] = $payment['Payment']['payment_uid'];
						$save_payment['Payment']['payerid'] = 'Overlap';
						$save_payment['Payment']['deleted'] = 0;

					} else {
						$this->Payment->create();
						$save_payment['Payment']['user_uid'] = $user['User']['user_uid'];
						$save_payment['Payment']['paypal_token'] = $exChkOut['TOKEN'];
					}

					$this->Payment->save($save_payment, false);
					$this->Paypal->RedirectToPayPal( $exChkOut['TOKEN'] );	// ペイパル決済へリダイレクト

				} else {	// チェックアウト失敗
					$this->redirect(array('controller' => 'regist', 'action' => 'paypal_error'));	// ペイパルエラー画面へリダイレクト
				}
			}
*/
		}


		// 監視プラン一覧を取得
		$product = $this->Product->getProductList('public');
		$this->set('product', $product);

		$this->set('agreement', $flag_agreement );
		$this->set('email', $flag_email );
		$this->set('campaign', $flag_campaign );


	}





	/**
	* 監視プラン申込
	*
	* @param  none
	* @return none
	*/
	public function form() {

		// 2013.12.09
		$this->redirect('/');	// TOPへリダイレクト


		App::import('Sanitize');

		$returnURL = 'https://monomi.info/regist/confirm/';
		$cancelURL = 'https://monomi.info/regist/cancel/';


		$flag_agreement = '';
		$flag_email = '';
		$flag_campaign = '';
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

			// 同じメールアドレスが存在するか確認（同じメールアドレスがあっても、仮会員や体験版ユーザはOKとする）
			if( false != $user_buf = $this->User->find('first',array('conditions'=>array('mail_address'=>$user['User']['mail_address']))) ){
//				if( $user_buf['User']['user_status'] == 1 ){
				if( $user_buf['User']['user_status'] == 1 && $user_buf['User']['payment_status'] != 2  ){
					$flag_email = 'isnotUniq';
				}
			}

			// 選択プラン情報を取得
			$product_buf = $this->Product->find('first',array('conditions'=>array('product_uid'=>$user['User']['plan'])));

			// 招待キャンペーン
			if( $product_buf['Product']['genre']==='campaign' ){		// 招待キャンペーンプランを選択していて、且つ正しい招待コードが入力されていない場合

				if( $user['User']['campaign'] === '' || $user['User']['campaign'] === null ){

					$flag_campaign = 'illegal_code';

				} else {

					$introducer = $this->User->find('first',array('conditions'=>array('campaign_code'=>$user['User']['campaign'])));
					if( $introducer == false ){
						$flag_campaign = 'illegal_code';
					}

				}
			}



			if( $this->Regist->validates() && $user['User']['agreement'] == 1 && $flag_email === '' && $flag_campaign === '' ){

//pr($product_buf);
				if( $product_buf == false ){	// 改ざんの可能性があるので、とりあえずTOPへリダイレクト
					$this->redirect('/');	// TOPへリダイレクト
				}

				$amount = $product_buf['Product']['amount'];
				$itemName = $product_buf['Product']['product_name'];
				$save_payment['Payment']['product_name']	= $product_buf['Product']['product_name'];
				$save_payment['Payment']['current_volume']	= $product_buf['Product']['current_volume'];
				$save_payment['Payment']['term']			= 30;
				$save_payment['Payment']['payment_amount']	= $amount;
				$save_payment['Payment']['start_date']		= date("Y-m-d H:i:s");
				$save_payment['Payment']['payment_date']	= date("Y-m-d H:i:s");
				$save_payment['Payment']['payment_status']	= 0;
				$save_payment['Payment']['product_uid']		= $product_buf['Product']['product_uid'];

				// User登録
				$user = $this->User->hashPasswords($this->data);	// 暗号化
				if( false != $user_save = $this->User->find('first',array('conditions'=>array('mail_address'=>$user['User']['mail_address']))) ){
//					$user_save['User']['deleted']		= 0;	// 削除フラグ解除

				} else {
					$user_save = array();
					$user_save['User']['mail_address']	= $user['User']['mail_address'];
					$this->User->create();
					$user_save['User']['payment_status']= 3;	// 未決済

				}
				$user_save['User']['passwd']		= $user['User']['passwd'];
//				$user_save['User']['user_status']	= 0;	// 仮登録
//				$user_save['User']['payment_status']= 3;	// 未決済
				if( isset($introducer) ){
					if( $introducer != false ){
						$user_save['User']['introducer_user_uid'] = $introducer['User']['user_uid'];		// 紹介者のuser_uid
					}
				}
//				$user_save['User']['current_volume']= $save_payment['Payment']['current_volume'];	// ワード数

				$this->User->save( $user_save, false );
				$user = $this->User->find('first',array('conditions'=>array('mail_address'=>$user['User']['mail_address'])));


				// PayPal SetExpressCheckoutでtoken取得
				$exChkOut = $this->Paypal->getToken( $amount, $returnURL, $cancelURL, $itemName, $user['User']['user_uid'] );
				$ack = strtoupper($exChkOut["ACK"]);
				if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING") {		// チェックアウト成功

					// Payment登録
					if( false != $payment = $this->Payment->find('first',array('conditions'=>array('user_uid'=>$user['User']['user_uid'], 'paypal_token'=>$exChkOut['TOKEN'] ))) ){
						$save_payment['Payment']['payment_uid'] = $payment['Payment']['payment_uid'];
						$save_payment['Payment']['payerid'] = 'Overlap';
						$save_payment['Payment']['deleted'] = 0;

					} else {
						$this->Payment->create();
						$save_payment['Payment']['user_uid'] = $user['User']['user_uid'];
						$save_payment['Payment']['paypal_token'] = $exChkOut['TOKEN'];
					}

					$this->Payment->save($save_payment, false);
					$this->Paypal->RedirectToPayPal( $exChkOut['TOKEN'] );	// ペイパル決済へリダイレクト

				} else {	// チェックアウト失敗
					$this->redirect(array('controller' => 'regist', 'action' => 'paypal_error'));	// ペイパルエラー画面へリダイレクト
				}
			}

		}


		// 監視プラン一覧を取得
		$product = $this->Product->getProductList('public');
		$this->set('product', $product);

		$this->set('agreement', $flag_agreement );
		$this->set('email', $flag_email );
		$this->set('campaign', $flag_campaign );


	}




	/**
	* 監視プラン申込確認
	*
	* @param  none
	* @return none
	*/
	public function confirm() {

//		Configure::write('debug', 2);	// テストのため一時的に

		// ペイパルから値を取得
		if (isset($_REQUEST['token'])) {
			$token = $_REQUEST['token'];
		} else {
			$this->redirect(array('controller' => 'regist', 'action' => 'paypal_error'));	// ペイパルエラー画面へリダイレクト
		}

		// tokenチェック
		if ( $token != "" ) {
			$resArray = $this->Paypal->GetShippingDetails( $token );
			$ack = strtoupper($resArray["ACK"]);
			if( $ack == "SUCCESS" || $ack == "SUCESSWITHWARNING") {
//pr($resArray);


				// 2013.01.30 add 継続申込み時、過去に契約済みのuser_uidが使えないことから、契約開始日を文字列に追加
//pr( $resArray['PAYMENTREQUEST_0_INVNUM'] );
				if( false !== strpos( $resArray['PAYMENTREQUEST_0_INVNUM'], '__') ){
					$strBuf = explode('__', $resArray['PAYMENTREQUEST_0_INVNUM'] );
					$user_uid = $strBuf[0];
//pr($user_uid);
				} else {
					$user_uid = $resArray['PAYMENTREQUEST_0_INVNUM'];
				}


				// paymentレコードからデータを取得。ない場合はエラーページへリダイレクト
//				$payment = $this->Payment->find('first',array('conditions'=>array('user_uid'=>$resArray['PAYMENTREQUEST_0_INVNUM'], 'paypal_token'=>$resArray['TOKEN'] )));
				$payment = $this->Payment->find('first',array('conditions'=>array('user_uid'=>$user_uid, 'paypal_token'=>$resArray['TOKEN'] )));
//pr($payment);
				if( false == $payment ){
					$this->redirect(array('controller' => 'regist', 'action' => 'paypal_error'));	// ペイパルエラー画面へリダイレクト
				}

				// 必要情報をセッションで一時保存
				$regist_data = array();
				$regist_data['user_uid']	= $payment['Payment']['user_uid'];
				$regist_data['payment_uid']	= $payment['Payment']['payment_uid'];
				$this->Session->write( 'buy_data', $regist_data );	// セッションにデータを保存

				// paymentを更新
				$payment['Payment']['payerid'] = $resArray['PAYERID'];
				$this->Payment->save($payment);

				$user = $this->User->find('first',array('conditions'=>array('user_uid'=>$payment['Payment']['user_uid'])));
				$user = $this->User->decryptedUser($user);
				$this->set('user', $user);
				$this->set('payment', $payment);

			} else {
				$this->redirect(array('controller' => 'regist', 'action' => 'paypal_error'));	// ペイパルエラー画面へリダイレクト
			}

		} else {
			$this->redirect(array('controller' => 'regist', 'action' => 'paypal_error'));	// ペイパルエラー画面へリダイレクト
		}

	}



	/**
	* 監視プラン申込完了
	*
	* @param  none
	* @return none
	*/
	public function thanks() {


//		Configure::write('debug', 2);	// テストのため一時的に

		$buy_data = $this->Session->read( 'buy_data' );
//pr($buy_data);

		if ( $buy_data != false ) {

			$user_uid		= $buy_data['user_uid'];
			$payment_uid	= $buy_data['payment_uid'];

			// 決済済みの場合は処理しない
			$payment = $this->Payment->find('first',array('conditions'=>array('user_uid'=>$user_uid, 'payment_uid'=>$payment_uid )));
			if( false == $payment ){
				$this->redirect(array('controller' => 'regist', 'action' => 'paypal_error'));	// ペイパルエラー画面へリダイレクト
			} else {
				if( $payment['Payment']['payment_status'] != 1 ){		// 決済済みでない場合のみ処理

					// ペイパル処理
					$resArray = $this->Paypal->ConfirmPayment( $payment['Payment']['payment_amount'], $payment['Payment']['paypal_token'], $payment['Payment']['payerid'], $payment['Payment']['product_name'], $payment['Payment']['payment_amount'] );
//pr($resArray);
					$ack = strtoupper($resArray["ACK"]);
					if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" ) {
//pr($ack);
						// payment更新
						$payment['Payment']['payment_status']	= 1;	// 支払い済みにステータス変更
						$this->Payment->save($payment, false);

						$user = $this->User->find('first',array('conditions'=>array('user_uid'=>$user_uid )));

						// ウェルカムメッセージを登録
						if( $user['User']['user_status'] != 1 ){
							$title	= 'はじめてご利用の方へ';
							$text  = 'モノミインフォにお申込みいただきありがとうございます。<br />';
							$text .= '監視サービスを始めるには、監視ワードの設定が必要です。<br />';
							$text .= '<a href="/users/words/">こちら</a>より設定してください。<br />';
							$text .= 'また<a href="/users/template_prof/">プロフィール</a>を登録することで、削除申請が簡単に行なえます。<br />';
							$this->Message->setMsg( $user, $title, $text );
						}


						// 招待キャンペーンコード生成
						for($i=0; $i<100; $i++){
							$campaign_code = uniqid(rand(100,999));
							if( false == $this->User->find('first',array('conditions'=>array('campaign_code'=>$campaign_code))) ){	// 同じコードがなければループをbreak
								break;
							}
						}


						// 招待キャンペーンを受けた場合、campaignレコードを生成（仮会員のみ適用）
						if( $user['User']['user_status'] == 0 && $user['User']['introducer_user_uid'] !== '' && $user['User']['introducer_user_uid'] != null ){
							$introducer = $this->User->find('first',array('first',array('conditions'=>array('user_uid'=>$user['User']['introducer_user_uid']))));
							if( $introducer != false ){
								$save_campaign = array();
								$save_campaign['Campaign']['campaign_name']			= '招待キャンペーン';
								$save_campaign['Campaign']['assign_from_user_uid']	= $user['User']['introducer_user_uid'];
								$save_campaign['Campaign']['assign_to_user_uid']	= $user['User']['user_uid'];
								$save_campaign['Campaign']['campaign_code']			= $introducer['User']['campaign_code'];
								$save_campaign['Campaign']['term']					= 30;		// 30
								$this->Campaign->create();
								$this->Campaign->save($save_campaign, false);
							}
						}


						// user更新
						$user['User']['user_status']	= 1;	// 本登録にステータス変更
						$user['User']['payment_status']	= 1;	// 契約有効にステータス変更
						$user['User']['period_status']	= 1;									// 有効期限フラグをON
						$current_period					= $payment['Payment']['start_date'] . " +30 days";	// 30日後
						$user['User']['current_period']	= date('Y-m-d H:i:s', strtotime($current_period));	// 契約終了日
						$user['User']['regist_date']	= $payment['Payment']['start_date'];	// 本登録にステータス変更
						$user['User']['current_volume']	= $payment['Payment']['current_volume'];	// 監視ワード数
						$user['User']['campaign_code']	= $campaign_code;	// 招待キャンペーンコード

						$this->User->save($user, false);




						// 自動メール送信
						$this->sendMail_client_standard( $this->User->decryptedUser($user), $payment );




					} else {
						$this->redirect(array('controller' => 'regist', 'action' => 'paypal_error'));	// ペイパルエラー画面へリダイレクト
					}
				}

			}

		} else {

		}

	}




	/**
	* 監視プラン申込キャンセル（着地点）
	*
	* @param  none
	* @return none
	*/
	public function bcancel() {
		$this->redirect('http://monomi.info/cancel_return_digi.php');
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

		// 2013.12.09
		$this->redirect('/');	// TOPへリダイレクト


		$flag_agreement = '';
		$flag_email		= '';

		if ( $this->RequestHandler->isPost() ) {

//			$user = $this->data;
//			$user = $this->User->decryptedUser($user);			// 復号化
//			$this->User->set( $user );


			$data['Regist'] = $this->data['User'];
			$user['User'] = $this->data['User'];
			$user = $this->User->hashPasswords($user);

			$this->Regist->set( $data );
//			$this->Regist->validates();


			// 利用規約同意の確認
			if( $user['User']['agreement'] != 1 ){
				$flag_agreement = 'notAgreement';
			}


			// 同じメールアドレスが存在するか確認（同じメールアドレスがあっても、仮会員はOKとする）
			if( false != $user_buf = $this->User->find('first',array('conditions'=>array('mail_address'=>$user['User']['mail_address']))) ){
				if( $user_buf['User']['user_status'] == 1 ){
					$flag_email = 'isnotUniq';
				}
			}


			if( $this->Regist->validates() && $user['User']['agreement'] == 1 && $flag_email === '' ){

				if( false != $user_buf ){
					$user['User']['user_uid'] = $user_buf['User']['user_uid'];
				} else {
					$this->User->create();
				}


				$user['User']['payment_status'] = '2';	// ペイメントステータスを体験版「2」を設定
				$user['User']['hash_key'] = md5(uniqid(rand(),1));	// 仮登録用ハッシュキーを生成
				$user['User']['hash_create_time'] = date("Y-m-d H:i:s");	// 仮登録用ハッシュキーを生成
//				$user =  $this->User->hashPasswords($user);			// 暗号化

				$this->User->save( $user, false );


				// 仮登録完了メールを送信
				$user = $this->User->decryptedUser( $user );
				$this->sendMail_client_kari( $user, 'trial' );

				// リダイレクト
				$this->redirect('/regist/pthanks/trial/');
			}
		}


		$this->set('email', $flag_email );
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
//			$time = date("Y-m-d H:i:s",strtotime("-24 hour"));
//pr($time);
//			if( false !== $user = $this->User->find('first', array('conditions'=>array('hash_key'=>$hash_key, 'hash_create_time >='=>$time ))) ){
			if( false !== $user = $this->User->find('first', array('conditions'=>array('hash_key'=>$hash_key ))) ){

				if( $user['User']['payment_status'] == 2 ){			// 体験版
					$mode = '体験版';
					$user['User']['current_volume'] = 1;									// 体験版ワード数
					$user['User']['current_period'] = date("Y-m-d",strtotime("+7 day"));	// 体験版7日分プラス
					$user['User']['period_status'] = 1;									// 有効期限フラグをON

					$this->sendMail_client_hon( $user );		// 本会員登録完了をメールで通知

				} else if( $user['User']['payment_status'] == 9 ){	// ベータテスト版
					$mode = 'ベータテスト版';
					$user['User']['current_volume'] = 2;									// ベータテスト版ワード数
					$user['User']['current_period'] = '2030-01-01';	// とりあえず2030年あたりに設定
					$user['User']['period_status'] = 1;									// 有効期限フラグをON

				}


				if( $user['User']['user_status'] == 0 && $mode != '' ){	// 現時点で仮会員の場合は、ステータスを本会員へ変更

					// ステータスを本登録に更新
					$user['User']['user_status'] = 1;
					$user['User']['regist_date'] = date("Y-m-d H:i:s");
					$this->User->save( $user, false );

					// ウェルカムメッセージを登録
					$title	= 'はじめてご利用の方へ';
					$text  = '物見インフォにお申込みいただきありがとうございます。<br />';
					$text .= '監視サービスを始めるには、監視ワードの設定が必要です。<br />';
					$text .= '<a href="/users/words/">こちら</a>より設定してください。<br />';
					$text .= 'また<a href="/users/template_prof/">プロフィール</a>を登録することで、削除申請が簡単に行なえます。<br />';
					$this->Message->setMsg( $user, $title, $text );

					$view_flag = 'success';


				} else if( $user['User']['user_status'] == 1 && $mode != '' ){		// 既に本会員の場合
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
		$msg .= "\r\n";
		$msg .= "*********************************************************\r\n";
		$msg .= "物見info： http://monomi.info/\r\n";
		$msg .= "お問合せ受付： http://monomi.info/inquiry/form/\r\n";
		$msg .= "*********************************************************\r\n";
		$msg .= "\r\n";


		$subject = '[物見info] 仮登録を受け付けました';
		$subject= mb_convert_encoding( $subject, 'ISO-2022-JP', 'UTF-8');
		$msg	= mb_convert_encoding( $msg, 'ISO-2022-JP', 'UTF-8');

		$this->Email->to			= $user['User']['mail_address'];
		$this->Email->from			= 'Monomi.Info<support@monomi.ciasol.com>';
		$this->Email->subject		= $subject;

		$this->Email->language = 'Japanese';
		$this->Email->charset  = 'ISO-2022-JP';

		$this->Email->sendAs		= 'text';
//		$this->Email->delivery		= 'mail';
		$this->Email->delivery		= 'smtp';
		$this->Email->lineLength	= 500;
		$this->Email->smtpOptions	= array(
					'port'		=>	25,
					'host'		=>	'localhost',
					'timeout'	=>	30
				);


		return $this->Email->send($msg);

	}






	/**
	* sendMail for client hon（体験版の本会員完了）
	*
	* @param
	* @return none
	*/
	private function sendMail_client_hon( $user ) {

		// 送信メッセージ本文を生成
		$msg = '';
		$msg .= "\r\n";
		$msg .= $user['User']['mail_address'] . " 様\r\n";
		$msg .= "※このメールにお心当たりのない場合は、URLにアクセスせずメールを破棄してください。\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "物見info 体験版への本登録が完了しました。\r\n";
		$msg .= "\r\n";
		$msg .= "監視サービスは下記からログインすることでご利用いただけます。\r\n";
		$msg .= "ログイン： https://monomi.info/users/login/ \r\n";
		$msg .= "\r\n";
		$msg .= "ご利用方法\r\n";
		$msg .= "1. 物見infoへログイン\r\n";
		$msg .= "2. 監視したいワードを設定\r\n";
		$msg .= "3. 不正コンテンツの検知をあなたのメールに報告\r\n";
		$msg .= "4. 不正コンテンツに対し削除申請\r\n";
		$msg .= "\r\n";
		$msg .= "今後とも物見infoをよろしくお願い申し上げます。\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "*********************************************************\r\n";
		$msg .= "物見info： http://monomi.info/\r\n";
		$msg .= "お問合せ受付： http://monomi.info/inquiry/form/\r\n";
		$msg .= "*********************************************************\r\n";
		$msg .= "\r\n";


		$subject = '[物見info] 本登録が完了しました';
		$subject= mb_convert_encoding( $subject, 'ISO-2022-JP', 'UTF-8');
		$msg	= mb_convert_encoding( $msg, 'ISO-2022-JP', 'UTF-8');

		$this->Email->to			= $user['User']['mail_address'];
		$this->Email->from			= 'Monomi.Info<support@monomi.ciasol.com>';
		$this->Email->subject		= $subject;

		$this->Email->language = 'Japanese';
		$this->Email->charset  = 'ISO-2022-JP';

		$this->Email->sendAs		= 'text';
		$this->Email->delivery		= 'smtp';
		$this->Email->lineLength	= 500;
		$this->Email->smtpOptions	= array(
					'port'		=>	25,
					'host'		=>	'localhost',
					'timeout'	=>	30
				);


		return $this->Email->send($msg);

	}




	/**
	* sendMail_client_standard（スタンダード監視プラン用 自動返信メール）
	*
	* @param
	* @return none
	*/
	private function sendMail_client_standard( $user, $payment ) {

		// 送信メッセージ本文を生成
		$msg = '';
		$msg .= "\r\n";
		$msg .= $user['User']['mail_address'] . " 様\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "物見info スタンダード監視プランへお申込みいただき、誠にありがとうございます。\r\n";
		$msg .= "下記お申込み内容でPaypalからの受領を確認致しました。\r\n";
		$msg .= "今後とも物見infoをよろしくお願い申し上げます。\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "───────────────────────────────────\r\n";
		$msg .= "\r\n";
		$msg .= "申込プラン： " . $payment['Payment']['product_name'] . "\r\n";
		$msg .= "お支払金額： ￥" . $payment['Payment']['payment_amount'] . "\r\n";
		$msg .= "\r\n";
		$msg .= "───────────────────────────────────\r\n";
		$msg .= "\r\n";
		$msg .= "※記載の料金はすべて税込金額です。\r\n";
		$msg .= "※書面による領収書の発行は行っておりませんのでご了承ください。\r\n";
		$msg .= "\r\n";
		$msg .= "監視サービスは下記からログインすることでご利用いただけます。\r\n";
		$msg .= "ログイン： https://monomi.info/users/login/ \r\n";
		$msg .= "\r\n";
		$msg .= "ご利用方法\r\n";
		$msg .= "1. 物見infoへログイン\r\n";
		$msg .= "2. 監視したいワードを設定\r\n";
		$msg .= "3. 検知した不正コンテンツ情報を通知\r\n";
		$msg .= "4. 不正コンテンツに対し削除申請\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "*********************************************************\r\n";
		$msg .= "物見info： http://monomi.info/ \r\n";
		$msg .= "お問合せ受付： http://monomi.info/inquiry/form/ \r\n";
		$msg .= "*********************************************************\r\n";
		$msg .= "\r\n";


		$subject = '[物見info] 受領メール';
		$subject= mb_convert_encoding( $subject, 'ISO-2022-JP', 'UTF-8');
		$msg	= mb_convert_encoding( $msg, 'ISO-2022-JP', 'UTF-8');

		$this->Email->to			= $user['User']['mail_address'];
		$this->Email->from			= 'Monomi.Info<support@monomi.ciasol.com>';
		$this->Email->subject		= $subject;

		$this->Email->language = 'Japanese';
		$this->Email->charset  = 'ISO-2022-JP';

		$this->Email->sendAs		= 'text';
//		$this->Email->delivery		= 'mail';
		$this->Email->delivery		= 'smtp';
		$this->Email->lineLength	= 500;
		$this->Email->smtpOptions	= array(
					'port'		=>	25,
					'host'		=>	'localhost',
					'timeout'	=>	30
				);


		return $this->Email->send($msg);

	}


/*
	public function test_thx() {

		$user['User']['mail_address'] = 'ryuichi.ys@gmail.com';
		$payment['Payment']['product_name']		= 'テストプロダクツ';
		$payment['Payment']['payment_amount']	= '780';

		$this->sendMail_client_standard( $user, $payment );


	}
*/


}

?>