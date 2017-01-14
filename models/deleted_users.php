<?php

class DeletedUsers extends AppModel {
	var $name = 'DeletedUsers';



	public function __construct() {
		$id = array("id" => false,
					"table" => "deleted_users",
				   );
		parent::__construct($id);
		$this->primaryKey = "user_uid";
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