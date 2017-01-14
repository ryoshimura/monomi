<?php

//App::import('Vendor', 'oauth', array('file' => 'OAuth'.DS.'oauth_consumer.php'));
App::import('Vendor', 'Oauth', array('file'=>'OAuth'.DS.'oauth_consumer.php'));

/**
* ホーム関連 コントローラー
*
* @package   パッケージ名
* @author    著作者 <著作者メール>
* @since     PHP 5.0
* @version $Id: DB.php,v 1.58 2004/03/13 16:17:19 danielc Exp $
*/
class UsersController extends AppController {

	var $name       = 'Users';                     // $nameを設定することでcontroller名を任意に設定することができる。
	var $components = array( 'Auth', 'Email', 'Cookie', 'Session', 'Security', 'RequestHandler' );     // 使用するcomponentを指定する。
	var $layout     = 'base_layout';               // 使用するレイアウト
	var $uses       = array( 'User', 'Message', 'Forum', 'Word', 'IllegalSite', 'IllegalResult', 'DownloadSite', 'ResultRelation', 'DownloadResult', 'UserInquiry', 'DeletedUsers', 'UserProfile', 'Template', 'ShareSendList', 'SendMail', 'Product', 'Paypal', 'Payment' );    	 // 使用するmodelを指定する。デフォルトmodelは必ず配列の先頭に記述する。
	var $helpers	= array( 'Form', 'Exform' );		// 拡張formHelper（datetime日本語版）を利用

	var $authLifeTime = 60;
	var $authLifeTimeUnit = 'minutes';


	/**
	* action,render前実行関数
	*
	* @param  none
	* @return none
	*/
	public function beforeFilter() {
		parent::beforeFilter();

// メンテナンス
/*
$ip = $_SERVER['REMOTE_ADDR'];
if ( '114.22.241.12' === $ip ){
	Configure::write('debug', 2);	// テストのため一時的に
} else {
	$this->redirect('/pgs/maintenance/');
}
*/



		// Authコンポーネント非適用Action
		$this->Auth->allow('logout', 'aj_inbox_request', 'aj_inbox_comp', 'aj_detail_template', 'aj_template_initial', 'aj_template_change', 'aj_template_send', 'aj_inbox_restore', 'rms');
		$this->Auth->userModel = 'User';							// 認証コンポーネントで使用するテーブル
		$this->Auth->authenticate = ClassRegistry::init('User');	// 暗号化方式を可逆式暗号に変更する。

		// 認証後index(リファラーがあればそちら)ページに自動で遷移させる。
		//$this->Auth->autoRedirect = false;

		// Authコンポーネント使用フィールド設定。
		$this->Auth->fields = array(
			  'username' => 'mail_address'
			, 'password' => 'passwd'
		);

		// ログイン画面のあるパス
		$this->Auth->loginAction = array(
			'controller' => 'users',
			'action'     => 'login'
		);

//		$this->Auth->loginAction = array('admin' => false, 'controller' => 'users', 'action' => 'login');

		// ログイン後に遷移するパス
		$this->Auth->loginRedirect = array(
			'controller' => 'users',
			'action'     => 'dashboard'
		);

		// ログアウト時に遷移するパス
		$this->Auth->logoutRedirect = array(
			'controller' => 'users',
			'action'     => 'login'
		);

		$this->Auth->loginError = 'メールアドレスもしくはパスワードが間違っています';
		$this->Auth->authError  = 'ログインしてください';

		$this->Auth->userScope = array( 'User.user_status'=>1, 'deleted'=>0 );		// 本登録済みのみ認証OKとする

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
	* login
	*
	* @param  none
	* @return none
	*/
	public function login() {
//		$this->Session->delete('Auth.redirect');
	}


	/**
	* logout
	*
	* @param  none
	* @return none
	*/
	public function logout() {
		$this->layout="";

		$this->Session->setFlash('ログアウトしました。');
        $this->redirect($this->Auth->logout());
	}




	/**
	* Userダッシュボード
	*
	* @param  none
	* @return none
	*/
	public function dashboard() {

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));

		$time = date("Y-m-d",strtotime("-30 day"));
		$msg = $this->Message->find('all', array('limit'=>'10', 'conditions'=>array('OR'=>array( array('user_uid'=>$user['User']['user_uid']), array('user_uid'=>'system')), 'regist_date >'=>$time, 'deleted'=>0), 'order'=>array('regist_date DESC'))); // メッセージ
		$monitoring = $this->IllegalResult->dashboard( $user['User']['user_uid'] ); // 検出数
//pr('test!');
		// 最終監視日時を取得
		$lastupdate = $this->IllegalResult->find('first', array('conditions'=>array('deleted'=>0), 'order'=>array('updated DESC')));

		$this->set( 'user', $user );
		$this->set( 'lastupdate', $lastupdate['IllegalResult']['updated'] );
		$this->set( 'msg', $msg );
		$this->set( 'monitoring', $monitoring );
	}




	/**
	* User監視トレイ
	*
	* @param  none
	* @return none
	*/
	public function inbox() {


		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));
//pr($user);

		// 2012.12.06 add
		$monitoring = $this->IllegalResult->dashboard( $user['User']['user_uid'] ); // 検出数
		if( $monitoring['ilcnt'] > 10000 ){
			$this->redirect( '/users/inbox_over/' );
		}




		$start_page = 1;
		$limit_count = 50;	// 1ページ内に表示するメインレコード数
		$sort = false;

		// 第１パラメータ
		if( isset($this->params['pass'][0]) ){
			if (preg_match("/^[0-9]+$/", $this->params['pass'][0])) {
				$start_page = $this->params['pass'][0];
			}
		}

		// add 2012.09.12 体験版は強制的に $start_page = 1 とする
		if( $user['User']['payment_status'] == '2' ){	// 体験版の場合
			$start_page = 1;
		}


		// 第２パラメータ
		if( isset($this->params['pass'][1]) ){
//			$start_page = $this->params['pass'][1];
			$sort = $this->params['pass'][1];
		} else {
			$sort = 'dated';
		}


		// 監視結果を取得
		$view_flg = true;
		if( $user['User']['payment_status'] != 0 ){
//			if( $user['User']['payment_status'] == 2 && strtotime("now") > strtotime($user['User']['current_period']) ){	// 体験版で且つ期限切れの場合
			if( $user['User']['payment_status'] == 2 && $user['User']['period_status'] == 0 ){	// 体験版で且つ期限切れの場合
				$view_flg = false;
				$this->set('Allcnt', 0 );
			}
		} else {
			$view_flg = false;
		}


		if( $view_flg == true ){
			if( $user['User']['payment_status'] == 2 ){			// 体験版の場合
				$data = $this->IllegalResult->inbox( $user['User']['user_uid'], $start_page, $limit_count, $sort, true );
			} else {
				$data = $this->IllegalResult->inbox( $user['User']['user_uid'], $start_page, $limit_count, $sort );
			}

			$this->set('start_record', (($start_page-1)*$limit_count)+1 );
			$this->set('page', $start_page );
			$this->set('limit_count', $limit_count );
			$this->set('Allcnt', $data['Allcnt'] );
			$this->set('sort', $sort);

			if( empty($data['data']) ){
				$data['data'] = array();
			}

			$this->set('data', $data['data'] );
//pr($data);
		}

		$this->set( 'payment_status', $user['User']['payment_status'] );	// 支払いステータス
		$this->set( 'user', $user );	// 支払いステータス
		$this->set( 'view_flg', $view_flg );	// falseは利用期限切れ
	}



	/**
	* User監視トレイ（想定量を超える検出結果数の場合
	*
	* @param  none
	* @return none
	*/
	public function inbox_over() {

	}



	/**
	* 【旧】User監視トレイ
	*
	* @param  none
	* @return none
	*/
	public function old_inbox() {


		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));

		$start_page = 1;
		$limit_count = 100;	// 1ページ内に表示するメインレコード数
		$sort = false;

		// 第１パラメータ
		if( isset($this->params['pass'][0]) ){
			if (preg_match("/^[0-9]+$/", $this->params['pass'][0])) {
				$start_page = $this->params['pass'][0];
			}
		}

		// add 2012.09.12 体験版は強制的に $start_page = 1 とする
		if( $user['User']['payment_status'] == '2' ){	// 体験版の場合
			$start_page = 1;
		}


		// 第２パラメータ
		if( isset($this->params['pass'][1]) ){
//			$start_page = $this->params['pass'][1];
			$sort = $this->params['pass'][1];
		} else {
			$sort = 'dated';
		}


		// 監視結果を取得
		$view_flg = true;
		if( $user['User']['payment_status'] != 0 ){
			if( $user['User']['payment_status'] == 2 && strtotime("now") > strtotime($user['User']['current_period']) ){	// 体験版で且つ期限切れの場合
				$view_flg = false;
				$this->set('Allcnt', 0 );
			}
		} else {
			$view_flg = false;
		}

//		$view_flg = true;

		if( $view_flg == true ){
			if( $user['User']['payment_status'] == 2 ){			// 体験版の場合
				$data = $this->IllegalResult->inbox( $user['User']['user_uid'], $start_page, $limit_count, $sort, true );
			} else {
				$data = $this->IllegalResult->inbox( $user['User']['user_uid'], $start_page, $limit_count, $sort );
			}

			$this->set('start_record', (($start_page-1)*$limit_count)+1 );
			$this->set('page', $start_page );
			$this->set('limit_count', $limit_count );
			$this->set('Allcnt', $data['Allcnt'] );
			$this->set('sort', $sort);

			if( empty($data['data']) ){
				$data['data'] = array();
			}

			$this->set('data', $data['data'] );
//pr($data);
		}

		$this->set( 'payment_status', $user['User']['payment_status'] );	// 支払いステータス
		$this->set( 'view_flg', $view_flg );	// falseは利用期限切れ
	}





	/**
	* User監視トレイ用 削除
	*
	* @param  none
	* @return none
	*/
	public function inbox_delete() {

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));

		$ir = $this->IllegalResult->getIR( $user['User']['user_uid'], $this->params['url']['iruid'] );
		foreach( $ir as $save ){
			$save['IllegalResult']['trash'] = 1;
			$this->IllegalResult->save( $save, false );
		}

		$this->redirect( $this->params['url']['currentUrl'] );
	}


	/**
	* User監視トレイ用 削除（AJAX版）
	*
	* @param  none
	* @return none
	*/
	public function aj_inbox_del() {
//		Configure::write('debug', 0);
		$this->layout="";

		if( false !== $save = $this->IllegalResult->find('first',array('conditions'=>array('illegal_result_uid'=>$this->params['url']['ir_id'])))){

			$save['IllegalResult']['trash'] = 1;
			$this->IllegalResult->save($save, false);

		}
	}



	/**
	* User監視トレイ用 削除要請中タグ
	*
	* @param  none
	* @return none
	*
	*/
	public function aj_inbox_request() {
		Configure::write('debug', 0);
		$this->layout="";

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));

//pr( $this->params );
		if( 'Enable' === $this->params['form']['flag'] ){
			$memo_request = 1;
		} else if( 'Disable' === $this->params['form']['flag'] ){
			$memo_request = 0;
		}


		$save = array();
		if( 'none' !== $this->params['form']['ir_id'] ){			// IllegalResultレコードを更新

			$ir = $this->IllegalResult->getIR( $user['User']['user_uid'], $this->params['form']['ir_id'] );
			foreach( $ir as $save ){
				$save['IllegalResult']['memo_request'] = $memo_request;
				$this->IllegalResult->save( $save, false );
			}

		} else if( 'none' !== $this->params['form']['dr_id'] ){	// DownloadResultレコードを更新

			$dr = $this->DownloadResult->getDR( $user['User']['user_uid'], $this->params['form']['dr_id'] );
			foreach( $dr as $save ){
				$save['DownloadResult']['memo_request'] = $memo_request;
				$this->DownloadResult->save( $save, false );
			}

		}

//		$this->set('data', $user['User']['user_uid']);

	}




	/**
	* User監視トレイ用 削除完了タグ
	*
	* @param  none
	* @return none
	*/
	public function aj_inbox_comp() {
		Configure::write('debug', 0);
		$this->layout="";

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));

//pr( $this->params );
		if( 'Enable' === $this->params['form']['flag'] ){
			$memo_complete = 1;
		} else if( 'Disable' === $this->params['form']['flag'] ){
			$memo_complete = 0;
		}


		$save = array();
		if( 'none' !== $this->params['form']['ir_id'] ){			// IllegalResultレコードを更新

			$ir = $this->IllegalResult->getIR( $user['User']['user_uid'], $this->params['form']['ir_id'] );
			foreach( $ir as $save ){
				$save['IllegalResult']['memo_complete'] = $memo_complete;
				$this->IllegalResult->save( $save, false );
			}

		} else if( 'none' !== $this->params['form']['dr_id'] ){	// DownloadResultレコードを更新

// 以下のじゃダメ 関連レコード全て更新しないとっ！

			$dr = $this->DownloadResult->getDR( $user['User']['user_uid'], $this->params['form']['dr_id'] );
			foreach( $dr as $save ){
				$save['DownloadResult']['memo_complete'] = $memo_complete;
				$this->DownloadResult->save( $save, false );
			}

		}


/*
		if( 'Enable' === $this->params['url']['flag'] ){
			$memo_complete = 1;
		} else if( 'Disable' === $this->params['url']['flag'] ){
			$memo_complete = 0;
		}

		$save = array();
		if( 'none' !== $this->params['url']['ir_id'] ){			// IllegalResultレコードを更新

			$save = $this->IllegalResult->find('first',array('conditions'=>array('illegal_result_uid'=>$this->params['url']['ir_id'])));
			$save['IllegalResult']['memo_complete'] = $memo_complete;
			$this->IllegalResult->save( $save, false );

		} else if( 'none' !== $this->params['url']['dr_id'] ){	// DownloadResultレコードを更新

			$save = $this->DownloadResult->find('first',array('conditions'=>array('download_result_uid'=>$this->params['url']['dr_id'])));
			$save['DownloadResult']['memo_complete'] = $memo_complete;
			$this->DownloadResult->save( $save, false );

		}
*/
//pr( $this->params );
	}



	/**
	* User監視トレイ用 テンプレート詳細AJAX
	*
	* @param  none
	* @return none
	*/
	public function aj_detail_template() {
//		Configure::write('debug', 0);
		$this->layout="";




//		echo json_encode($ic);
		$this->render('/users/ajx');
	}




	/**
	* User監視トレイ用 テンプレート初期化AJAX
	*
	* @param  none
	* @return none
	*/
	public function aj_template_initial() {
		Configure::write('debug', 0);
		$this->layout="";

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));
		$user = $this->User->decryptedUser($user);// 2012.10.13 復号化


		$list = $this->Template->find('all',array('fields'=>array('template_uid','template_name'), 'conditions'=>array('deleted'=>0), 'order'=>array('template_uid ASC') ));


		$uid = $this->params['url']['uid'];
		$is_site = false;
		$ds_site = false;

		if( $uid === '' ) {		// 2012.10.10 アップローダが特定できなくてもテンプレート昨日をONにするため
			$destination = '';
			$contactus = '';
			$site_name = '';

		} else if( 'is' === $this->params['url']['mode'] ){
			$site = $this->IllegalSite->getTempInfo( $uid );
			$is_site = $site;
			$destination = $site[0]['IRS']['contact_mail'];
			$contactus = $site[0]['IRS']['contact_url'];
			$site_name = $site[0]['IRS']['site_name'];

		} else if( 'ds' === $this->params['url']['mode'] ){
			$site = $this->DownloadSite->getTempInfo( $uid );
			$ds_site = $site;
			$destination = $site[0]['DRS']['contact_mail'];
			$contactus = $site[0]['DRS']['contact_url'];
			$site_name = $site[0]['DRS']['site_name'];

		}



		// テンプレートの置換
		$template = $this->Template->getTemplate( $list[0]['Template']['template_uid'], $user['User']['user_uid'], $is_site, $ds_site );

		// 送信者を取得
		$prof = $this->UserProfile->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));
		$prof = $this->UserProfile->decryptedProf($prof);	// 復号化

		$sender = $user['User']['mail_address'];
		if( $prof !== false ){
			if( $prof['UserProfile']['creator_mail_address'] !== null && $prof['UserProfile']['creator_mail_address'] !== ''  ){
				$sender = $prof['UserProfile']['creator_mail_address'];
			}
		}


		$aryProf = array(
			'destination'	=> $destination,
			'contactus'		=> $contactus,
			'sender'		=> $sender,
			'site_name'		=> $site_name
		);


		$data = array();
		$data['list']	= $list;
		$data['tmp']	= $template;
		$data['prof']	= $aryProf;
//		$data['site']	= $site[0];
		if( $uid === '' ){
			$data['site']	= false;
		} else {
			$data['site']	= $site[0];
		}

		echo json_encode($data);
		$this->render('/users/ajx');
	}



	/**
	* User監視トレイ用 テンプレート変更AJAX
	*
	* @param  none
	* @return none
	*/
	public function aj_template_change() {
		Configure::write('debug', 0);
		$this->layout="";

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));

//		$list = $this->Template->find('all',array('fields'=>array('template_uid','template_name'), 'conditions'=>array('deleted'=>0), 'order'=>array('template_uid ASC') ));


		$uid = $this->params['url']['uid'];
		$is_site = false;
		$ds_site = false;
		$notfound_sitename = false;

		if( $uid === '' ) {		// 2012.10.10 アップローダが特定できなくてもテンプレート機能をONにするため
			$destination = '';
			$contactus = '';
			$site_name = '';

		} else if( $uid === 'none' ){	// アップローダnotfoundの場合
//			$site = $this->DownloadSite->getTempInfoNf
			$notfound_sitename['name'] = $this->params['url']['site_name'];
			$notfound_sitename['iruid'] = $this->params['url']['iruid'];

		} else if( 'is' === $this->params['url']['mode'] ){
			$site = $this->IllegalSite->getTempInfo( $uid );
			$is_site = $site;

		} else if( 'ds' === $this->params['url']['mode'] ){
			$site = $this->DownloadSite->getTempInfo( $uid );
			$ds_site = $site;

		}


		// テンプレートの置換
		$template = $this->Template->getTemplate( $this->params['url']['tmp_uid'], $user['User']['user_uid'], $is_site, $ds_site, $notfound_sitename );


		$data = array();
		$data['tmp']	= $template;

		echo json_encode($data);
		$this->render('/users/ajx');
	}





	/**
	* User監視トレイ用 メール送信AJAX
	*
	* @param  none
	* @return none
	*/
	public function aj_template_send() {
		Configure::write('debug', 0);
		$this->layout="";

		$this->Email->to			= $this->params['form']['templateDestination'];
		$this->Email->from			= $this->params['form']['templateSender'];
		$this->Email->subject		= $this->params['form']['templateSubject'];
		$this->Email->sendAs		= 'text';
		$this->Email->delivery		= 'mail';
		$this->Email->delivery		= 'smtp';
		$this->Email->lineLength	= 500;
		$this->Email->smtpOptions	= array(
			'port'		=>	25,
			'host'		=>	'localhost',
			'timeout'	=>	30
		);
		$this->Email->send( $this->params['form']['templateTextBody'] );

		// 自分に転送
		if( 'true' === $this->params['form']['templateTransfer'] ){
			$this->Email->to			= $this->params['form']['templateSender'];
			$this->Email->send( $this->params['form']['templateTextBody'] );
		}


//		sleep(1);

		$this->render('/users/ajx');
	}




	/**
	* User 監視トレイ ゴミ箱
	*
	* @param  none
	* @return none
	*/
	public function trash() {

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));


		// 監視結果を取得
		$view_flg = true;
		if( $user['User']['payment_status'] != 0 ){
//			if( $user['User']['payment_status'] == 2 && strtotime($user['User']['regist_date']) < strtotime("-3 day") ){	// 体験版で登録日から３日経過はアウト
			if( $user['User']['payment_status'] == 2 && strtotime("now") > strtotime($user['User']['current_period']) ){	// 体験版で且つ期限切れの場合
				$view_flg = false;
			}
		} else {
			$view_flg = false;
		}

		if( $view_flg == true ){
			$data = $this->IllegalResult->trash( $user['User']['user_uid'] );

//			$this->set('start_record', (($start_page-1)*$limit_count)+1 );
//			$this->set('page', $start_page );
//			$this->set('limit_count', $limit_count );
//			$this->set('Allcnt', $data['Allcnt'] );

			$this->set('data', $data['data'] );
		}
	}



	/**
	* User監視トレイ ゴミ箱 監視トレイに戻す
	*
	* @param  none
	* @return none
	*/
	public function aj_inbox_restore() {
		Configure::write('debug', 0);
		$this->layout="";

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));

		$ir = $this->IllegalResult->getIR( $user['User']['user_uid'], $this->params['url']['ir_id'] );
		foreach( $ir as $save ){
			$save['IllegalResult']['trash'] = 0;
			$this->IllegalResult->save( $save, false );
		}

$this->set('data', $this->params['url']['ir_id']);

		$this->render('/users/ajx');
	}







	/**
	* User監視ワード
	*
	* @param  none
	* @return none
	*/
	public function words() {

		App::import('Sanitize');

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得

		$word_count = $user['User']['current_volume'];
		if ( $this->RequestHandler->isPost() ) {

//pr($this->data);

			// postデータ整形
			$i = 0;
			$dpost = array();
			foreach( $this->data['User'] as $key => $val ){

				$val = Sanitize::stripScripts($val);
				$val = Sanitize::stripImages($val);
				$val = trim($val);

				if( false !== strpos($key, 'word') ){
					$i++;
					$dpost[$i]['word'] = $val;
				} else if( false !== strpos($key, 'work_name') ){
					$dpost[$i]['work_name'] = $val;
				} else if( false !== strpos($key, 'work_url_jp') ){
					$dpost[$i]['work_url_jp'] = $val;
				} else if( false !== strpos($key, 'work_url_en') ){
					$dpost[$i]['work_url_en'] = $val;
				}

			}

//pr( $dpost );

			foreach( $dpost as $key => $val ){

				$pos = $key;
/*				foreach( $val as $v ){
//pr($val);
					$v = Sanitize::stripScripts($v);
					$v = trim($v);
pr($v);
				}
*/
//pr('pos: '.$pos);
				$beforeWord = $this->Word->find('first', array('conditions'=>array( 'position'=>$pos, 'user_uid'=>$user['User']['user_uid'], 'deleted'=>0 )));


				if( $beforeWord == false ){
					if( $val['word'] === '' ){
//pr('1:'.$val['word']);
						// 過去もなく今回もない場合はスルー
					} else {
//pr('2:'.$val['word']);
						// 過去にないが入力あった場合は新規追加
						$this->Word->create();
						$save = array();
						$save['Word']['search_word']	= $val['word'];
						$save['Word']['position']		= $pos;
						$save['Word']['user_uid']		= $user['User']['user_uid'];
						$save['Word']['work_name']		= $val['work_name'];
						$save['Word']['work_url_jp']	= $val['work_url_jp'];
						$save['Word']['work_url_en']	= $val['work_url_en'];
						$save = $this->Word->encryptedWord($save);	// 暗号化
						$this->Word->save($save, false);
					}
				} else {
//pr('3:'.$val['word']);
					if( $val['word'] === '' ){					// 過去にあるが今回ない場合は過去を論理削除のみ
//pr('4:'.$val['word']);
						$beforeWord['Word']['deleted'] = 1;
						$this->Word->save($beforeWord, false);
					} else {	// 過去にあって今回もある
//pr('5:'.$val['word']);
						$buf['Word']['search_word'] = $val['word'];	//暗号用の器に格納
						$buf = $this->Word->encryptedWord($buf);	// 照合の為暗号化
						if( $buf['Word']['search_word'] !== $beforeWord['Word']['search_word'] ){
//pr('6:'.$val['word']);
							// 過去と違う場合は過去を論理削除して新規追加
							$beforeWord['Word']['deleted'] = 1;
							$this->Word->save($beforeWord, false);

							$this->Word->create();
							$save = array();
							$save['Word']['search_word']	= $val['word'];
							$save['Word']['position']		= $pos;
							$save['Word']['user_uid']		= $user['User']['user_uid'];
							$save['Word']['work_name']		= $val['work_name'];
							$save['Word']['work_url_jp']	= $val['work_url_jp'];
							$save['Word']['work_url_en']	= $val['work_url_en'];
							$save = $this->Word->encryptedWord($save);	// 暗号化
							$this->Word->save($save, false);
						} else {
//pr('7:'.$val['word']);
							// 過去にあるがワードが同じなら更新
							$save = array();
							$save = $this->Word->decryptedWord($beforeWord);	// 復号化
//							$save['Word']['search_word']	= $val['word'];
//							$save['Word']['position']		= $pos;
//							$save['Word']['user_uid']		= $user['User']['user_uid'];
							$save['Word']['work_name']		= $val['work_name'];
							$save['Word']['work_url_jp']	= $val['work_url_jp'];
							$save['Word']['work_url_en']	= $val['work_url_en'];
							$save = $this->Word->encryptedWord($save);	// 暗号化
							$this->Word->save($save, false);
						}
					}
				}

			}

			$this->set('regist_flag', true );

		} else {

			$words = $this->Word->find('all', array('conditions'=>array('user_uid'=>$user['User']['user_uid'], 'deleted'=>0)));
//pr($words);
			foreach( $words as $k => $v ){
				$words[$k] = $this->Word->decryptedWord($v);	// 復号化
			}

			foreach( $words as $val ){
				$num = $val['Word']['position'];
				$key = 'word' . $num;
				$this->data['User'][$key] = $val['Word']['search_word'];
				$key = 'work_name' . $num;
				$this->data['User'][$key] = $val['Word']['work_name'];
				$key = 'work_url_jp' . $num;
				$this->data['User'][$key] = $val['Word']['work_url_jp'];
				$key = 'work_url_en' . $num;
				$this->data['User'][$key] = $val['Word']['work_url_en'];
			}

			for( $i=1; $i<=$word_count; $i++ ){
				$key = 'word' . $i;
				if( !isset( $this->data['User'][$key] ) ){
					$this->data['User'][$key] = '';
					$key = 'work_name' . $i;
					$this->data['User'][$key] = '';
					$key = 'work_url_jp' . $i;
					$this->data['User'][$key] = '';
					$key = 'work_url_en' . $i;
					$this->data['User'][$key] = '';
				}
			}

		}

//pr($word_count);
		$this->set('word_count', $word_count );
		$this->set('payment_status', $user['User']['payment_status'] );

	}




	/**
	* User監視ワード
	*
	* @param  none
	* @return none
	*/
	public function old_words() {

		App::import('Sanitize');

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得
//pr($user);

		$word_count = $user['User']['current_volume'];
//pr($this->data);

		if ( $this->RequestHandler->isPost() ) {
//pr($this->data);

//			$words = $this->Word->find('all', array('conditions'=>array('user_uid'=>$user['User']['user_uid'], 'deleted'=>0)));
			foreach( $this->data['User'] as $key => $val ){
//pr($val);
//				$val = Sanitize::html($val);
				$val = Sanitize::stripScripts($val);
				$val = trim($val);

				// POS以外のKeyはcontinue
				if( !preg_match("/word[0-9]/", $key) ){
					continue;
				}

				$pos = str_replace ('word', '', $key);

				$beforeWord = $this->Word->find('first', array('conditions'=>array( 'position'=>$pos, 'user_uid'=>$user['User']['user_uid'], 'deleted'=>0 )));

				if( $beforeWord === false ){
					if( $val === '' ){
						// 過去もなく今回もない場合はスルー
					} else {
						// 過去にないが入力あった場合は新規追加
						$this->Word->create();
						$save['Word']['search_word']	= $val;
						$save['Word']['position']		= $pos;
						$save['Word']['user_uid']		= $user['User']['user_uid'];
						$save = $this->Word->encryptedWord($save);	// 暗号化
						$this->Word->save($save, false);
					}

				} else {

					if( $val === '' ){					// 過去にあるが今回ない場合は過去を論理削除のみ
						$beforeWord['Word']['deleted'] = 1;
						$this->Word->save($beforeWord, false);

					} else {	// 過去にあって今回もある

						$buf['Word']['search_word'] = $val;	//暗号用の器に格納
						$buf = $this->Word->encryptedWord($buf);	// 照合の為暗号化
						if( $buf['Word']['search_word'] !== $beforeWord['Word']['search_word'] ){
							// 過去と違う場合は過去を論理削除して新規追加
							$beforeWord['Word']['deleted'] = 1;
							$this->Word->save($beforeWord, false);

							$this->Word->create();
							$save['Word']['search_word']	= $val;
							$save['Word']['position']		= $pos;
							$save['Word']['user_uid']		= $user['User']['user_uid'];
							$save = $this->Word->encryptedWord($save);	// 暗号化
							$this->Word->save($save, false);

						} else {
							// 過去にあるがワードが同じならスルー
						}
					}
				}

			}

			$this->set('regist_flag', true );

		} else {

			$words = $this->Word->find('all', array('conditions'=>array('user_uid'=>$user['User']['user_uid'], 'deleted'=>0)));
//pr($words);
			foreach( $words as $k => $v ){
				$words[$k] = $this->Word->decryptedWord($v);	// 復号化
			}

			foreach( $words as $val ){
				$num = $val['Word']['position'];
				$key = 'word' . $num;
				$this->data['User'][$key] = $val['Word']['search_word'];
			}

			for( $i=1; $i<=$word_count; $i++ ){
				$key = 'word' . $i;
				if( !isset( $this->data['User'][$key] ) ){
					$this->data['User'][$key] = '';
				}
			}

		}

//pr($word_count);
		$this->set('word_count', $word_count );
		$this->set('payment_status', $user['User']['payment_status'] );

	}



	/**
	* User違法サイト一覧
	*
	* @param  none
	* @return none
	*/
	public function ilist() {

		App::import('Sanitize');

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得

		$cnt = array();
		$cnt['is']				= $this->IllegalSite->find('count', array('conditions'=>array('deleted'=>0)));
		$cnt['ds']				= $this->DownloadSite->find('count', array('conditions'=>array('deleted'=>0)));
		$cnt['new']				= $this->IllegalSite->find('count', array('conditions'=>array('deleted'=>0, 'flag_new'=>1)));
		$cnt['all']				= $this->IllegalSite->find('count', array('conditions'=>array('deleted'=>0, 'flag_all'=>1)));

		$cnt['flag_tv_game']	= $this->IllegalSite->find('count', array('conditions'=>array('deleted'=>0, 'flag_tv_game'=>1)));
		$cnt['flag_pc_game']	= $this->IllegalSite->find('count', array('conditions'=>array('deleted'=>0, 'flag_pc_game'=>1)));
		$cnt['flag_digi_doujin']= $this->IllegalSite->find('count', array('conditions'=>array('deleted'=>0, 'flag_digi_doujin'=>1)));
		$cnt['flag_doujinshi']	= $this->IllegalSite->find('count', array('conditions'=>array('deleted'=>0, 'flag_doujinshi'=>1)));
		$cnt['flag_comic']		= $this->IllegalSite->find('count', array('conditions'=>array('deleted'=>0, 'flag_comic'=>1)));
		$cnt['flag_music']		= $this->IllegalSite->find('count', array('conditions'=>array('deleted'=>0, 'flag_music'=>1)));
		$cnt['flag_anime']		= $this->IllegalSite->find('count', array('conditions'=>array('deleted'=>0, 'flag_anime'=>1)));
		$cnt['flag_ova']		= $this->IllegalSite->find('count', array('conditions'=>array('deleted'=>0, 'flag_ova'=>1)));
		$cnt['flag_av']			= $this->IllegalSite->find('count', array('conditions'=>array('deleted'=>0, 'flag_av'=>1)));
		$cnt['flag_etc']		= $this->IllegalSite->find('count', array('conditions'=>array('deleted'=>0, 'flag_etc'=>1)));


		$view_report = true;
		if ( $this->RequestHandler->isPost() ) {

			$this->sendMail_admin( $user, Sanitize::stripScripts($this->data['User']['report']), '[物見info] 不正サイト報告（返信不要）' );		// メール送信
			$view_report = false;
		}

		$this->set( 'cnt', $cnt );
		$this->set( 'view_report', $view_report );
	}


	/**
	* Userダウンローダ一覧
	*
	* @param  none
	* @return none
	*/
	public function dlist() {

		$list = $this->DownloadSite->find('all', array('conditions'=>array('deleted'=>0), 'order'=>array('site_name ASC')));

		$cnt['dlist'] = count($list);
		$cnt['ilist'] = $this->IllegalSite->find('count', array('conditions'=>array('deleted'=>0), 'order'=>array('site_name ASC')));

		$this->set( 'cnt', $cnt );
		$this->set( 'list', $list );

	}




	/**
	* 違法サイト/アップローダ詳細
	*
	* @param  none
	* @return none
	*/
	public function detail() {

		// 苦肉の策ｗ SSL表示だとサムネイル部分で警告でるので、このアクションだけSSLを外す
//		if($this->RequestHandler->isSSL()){
//		    $this->redirect("http://".env('SERVER_NAME').$this->here);
//		}

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得


		// 第２パラメータ uid
		if( isset($this->params['pass'][1]) ){
			$uid = $this->params['pass'][1];
		} else {
			$this->redirect(array('action'=>'dashboard'));	// パラメータが正常でない場合、ダッシュボードへリダイレクト
		}



		// POSTされた場合
		if ( $this->RequestHandler->isPost() ) {

			$save = array();
			$save['Forum'] = $this->data['Forum'];
			$save['Forum']['user_uid'] = $user['User']['user_uid'];

			if( 'is' === $this->params['pass'][0] ){
				$save['Forum']['site_uid'] = $uid;
			} else {
				$save['Forum']['download_site_uid'] = $uid;
			}

			$this->Forum->create();
			$this->Forum->save( $save );

		}




		// 第１パラメータ 違法サイトorアップローダ
		if( isset($this->params['pass'][0]) ){
			if( 'is' === $this->params['pass'][0] ){
				$data = $this->IllegalSite->find('first', array('conditions'=>array('deleted'=>0, 'site_uid'=>$uid)));
				$msg  = $this->Forum->find('all', array('conditions'=>array('deleted'=>0, 'site_uid'=>$uid), 'order'=>array('created DESC')));

			} else if( 'ds' === $this->params['pass'][0] ){
				$data = $this->DownloadSite->find('first',array('conditions'=>array('deleted'=>0, 'download_site_uid'=>$uid)));
				$msg  = $this->Forum->find('all', array('conditions'=>array('deleted'=>0, 'download_site_uid'=>$uid), 'order'=>array('created DESC')));

			} else {
				$this->redirect(array('action'=>'dashboard'));	// パラメータが正常でない場合、ダッシュボードへリダイレクト
			}
		}

		if( $data == false ){	// 対象データが存在しない場合
			$this->redirect(array('action'=>'dashboard'));	// ダッシュボードへリダイレクト
		}

//pr($data);
		$this->set( 'mode', $this->params['pass'][0] );
		$this->set( 'uid', $uid );
		$this->set( 'user', $user );
		$this->set( 'msg', $msg );
		$this->set( 'data', $data );

	}



	/**
	* 違法サイト/アップローダ詳細用フォーラムコメント削除
	*
	* @param  none
	* @return none
	*/
	public function forum_delete() {

//pr($this->params['url']);
		$uid = $this->params['url']['uid'];
		$this->Forum->delete( $uid );

		$url = '/users/detail/' . $this->params['url']['mode'] . '/' . $this->params['url']['sid'] . '/';
		$this->redirect( $url );
	}






	/**
	* Userアカウント情報
	*
	* @param  none
	* @return none
	*/
	public function profile() {

		App::import('Sanitize');

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得


		if ( $this->RequestHandler->isPost() ) {
//pr($this->data);

			// バリデーション
			$res = $this->User->profile_validate( $this->data, $user );
//pr($this->data);
			if( true === $res ){

				// 登録処理
				$save = $user;
				if( $this->data['User']['mail_address'] !== '' ){
					$save['User']['mail_address'] = $this->data['User']['mail_address'];

				}
				if( $this->data['User']['passwd'] !== '' ){
					$save['User']['passwd'] = $this->data['User']['passwd'];
				}

				$this->User->save($save, false);
				$this->set('msg', '変更しました');

			} else {
				$this->set('vali', $res);
			}

		}


		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 再度、最新のUser情報を取得
		$user = $this->User->decryptedUser( $user );	// 表示のため、現在のメールアドレスを復号
		$this->set('mail_address', $user['User']['mail_address']);
		$this->set('user', $user);

	}






	/**
	* Userアカウント再契約
	*
	* @param  none
	* @return none
	*/
	public function t_resign() {

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得

		if( $user['User']['payment_status'] == 1 && $user['User']['period_status'] == 1 ){	// 契約期間が残っている場合は、処理せずリダイレクト
			$this->redirect('/users/profile/');	// プロフィールへリダイレクト
		}

		$returnURL = 'https://monomi.info/regist/confirm/';
		$cancelURL = 'https://monomi.info/regist/cancel/';

		if ( $this->RequestHandler->isPost() ) {

			$product_buf = $this->Product->find('first',array('conditions'=>array('product_uid'=>$this->data['User']['plan'])));
//pr($product_buf);
			if( $product_buf == false ){	// 改ざんの可能性があるので、とりあえずTOPへリダイレクト
				$this->redirect('/users/dashboard/');	// TOPへリダイレクト
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


			// PayPal SetExpressCheckoutでtoken取得
			$exChkOut = $this->Paypal->getToken( $amount, $returnURL, $cancelURL, $itemName, $user['User']['user_uid'] );
			$ack = strtoupper($exChkOut["ACK"]);
			if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING") {		// チェックアウト成功

//pr($exChkOut);
				// Payment登録
				if( false != $payment = $this->Payment->find('first',array('conditions'=>array('user_uid'=>$user['User']['user_uid'], 'paypal_token'=>$exChkOut['TOKEN'] ))) ){
					$save_payment['Payment']['payment_uid'] = $payment['Payment']['payment_uid'];
					$save_payment['Payment']['deleted'] = 0;
				} else {
					$this->Payment->create();
					$save_payment['Payment']['user_uid'] = $user['User']['user_uid'];
					$save_payment['Payment']['paypal_token'] = $exChkOut['TOKEN'];
				}

//pr($save_payment);
				$this->Payment->save($save_payment, false);
				$this->Paypal->RedirectToPayPal( $exChkOut['TOKEN'] );	// ペイパル決済へリダイレクト
			} else {	// チェックアウト失敗
				$this->redirect(array('controller' => 'regist', 'action' => 'paypal_error'));	// ペイパルエラー画面へリダイレクト
			}
		}


		// 監視プラン一覧を取得
		if( $user['User']['beta_user'] == 0 ){		// 通常ユーザ
			$product = $this->Product->getProductList('normal');
		} else if( $user['User']['beta_user'] == 1 ){	// ベータ利用ユーザ
			$product = $this->Product->getProductList('beta');
		}
//pr($user);
//pr($product);
		$this->set('product', $product);

	}






	/**
	* Userアカウント再契約
	*
	* @param  none
	* @return none
	*/
	public function resign() {

//Configure::write('debug', 2);	// テストのため一時的に

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得

		if( $user['User']['payment_status'] == 1 && $user['User']['period_status'] == 1 ){	// 契約期間が残っている場合は、処理せずリダイレクト
			$this->redirect('/users/profile/');	// プロフィールへリダイレクト
		}

		$returnURL = 'https://monomi.info/regist/confirm/';
		$cancelURL = 'https://monomi.info/regist/cancel/';

		if ( $this->RequestHandler->isPost() ) {

			$product_buf = $this->Product->find('first',array('conditions'=>array('product_uid'=>$this->data['User']['plan'])));
//pr($product_buf);
			if( $product_buf == false ){	// 改ざんの可能性があるので、とりあえずTOPへリダイレクト
				$this->redirect('/users/dashboard/');	// TOPへリダイレクト
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


			// 2013.01.30 add 継続申込み時、過去に契約済みのuser_uidが使えないことから、契約開始日を文字列に追加
			if( $user['User']['regist_date'] != null ){
				$token_user_uid = $user['User']['user_uid'] . '__' . $user['User']['regist_date'];
			}
//pr($token_user_uid);
			if( false !== strpos( $token_user_uid, '__') ){
				$strBuf = explode('__', $token_user_uid );
				$user['User']['user_uid'] = $strBuf[0];
//				pr( $strBuf[0] );
			}


			// PayPal SetExpressCheckoutでtoken取得
//			$exChkOut = $this->Paypal->getToken( $amount, $returnURL, $cancelURL, $itemName, $user['User']['user_uid'] );
			$exChkOut = $this->Paypal->getToken( $amount, $returnURL, $cancelURL, $itemName, $token_user_uid );
			$ack = strtoupper($exChkOut["ACK"]);
			if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING") {		// チェックアウト成功

//pr($exChkOut);
				// Payment登録
				if( false != $payment = $this->Payment->find('first',array('conditions'=>array('user_uid'=>$user['User']['user_uid'], 'paypal_token'=>$exChkOut['TOKEN'] ))) ){
					$save_payment['Payment']['payment_uid'] = $payment['Payment']['payment_uid'];
					$save_payment['Payment']['deleted'] = 0;
				} else {
					$this->Payment->create();
					$save_payment['Payment']['user_uid'] = $user['User']['user_uid'];
					$save_payment['Payment']['paypal_token'] = $exChkOut['TOKEN'];
				}

//pr($save_payment);
				$this->Payment->save($save_payment, false);
				$this->Paypal->RedirectToPayPal( $exChkOut['TOKEN'] );	// ペイパル決済へリダイレクト

			} else {	// チェックアウト失敗
//pr($exChkOut);
				$this->redirect(array('controller' => 'regist', 'action' => 'paypal_error'));	// ペイパルエラー画面へリダイレクト
			}
		}


		// 監視プラン一覧を取得
		if( $user['User']['beta_user'] == 0 ){		// 通常ユーザ
			$product = $this->Product->getProductList('normal');
		} else if( $user['User']['beta_user'] == 1 ){	// ベータ利用ユーザ
			$product = $this->Product->getProductList('beta');
		}
//pr($user);
//pr($product);
		$this->set('product', $product);

	}





	/**
	* User テンプレート プロフィール
	*
	* @param  none
	* @return none
	*/
	public function template_prof() {

		App::import('Sanitize');

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得
		$prof = $this->UserProfile->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));

		if ( $this->RequestHandler->isPost() ) {
//pr($this->data);

			$save = array();
			$save['UserProfile'] = $this->data['UserProfile'];

			if( $prof !== false ){
				$save['UserProfile']['profile_uid'] = $prof['UserProfile']['profile_uid'];
			} else {
				$this->UserProfile->create();
				$save['UserProfile']['user_uid'] = $user['User']['user_uid'];
			}

//pr($save);
			// サニタイズ
			foreach( $save['UserProfile'] as $k => $v ){

//				$save['UserProfile'][$k] = Sanitize::html($v);
				$save['UserProfile'][$k] = Sanitize::stripScripts($v);

			}



			$save = $this->UserProfile->encryptedProf($save);	// 暗号化

			$this->UserProfile->save( $save );

			$this->set( 'post_flag', true );

		} else {
			if( $prof !== false ){

				$this->data = $this->UserProfile->decryptedProf($prof);	// 復号化
//				$this->data = $prof;
			}

			$this->set( 'post_flag', false );

		}

		$this->set('user', $user);

	}




	/**
	* Userヘルプ
	*
	* @param  none
	* @return none
	*/
	public function help() {

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得

		$this->set('user', $user);
	}




	/**
	* User招待キャンペーン
	*
	* @param  none
	* @return none
	*/
	public function campaign() {

	}








	/**
	* User解約
	*
	* @param  none
	* @return none
	*/
	public function cancel() {

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得

		$this->set('user', $user);
	}




	/**
	* User解約完了
	*
	* @param  none
	* @return none
	*/
	public function cancelled() {

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得

		// 即解約
		// 残期間がない（または無制限または体験版も）
		$pms = $user['User']['payment_status'];
		$pds = $user['User']['period_status'];
//		if( 0 == $user['User']['payment_status'] || 2 == $user['User']['payment_status'] || 3 == $user['User']['payment_status']  || 9 == $user['User']['payment_status'] ){
		if( 0==$pms || 3==$pms  || 9==$pms || ( 1==$pms && 0==$pds ) ){

			$user['User']['cancelled'] = 1;		// 解約フラグ
			$user['User']['user_status'] = 9;	// 退会
			$user['User']['period_status'] = 0;	// 契約期限切れ
			$save['DeletedUsers'] = $user['User'];	// ユーザ情報を削除テーブルに移動
			$this->DeletedUsers->save($save);
			$this->User->delete( $user['User']['user_uid'], false );

			// Authをリフレッシュしリダイレクト
			$this->Auth->logout();
			$this->redirect('/pgs/cancelled/');

		} else if( 2==$pms ) {		// 体験版の場合

			$user['User']['cancelled'] = 1;		// 解約フラグ
			$user['User']['user_status'] = 9;	// 退会
			$user['User']['period_status'] = 0;	// 契約期限切れ
			$this->User->save( $user, false );

			// Authをリフレッシュしリダイレクト
			$this->Auth->logout();
			$this->redirect('/pgs/cancelled/');

		} else if( 1==$pms && 1==$pds ) {		// スタンダードプランで契約期限が残っている場合

			if( $user['User']['cancelled'] != 1 ){

				$user['User']['cancelled'] = 1;
				$this->User->save( $user, false );

				// 解約完了メッセージを登録
				$title = '解約手続きを受け付けました';
				$text  = 'ご利用期間経過後、全ての個人情報を削除します。<br />';
				$this->Message->setMsg( $user, $title, $text );

				$this->set('cancelled', false);

			} else {	// 既に解約済みの場合

				$this->set('cancelled', true);
			}

		}

	}




	/**
	* User問合せ
	*
	* @param  none
	* @return none
	*/
	public function contact() {

//		$user = $this->Auth->user();	// ユーザ情報取得
		App::import('Sanitize');

		if ( $this->RequestHandler->isPost() ) {

			$this->UserInquiry->set( $this->data );		// バリデーション
			if( $this->UserInquiry->validates() ){	// バリデーションチェックし問題なければ確認画面へ遷移

				$this->data['UserInquiry']['text'] = Sanitize::stripScripts($this->data['UserInquiry']['text']);
				$this->Session->write( 'form_data', $this->data );	// セッションにデータを保存
				$this->redirect(array('action'=>'iconfirm'));			// iconfirmへリダイレクト

			}
		}

	}





	/**
	* User問合せ 確認
	*
	* @param
	* @return none
	*/
	public function iconfirm() {

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得
//pr($user);

		$data = $this->Session->read( 'form_data' );	// セッション読込
		if( false == $data ){
			$this->redirect(array('action'=>'contact'));	// セッションが正常に読込できない場合、formへ戻す
		}

		if ( $this->RequestHandler->isPost() ) {
			$this->sendMail_admin( $user, $this->data['UserInquiry']['text'], '[物見info] 会員より問合せ' );		// メール送信
			$this->sendMail_client( $user, $this->data['UserInquiry']['text'] );		// メール送信
			$this->redirect(array('action'=>'ithx'));								// ithxへリダイレクト
		}

		$this->set( 'data', $data );

	}


	/**
	* User問合せ 完了
	*
	* @param
	* @return none
	*/
	public function ithx() {

	}



	/**
	* User パスワード忘れ
	*
	* @param
	* @return none
	*/
	public function rms() {

		$view_flag = '';
		if ( $this->RequestHandler->isPost() ) {

			// 登録済みメールアドレスか確認
//pr($this->data);
			$mail_address = $this->data['User']['mail_address'];
//			$mail_address = $this->$this->data['User']['mail_address'];	// 暗号化して照合

			$user = $this->User->find('first', array('conditions'=>array('mail_address'=>$mail_address)));

			if( false === $user ){
				$view_flag = 'none_mail';
			} else {
				// 登録済みならパスワードを採番
				$strinit = "abcdefghkmnprstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ12345679";
				$strarray = preg_split("//", $strinit, 0, PREG_SPLIT_NO_EMPTY);
				for ($i = 0, $str = null; $i < 10; $i++) {
					$str .= $strarray[array_rand($strarray, 1)];
				}
//pr('str: '.$str);
//				$user['User']['passwd'] = $str;
				$user['User']['passwd'] = base64_encode($this->User->encryptedData($str, CERT_KEY));
//pr('str: '.$user['User']['passwd']);
//pr($user);
				$this->User->save( $user, false );	// 新パスワードを上書き登録

				// メール送信
				$this->sendMail_new_password( $user );

				$view_flag = 'send_mail';
			}
		}

		$this->set( 'view_flag', $view_flag );
	}






	/**
	* User 削除申請フォーム
	*
	* @param
	* @return none
	*/
	public function dmca() {

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得
		$user = $this->User->decryptedUser($user);


		// パラメータ
		$uid = '';
		$mode = '';
		$iruid = '';
		if( isset($this->params['pass'][0]) ){ $mode = $this->params['pass'][0]; }
		if( isset($this->params['pass'][1]) ){ $uid = $this->params['pass'][1]; }
		if( $uid === '' || $mode === '' ){
//			$this->redirect('/users/inbox/');

//pr($this->params['url']);
			if( isset($this->params['url']['iruid']) ){		// NotFoundからアップロード先選択を経由してきた場合

				$list	= $this->IllegalResult->dmca_list( 'is', $this->params['url']['iruid'] );	// step1 不正サイト一覧を取得
				$select	= $this->Template->getSelect();
				$send	= $this->ShareSendList->getSendListNf( $this->params['url']['dsuid'] );

				$mode = 'ds';
				$uid = 'none';
				$this->set('dsuid',$this->params['url']['dsuid']);
				$this->set('worduid',$this->params['url']['worduid']);

				// 2012.12.3 add
				$iruid = $this->params['url']['iruid'];

			} else {
				$this->redirect('/users/inbox/');		// パラメータが不足している場合、監視トレイへリダイレクト

			}

		} else {

			// 情報取得
			$list	= $this->IllegalResult->dmca_list( $mode, $uid );	// step1 不正サイト一覧を取得
			$select	= $this->Template->getSelect( $mode, $uid );
			$send	= $this->ShareSendList->getSendList( $mode, $uid );

		}


		$sender = $this->UserProfile->get_sender( $user );
//pr($send);
		$this->set('list',$list);	// step1
		$this->set('select',$select);	// step2
		$this->set('send',$send);
		$this->set('mode',$mode);
		$this->set('uid',$uid);
		$this->set('user', $user);
		$this->set('ref', $this->referer());
		$this->set('sender', $sender);

		// 2012.12.3 add
		$this->set('iruid', $iruid);


		// 2012.12.05 add
		if( isset($this->params['url']['bl']) ){
			$this->set('backlink', str_replace( '_post', '#post', $this->params['url']['bl']));
		} else {
			$this->set('backlink', '/users/inbox/');
		}

	}







	/**
	* User 削除申請フォーム送信完了
	*
	* @param
	* @return none
	*/
	public function sendcomp() {

		App::import('Sanitize');

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得
		$user = $this->User->decryptedUser($user);

		if ( $this->RequestHandler->isPost() ) {
//pr( $this->data );

			$mail = $this->data['Temp'];
			$to = array();
			$cc = array();
			if( $mail['Destination1'] !== '' ){ array_push($to, $mail['Destination1']); }
			if( $mail['Destination2'] !== '' ){ array_push($to, $mail['Destination2']); }
			if( $mail['Destination3'] !== '' ){ array_push($to, $mail['Destination3']); }
			if( $mail['CC1'] !== '' ){ array_push($cc, $mail['CC1']); }
			if( $mail['CC2'] !== '' ){ array_push($cc, $mail['CC2']); }
			if( $mail['CC3'] !== '' ){ array_push($cc, $mail['CC3']); }

			// 2012.12.05 add
//			$mail['subject']	= mb_convert_encoding( $mail['subject'], 'ISO-2022-JP', 'UTF-8');
//			$mail['body_text']	= mb_convert_encoding( $mail['body_text'], 'ISO-2022-JP', 'UTF-8');

			$this->Email->reset();
			$this->Email->to			= $to;
			$this->Email->cc			= $cc;
			$this->Email->from			= $mail['sender'];
			$this->Email->subject		= $mail['subject'];

			// 2012.12.05 add
//			$this->Email->language = 'Japanese';
//			$this->Email->charset  = 'ISO-2022-JP';

			$this->Email->sendAs		= 'text';
//			$this->Email->delivery		= 'mail';
			$this->Email->delivery		= 'smtp';
			$this->Email->lineLength	= 300;
			$this->Email->smtpOptions	= array(
				'port'		=>	25,
				'host'		=>	'localhost',
				'timeout'	=>	30
			);

			if( $mail['transfer'] == 1 ){
				$this->Email->bcc			= array( $mail['sender'] );
			}


			// DBへ送信履歴を保存
			$save = array();
			$save['SendMail']['user_uid']		= $user['User']['user_uid'];
			$save['SendMail']['from_address']	= $mail['sender'];
			$save['SendMail']['to_address_1']	= $mail['Destination1'];
			$save['SendMail']['to_address_2']	= $mail['Destination2'];
			$save['SendMail']['to_address_3']	= $mail['Destination3'];
			$save['SendMail']['cc_1']			= $mail['CC1'];
			$save['SendMail']['cc_2']			= $mail['CC2'];
			$save['SendMail']['cc_3']			= $mail['CC3'];
			$save['SendMail']['send_date']		= date("Y-m-d H:i:s");


			// メール送信
			$success_flag = 0;
			if( false != $this->Email->send( $mail['body_text'] ) ){
				$save['SendMail']['success_flag']	= 1;
				$success_flag = 1;

			} else {
				$save['SendMail']['success_flag']	= 0;
				$success_flag = 0;
			}

			$save = $this->SendMail->encryptedWord($save);	//暗号化

			$this->SendMail->create();
			$this->SendMail->save($save, false);
//pr( $save );


			// 削除申請中ステータスを更新
			if( $save['SendMail']['success_flag'] == 1 ){

				if( $mail['HiddenUid'] !== 'none' ){

					$aryUid = explode(",", $mail['HiddenAryId'] );
					foreach( $aryUid as $uid ){
						if( $mail['HiddenMode'] === 'is' ){
							// uidではなく同url全てステータスを更新
							$ir = $this->IllegalResult->getIR( $user['User']['user_uid'], $uid );
							foreach( $ir as $save ){
									$save['IllegalResult']['memo_request'] = 1;
								$this->IllegalResult->save( $save, false );
							}
						} elseif( $mail['HiddenMode'] === 'ds' ){
							// uidではなく同url全てステータスを更新
							$dr = $this->DownloadResult->getDR( $user['User']['user_uid'], $uid );
							foreach( $dr as $save ){
								$save['DownloadResult']['memo_request'] = 1;
								$this->DownloadResult->save( $save, false );
							}
						}
					}

				} else {		// notFound経由の場合、ダウンロードリザルトが存在しないため、新たにレコードを作りステータスを更新

					$ir_uid = $mail['HiddenAryId'];		// notFound経由の場合は、一つしかIDがないため、直接使う
					// ダウンロードリザルトを作る
					$save = array();
					$save['DownloadResult']['download_site_uid'] = $mail['HiddenDsUid'];
					$save['DownloadResult']['word_uid'] = $mail['HiddenWordUid'];
					$save['DownloadResult']['download_result_url'] = '';
					$save['DownloadResult']['memo_request'] = 1;
					$this->DownloadResult->create();
					$this->DownloadResult->save($save, false);

					// リレーションを作る
					$save = array();
					$save['ResultRelation']['download_result_uid']	= $this->DownloadResult->getInsertID();
					$save['ResultRelation']['illegal_result_uid']	= $ir_uid;
					$this->ResultRelation->create();
					$this->ResultRelation->save($save, false);

				}

			}

			$this->set('ref', $mail['HiddenRef']);	// 監視トレイのURL
			$this->set('success_flag', $success_flag);
			$this->set('backlink', $mail['HiddenBackLink']);

		} else {
			$this->redirect('/users/inbox/');
		}



	}









	/**
	* User 削除申請先アップローダ選択画面
	*
	* @param
	* @return none
	*/
	public function dmcanf() {

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得
//		$user = $this->User->decryptedUser($user);

		// パラメータ
		$uid = '';
		$mode = '';
		if( isset($this->params['pass'][0]) ){ $uid = $this->params['pass'][0]; }
		if( isset($this->params['pass'][1]) ){ $word_uid = $this->params['pass'][1]; }
		if( $uid === '' || $word_uid === '' ){ $this->redirect('/users/inbox/'); }	// パラメータが不足している場合、監視トレイへリダイレクト

		$ds = $this->DownloadSite->find('all', array('order'=>array('site_name ASC'), 'conditions'=>array('deleted'=>0)));	// アップローダ情報取得
		$lr = $this->IllegalResult->find('first', array('conditions'=>array('illegal_result_uid'=>$uid)));	// アップローダ情報取得

		$this->set('word_uid', $word_uid);
		$this->set('ds', $ds);
		$this->set('lr', $lr);

		// 2012.12.05 add
		if( isset($this->params['url']['bl']) ){
			$this->set('backlink', str_replace( '_post', '#post', $this->params['url']['bl']));
		} else {
			$this->set('backlink', '/users/inbox/');
		}
	}









	/**
	* sendMail for Administrator
	*
	* @param
	* @return none
	*/
	private function sendMail_admin( $user, $text, $subject ) {

		// 2012.10.15 add 復号化
		$user = $this->User->decryptedUser($user);
//pr($user);

		// 送信メッセージ本文を生成
		$msg = '';
		$msg .= '送信者ID:  '. $user['User']['user_uid'] . "\r\n";
		$msg .= '送信者EMAIL:  '. $user['User']['mail_address'] . "\r\n";
		$msg .= "\r\n";
		$msg .= "===================================================\r\n";
		$msg .= "\r\n";
		$msg .= $text . "\r\n";
		$msg .= "\r\n";
		$msg .= "===================================================\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "****************************************************\r\n";
		$msg .= "物見info\r\n";
		$msg .= "\r\n";
		$msg .= "****************************************************\r\n";

		$this->Email->reset();

		$subject= mb_convert_encoding( $subject, 'ISO-2022-JP', 'UTF-8');
		$msg	= mb_convert_encoding( $msg, 'ISO-2022-JP', 'UTF-8');

		$this->Email->to			= EMAIL_ADMIN;
		$this->Email->from			= 'Monomi.Info<support@monomi.info>';
		$this->Email->subject		= $subject;

		$this->Email->language = 'Japanese';
		$this->Email->charset  = 'ISO-2022-JP';

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



	/**
	* sendMail for Client（問合せの確認メール）
	*
	* @param
	* @return none
	*/
	private function sendMail_client( $user, $text ) {

		// 2012.10.15 add 復号化
		$user = $this->User->decryptedUser($user);
//pr($user);

		// to問合せ者 送信メッセージ本文を生成
		// 送信メッセージ本文を生成
		$msg = '';
		$msg .= $user['User']['mail_address'] . " 様\r\n\r\n";
		$msg .= "この度は物見infoへお問合わせいただき誠にありがとうございます。\r\n";
		$msg .= "以下の内容で受付いたしました。\r\n\r\n";
		$msg .= "===================================================\r\n";
		$msg .= $text . "\r\n";
		$msg .= "===================================================\r\n";
		$msg .= "\r\n";
		$msg .= "このメッセージはお客様へのお知らせ専用です。\r\n";
		$msg .= "このメッセージへの返信としてご質問をお送りいただいても\r\n";
		$msg .= "ご回答できませんので、ご了承ください。\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "*********************************************************\r\n";
		$msg .= "物見info： http://monomi.info/\r\n";
		$msg .= "お問合せ受付： https://monomi.info/inquiry/form/\r\n";
		$msg .= "*********************************************************\r\n";

		$this->Email->reset();

		$subject= '[物見info] お問合せを承りました';
		$subject= mb_convert_encoding( $subject, 'ISO-2022-JP', 'UTF-8');
		$msg	= mb_convert_encoding( $msg, 'ISO-2022-JP', 'UTF-8');

		$this->Email->to			= $user['User']['mail_address'];
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

		return $this->Email->send($msg);

	}







	/**
	* sendMail for Administrator
	*
	* @param
	* @return none
	*/
	private function sendMail_new_password( $user ) {

		// 2012.10.15 add 復号化
		$user = $this->User->decryptedUser($user);

		// 送信メッセージ本文を生成
		$msg = '';
		$msg .= "\r\n";
		$msg .= $user['User']['mail_address'] . " 様\r\n";
		$msg .= "※このメールにお心当たりのない場合は、URLにアクセスせずメールを破棄してください。\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";

		$msg .= "物見infoをご利用いただき、誠にありがとうございます。\r\n";
		$msg .= "パスワードを下記のとおり発行しました。\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "新パスワード： " . $user['User']['passwd'] . "\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "以下のURLからログインをお願いします。\r\n";
		$msg .= 'http://monomi.info/users/login/' . "\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "[お問合せ]\r\n";
		$msg .= "http://monomi.info/inquiry/form/\r\n";
		$msg .= "\r\n";
		$msg .= "[物見info]\r\n";
		$msg .= 'URL: http://monomi.info/' . "\r\n";
		$msg .= "\r\n";

		$this->Email->reset();

		$subject= '[物見info] 新パスワードを発行しました';
		$subject= mb_convert_encoding( $subject, 'ISO-2022-JP', 'UTF-8');
		$msg	= mb_convert_encoding( $msg, 'ISO-2022-JP', 'UTF-8');

		$this->Email->to			= $user['User']['mail_address'];
		$this->Email->from			= 'Monomi.Info<support@monomi.info>';
		$this->Email->subject		= $subject;

		$this->Email->language = 'Japanese';
		$this->Email->charset  = 'ISO-2022-JP';

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








	/**
	* TEST管理画面
	*
	* @param
	* @return none
	*/
	public function adm() {

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得
		$user = $this->User->decryptedUser($user);

		if( $user['User']['user_uid'] !== '5053f44d-756c-45b0-b1cc-3a96a32bb0e6' && $user['User']['user_uid'] !== '507b3ef7-4bf4-4c2d-bc7f-15e045036250' ){
			$this->redirect('/');
		}

		$users = $this->User->find('all');
		foreach( $users as $k=>$v ){
			$users[$k] = $this->User->decryptedUser( $v );
		}

		$this->set('users', $users);

	}








	/**
	* TEST管理画面
	*
	* @param
	* @return none
	*/
	public function adm_word() {

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得
		$user = $this->User->decryptedUser($user);

		if( $user['User']['user_uid'] !== '5053f44d-756c-45b0-b1cc-3a96a32bb0e6' && $user['User']['user_uid'] !== '507b3ef7-4bf4-4c2d-bc7f-15e045036250' ){
			$this->redirect('/');
		}


		// 第１パラメータ
		if( !isset($this->params['pass'][0]) ){
			$this->redirect('/users/dashboard/');
		}

		$data = $this->Word->find('all',array('conditions'=>array('user_uid'=>$this->params['pass'][0])));
		foreach( $data as $key=>$val ){
			$data[$key] = $this->Word->decryptedWord($val);
		}

		$this->set('data', $data);

	}




}

?>