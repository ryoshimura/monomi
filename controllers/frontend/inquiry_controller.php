<?php

/**
* 説明
* @see 関連関数1,関連関数2
*/


/**
* Inquiry関連 コントローラー
*
* @package   パッケージ名
* @author    著作者 <著作者メール>
* @since     PHP 5.0
* @version $Id: DB.php,v 1.58 2004/03/13 16:17:19 danielc Exp $
*/
class InquiryController extends AppController {

	var $name       = 'inquiry';                     // $nameを設定することでcontroller名を任意に設定することができる。
	var $components = array('Email', 'Cookie', 'Session', 'Security');     // 使用するcomponentを指定する。
	var $layout     = 'base_layout';               // 使用するレイアウト
	var $uses       = array( 'Inquiry' );    	 // 使用するmodelを指定する。デフォルトmodelは必ず配列の先頭に記述する。
	var $helpers = array('Form','Exform');


	/**
	* action,render前実行関数
	*
	* @param  none
	* @return none
	*/
	public function beforeFilter() {
		parent::beforeFilter();

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
	* @param
	* @return none
	*/
	public function index() {

		$this->redirect(array('controller'=>'inquiry','action'=>'form'));

	}



	/**
	* form
	*
	* @param
	* @return none
	*/
	public function form() {

		App::import('Sanitize');

		if ( $this->RequestHandler->isPost() ) {

			// バリデーション
			$this->Inquiry->set($this->data);
			if( $this->Inquiry->validates($this->params['data']) ){

				$this->data['Inquiry']['mail_address'] =Sanitize::stripScripts( $this->data['Inquiry']['mail_address'] );
				$this->data['Inquiry']['text'] = Sanitize::stripScripts( $this->data['Inquiry']['text'] );

				$this->Session->write( 'inquiry_data', $this->data );	// セッションにデータを保存
				$this->redirect(array('controller'=>'inquiry','action'=>'confirm'));					// confirmへリダイレクト
			}
		}


	}



	/**
	* confirm
	*
	* @param
	* @return none
	*/
	public function confirm() {


		$data = $this->Session->read( 'inquiry_data' );	// セッション読込

		if( false == $data ){
			$this->redirect(array('controller'=>'inquiry','action'=>'form'));	// セッションが正常に読込できない場合、formへ戻す
		}

//pr($data);

		$this->set( 'data', $data );

	}



	/**
	* thanks
	*
	* @param
	* @return none
	*/
	public function thanks() {

		if( !$this->RequestHandler->isPost() ){
			$this->redirect(array('controller'=>'inquiry','action'=>'form'));	// POST以外で来た場合、formへ戻す
		}

		// to監視者 送信メッセージ本文を生成
		$msg = '';
		$msg .= '【問合せ者のメールアドレス】　' . $this->data['Inquiry']['mail_address'] . "\r\n";
		$msg .= "\r\n";
		$msg .= "【問合せ内容】\r\n";
		$msg .= $this->data['Inquiry']['text'] . "\r\n";

		$subject = '[物見info] 一般お問合せ';
		$subject= mb_convert_encoding( $subject, 'ISO-2022-JP', 'UTF-8');
		$msg	= mb_convert_encoding( $msg, 'ISO-2022-JP', 'UTF-8');


		$this->Email->to			= EMAIL_ADMIN;
		$this->Email->from			= $this->data['Inquiry']['mail_address'];
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
		$this->Email->send($msg);
		$this->Email->reset();


		// to問合せ者 送信メッセージ本文を生成
		// 送信メッセージ本文を生成
		$msg = '';
		$msg .= $this->data['Inquiry']['mail_address'] . " 様\r\n\r\n";
		$msg .= "この度は物見infoへお問合わせいただき誠にありがとうございます。\r\n";
		$msg .= "以下の内容で受付いたしました。\r\n\r\n";
		$msg .= "===================================================\r\n";
		$msg .= $this->data['Inquiry']['text'] . "\r\n";
		$msg .= "===================================================\r\n";
		$msg .= "\r\n";
		$msg .= "このメッセージはお客様へのお知らせ専用のため、\r\n";
		$msg .= "返信としてご質問をお送りいただいてもご回答できません。\r\n";
		$msg .= "ご了承ください。\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "*********************************************************\r\n";
		$msg .= "物見info： http://monomi.info/\r\n";
		$msg .= "お問合せ受付： http://monomi.info/inquiry/form/\r\n";
		$msg .= "*********************************************************\r\n";


		$subject = '[物見info] お問合せありがとうございます';
		$subject= mb_convert_encoding( $subject, 'ISO-2022-JP', 'UTF-8');
		$msg	= mb_convert_encoding( $msg, 'ISO-2022-JP', 'UTF-8');


		$this->Email->to			= $this->data['Inquiry']['mail_address'];
		$this->Email->from			= 'Monomi.Info<support@monomi.info>';
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
		$this->Email->send($msg);

	}


}

?>