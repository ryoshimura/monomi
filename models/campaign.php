<?php

class Campaign extends AppModel {
	var $name = 'Campaign';



	public function __construct() {
		$id = array("id" => false,
					"table" => "campaigns",
				   );
		parent::__construct($id);
		$this->primaryKey = "campaign_uid";
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




	public function setCampaignTerm() {

		$User = ClassRegistry::init('User');
		$Message = ClassRegistry::init('Message');

		$period_date = date("Y-m-d 00:00:00", strtotime("+6 month"));

		// リスト取得
		$sql = 'SELECT *';
//		$sql.= 'Campaign.campaign_uid,';
//		$sql.= 'Campaign.assign_from_user_uid,';
//		$sql.= 'Campaign.term';
		$sql.= ' FROM ';
		$sql.= 'campaigns AS Campaign ';
		$sql.= 'LEFT JOIN users AS User ON User.user_uid = Campaign.assign_from_user_uid ';
		$sql.= 'WHERE ';
		$sql.= 'Campaign.used = 0 ';
		$sql.= 'AND Campaign.campaign_name = "招待キャンペーン" ';
		$sql.= 'AND User.period_status = 1 ';
		$sql.= 'AND User.current_period < "'. $period_date .'" ';
		$sql.= 'ORDER BY ';
		$sql.= 'Campaign.assign_from_user_uid ';
		$cmps = $this->query( $sql );
//pr($cmps);


		$i=0;
		$flag_frist = true;
		$cmplist = array();
		foreach( $cmps as $key => $val ){

			if( $flag_frist == true ){	// 最初のレコード
				$cmplist[$i]['user_uid']= $val['Campaign']['assign_from_user_uid'];
				$cmplist[$i]['term']	= $val['Campaign']['term'];
				$flag_frist = false;

			} else {	// ２番目以降のレコード
				if( $cmps[$key-1]['Campaign']['assign_from_user_uid'] === $val['Campaign']['assign_from_user_uid'] ){	// 前レコードとassign_from_user_uidが同じ場合

					$cmplist[$i]['term'] += $val['Campaign']['term'];

				} else {
					$i++;
					$cmplist[$i]['user_uid']= $val['Campaign']['assign_from_user_uid'];
					$cmplist[$i]['term']	= $val['Campaign']['term'];

				}
			}

			$save = array();
			$save['Campaign'] = $val['Campaign'];
			$save['Campaign']['used'] = 1;
			$this->save( $save, false );

		}

		// 招待キャンペーン特典を付与
		foreach( $cmplist as $val ){

			// User更新（ただし、契約期間中のみ）
			$user = $User->find('first',array('conditions'=>array('user_uid'=>$val['user_uid'], 'period_status'=>1)));
			if( $user == false ){
				continue;
			}

			$newPeriod = strtotime($user['User']['current_period']) + ( $val['term'] * 60 * 60 * 24 );
			$user['User']['current_period']	= date( 'Y-m-d 00:00:00', $newPeriod );
			$User->save( $user, false );

			// メッセージ配信
			$Message->setAddCampaign( $user, $val['term'] );
print_r('campaign: ' . $user['User']['user_uid'] . "\n");
		}

	}




	private function UsedCampaign( $campaign_uid ) {

		$save = $this->find('first', array('conditions'=>array('campaign_uid'=>$campaign_uid)));
		$save['Campaign']['used'] = 1;
		$this->save( $save, false );
	}


}

?>