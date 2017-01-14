<?php
App::import('Core', 'HttpSocket');

class CrawlersTask extends Shell
{
//	var $uses = array('Crawler', 'Transaction');
	var $uses       = array( 'Work', 'Regist', 'CrawlerDlsite' );

	// オーバーライドして、Welcome to CakePHP･･･のメッセージを出さないようにする。
//	function startup() {}
    function main() {
        $this->out( "いらっしゃいませ" );
        $this->hr();
        $this->out( "ごちゅうもんをどうぞ" );
    }



	function execute() {
		$url = 'http://bantyou.livedoor.biz/';
		$this->Crawler->crawl($url);

		for ($i=2; $i<50; $i++) {
			$url = 'http://bantyou.livedoor.biz/?p='.strval($i);
			$this->Crawler->crawl($url);
			sleep(1);
		}

		$options = array();
		$options['conditions'] = array('Crawler.response_status' =>null);
		$options['fields']     = array('Crawler.uuid', 'Crawler.image_url', 'Crawler.image_file_path', 'Crawler.response_status');

		$crawler_list = $this->Crawler->find('all', $options);
//print_r($crawler_list);
		foreach ($crawler_list as $crawler) {
			$this->Crawler->getImageFile($crawler['Crawler']);
//print_r($crawler);
			$this->Crawler->save($crawler);
			sleep( mt_rand(0, 1) );
		}

	}


	/**
	* 新規 DLsiteクロールバッチ
	*
	* @param  none
	* @return none
	*/
	function crlNewDlsite() {

	}


	/**
	* 新規 DMMクロールバッチ
	*
	* @param  none
	* @return none
	*/
	function crlNewDmm() {

	}


	/**
	* 既存 DLsiteクロールバッチ
	*
	* @param  none
	* @return none
	*/
	function crlDlsite() {
$this->out( join( $this->args ) );

		$this->out( $this->args[0] );
		$this->out( $this->args[1] );
		$this->out( $this->args[2] );

/*
		$data = array();
		$data = $this->CrawlerDlsite->get_dlsite_works( 2010, 12, 2);
		if( empty($data) ){
			exit;
		}

		foreach( $data as $val ){		// 作品数分ループ
			$work['Work']		= $val['Work'];
			$regist['Regist']	= $val['Regist'];

			// worksレコード生成
			$rc = $this->Work->find('first', array( 'conditions'=>array( 'Work.work_name'=>$work['Work']['work_name'], 'Work.circle_name'=>$work['Work']['circle_name'] )));
			if( !empty($rc) ){	// 同作品が既に存在している場合はUPDATE
				$work['Work']['work_uid'] = $rc['Work']['work_uid'];
			} else {
				$this->Work->create();
			}
			$this->Work->save( $work, false );

			if( !empty($rc) ){
				$regist['Regist']['work_uid'] = $rc['Work']['work_uid'];
			} else {
				$regist['Regist']['work_uid'] = $this->Work->getLastInsertID();
			}

			// registsレコード生成
			$rc = $this->Regist->find('first', array( 'conditions'=>array( 'Regist.work_uid'=>$regist['Regist']['work_uid'], 'Regist.vendor_uid'=>$regist['Regist']['vendor_uid'] )));
			if( !empty($rc) ){	// 同作品が既に存在している場合はUPDATE
				$regist['Regist']['regist_uid'] = $rc['Regist']['regist_uid'];
			} else {
				$this->Regist->create();
			}
			$this->Regist->save( $regist, false );
		}
*/
	}


	/**
	* 既存 DMMクロールバッチ
	*
	* @param  none
	* @return none
	*/
	function crlDmm() {

	}


	/**
	* 作品属性カテゴライズバッチ
	*
	* @param  none
	* @return none
	*/
	function exCtgl() {

	}

}
