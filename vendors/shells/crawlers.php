<?php
App::import('Core', 'HttpSocket');
App::import('Component', 'Email');

class CrawlersShell extends Shell
{
//	var $uses = array('Crawler', 'Transaction');
//	var $components = array( 'Email' );     // 使用するcomponentを指定する。
	var $uses       = array( 'Crlsite', 'User', 'DeletedUsers', 'HtmlCache', 'Campaign' );    	 // 使用するmodelを指定する。デフォルトmodelは必ず配列の先頭に記述する。

	// オーバーライドして、Welcome to CakePHP･･･のメッセージを出さないようにする。
//	function startup() {}



	/**
	* DB接続テストを行なうコンストラクタ
	*
	* @param  none
	* @return none
	*/
/*	function constructClasses() {
		// データベースの接続確認をする
		$conn = ConnectionManager::getInstance();
		@$ds = $conn->getDataSource('default');
		if (!$ds->isConnected()) {
			// このへんで各種回避処理をする
			exit;
		}
		return parent::constructClasses();
	}
*/



	// add 2012.09.15 メール送信のために追加
	function startup(){
//		$this->controller = new Controller();
		$this->Email = new EmailComponent($this);
//		$this->Email->startup($this->controller);
		$this->Email->initialize($this->controller);
	}


	/**
	* 新着分のみスクレイピング
	*
	* @param  none
	* @return none
	*/
	public function crawler_site() {
pr("test!");
		if( !isset( $this->args[0] ) ){	// 0の場合、リライト文章があっても上書き
			$this->out( date('Y/m/d H:i:s') .  'Error Param');
			exit;
		}

		$start = $this->args[0];
		$this->out( date('Y/m/d H:i:s') . ' START crawler_site : ' . $start );

		$aryErr = $this->Crlsite->crawler_site( 'normal', $start );

		foreach( $aryErr as $key=>$val ){
			foreach( $val as $k => $v ){
				$this->out( ' ERROR: ' . $v['status'] . '  SITE: ' . $key . '  URL: ' . $v['url'] );
			}
		}

		$this->out( date('Y/m/d H:i:s') .  ' END crawler_site : ' . $start );
	}



	/**
	* 全件スクレイピング
	*
	* @param  none
	* @return none
	*/
	public function crawler_searchbox() {

		if( !isset( $this->args[0] ) ){	// 0の場合、リライト文章があっても上書き
			$this->out( date('Y/m/d H:i:s') .  'Error Param');
			exit;
		}

		$start = $this->args[0];
		$this->out( date('Y/m/d H:i:s') .  ' START crawler_searchbox : ' . $start );

		$aryErr = $this->Crlsite->crawler_site( 'search', $start );

		foreach( $aryErr as $key=>$val ){
			foreach( $val as $k => $v ){
				$this->out( ' ERROR: ' . $v['status'] . '  SITE: ' . $key . '  URL: ' . $v['url'] );
			}
		}

		$this->out( date('Y/m/d H:i:s') .  ' END crawler_searchbox : ' . $start );
	}



	/**
	* 新規登録分のみ全件スクレイピング
	*
	* @param  none
	* @return none
	*/
	public function crawler_new_word() {


		$this->out( date('Y/m/d H:i:s') .  ' START crawler_new_word' );

		$aryErr = $this->Crlsite->crawler_site( 'search_new' );

		foreach( $aryErr as $key=>$val ){
			foreach( $val as $k => $v ){
				$this->out( ' ERROR: ' . $v['status'] . '  SITE: ' . $key . '  URL: ' . $v['url'] );
			}
		}

		$this->out( date('Y/m/d H:i:s') .  ' END crawler_new_word' );
	}




	/**
	* トレント検索スクレイピング
	*
	* @param  none
	* @return none
	*/
	public function crawler_torrent() {


		if( !isset( $this->args[0] ) ){	// 0の場合、リライト文章があっても上書き
			$this->out( date('Y/m/d H:i:s') .  'Error Param');
			exit;
		}
		$start = $this->args[0];
		$this->out( date('Y/m/d H:i:s') .  ' START crawler_torrent : ' . $start );

		$aryErr = $this->Crlsite->crawler_site( 'torrent_search', $start );

		foreach( $aryErr as $key=>$val ){
			foreach( $val as $k => $v ){
				$this->out( ' ERROR: ' . $v['status'] . '  SITE: ' . $key . '  URL: ' . $v['url'] );
			}
		}

		$this->out( date('Y/m/d H:i:s') .  ' END crawler_torrent : ' . $start );
	}





	/**
	* キャッシュしたHTMLをスクレイピング
	*
	* @param  none
	* @return none
	*/
	public function crawler_cache() {


		$this->out( date('Y/m/d H:i:s') . ' START crawler_cache' );

		$aryErr = $this->Crlsite->crawler_site( 'cache' );

		foreach( $aryErr as $key=>$val ){
			foreach( $val as $k => $v ){
				$this->out( ' ERROR: ' . $v['status'] . '  SITE: ' . $key . '  URL: ' . $v['url'] );
			}
		}

		$this->out( date('Y/m/d H:i:s') .  ' END crawler_cache' );
	}




	/**
	* 新規に検知された不正サイトのみユーザにメール通知
	*
	* @param  none
	* @return none
	*/
	public function send_notice() {
		$this->out( ' -' . date('Y/m/d H:i:s') .  ' START send_notice');


		// 通知データを抽出
		$data = $this->User->get_notice_list();

//pr($data);
		// メール送信
		foreach( $data as $user ){

			$msg = $this->get_template_msg( $user );
			$mail_address = $user['mail_address'];

print_r('mail: ' . $mail_address . "\n");

			unset( $user['mail_address'] );
			$payment_status = $user['payment_status'];
			unset( $user['payment_status'] );
			$text = '';

/*			foreach( $user as $word ){

				$search_word = $word['search_word'];
				unset( $word['search_word'] );

				$text .= "\r\n[" . $search_word . "]\r\n";

				foreach( $word as $site ){
					$text .= ' →' . $site['url'] . "\r\n";
				}
			}
*/

			// 本文置換
			$msg = str_replace( '[[MAIL_ADDRESS]]', $mail_address, $msg );
//pr($msg);
//			if( $payment_status != 2 ){
//				$msg = str_replace( '[[NOTICE_TEXT]]', $text, $msg );
//			}


			$subject = '[物見インフォ] 不正の疑いのあるサイトが見つかりました';
			$subject= mb_convert_encoding( $subject, 'ISO-2022-JP', 'UTF-8');
			$msg	= mb_convert_encoding( $msg, 'ISO-2022-JP', 'UTF-8');

			$this->Email->to			= $mail_address;
			$this->Email->from			= 'Monomi.Info<support@monomi.ciasol.com>';
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

			$this->Email->send( $msg );
			$this->Email->reset();

		}


		$this->out( ' -' . date('Y/m/d H:i:s') .  ' END send_notice');
	}


	/**
	* 不正サイト通知用メールテンプレート
	*
	* @param  none
	* @return none
	*/
	public function get_template_msg( $prm ) {

		// 送信メッセージ本文を生成
		$msg = '';
		$msg .= "\r\n";
		$msg .= '[[MAIL_ADDRESS]]' . " 様\r\n";
		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "物見インフォをご利用いただきありがとうございます\r\n";
		$msg .= "\r\n";
		$msg .= "あなたの著作物を掲載したサイトが新たに". $prm['ir_count'] ."件見つかりました。\r\n";
		$msg .= "\r\n";
		$msg .= "下記URLよりご確認ください。\r\n";
		$msg .= 'https://monomi.info/users/inbox/' . "\r\n";

		$msg .= "\r\n";
		$msg .= "\r\n";
		$msg .= "[物見インフォ]\r\n";
		$msg .= 'URL: http://monomi.info/' . "\r\n";
		$msg .= "\r\n";
		$msg .= "[お問合せ]\r\n";
		$msg .= "http://monomi.info/inquiry/form/\r\n";
		$msg .= "\r\n";

		return $msg;
	}







	/**
	* アカウントステータス更新バッチ
	* 毎時
	* @param  none
	* @return none
	*/
	public function user_status_update() {

		$this->out( date('Y/m/d H:i:s') .  ' START user_status_update' );

		// 仮登録終了( 仮登録のまま User.hash_create_timeから24時間以上経過したレコードをdeleted_userへ移動
		$time = date("Y-m-d H:i:s",strtotime("-24 hour"));
		$users = $this->User->find('all', array('conditions'=>array('user_status'=>0, 'hash_create_time <'=>$time)));
		foreach( $users as $user ){
			$save = array();
			$save['DeletedUsers'] = $user['User'];
			$save['DeletedUsers']['updated'] = date('Y-m-d H:i:s');
print_r('kari: ' . $user['User']['user_uid'] . "\n");
			$this->DeletedUsers->create();
			$this->DeletedUsers->save( $save, false );	// DeletedUsersへ登録

			$this->User->delete( $user['User']['user_uid'], false );	// Userの対象レコードを物理削除
		}


		// 仮登録・未決済のままま User.created から24時間以上経過したレコードをdeleted_userへ移動
		$time = date("Y-m-d H:i:s",strtotime("-24 hour"));
		$users = $this->User->find('all', array('conditions'=>array('user_status'=>0, 'payment_status'=>3, 'created <'=>$time)));
		foreach( $users as $user ){
			$save = array();
			$save['DeletedUsers'] = $user['User'];
			$save['DeletedUsers']['updated'] = date('Y-m-d H:i:s');
print_r('notPayment: ' . $user['User']['user_uid'] . "\n");
			$this->DeletedUsers->create();
			$this->DeletedUsers->save( $save, false );	// DeletedUsersへ登録

			$this->User->delete( $user['User']['user_uid'], false );	// Userの対象レコードを物理削除
		}


		// 体験版終了
		$time = date("Y-m-d H:i:s",strtotime("-7 day"));
		$users = $this->User->find('all', array('conditions'=>array('user_status'=>1, 'payment_status'=>2, 'period_status'=>1, 'regist_date <'=>$time)));
		foreach( $users as $user ){
print_r('trial: ' . $user['User']['user_uid'] . "\n");
			$user['User']['period_status']	= 0;
			$this->User->save( $user, false );
		}


		// スタンダードプラン終了
		$time = date("Y-m-d H:i:s");	// 現在の日時
		$users = $this->User->find('all', array('conditions'=>array('user_status'=>1, 'payment_status'=>1, 'period_status'=>1, 'current_period <'=>$time)));
		foreach( $users as $user ){
print_r('standard: ' . $user['User']['user_uid'] . "\n");
			$user['User']['period_status']	= 0;
			$this->User->save( $user, false );
		}


		// 招待キャンペーンによる利用期間延長を付与
		$this->Campaign->setCampaignTerm();



		$this->out( date('Y/m/d H:i:s') .  ' END user_status_update' );
	}





	/**
	* 招待キャンペーンによる期間延長付与
	* 毎時
	* @param  none
	* @return none
	*/
	public function add_campaign_term() {

		$this->out( date('Y/m/d H:i:s') .  ' START user_status_update' );

		$this->Campaign->setCampaignTerm();

		$this->out( date('Y/m/d H:i:s') .  ' END user_status_update' );
	}


	/**
	* 古いHTMLキャッシュを削除バッチ
	* 毎時
	* @param  none
	* @return none
	*/
	public function delete_cache() {

		$this->out( date('Y/m/d H:i:s') .  ' START delete_cache' );

		// DBから30日間経過したキャッシュデータを抽出
//		$time = date("Y-m-d H:i:s",strtotime("-30 day"));
		$time = date("Y-m-d H:i:s",strtotime("-20 day"));
		$data = $this->HtmlCache->find('all',array('conditions'=>array('created <'=>$time)));

		// データを物理削除し、HTMLファイルも物理削除
		foreach ( $data as $val ){
print_r( $val['HtmlCache']['cache_uid'] . "\n" );
			$file_pass = HTML_CACHE_PATH . $val['HtmlCache']['cache_uid'];
			unlink( $file_pass );
			$this->HtmlCache->delete( $val['HtmlCache']['cache_uid'] );
		}

		$this->out( date('Y/m/d H:i:s') .  ' END delete_cache' );
	}


}
