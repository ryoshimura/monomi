<?php

class Model extends AppModel {
	var $name = 'Model';

	public function __construct() {
		$id = array("id" => false,
					"table" => "models",
				   );
		parent::__construct($id);
		$this->primaryKey = "model_uid";
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