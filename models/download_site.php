<?php

class DownloadSite extends AppModel {
	var $name = 'DownloadSite';



	public function __construct() {
		$id = array("id" => false,
					"table" => "download_sites",
				   );
		parent::__construct($id);
		$this->primaryKey = "download_site_uid";
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

		// 紐付くDR全て取得
//		$ResultRelation = ClassRegistry::init('ResultRelation');
//		$rr = $ResultRelation->find('first',array('conditions'=>array('c'=>$uid)));


		$sql = 'SELECT ';
		$sql.= 'RR.illegal_result_uid,';
		$sql.= 'DR.download_site_uid';
		$sql.= ' FROM ';
		$sql.= 'result_relations AS RR ';
		$sql.= 'LEFT JOIN illegal_results AS IR ON IR.illegal_result_uid = RR.illegal_result_uid ';
		$sql.= 'LEFT JOIN download_results AS DR ON DR.download_result_uid = RR.download_result_uid ';
		$sql.= 'WHERE ';
		$sql.= 'RR.download_result_uid = "' . $uid . '" ';
		$rr  = $this->query( $sql );
//pr($rr);

		$sql = 'SELECT ';
		$sql.= 'DRS.site_name,';
		$sql.= 'DRS.contact_mail,';
		$sql.= 'DRS.contact_url,';
		$sql.= 'DR.download_result_url,';
		$sql.= 'IR.illegal_result_uid,';
		$sql.= 'IR.illegal_url';
		$sql.= ' FROM ';
		$sql.= 'download_results AS DR ';
		$sql.= 'LEFT JOIN download_sites AS DRS ON DRS.download_site_uid = DR.download_site_uid ';
		$sql.= 'LEFT JOIN result_relations AS RR ON RR.download_result_uid = DR.download_result_uid ';
		$sql.= 'LEFT JOIN illegal_results AS IR ON IR.illegal_result_uid = RR.illegal_result_uid ';
		$sql.= 'WHERE ';
		$sql.= 'RR.illegal_result_uid = "' . $rr[0]['RR']['illegal_result_uid']. '" ';
		$sql.= 'AND DR.download_site_uid = "' . $rr[0]['DR']['download_site_uid']. '" ';
		$data = $this->query( $sql );

		return $data;
	}

}

?>