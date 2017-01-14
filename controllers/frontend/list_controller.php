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
class ListController extends AppController {

	var $name       = 'list';                     // $nameを設定することでcontroller名を任意に設定することができる。
	//var $components = array('Auth', 'Cookie');     // 使用するcomponentを指定する。
	var $layout     = 'base_layout';               // 使用するレイアウト
	var $uses       = array( 'Product', 'Rakuten', 'Amazon' );    	 // 使用するmodelを指定する。デフォルトmodelは必ず配列の先頭に記述する。


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
	}

	/**
	* index
	*
	* @param  none
	* @return none
	*/
	public function search() {
		$this->layout="";
		$meta['title']			 = 'monomi';
		$meta['h1']		 = 'monomi';
		$meta['description'] = 'monomi';
		$meta['keywords']	 = 'ゴルフ会員権,売買';

		$this->set( 'meta', $meta );


		if ( !$this->RequestHandler->isPost() ) {
			// 検索結果なしページへリダイレクト
//			$this->redirect(array('controller'=>'homes','action'=>'index'));
		}


		$keywords = $this->data['keywords'];

		// amazon検索
		$search_index	= 'All';
		$amazon = $this->Amazon->connect( $search_index, $keywords );

		foreach( $amazon->Items->Item as $item ){
			$amazon_data[] = (String)$item->ItemAttributes->Title;
		}

print_r( $amazon_data );


		// 楽天プロダクト検索
		$rakuten = $this->Rakuten->product_search_keyword( $keywords );
print_r( $rakuten );


//		$this->set( 'rakuten', $rakuten );
//		$this->set( 'amazon', $amazon );
	}



}

?>