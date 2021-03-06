<?php

/**
* 説明
* @see 関連関数1,関連関数2
*/


/**
* ホーム関連 コントローラー
*
* @package   パッケージ名
* @author    著作者 <著作者メール>
* @since     PHP 5.0
* @version $Id: DB.php,v 1.58 2004/03/13 16:17:19 danielc Exp $
*/
class HomesController extends AppController {

	var $name       = 'homes';                     // $nameを設定することでcontroller名を任意に設定することができる。
	var $components = array("RequestHandler");     // 使用するcomponentを指定する。
	var $layout     = 'base_layout';               // 使用するレイアウト
	var $uses       = array('IllegalSite', 'IllegalResult', 'DownloadResult');    	 // 使用するmodelを指定する。デフォルトmodelは必ず配列の先頭に記述する。


	/**
	* action,render前実行関数
	*
	* @param  none
	* @return none
	*/
	public function beforeFilter() {
		parent::beforeFilter();

		// Authコンポーネント非適用Action
//		$this->Auth->allow('index', 'delete', 'act_dlsite', 'act_dmm', 'categorize', 'test');

/*
		if( $this->RequestHandler->isSSL() ){
			$this->redirect( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		}
*/
	}

	/**
	* index
	*
	* @param  none
	* @return none
	*/
	public function index() {

		$aryCnt = array();
		$aryCnt['ilsite'] = $this->IllegalSite->find('count', array('conditions'=>array('deleted'=>0)));
		$aryCnt['res'] = $this->IllegalResult->find('count', array('conditions'=>array('deleted'=>0)));
		$aryCnt['res'] += $this->DownloadResult->find('count', array('conditions'=>array('deleted'=>0)));

		$this->set('cnt', $aryCnt);
	}


	/**
	* プライバシーポリシー
	*
	* @param  none
	* @return none
	*/
	public function policy() {

	}

	/**
	* 解約完了
	*
	* @param  none
	* @return none
	*/
	public function cancelled() {

	}


	/**
	* 利用規約
	*
	* @param  none
	* @return none
	*/
	public function terms_of_service() {

	}



	/**
	* 特商法に基づく表示
	*
	* @param  none
	* @return none
	*/
	public function tokushoho() {

	}



	/**
	* メンテナンス
	*
	* @param  none
	* @return none
	*/
	public function maintenance() {

	}




}

?>