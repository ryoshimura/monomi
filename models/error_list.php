<?php

class ErrorList extends AppModel {
	var $name = 'ErrorList';



	public function __construct() {
		$id = array("id" => false,
					"table" => "error_lists",
				   );
		parent::__construct($id);
		$this->primaryKey = "list_uid";
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