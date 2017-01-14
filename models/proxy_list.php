<?php

class ProxyList extends AppModel {
	var $name = 'ProxyList';



	public function __construct() {
		$id = array("id" => false,
					"table" => "proxy_lists",
				   );
		parent::__construct($id);
		$this->primaryKey = "proxy_uid";
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