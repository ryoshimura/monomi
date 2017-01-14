<?php

class DownloadResult extends AppModel {
	var $name = 'DownloadResult';



	public function __construct() {
		$id = array("id" => false,
					"table" => "download_results",
				   );
		parent::__construct($id);
		$this->primaryKey = "download_result_uid";
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
	* user_uid と url から該当DownloadResultレコードを抽出 for aj_inbox_request, aj_inbox_comp
	*
	* @param
	* @return
	*/
	public function getDR( $user_uid, $dr_uid ) {

//		$buf = $this->find( 'first', array('conditions'=>array('download_result_uid'=>$dr_uid)) );

		// 紐付き元のイリーガルリザルトuidを取得
		$sql = 'SELECT ';
		$sql.= 'IR.illegal_result_uid,';
		$sql.= 'DR.download_site_uid';
		$sql.= ' FROM ';
		$sql.= 'illegal_results AS IR ';
		$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = IR.word_uid ';
		$sql.= 'LEFT JOIN result_relations AS RR ON RR.illegal_result_uid = IR.illegal_result_uid ';
		$sql.= 'LEFT JOIN download_results AS DR ON RR.download_result_uid = DR.download_result_uid ';
		$sql.= 'WHERE ';
		$sql.= 'Word.user_uid = "'. $user_uid .'" ';
		$sql.= 'AND Word.deleted = 0 ';
		$sql.= 'AND DR.download_result_uid = "'. $dr_uid .'" ';
		$buf = $this->query( $sql );
//pr($buf);

		// 紐付いてるイリーガルサイトの同アップローダのurlを全て取得
		$sql = 'SELECT *';
		$sql.= ' FROM ';
		$sql.= 'download_results AS DownloadResult ';
		$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = DownloadResult.word_uid ';
		$sql.= 'LEFT JOIN result_relations AS RR ON RR.download_result_uid = DownloadResult.download_result_uid ';
		$sql.= 'LEFT JOIN illegal_results AS IllegalResult ON RR.illegal_result_uid = IllegalResult.illegal_result_uid ';
		$sql.= 'WHERE ';
		$sql.= 'IllegalResult.illegal_result_uid = "'. $buf[0]['IR']['illegal_result_uid'] .'" ';
		$sql.= 'AND DownloadResult.download_site_uid = "'. $buf[0]['DR']['download_site_uid'] .'" ';
		$buf = $this->query( $sql );
//pr($buf);

		// download_result_url == '' or download_result_url == null の場合（アップローダ未検知）、ここでreturn
		if( $buf[0]['DownloadResult']['download_result_url']=='' || $buf[0]['DownloadResult']['download_result_url']==null ){
			return $buf;

		} else {

			// 上で取得したurlとuser_uidを含むDRレコード全て抽出
			$sql = 'SELECT *';
			$sql.= ' FROM ';
			$sql.= 'download_results AS DownloadResult ';
			$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = DownloadResult.word_uid ';
			$sql.= 'WHERE ';
			$sql.= 'Word.user_uid = "'. $user_uid .'" ';
			$sql.= 'AND DownloadResult.download_result_url IN ( ';
			$i = 0;
			foreach($buf as $buf){
				if($i == 0){
					$sql.= '"'. $buf['DownloadResult']['download_result_url'] .'"';
				} else {
					$sql.= ',"'. $buf['DownloadResult']['download_result_url'] .'"';
				}
				$i++;
			}
			$sql.= ' ) ';
			$res = $this->query( $sql );
//pr($res);

		}

		return $res;

	}


}

?>