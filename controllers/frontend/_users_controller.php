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
	var $uses       = array( 'User', 'Message', 'Forum', 'Word', 'IllegalSite', 'IllegalResult', 'DownloadSite', 'DownloadResult', 'UserInquiry', 'DeletedUsers', 'UserProfile', 'Template' );    	 // 使用するmodelを指定する。デフォルトmodelは必ず配列の先頭に記述する。
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

//		$this->redirect('/homes/maintenance/');


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
		$this->Security->blackHoleCallback = '_sslFail';
//	    $this->Security->requireSecure();
//	    $this->Security->requireSecure(
//	    	'login', 'dashboard', 'inbox', 'trash', 'words','profile','template_prof','cancel','cancelled','contact','iconfirm','ithx','rms','dlist','ilist','detail'
//	    );	// 強制SSLを中断するには、この行をコメントアウト

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

//pr( $user['User']['mail_address'] );
//pr( $this->User->decryptedData($user['User']['mail_address']) );


		$msg = $this->Message->find('all', array('conditions'=>array('user_uid'=>$user['User']['user_uid'], 'deleted'=>0), 'order'=>array('regist_date DESC'))); // メッセージ
		$monitoring = $this->IllegalResult->dashboard( $user['User']['user_uid'] ); // 検出数

		// 最終監視日時を取得
		$lastupdate = $this->IllegalResult->find('first', array('conditions'=>array('deleted'=>0), 'order'=>array('updated DESC')));

//pr($msg);
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

//$template = $this->Template->getTemplate( '00001', '500e7a76-db90-4321-956d-b8e845036250', 'アップローダ名', 'アップローダURL' );
//pr($template);
//$site = $this->DownloadSite->getTempInfo( '506436fe-624c-4c3c-8bcf-18e845036250' );
//pr($site);
//$template = $this->Template->getTemplate( '00001', '500e7a76-db90-4321-956d-b8e845036250', false, $site );
//pr($template);


		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));

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
	* .
	*/
	public function aj_inbox_request() {
//		Configure::write('debug', 0);
		$this->layout="";
//pr( $this->params );
		if( 'Enable' === $this->params['url']['flag'] ){
			$memo_request = 1;
		} else if( 'Disable' === $this->params['url']['flag'] ){
			$memo_request = 0;
		}

		$save = array();
		if( 'none' !== $this->params['url']['ir_id'] ){			// IllegalResultレコードを更新

			$save = $this->IllegalResult->find('first',array('conditions'=>array('illegal_result_uid'=>$this->params['url']['ir_id'])));
			$save['IllegalResult']['memo_request'] = $memo_request;
			$this->IllegalResult->save( $save, false );

		} else if( 'none' !== $this->params['url']['dr_id'] ){	// DownloadResultレコードを更新

			$save = $this->DownloadResult->find('first',array('conditions'=>array('download_result_uid'=>$this->params['url']['dr_id'])));
			$save['DownloadResult']['memo_request'] = $memo_request;
			$this->DownloadResult->save( $save, false );

		}

//		$this->set('data',$this->params['url']['ir_id']);

	}




	/**
	* User監視トレイ用 削除完了タグ
	*
	* @param  none
	* @return none
	*/
	public function aj_inbox_comp() {
//		Configure::write('debug', 0);
		$this->layout="";


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

		if( $uid === '' ) {		// 2012.10.10 アップローダが特定できなくてもテンプレート昨日をONにするため
			$destination = '';
			$contactus = '';
			$site_name = '';
		} else if( 'is' === $this->params['url']['mode'] ){
			$site = $this->IllegalSite->getTempInfo( $uid );
			$is_site = $site;

		} else if( 'ds' === $this->params['url']['mode'] ){
			$site = $this->DownloadSite->getTempInfo( $uid );
			$ds_site = $site;

		}


		// テンプレートの置換
		$template = $this->Template->getTemplate( $this->params['url']['tmp_uid'], $user['User']['user_uid'], $is_site, $ds_site );


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
/*
		$start_page = 1;
		$limit_count = 10;	// 1ページ内に表示するメインレコード数

		// 第１パラメータ
		if( isset($this->params['pass'][0]) ){
			$start_page = $this->params['pass'][0];
		}
*/

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
	* User監視トレイ用 削除
	*
	* @param  none
	* @return none
	*/
	public function aj_inbox_restore() {
//		Configure::write('debug', 0);
		$this->layout="";

		if( false !== $save = $this->IllegalResult->find('first',array('conditions'=>array('illegal_result_uid'=>$this->params['url']['ir_id'])))){

			$save['IllegalResult']['trash'] = 0;
			$this->IllegalResult->save($save, false);

		}
	}






	/**
	* User監視ワード
	*
	* @param  none
	* @return none
	*/
	public function words() {

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
			foreach( $words as $k => $v ){
				$words[$k] = $this->Word->decryptedWord($v);	// 復号化
			}

			foreach( $words as $val ){
				$num = $val['Word']['position'];
//				$this->data['User'][$num] = $val['Word']['search_word'];
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

		$list = $this->IllegalSite->find('all', array('conditions'=>array('deleted'=>0), 'order'=>array('site_name ASC')));
		$cnt['ilist'] = count($list);
		$cnt['dlist'] = $this->DownloadSite->find('count', array('conditions'=>array('deleted'=>0), 'order'=>array('site_name ASC')));

		$this->set( 'cnt', $cnt );
		$this->set( 'list', $list );
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

		$user = $this->Auth->user();	// ユーザ情報取得
		$user = $this->User->find('first', array('conditions'=>array('user_uid'=>$user['User']['user_uid'])));		// 念の為、最新のUser情報を取得


		if ( $this->RequestHandler->isPost() ) {
//pr($this->data);

			// バリデーション
			$res = $this->User->profile_validate( $this->data, $user );
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
	* User テンプレート プロフィール
	*
	* @param  none
	* @return none
	*/
	public function template_prof() {

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

		// ユーザステータスによって分岐
		if( 0 == $user['User']['payment_status'] || 2 == $user['User']['payment_status'] || 9 == $user['User']['payment_status'] ){

			$user['User']['cancelled'] = 1;
			$save['DeletedUsers'] = $user['User'];	// ユーザ情報を削除テーブルに移動
			$this->DeletedUsers->save($save);
			$this->User->delete( $user['User']['user_uid'], false );

			// Authをリフレッシュしリダイレクト
			$this->Auth->logout();
			$this->redirect('/homes/cancelled/');

		} else if( 1 == $user['User']['payment_status'] ) {		// 契約期間が残っている場合

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

		if ( $this->RequestHandler->isPost() ) {

			$this->UserInquiry->set( $this->data );		// バリデーション
			if( $this->UserInquiry->validates() ){	// バリデーションチェックし問題なければ確認画面へ遷移

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
			$this->sendMail_admin( $user, $this->data['UserInquiry']['text'] );		// メール送信
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
	* スタンダード監視プラン申込
	*
	* @param
	* @return none
	*/
	public function sdrequest() {

		// パブリックの本登録画面と同じないようでOKかと

		// 既に申込済みで利用期限も残っている方は決済へのリンクを消して警告メッセージ or 延長受付かなぁ～？
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
	* sendMail for Administrator
	*
	* @param
	* @return none
	*/
	private function sendMail_admin( $user, $text ) {

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
		$msg .= "モノミインフォ\r\n";
		$msg .= "\r\n";
		$msg .= "****************************************************\r\n";


		$this->Email->to			= EMAIL_ADMIN;
		$this->Email->from			= 'モノミインフォ管理<support@monomi.ciasol.com>';
		$this->Email->subject		= '【モノミ問合せ】会員より問合せ';

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
		$msg .= "この度はモノミインフォへお問合わせいただき誠にありがとうございます。\r\n";
		$msg .= "以下の内容で受付いたしました。\r\n\r\n";
		$msg .= "===================================================\r\n";
		$msg .= $text . "\r\n";
		$msg .= "===================================================\r\n";
		$msg .= "\r\n";
		$msg .= "このメッセージはお客様へのお知らせ専用ですので、\r\n";
		$msg .= "このメッセージへの返信としてご質問をお送りいただいても\r\n";
		$msg .= "ご回答できませんので、ご了承ください。\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "*********************************************************\r\n";
		$msg .= "モノミインフォ： http://monomi.info/\r\n";
		$msg .= "お問合せ受付： http://monomi.info/inquiry/form/\r\n";
		$msg .= "*********************************************************\r\n";

		$this->Email->to			= $user['User']['mail_address'];
		$this->Email->from			= 'モノミインフォサポート<support@monomi.ciasol.com>';
		$this->Email->subject		= '[モノミインフォ] お問合せを承りました';
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

		$msg .= "モノミインフォをご利用いただき、誠にありがとうございます。\r\n";
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
		$msg .= "[モノミインフォ]\r\n";
		$msg .= 'URL: http://monomi.info/' . "\r\n";
		$msg .= "\r\n";


		$this->Email->to			= $user['User']['mail_address'];
		$this->Email->from			= 'モノミインフォサポート<support@monomi.ciasol.com>';
		$this->Email->subject		= '【モノミインフォ】新パスワードを発行しました';

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