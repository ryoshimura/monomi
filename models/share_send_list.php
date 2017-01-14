<?php

class ShareSendList extends AppModel {
	var $name = 'ShareSendList';



	public function __construct() {
		$id = array("id" => false,
					"table" => "share_send_lists",
				   );
		parent::__construct($id);
		$this->primaryKey = "share_uid";
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
	* 共有宛先情報を取得
	*
	*/
	public function getSendList( $mode, $uid ) {


		if( $mode === 'is' ){

			$sql = 'SELECT ';
			$sql.= 'IRS.site_uid,';
			$sql.= 'IRS.site_name,';
			$sql.= 'IRS.flag_dmca,';
			$sql.= 'IRS.contact_mail,';
			$sql.= 'IRS.contact_url';
			$sql.= ' FROM ';
			$sql.= 'illegal_results AS IR ';
			$sql.= 'LEFT JOIN illegal_sites AS IRS ON IRS.site_uid = IR.site_uid ';
			$sql.= 'WHERE ';
			$sql.= 'IR.illegal_result_uid = "'. $uid .'" ';
			$buf = $this->query( $sql );
			$res['site_name']= $buf[0]['IRS']['site_name'];
			$res['flag_dmca']= $buf[0]['IRS']['flag_dmca'];


			$data = $this->find('first',array('conditions'=>array('deleted'=>0, 'site_uid'=>$buf[0]['IRS']['site_uid'])));
			if( $data == false){
				$res['to_address_1']= $buf[0]['IRS']['contact_mail'];
				$res['to_address_2']=  '';
				$res['to_address_3']=  '';
				$res['cc_1']= '';
				$res['cc_2']= '';
				$res['cc_3']= '';
				$res['contact_url']	= $buf[0]['IRS']['contact_url'];
				$res['note']= '';

			} else {
				$res['to_address_1']= $data['ShareSendList']['to_address_1'];
				$res['to_address_2']= $data['ShareSendList']['to_address_2'];
				$res['to_address_3']= $data['ShareSendList']['to_address_3'];
				$res['cc_1']= $data['ShareSendList']['cc_1'];
				$res['cc_2']= $data['ShareSendList']['cc_2'];
				$res['cc_3']= $data['ShareSendList']['cc_3'];
				$res['contact_url']	= $data['ShareSendList']['contact_url'];
				$res['note']= $data['ShareSendList']['note'];

			}


		} else if( $mode === 'ds' ){

			$sql = 'SELECT ';
			$sql.= 'DS.download_site_uid,';
			$sql.= 'DS.site_name,';
			$sql.= 'DS.flag_dmca,';
			$sql.= 'DS.contact_mail,';
			$sql.= 'DS.contact_url';
			$sql.= ' FROM ';
			$sql.= 'download_results AS DR ';
			$sql.= 'LEFT JOIN download_sites AS DS ON DS.download_site_uid = DR.download_site_uid ';
			$sql.= 'WHERE ';
			$sql.= 'DR.download_result_uid = "'. $uid .'" ';
			$buf = $this->query( $sql );
			$res['site_name']= $buf[0]['DS']['site_name'];
			$res['flag_dmca']= $buf[0]['DS']['flag_dmca'];

			$data = $this->find('first',array('conditions'=>array('deleted'=>0, 'download_site_uid'=>$buf[0]['DS']['download_site_uid'])));
			if( $data == false){
				$res['to_address_1']= $buf[0]['DS']['contact_mail'];
				$res['to_address_2']=  '';
				$res['to_address_3']=  '';
				$res['cc_1']= '';
				$res['cc_2']= '';
				$res['cc_3']= '';
				$res['contact_url']	= $buf[0]['DS']['contact_url'];
				$res['note']= '';

			} else {
				$res['to_address_1']= $data['ShareSendList']['to_address_1'];
				$res['to_address_2']= $data['ShareSendList']['to_address_2'];
				$res['to_address_3']= $data['ShareSendList']['to_address_3'];
				$res['cc_1']= $data['ShareSendList']['cc_1'];
				$res['cc_2']= $data['ShareSendList']['cc_2'];
				$res['cc_3']= $data['ShareSendList']['cc_3'];
				$res['contact_url']	= $data['ShareSendList']['contact_url'];
				$res['note']= $data['ShareSendList']['note'];

			}

		}



		return $res;

	}




	/**
	* 共有宛先情報を取得（NotFound）
	*
	*/
	public function getSendListNf ( $dsuid ) {

			$sql = 'SELECT ';
			$sql.= 'DS.download_site_uid,';
			$sql.= 'DS.site_name,';
			$sql.= 'DS.flag_dmca,';
			$sql.= 'DS.contact_mail,';
			$sql.= 'DS.contact_url';
			$sql.= ' FROM ';
//			$sql.= 'download_results AS DR ';
//			$sql.= 'LEFT JOIN download_sites AS DS ON DS.download_site_uid = DR.download_site_uid ';
			$sql.= 'download_sites AS DS ';
			$sql.= 'WHERE ';
//			$sql.= 'DR.download_result_uid = "'. $uid .'" ';
			$sql.= 'DS.download_site_uid = "'. $dsuid .'" ';
			$buf = $this->query( $sql );
//pr($buf);

			$res['site_name']= $buf[0]['DS']['site_name'];
			$res['flag_dmca']= $buf[0]['DS']['flag_dmca'];



			$data = $this->find('first',array('conditions'=>array('deleted'=>0, 'download_site_uid'=>$buf[0]['DS']['download_site_uid'])));
//pr($data);
			if( $data == false){
				$res['to_address_1']= $buf[0]['DS']['contact_mail'];
				$res['to_address_2']=  '';
				$res['to_address_3']=  '';
				$res['cc_1']= '';
				$res['cc_2']= '';
				$res['cc_3']= '';
				$res['contact_url']	= $buf[0]['DS']['contact_url'];
				$res['note']= '';

			} else {
				$res['to_address_1']= $data['ShareSendList']['to_address_1'];
				$res['to_address_2']= $data['ShareSendList']['to_address_2'];
				$res['to_address_3']= $data['ShareSendList']['to_address_3'];
				$res['cc_1']= $data['ShareSendList']['cc_1'];
				$res['cc_2']= $data['ShareSendList']['cc_2'];
				$res['cc_3']= $data['ShareSendList']['cc_3'];
				$res['contact_url']	= $data['ShareSendList']['contact_url'];
				$res['note']= $data['ShareSendList']['note'];

			}



		return $res;

	}






}

?>