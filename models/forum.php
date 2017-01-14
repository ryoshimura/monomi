<?php

class Forum extends AppModel {
	var $name = 'Forum';


	public $validate = array(
			    'text' => array(
					'rule' => array('between', 1, 10000),
					'required'	=> true,
					'message' => '書き込みは1～5,000文字でお願いします'
				)
			);



	public function __construct() {
		$id = array("id" => false,
					"table" => "forums",
				   );
		parent::__construct($id);
		$this->primaryKey = "forum_uid";
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






}

?>