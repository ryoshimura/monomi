<?php

class Message extends AppModel {
	var $name = 'Message';



	public function __construct() {
		$id = array("id" => false,
					"table" => "messages",
				   );
		parent::__construct($id);
		$this->primaryKey = "message_uid";
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





	/**
	* メッセージ登録
	*
	* @param
	* @return
	*/
	public function setMsg( $user, $title, $text ) {

		$save = array();
		$save['Message']['user_uid']	= $user['User']['user_uid'];
		$save['Message']['title']		= $title;
		$save['Message']['text']		= $text;
		$save['Message']['regist_date']	= date("Y-m-d");

		$this->create();
		$this->save( $save, false );
	}






	/**
	* 招待キャンペーンの特定付与メッセージ
	*
	* @param
	* @return
	*/
	public function setAddCampaign( $user, $addDays ) {

		$title	= '招待キャンペーン特典でご利用期間が延長されました。';
		$text  = 'お客様の招待コードで新規お申込みがありました。<br />';
		$text .= 'キャンペーン特典として、お客様のご利用期間を'. $addDays .'日間無料で延長させていただきます。<br />';
		$text .= '引き続き物見インフォをよろしくお願いします。';

		$this->setMsg( $user, $title, $text );
	}





}

?>