<?php

class ResultRelation extends AppModel {
	var $name = 'ResultRelation';



	public function __construct() {
		$id = array("id" => false,
					"table" => "result_relations",
				   );
		parent::__construct($id);
		$this->primaryKey = "relation_uid";
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