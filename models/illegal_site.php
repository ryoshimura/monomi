<?php

class IllegalSite extends AppModel {
	var $name = 'IllegalSite';



	public function __construct() {
		$id = array("id" => false,
					"table" => "illegal_sites",
				   );
		parent::__construct($id);
		$this->primaryKey = "site_uid";
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
	* inbox template初期化で使用
	*
	* @param
	* @return
	*/
	public function getTempInfo( $uid ) {

		// 検出イリーガルサイト数
		$sql = 'SELECT ';
		$sql.= 'IRS.site_name,';
		$sql.= 'IRS.contact_mail,';
		$sql.= 'IRS.contact_url,';
		$sql.= 'IR.illegal_result_uid,';
		$sql.= 'IR.illegal_url';
		$sql.= ' FROM ';
		$sql.= 'illegal_results AS IR ';
		$sql.= 'LEFT JOIN illegal_sites AS IRS ON IRS.site_uid = IR.site_uid ';
		$sql.= 'WHERE ';
		$sql.= 'IR.illegal_result_uid = "' . $uid. '" ';

		$data = $this->query( $sql );
//		$data = $data[0];

		return $data;
	}



}

?>