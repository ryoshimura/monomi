<?php
require_once "tracer.php";

class Crlsite extends Tracer {

	public $name = 'Crlsite';
	public $useTable = false;

//	public $data = array( 'Crawler'=>array() );

	public function __construct() {
/*
		$id = array("id" => false,
					"table" => "site_image_datas",
				   );
		parent::__construct($id);
		$this->primaryKey = "uuid";

		$data['Crawler']['site_uid'] = String::uuid();
*/
	}






	/**
	* イリーガルサイトをクロール
	*
	* @param
	* @return none
	*/
	public function crawler_site( $mode, $start = 0 ) {

		$aryErr = array();

		// プロキシListを更新
		$crlModels = ClassRegistry::init('Crlmodels');
		$crlModels->update_proxy_list();


		// クロールサイト一覧を取得
		$IllegalSite = ClassRegistry::init('IllegalSite');
//		$site = $IllegalSite->find('all', array('conditions'=>array('deleted'=>0)));

		if( $mode === 'search_new' ){	// １時間以内に新規登録されたワードの場合は、offsetやlimitなしでスクレイピング
			$site = $IllegalSite->find('all', array('conditions'=>array('deleted'=>0)));

		} else if( $mode === 'cache' ){
			$site = $IllegalSite->find('all', array('conditions'=>array('deleted'=>0)));

		} else if( $mode === 'torrent_search' ){
			$site = $IllegalSite->find('all', array('conditions'=>array('deleted'=>0, 'flag_torrent'=>1), 'order'=>array('site_uid ASC'), 'offset'=>$start, 'limit'=>5 ));

		} else {
			$site = $IllegalSite->find('all', array('conditions'=>array('deleted'=>0), 'order'=>array('site_uid ASC'), 'offset'=>$start, 'limit'=>5 ));

		}

//pr($site);

		foreach( $site as $site ){

			$site_name = $site['IllegalSite']['site_name'];
			$type = $site['IllegalSite']['sc_type'];

			if( 'public_1' === $type ){
				$ScPublic1 = ClassRegistry::init('ScPublic1');
				if( $mode === 'search' ){ $aryErr[$site_name] = $ScPublic1->ispSrch( $site ); }	// 検索で全件クロール
					else if( $mode === 'search_new' ) { $aryErr[$site_name] = $ScPublic1->ispSrch( $site, 'new' ); }	// １時間以内に登録されたワードのみ検索クロール
					else if( $mode === 'normal' ){ $aryErr[$site_name] = $ScPublic1->ispCrw( $site ); }		// 最新記事のみクロール

			} else if( 'public_1c' === $type ){		// public_1キャッシュ版
				$ScPublic1c = ClassRegistry::init('ScPublic1c');
				if( $mode === 'search' ){ $aryErr[$site_name] = $ScPublic1c->ispSrch( $site ); }	// 検索で全件クロール
					else if( $mode === 'search_new' ) { $aryErr[$site_name] = $ScPublic1c->ispSrch( $site, 'new' ); }	// １時間以内に登録されたワードのみ検索クロール
					else if( $mode === 'normal' ){ $aryErr[$site_name] = $ScPublic1c->ispCrw( $site ); }		// 最新記事のみクロール

			} else if( 'public_1cuni' === $type ){		// public_1キャッシュ＋ユニコード版
				$ScPublic1cuni = ClassRegistry::init('ScPublic1cuni');
				if( $mode === 'search' ){ $aryErr[$site_name] = $ScPublic1cuni->ispSrch( $site ); }	// 検索で全件クロール
					else if( $mode === 'search_new' ) { $aryErr[$site_name] = $ScPublic1cuni->ispSrch( $site, 'new' ); }	// １時間以内に登録されたワードのみ検索クロール
					else if( $mode === 'normal' ){ $aryErr[$site_name] = $ScPublic1cuni->ispCrw( $site ); }		// 最新記事のみクロール

			} else if( 'public_1ca' === $type ){		// public_1キャッシュ＋<a>なしアップローダ含め全検出版
				$ScPublic1ca = ClassRegistry::init('ScPublic1ca');
				if( $mode === 'search' ){ $aryErr[$site_name] = $ScPublic1ca->ispSrch( $site ); }	// 検索で全件クロール
					else if( $mode === 'search_new' ) { $aryErr[$site_name] = $ScPublic1ca->ispSrch( $site, 'new' ); }	// １時間以内に登録されたワードのみ検索クロール
					else if( $mode === 'normal' ){ $aryErr[$site_name] = $ScPublic1ca->ispCrw( $site ); }		// 最新記事のみクロール

			} else if( 'public_2c' === $type ){		// public_2キャッシュ版
				$ScPublic2c = ClassRegistry::init('ScPublic2c');
				if( $mode === 'search' ){ $aryErr[$site_name] = $ScPublic2c->ispSrch( $site ); }	// 検索で全件クロール
					else if( $mode === 'search_new' ) { $aryErr[$site_name] = $ScPublic2c->ispSrch( $site, 'new' ); }	// １時間以内に登録されたワードのみ検索クロール
					else if( $mode === 'normal' ){ $aryErr[$site_name] = $ScPublic2c->ispCrw( $site ); }		// 最新記事のみクロール

			} else if( 'public_3c' === $type ){		// public_3キャッシュ＋<a>なしアップローダ含め全検出版
				$ScPublic3c = ClassRegistry::init('ScPublic3c');
				if( $mode === 'search' ){ $aryErr[$site_name] = $ScPublic3c->ispSrch( $site ); }	// 検索で全件クロール
					else if( $mode === 'search_new' ) { $aryErr[$site_name] = $ScPublic3c->ispSrch( $site, 'new' ); }	// １時間以内に登録されたワードのみ検索クロール
					else if( $mode === 'normal' ){ $aryErr[$site_name] = $ScPublic3c->ispCrw( $site ); }		// 最新記事のみクロール

			} else if( 'public_torrent1' === $type ){		// トレント全件検索
				$ScPublicTorrent1 = ClassRegistry::init('ScPublicTorrent1');
				if( $mode === 'torrent_search' ){ $aryErr[$site_name] = $ScPublicTorrent1->ispSrch( $site ); }
					else if( $mode === 'search_new' ){ $aryErr[$site_name] = $ScPublicTorrent1->ispSrch( $site, 'new' ); }

			} else if( 'nyaa' === $type ){		// ANIME-SHARING専用タグ
				$ScNyaa = ClassRegistry::init('ScNyaa');
				if( $mode === 'normal' ){ $aryErr[$site_name] = $ScNyaa->ispCrw( $site ); }
					else if( $mode === 'search' ) { $aryErr[$site_name] = $ScNyaa->ispSrch( $site ); }	// 全件検索モード
					else if( $mode === 'search_new' ) { $aryErr[$site_name] = $ScNyaa->ispSrch( $site, 'new' ); }	// 新規登録ワードのみ
//					else if( $mode === 'cache' ) { $aryErr[$site_name] = $ScNyaa->ispCache( $site ); }	// キャッシュモード

			} else if( 'anime-sharing' === $type ){		// ANIME-SHARING専用タグ
				$ScAnimeSharing = ClassRegistry::init('ScAnimeSharing');
				if( $mode === 'normal' ){ $aryErr[$site_name] = $ScAnimeSharing->ispCrw( $site ); }
					else if( $mode === 'cache' ) { $aryErr[$site_name] = $ScAnimeSharing->ispCache( $site ); }	// キャッシュモード

			} else if( 'ehentai' === $type ){		// e-hentai専用タグ
				$ScEhentai = ClassRegistry::init('ScEhentai');
				if( $mode === 'search' ){ $aryErr[$site_name] = $ScEhentai->ispSrch( $site ); }

			} else if( 'throne' === $type ){		// throne専用タグ
				$ScThrone = ClassRegistry::init('ScThrone');
				if( $mode === 'search' ){ $aryErr[$site_name] = $ScThrone->ispSrch( $site ); }	// 検索で全件クロール
					else if( $mode === 'search_new' ) { $aryErr[$site_name] = $ScThrone->ispSrch( $site, 'new' ); }	// １時間以内に登録されたワードのみ検索クロール
					else if( $mode === 'normal' ){ $aryErr[$site_name] = $ScThrone->ispCrw( $site ); }		// 最新記事のみクロール

			} else if( 'elbrollo' === $type ){		// elbrollo専用タグ
				$ScElbrollo = ClassRegistry::init('ScElbrollo');
				if( $mode === 'normal' ){ $aryErr[$site_name] = $ScElbrollo->ispCrw( $site ); }
					else if( $mode === 'cache' ) { $aryErr[$site_name] = $ScElbrollo->ispCache( $site ); }	// キャッシュモード

			} else if( 'erojiji' === $type ){		// erojiji専用タグ
				$ScErojiji = ClassRegistry::init('ScErojiji');
				if( $mode === 'normal' ){ $aryErr[$site_name] = $ScErojiji->ispCrw( $site ); }
					else if( $mode === 'cache' ) { $aryErr[$site_name] = $ScErojiji->ispCache( $site ); }	// キャッシュモード

			} else if( 'usnzone' === $type ){		// usnzone専用タグ
				$ScUsnzone = ClassRegistry::init('ScUsnzone');
				if( $mode === 'normal' ){ $aryErr[$site_name] = $ScUsnzone->ispCrw( $site ); }

			} else if( 'portalnet' === $type ){		// portalnet専用タグ
				$ScPortalnet = ClassRegistry::init('ScPortalnet');
				if( $mode === 'normal' ){ $aryErr[$site_name] = $ScPortalnet->ispCrw( $site ); }

			} else if( 'deadfrog' === $type ){		// deadfrog専用タグ
				$ScDeadfrog = ClassRegistry::init('ScDeadfrog');
				if( $mode === 'normal' ){ $aryErr[$site_name] = $ScDeadfrog->ispCrw( $site ); }

			} else if( '2dbook' === $type ){		// deadfrog専用タグ
				$ScU2dbook = ClassRegistry::init('ScU2dbook');
				if( $mode === 'normal' ){ $aryErr[$site_name] = $ScU2dbook->ispCrw( $site ); }
					else if( $mode === 'search' ) { $aryErr[$site_name] = $ScU2dbook->ispSrch( $site ); }	// 全件検索モード
					else if( $mode === 'search_new' ) { $aryErr[$site_name] = $ScU2dbook->ispSrch( $site, 'new' ); }	// 新規登録ワードのみ

			} else if( 'nihonomaru' === $type ){		// nihonomaru専用タグ
				$ScNihonomaru = ClassRegistry::init('ScNihonomaru');
				if( $mode === 'normal' ){ $aryErr[$site_name] = $ScNihonomaru->ispCrw( $site ); }

			} else if( 'mangalovemake' === $type ){		// mangalovemake専用タグ
				$ScMangalovemake = ClassRegistry::init('ScMangalovemake');
				if( $mode === 'normal' ){ $aryErr[$site_name] = $ScMangalovemake->ispCrw( $site ); }
					else if( $mode === 'search' ) { $aryErr[$site_name] = $ScMangalovemake->ispSrch( $site ); }	// 全件検索モード
					else if( $mode === 'search_new' ) { $aryErr[$site_name] = $ScMangalovemake->ispSrch( $site, 'new' ); }	// 新規登録ワードのみ

			}


//print_r( 'crlsit END ' . memory_get_usage() . "\n");


		}

//print_r('crawler_site End '.memory_get_usage() . "\n");

		return $aryErr;
	}





	/**
	* エラーとなったURLを再クロール
	*
	* @param
	* @return none
	*/
	public function crawler_error_site( $mode, $start ) {

		$aryErr = array();

		// プロキシListを更新
		$crlModels = ClassRegistry::init('Crlmodels');
		$crlModels->update_proxy_list();


		// クロールサイト一覧を取得
		$IllegalSite = ClassRegistry::init('IllegalSite');
//		$site = $IllegalSite->find('all', array('conditions'=>array('deleted'=>0)));
		$site = $IllegalSite->find('all', array('conditions'=>array('deleted'=>0), 'order'=>array('site_uid ASC'), 'offset'=>$start, 'limit'=>5 ));

		foreach( $site as $site ){

			$site_name = $site['IllegalSite']['site_name'];
			$type = $site['IllegalSite']['sc_type'];

			if( 'public_1' === $type ){
				$ScPublic1 = ClassRegistry::init('ScPublic1');
				if( $mode === 'search' ){ $aryErr[$site_name] = $ScPublic1->ispSrch( $site ); }	// 検索で全件クロール
					else if( $mode === 'search_new' ) { $aryErr[$site_name] = $ScPublic1->ispSrch( $site, 'new' ); }	// １時間以内に登録されたワードのみ検索クロール
					else { $aryErr[$site_name] = $ScPublic1->ispCrw( $site ); }		// 最新記事のみクロール

			} else {

			}

		}

//print_r('crawler_site End '.memory_get_usage() . "\n");

		return $aryErr;
	}




}

?>