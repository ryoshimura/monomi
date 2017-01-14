<?php

class IllegalResult extends AppModel {
	var $name = 'IllegalResult';



	public function __construct() {
		$id = array("id" => false,
					"table" => "illegal_results",
				   );
		parent::__construct($id);
		$this->primaryKey = "illegal_result_uid";
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
	* dashboardで使用する検出数
	*
	* @param
	* @return
	*/
	public function dashboard( $user_uid ) {


		$res = array(
			'ilcnt'	=> 0,
			'dlcnt'	=> 0
		);


		// 検出イリーガルサイト数
		$sql = 'SELECT ';
		$sql.= 'COUNT(*)';
		$sql.= ' FROM ';
		$sql.= 'illegal_results AS IR ';
		$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = IR.word_uid ';
//		$sql.= 'LEFT JOIN users AS User ON User.user_uid = Word.user_uid ';
//		$sql.= 'LEFT JOIN illegal_sites AS IRS ON IRS.site_uid = IR.site_uid ';
		$sql.= 'WHERE ';
//		$sql.= 'User.user_uid = "'. $user_uid .'" ';
		$sql.= 'Word.user_uid = "'. $user_uid .'" ';
		$sql.= 'AND IR.trash = 0 ';
		$sql.= 'AND IR.deleted = 0 ';
		$sql.= 'AND Word.deleted = 0 ';
//		$sql.= 'LIMIT 100';
//pr($sql);
		$ilcnt = $this->query( $sql );
		$res['ilcnt'] = $ilcnt[0][0]['COUNT(*)'];


/*
		// 検出アップローダ数
		$sql = 'SELECT ';
		$sql.= 'COUNT(*)';
		$sql.= ' FROM ';
		$sql.= 'illegal_results AS IR ';
		$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = IR.word_uid ';
//		$sql.= 'LEFT JOIN users AS User ON User.user_uid = Word.user_uid ';
//		$sql.= 'LEFT JOIN illegal_sites AS IRS ON IRS.site_uid = IR.site_uid ';
		$sql.= 'LEFT JOIN result_relations AS RR ON RR.illegal_result_uid = IR.illegal_result_uid ';
		$sql.= 'LEFT JOIN download_results AS DR ON RR.download_result_uid = DR.download_result_uid ';
		$sql.= 'WHERE ';
		$sql.= 'Word.user_uid = "'. $user_uid .'" ';
		$sql.= 'AND IR.trash = 0 ';
		$sql.= 'AND IR.deleted = 0 ';
//		$sql.= 'AND IRS.deleted = 0 ';
		$sql.= 'AND Word.deleted = 0 ';
		$sql.= 'LIMIT 100';

		$dlcnt = $this->query( $sql );
		$res['dlcnt'] = $dlcnt[0][0]['COUNT(*)'];
*/
		return $res;
	}







	/**
	* inboxで使用するLISTを取得
	*
	* @param
	* @return
	*/
	public function inbox( $user_uid, $page, $limit_cnt, $sort, $trial_flag = false ) {


		$Word = ClassRegistry::init('Word');


		$res = array(
			'data'	=> '',
			'Allcnt'=> 0
		);


		// 全件取得L
		$sql = 'SELECT ';
		$sql.= 'IR.illegal_result_uid,';
		$sql.= 'IR.illegal_url,';
		$sql.= 'IR.regist_date,';
		$sql.= 'IR.created,';
		$sql.= 'IR.trash,';
		$sql.= 'IR.memo_request,';
		$sql.= 'IR.memo_complete,';
		$sql.= 'DS.download_site_uid,';
		$sql.= 'DS.site_name,';
		$sql.= 'DS.download_url,';
		$sql.= 'DR.download_result_uid,';
		$sql.= 'DR.download_result_url,';
		$sql.= 'DR.trash,';
		$sql.= 'DR.memo_request,';
		$sql.= 'DR.memo_complete,';
		$sql.= 'IRS.site_uid,';
		$sql.= 'IRS.site_name,';
		$sql.= 'IRS.flag_torrent,';
		$sql.= 'Word.word_uid,';
		$sql.= 'Word.search_word';

		$sql.= ' FROM ';
		$sql.= 'illegal_results AS IR ';
		$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = IR.word_uid ';
		$sql.= 'LEFT JOIN illegal_sites AS IRS ON IRS.site_uid = IR.site_uid ';
		$sql.= 'LEFT JOIN result_relations AS RR ON RR.illegal_result_uid = IR.illegal_result_uid ';
		$sql.= 'LEFT JOIN download_results AS DR ON RR.download_result_uid = DR.download_result_uid ';
		$sql.= 'LEFT JOIN download_sites AS DS ON DS.download_site_uid = DR.download_site_uid ';

		$sql.= 'WHERE ';
		$sql.= 'IRS.deleted = 0 ';
		$sql.= 'AND IR.trash = 0 ';
		$sql.= 'AND Word.user_uid = "'. $user_uid .'" ';
		$sql.= 'AND Word.deleted = 0 ';
		$sql.= 'AND IR.deleted = 0 ';

		$sql.= 'ORDER BY ';
		$sql.= 'IR.illegal_result_uid, DS.site_name, Word.search_word' ;
//		$sql.= 'DS.site_name, Word.search_word' ;
//		$sql.= ' LIMIT 0,10';
		$data = $this->query( $sql );

//pr($sql);

/*
		// 復号化
		foreach($data as $k=>$v){
			$buf = array();
			$buf['Word'] = $v['Word'];
			$buf = $Word->decryptedWord( $buf );
			$data[$k]['Word'] = $buf['Word'];
		}
*/

//pr($data);
		// アップローダをまとめる
		$i=0;
		$j=0;
		$k=0;
		$buf = array();
		$flag_first = true;
		foreach( $data as $key => $val ){

			if( $flag_first == true  ){
				$buf[$i]['IR'] = $val['IR'];
				$buf[$i]['IRS'] = $val['IRS'];
//				$buf[$i]['SR'] = $val['SR'];
				$buf[$i]['Word'] = $val['Word'];
				$buf[$i]['SUB'][$j]['DS'] = $val['DS'];
				$buf[$i]['SUB'][$j]['DR'] = $val['DR'];
//				$buf[$i]['SUB'][$j]['SR'] = $val['SR'];
				$buf[$i]['SUB'][$j]['URL'][$k] = $val['DR']['download_result_url'];

				$flag_first = false;

			} else {
				if( $val['IR']['illegal_result_uid'] === $data[$key-1]['IR']['illegal_result_uid'] ){	// 前レコードとillegal_result_uidが同じ場合

//					$j++;
//					$buf[$i]['SUB'][$j]['DS'] = $val['DS'];
//					$buf[$i]['SUB'][$j]['DR'] = $val['DR'];

					if( $val['DS']['site_name'] !== $data[$key-1]['DS']['site_name'] ){		// 前レコードのダウンロードサイト名と異なる場合
						$j++;
						$k=0;
						$buf[$i]['SUB'][$j]['DS'] = $val['DS'];
						$buf[$i]['SUB'][$j]['DR'] = $val['DR'];
//						$buf[$i]['SUB'][$j]['SR'] = $val['SR'];
						$buf[$i]['SUB'][$j]['URL'][$k] = $val['DR']['download_result_url'];

					} else {

						$k++;
						$buf[$i]['SUB'][$j]['URL'][$k] = $val['DR']['download_result_url'];

					}

				} else {
					$i++;
					$j=0;
					$k=0;
					$buf[$i]['IR'] = $val['IR'];
					$buf[$i]['IRS'] = $val['IRS'];
//					$buf[$i]['SR'] = $val['SR'];
					$buf[$i]['Word'] = $val['Word'];
					$buf[$i]['SUB'][$j]['DS'] = $val['DS'];
					$buf[$i]['SUB'][$j]['DR'] = $val['DR'];
//					$buf[$i]['SUB'][$j]['SR'] = $val['SR'];
					$buf[$i]['SUB'][$j]['URL'][$k] = $val['DR']['download_result_url'];
				}
			}
		}
//pr($buf);


		// 重複 illegal_url をまとめる
		$i=0;
		$vBuf = array();
		$flag_first = true;
		$flag_overlap = false;
		foreach( $buf as $key => $val ){

			if( $flag_first == true  ){
				$vBuf[$i] = $val;
				$flag_first = false;

			} else {

				foreach( $vBuf as $k => $v ){

					// illegal_urlが同じだがillegal_result_uidが異なるレコードはまとめる。別ワードで同じurlを検知したケース。
					if( $val['IR']['illegal_url'] === $v['IR']['illegal_url'] && $val['IR']['illegal_result_uid'] !== $v['IR']['illegal_result_uid'] ){

						$vBuf[$k]['Word']['search_word'] .= ', ' . $val['Word']['search_word'];

						// 日付も新しい場合は上書き
						if( strtotime($v['IR']['regist_date']) < strtotime($val['IR']['regist_date']) ){
							$vBuf[$k]['IR']['regist_date'] = $val['IR']['regist_date'];
						}

						$flag_overlap = true;
						break;
					}
				}

				if( $flag_overlap != true ){	// 重複ない場合はそのまま格納
					$i++;
					$vBuf[$i] = $val;
				}
				$flag_overlap = false;
			}
		}

//pr($vBuf);


		// ソート
		$sort_key = array();
		if( false == $sort || 'dated' === $sort ){	// 通常表示
			foreach( $vBuf as $key => $row) {
//				$sort_key[$key] = $row['IR']['regist_date'];
				$sort_key[$key] = $row['IR']['created'];
			}
			array_multisort( $sort_key, SORT_DESC, SORT_STRING, $vBuf );

		} else if( 'datea' === $sort ){	// 検出日 昇順
			foreach( $vBuf as $key => $row) {
//				$sort_key[$key] = $row['IR']['regist_date'];
				$sort_key[$key] = $row['IR']['created'];
			}
			array_multisort( $sort_key, SORT_ASC, SORT_STRING, $vBuf );

		} else if( 'sited' === $sort ){	// 検出サイト 降順
			foreach( $vBuf as $key => $row) {
				$sort_key[$key] = $row['IRS']['site_name'];
			}
			array_multisort( $sort_key, SORT_DESC, SORT_STRING, $vBuf );

		} else if( 'sitea' === $sort ){	// 検出サイト 昇順
			foreach( $vBuf as $key => $row) {
				$sort_key[$key] = $row['IRS']['site_name'];
			}
			array_multisort( $sort_key, SORT_ASC, SORT_STRING, $vBuf );

		}

		$res['Allcnt'] = count($vBuf);

//pr($vBuf);

		// LIMITで件数指定
		$i = 0;
//		$start	= ( $page -1 ) * $limit_cnt;
		$start	= ( $page - 1 ) * $limit_cnt;
		$end	= $start + $limit_cnt - 1;
		if( $trial_flag == false ){		// 体験版でない場合

			foreach( $vBuf as $key => $val ){
				if( $key >= $start && $key <= $end ){
					$buf = array();
					$buf['Word'] = $val['Word'];
					$buf = $Word->decryptedWord( $buf );
					$val['Word'] = $buf['Word'];
					$res['data'][$i] = $val;
					$i++;
				}
			}

		} else {						// 体験版の場合
			$end = 2;	// ３件のみ
			foreach( $vBuf as $key => $val ){
				if( $i <= $end && ( $val['SUB'][0]['DS']['download_site_uid'] !== '' && $val['SUB'][0]['DS']['download_site_uid'] !== null ) ){
					$buf = array();
					$buf['Word'] = $val['Word'];
					$buf = $Word->decryptedWord( $buf );
					$val['Word'] = $buf['Word'];
					$res['data'][$i] = $val;
					$i++;
				}
			}

//pr( count($res['data']) );
//pr( $res );
			if( 3 > count($res['data']) ){	// 3つに満たない場合は、アップロード不明リザルトも表示
				$i = 0;
				foreach( $vBuf as $key => $val ){
					if( $key >= $start && $key <= $end ){
						$buf = array();
						$buf['Word'] = $val['Word'];
						$buf = $Word->decryptedWord( $buf );
						$val['Word'] = $buf['Word'];
						$res['data'][$i] = $val;
						$i++;
					}
				}
			}

		}






//pr($res);

		return $res;
	}




	/**
	* （旧）inboxで使用するLISTを取得
	*
	* @param
	* @return
	*/
	public function old_inbox( $user_uid, $page, $limit_cnt, $sort, $trial_flag = false ) {


		$Word = ClassRegistry::init('Word');


		$res = array(
			'data'	=> '',
			'Allcnt'=> 0
		);

		// 全件数取得
		$sql = 'SELECT ';
		$sql.= 'COUNT(*)';
		$sql.= ' FROM ';
		$sql.= 'illegal_results AS IR ';
		$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = IR.word_uid ';
		$sql.= 'LEFT JOIN users AS User ON User.user_uid = Word.user_uid ';
		$sql.= 'LEFT JOIN illegal_sites AS IRS ON IRS.site_uid = IR.site_uid ';
		$sql.= 'WHERE ';
		$sql.= 'User.user_uid = "'. $user_uid .'" ';
		$sql.= 'AND IR.trash = 0 ';
		$sql.= 'AND IR.deleted = 0 ';
		$sql.= 'AND IRS.deleted = 0 ';
		$sql.= 'AND Word.deleted = 0 ';

		$Allcnt = $this->query( $sql );
		$res['Allcnt'] = $Allcnt[0][0]['COUNT(*)'];


		$start	= ( $page -1 ) * $limit_cnt;

		// ベースとなるSQL
		$sql = 'SELECT ';
		$sql.= 'IR.illegal_result_uid,';
		$sql.= 'IR.regist_date,';
		$sql.= 'IRS.site_name,';
		$sql.= 'Word.search_word';
		$sql.= ' FROM ';
		$sql.= 'illegal_results AS IR ';
		$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = IR.word_uid ';
		$sql.= 'LEFT JOIN users AS User ON User.user_uid = Word.user_uid ';
		$sql.= 'LEFT JOIN illegal_sites AS IRS ON IRS.site_uid = IR.site_uid ';

		$sql.= 'WHERE ';
		$sql.= 'User.user_uid = "'. $user_uid .'" ';
		$sql.= 'AND IR.trash = 0 ';
		$sql.= 'AND IR.deleted = 0 ';
		$sql.= 'AND Word.deleted = 0 ';

		// add 2012.09.17 ソート機能追加
		if( $trial_flag == false ){		// 体験版以外の場合はここでもソート
			$sql.= 'ORDER BY ';
			if( false == $sort || 'dated' === $sort ){	// 通常表示
				$sql.= 'IR.regist_date DESC, Word.search_word, IRS.site_name, IR.illegal_result_uid' ;
			} else if( 'datea' === $sort ){	// 検出日 降順
				$sql.= 'IR.regist_date ASC, Word.search_word, IRS.site_name, IR.illegal_result_uid' ;
			} else if( 'sited' === $sort ){	// 検出サイト 昇順
				$sql.= 'IRS.site_name DESC, IR.regist_date DESC, Word.search_word, IR.illegal_result_uid' ;
			} else if( 'sitea' === $sort ){	// 検出サイト 降順
				$sql.= 'IRS.site_name ASC, IR.regist_date DESC, Word.search_word, IR.illegal_result_uid' ;
			} else if( 'wordd' === $sort ){	// 監視ワード 昇順
				$sql.= 'Word.search_word DESC, IR.regist_date DESC, Word.search_word, IRS.site_name, IR.illegal_result_uid' ;
			} else if( 'worda' === $sort ){	// 監視ワード 降順
				$sql.= 'Word.search_word ASC, IR.regist_date DESC, Word.search_word, IRS.site_name, IR.illegal_result_uid' ;
			}
		}

		// LIMIT
		if( $trial_flag == false ){		// 体験版でない場合
			$sql.= ' LIMIT '. $start . ', ' . $limit_cnt . ' ';
		} else {						// 体験版の場合
			$sql.= ' LIMIT '. $start . ', 5 ';
		}

		$rc = $this->query( $sql );


		if( empty( $rc ) ){		// 対象データが存在しない場合
			return $res;
		}

//pr($rc);


		// ベースとなるSQL
		$sql = 'SELECT ';
		$sql.= 'IR.illegal_result_uid,';
		$sql.= 'IR.illegal_url,';
		$sql.= 'IR.regist_date,';
		$sql.= 'IR.trash,';
		$sql.= 'IR.regist_date,';
		$sql.= 'IR.memo_request,';
		$sql.= 'IR.memo_complete,';
		$sql.= 'DS.download_site_uid,';
		$sql.= 'DS.site_name,';
		$sql.= 'DS.download_url,';
		$sql.= 'DR.download_result_uid,';
		$sql.= 'DR.download_result_url,';
		$sql.= 'DR.trash,';
		$sql.= 'DR.memo_request,';
		$sql.= 'DR.memo_complete,';
		$sql.= 'IRS.site_uid,';
		$sql.= 'IRS.site_name,';
		$sql.= 'Word.search_word';

		$sql.= ' FROM ';
		$sql.= 'illegal_results AS IR ';
		$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = IR.word_uid ';
		$sql.= 'LEFT JOIN users AS User ON User.user_uid = Word.user_uid ';
		$sql.= 'LEFT JOIN illegal_sites AS IRS ON IRS.site_uid = IR.site_uid ';
		$sql.= 'LEFT JOIN result_relations AS RR ON RR.illegal_result_uid = IR.illegal_result_uid ';
		$sql.= 'LEFT JOIN download_results AS DR ON RR.download_result_uid = DR.download_result_uid ';
		$sql.= 'LEFT JOIN download_sites AS DS ON DS.download_site_uid = DR.download_site_uid ';

		$sql.= 'WHERE ';
		$sql.= 'IRS.deleted = 0 ';
//		$sql.= 'AND DS.deleted = 0 ';
		$sql.= 'AND IR.illegal_result_uid IN ( ';
		$i = 0;
		foreach($rc as $rc){
			if($i == 0){
				$sql.= '"'. $rc['IR']['illegal_result_uid'] .'"';
			} else {
				$sql.= ',"'. $rc['IR']['illegal_result_uid'] .'"';
			}
			$i++;
		}
		$sql.= ' ) ';

//		$sql.= 'ORDER BY ';
//		$sql.= 'IR.regist_date DESC, Word.search_word, IRS.site_name, IR.illegal_result_uid, DS.site_name' ;

//pr($sort);
		// add 2012.09.17 ソート機能追加
		if( false == $sort || 'dated' === $sort ){	// 通常表示
			$sql.= 'ORDER BY IR.regist_date DESC, Word.search_word, IRS.site_name, IR.illegal_result_uid, DS.site_name' ;

		} else if( 'datea' === $sort ){	// 検出日 降順
			$sql.= 'ORDER BY IR.regist_date ASC, Word.search_word, IRS.site_name, IR.illegal_result_uid, DS.site_name' ;

		} else if( 'sited' === $sort ){	// 検出サイト 昇順
			$sql.= 'ORDER BY IRS.site_name DESC, IR.regist_date DESC, Word.search_word, IR.illegal_result_uid, DS.site_name' ;

		} else if( 'sitea' === $sort ){	// 検出サイト 降順
			$sql.= 'ORDER BY IRS.site_name ASC, IR.regist_date DESC, Word.search_word, IR.illegal_result_uid, DS.site_name' ;

//		} else if( 'wordd' === $sort ){	// 監視ワード 昇順
//			$sql.= 'Word.search_word DESC, IR.regist_date DESC, Word.search_word, IRS.site_name, IR.illegal_result_uid, DS.site_name' ;
//
//		} else if( 'worda' === $sort ){	// 監視ワード 降順
//			$sql.= 'Word.search_word ASC, IR.regist_date DESC, Word.search_word, IRS.site_name, IR.illegal_result_uid, DS.site_name' ;

		}


		$data = $this->query( $sql );
//pr($data);

		// 復号化
		foreach($data as $k=>$v){
			$buf = array();
			$buf['Word'] = $v['Word'];
			$buf = $Word->decryptedWord( $buf );
			$data[$k]['Word'] = $buf['Word'];
		}


		// 監視ワードのソート
		$sort_key = array();
		foreach( $data as $key => $row) {
//				$sort_key[$key] = $row['GoraCourse']['ic_distance'];
			$sort_key[$key] = $row['Word']['search_word'];
		}

		if( 'wordd' === $sort ){	// 監視ワード 昇順
			array_multisort( $sort_key, SORT_DESC, SORT_NUMERIC, $data );
		} else if( 'worda' === $sort ){	// 監視ワード 降順
			array_multisort( $sort_key, SORT_ASC, SORT_NUMERIC, $data );
		}


//pr($data);
		// データ整形
		$i = 0;
		$IRcnt = 0;
		$DRcnt = 0;
		$resbuf = array();
		foreach( $data as $key => $val ){

			if( $i == 0 ){
				$val['IR']['sub_flag'] = 0;
				$resbuf[] = $val;

				$val['IR']['sub_flag'] = 1;
				$resbuf[] = $val;

				$IRcnt++;
				$DRcnt++;

			} else {

				if( $val['IR']['illegal_result_uid'] === $data[$key-1]['IR']['illegal_result_uid'] ){	// 前レコードとillegal_result_uidが同じ場合
					$val['IR']['sub_flag'] = 1;
					$resbuf[] = $val;

					$DRcnt++;

				} else {
					$val['IR']['sub_flag'] = 0;
					$resbuf[] = $val;

					$val['IR']['sub_flag'] = 1;
					$resbuf[] = $val;

					$IRcnt++;
					$DRcnt++;
				}
			}

			$i++;
		}

//pr($resbuf);

		// リターン用変数を用意
		$res['data'] = $resbuf;


		return $res;
	}





	/**
	* trashで使用するLISTを取得
	*
	* @param
	* @return
	*/
	public function trash( $user_uid ) {

		$Word = ClassRegistry::init('Word');

		$res = array(
			'data'	=> '',
			'Allcnt'=> 0
		);


		// 全件取得
		$sql = 'SELECT ';
		$sql.= 'IR.illegal_result_uid,';
		$sql.= 'IR.illegal_url,';
		$sql.= 'IR.regist_date,';
		$sql.= 'IR.trash,';
		$sql.= 'IR.regist_date,';
		$sql.= 'IR.memo_request,';
		$sql.= 'IR.memo_complete,';
		$sql.= 'IRS.site_name,';
		$sql.= 'Word.search_word';

		$sql.= ' FROM ';
		$sql.= 'illegal_results AS IR ';
		$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = IR.word_uid ';
		$sql.= 'LEFT JOIN illegal_sites AS IRS ON IRS.site_uid = IR.site_uid ';

		$sql.= 'WHERE ';
		$sql.= 'IRS.deleted = 0 ';
		$sql.= 'AND IR.trash = 1 ';
		$sql.= 'AND IR.deleted = 0 ';
		$sql.= 'AND Word.user_uid = "'. $user_uid .'" ';
		$sql.= 'AND Word.deleted = 0 ';
		$sql.= 'ORDER BY ';
		$sql.= 'IR.regist_date DESC' ;
		$data = $this->query( $sql );

		// 復号化
		foreach($data as $k=>$v){
			$buf = array();
			$buf['Word'] = $v['Word'];
			$buf = $Word->decryptedWord( $buf );
			$data[$k]['Word'] = $buf['Word'];
		}


//pr($data);
		// 重複 illegal_url をまとめる
		$i=0;
		$vBuf = array();
		$flag_first = true;
		$flag_overlap = false;
		foreach( $data as $key => $val ){

			if( $flag_first == true  ){
				$vBuf[$i] = $val;
				$flag_first = false;

			} else {

				foreach( $vBuf as $k => $v ){

					// illegal_urlが同じだがillegal_result_uidが異なるレコードはまとめる。別ワードで同じurlを検知したケース。
					if( $val['IR']['illegal_url'] === $v['IR']['illegal_url'] && $val['IR']['illegal_result_uid'] !== $v['IR']['illegal_result_uid'] ){

						$vBuf[$k]['Word']['search_word'] .= ', ' . $val['Word']['search_word'];

						// 日付も新しい場合は上書き
						if( strtotime($v['IR']['regist_date']) < strtotime($val['IR']['regist_date']) ){
							$vBuf[$k]['IR']['regist_date'] = $val['IR']['regist_date'];
						}

						$flag_overlap = true;
						break;
					}
				}

				if( $flag_overlap != true ){	// 重複ない場合はそのまま格納
					$i++;
					$vBuf[$i] = $val;
				}
				$flag_overlap = false;
			}
		}


		$res['data'] = $vBuf;
		return $res;

	}







	/**
	* （旧）trashで使用するLISTを取得
	*
	* @param
	* @return
	*/
	public function old_trash( $user_uid ) {

		$Word = ClassRegistry::init('Word');

		$res = array(
			'data'	=> '',
			'Allcnt'=> 0
		);


		// 全件数取得
/*		$sql = 'SELECT ';
		$sql.= 'COUNT(*)';
		$sql.= ' FROM ';
		$sql.= 'illegal_results AS IR ';
		$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = IR.word_uid ';
		$sql.= 'LEFT JOIN users AS User ON User.user_uid = Word.user_uid ';
		$sql.= 'LEFT JOIN illegal_sites AS IRS ON IRS.site_uid = IR.site_uid ';
		$sql.= 'WHERE ';
		$sql.= 'User.user_uid = "'. $user_uid .'" ';
		$sql.= 'AND IR.trash = 1 ';
		$sql.= 'AND IR.deleted = 0 ';
		$sql.= 'AND Word.deleted = 0 ';

		$Allcnt = $this->query( $sql );
		$res['Allcnt'] = $Allcnt[0][0]['COUNT(*)'];
*/



//		$start	= ( $page -1 ) * $limit_cnt;

		// ベースとなるSQL
		$sql = 'SELECT ';
		$sql.= 'IR.illegal_result_uid,';
		$sql.= 'IR.illegal_url,';
		$sql.= 'IR.regist_date,';
		$sql.= 'IR.trash,';
		$sql.= 'IR.regist_date,';
		$sql.= 'IR.memo_request,';
		$sql.= 'IR.memo_complete,';
		$sql.= 'IRS.site_name,';
		$sql.= 'Word.search_word';


		$sql.= ' FROM ';
		$sql.= 'illegal_results AS IR ';
		$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = IR.word_uid ';
		$sql.= 'LEFT JOIN users AS User ON User.user_uid = Word.user_uid ';
		$sql.= 'LEFT JOIN illegal_sites AS IRS ON IRS.site_uid = IR.site_uid ';

		$sql.= 'WHERE ';
		$sql.= 'User.user_uid = "'. $user_uid .'" ';
		$sql.= 'AND IR.trash = 1 ';
		$sql.= 'AND IR.deleted = 0 ';
		$sql.= 'AND Word.deleted = 0 ';

//		$sql.= 'LIMIT '. $start . ', ' . $limit_cnt . ' ';

		$rc = $this->query( $sql );
//pr($rc);

		// 復号化
		foreach($rc as $k=>$v){
			$buf = array();
			$buf['Word'] = $v['Word'];
			$buf = $Word->decryptedWord( $buf );
			$rc[$k]['Word'] = $buf['Word'];
		}

		// リターン用変数を用意
		$res['data'] = $rc;

//pr($res);

		return $res;
	}





	/**
	* dmcaで使用するサイト一覧を取得
	*
	* @param
	* @return
	*/
	public function dmca_list(  $mode, $uid  ) {

		$data = array();

		if( $mode === 'is' ){

			// イリーガルサイト
			$sql = 'SELECT ';
			$sql.= 'IR.illegal_result_uid,';
			$sql.= 'IR.illegal_url,';
			$sql.= 'IRS.site_name';
			$sql.= ' FROM ';
			$sql.= 'illegal_results AS IR ';
			$sql.= 'LEFT JOIN illegal_sites AS IRS ON IRS.site_uid = IR.site_uid ';
			$sql.= 'WHERE ';
			$sql.= 'IR.illegal_result_uid = "'. $uid .'" ';
			$buf = $this->query( $sql );
			$data['is'] = $buf[0];

			// アップローダ
/*			$sql = 'SELECT ';
			$sql.= 'DS.site_name,';
			$sql.= 'DR.download_result_url';
			$sql.= ' FROM ';
			$sql.= 'illegal_results AS IR ';
			$sql.= 'LEFT JOIN illegal_sites AS IRS ON IRS.site_uid = IR.site_uid ';
			$sql.= 'LEFT JOIN result_relations AS RR ON RR.illegal_result_uid = IR.illegal_result_uid ';
			$sql.= 'LEFT JOIN download_results AS DR ON RR.download_result_uid = DR.download_result_uid ';
			$sql.= 'LEFT JOIN download_sites AS DS ON DS.download_site_uid = DR.download_site_uid ';
			$sql.= 'WHERE ';
			$sql.= 'IR.illegal_result_uid = "'. $uid .'" ';
			$sql.= 'ORDER BY ';
			$sql.= 'DS.site_name ASC, DR.download_result_url ASC' ;
			$buf = $this->query( $sql );
			$data['ds'] = $buf;
*/
			$data['ds'] = array();
			$data['hidden'] = $data['is']['IR']['illegal_result_uid'];


		} else if( $mode === 'ds' ){

			// イリーガルサイト
			$sql = 'SELECT ';
			$sql.= 'IR.illegal_result_uid,';
			$sql.= 'IR.illegal_url,';
			$sql.= 'IRS.site_name';
			$sql.= ' FROM ';
			$sql.= 'illegal_results AS IR ';
			$sql.= 'LEFT JOIN illegal_sites AS IRS ON IRS.site_uid = IR.site_uid ';
			$sql.= 'LEFT JOIN result_relations AS RR ON RR.illegal_result_uid = IR.illegal_result_uid ';
			$sql.= 'LEFT JOIN download_results AS DR ON RR.download_result_uid = DR.download_result_uid ';
			$sql.= 'LEFT JOIN download_sites AS DS ON DS.download_site_uid = DR.download_site_uid ';
			$sql.= 'WHERE ';
			$sql.= 'DR.download_result_uid = "'. $uid .'" ';
			$sql.= 'ORDER BY ';
			$sql.= 'DS.site_name ASC, DR.download_result_url ASC' ;
			$buf = $this->query( $sql );
			$data['is']['IR'] = $buf[0]['IR'];
			$data['is']['IRS'] = $buf[0]['IRS'];


			// アップローダ
			$sql = 'SELECT ';
			$sql.= 'DS.download_site_uid';
			$sql.= ' FROM ';
			$sql.= 'download_results AS DR ';
			$sql.= 'LEFT JOIN download_sites AS DS ON DS.download_site_uid = DR.download_site_uid ';
			$sql.= 'WHERE ';
			$sql.= 'DR.download_result_uid = "'. $uid .'" ';
			$buf = $this->query( $sql );
			$download_site_uid = $buf[0]['DS']['download_site_uid'];

			$sql = 'SELECT ';
			$sql.= 'DR.download_result_uid,';
			$sql.= 'DS.site_name,';
			$sql.= 'DR.download_result_url';
			$sql.= ' FROM ';
			$sql.= 'download_results AS DR ';
			$sql.= 'LEFT JOIN download_sites AS DS ON DS.download_site_uid = DR.download_site_uid ';
			$sql.= 'LEFT JOIN result_relations AS RR ON RR.download_result_uid = DR.download_result_uid ';
			$sql.= 'LEFT JOIN illegal_results AS IR ON RR.illegal_result_uid = IR.illegal_result_uid ';
			$sql.= 'WHERE ';
//			$sql.= 'DR.download_result_uid = "'. $uid .'" ';
			$sql.= 'IR.illegal_result_uid = "'. $data['is']['IR']['illegal_result_uid'] .'" ';
			$sql.= 'AND DS.download_site_uid = "'. $download_site_uid .'" ';
			$sql.= 'ORDER BY ';
			$sql.= 'DS.site_name ASC, DR.download_result_url ASC' ;
			$buf = $this->query( $sql );
			$data['ds'] = $buf;

			$i=0;
			foreach( $buf as $buf ){
				if( $i == 0 ){
					$data['hidden'] = $buf['DR']['download_result_uid'];
				} else {
					$data['hidden'] .= ',' . $buf['DR']['download_result_uid'];
				}
				$i++;
			}

		}


		return $data;

	}




	/**
	* user_uid と url から該当IllegalResultレコードを抽出 for aj_inbox_request, aj_inbox_comp
	*
	* @param
	* @return
	*/
	public function getIR( $user_uid, $ir_uid ) {

		$buf = $this->find( 'first', array('conditions'=>array('illegal_result_uid'=>$ir_uid)) );

		// イリーガルサイト
		$sql = 'SELECT *';
		$sql.= ' FROM ';
		$sql.= 'illegal_results AS IllegalResult ';
		$sql.= 'LEFT JOIN words AS Word ON Word.word_uid = IllegalResult.word_uid ';
		$sql.= 'WHERE ';
		$sql.= 'Word.user_uid = "'. $user_uid .'" ';
		$sql.= 'AND IllegalResult.illegal_url = "'. $buf['IllegalResult']['illegal_url'] .'" ';

		return $this->query( $sql );

	}







}

?>