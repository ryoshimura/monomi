<?php
App::import('Core', 'HttpSocket');

class SimilarsTask extends Shell
{
	var $uses = array('CvSimilarImage', 'CvCountour', 'Crawler', 'Transaction');

	// オーバーライドして、Welcome to CakePHP･･･のメッセージを出さないようにする。
	function startup() {}

	function execute() {
//		$url = 'http://bantyou.livedoor.biz/';
//		$this->Crawler->crawl($url);
//
//		for ($i=2; $i<50; $i++) {
//			$url = 'http://bantyou.livedoor.biz/?p='.strval($i);
//			$this->Crawler->crawl($url);
//			sleep(1);
//		}
//
//		$options = array();
//		$options['conditions'] = array('Crawler.status' =>null);
//		$options['fields']     = array('Crawler.uuid', 'Crawler.image_url', 'Crawler.image_file_path', 'Crawler.status');
//
//		$crawler_list = $this->Crawler->find('all', $options);
//
//		foreach ($crawler_list as $crawler) {
//			$this->Crawler->getImageFile($crawler['Crawler']);
//			$this->Crawler->save($crawler);
//			sleep( mt_rand(1, 3) );
//		}
//exit;

		$input_picture_A = "/home/ciasol/cake/apps/acurs/webroot/img/hist_img/007.jpg";
		$input_picture_B =
			array(
				 "main.jpg"
				,"compressed_25.jpg"
				,"compressed_50.jpg"
				,"compressed_75.jpg"
				,"contrast_m50.jpg"
				,"contrast_p50.jpg"
				,"lightness_m50.jpg"
				,"lightness_p50.jpg"
				,"mono_24bit.jpg"
				,"nega_24bit.jpg"
				,"reducedcolors_1bit_median.jpg"
				,"reducedcolors_4bit_median.jpg"
				,"reducedcolors_8bit_median.jpg"
				,"reducedcolors_16bit_median.jpg"
				,"resize_3D_25.jpg"
				,"resize_3D_50.jpg"
				,"resize_3D_75.jpg"
				,"resize_avg_50.jpg"
				,"resize_easysmall_50.jpg"
				,"overwhite_l70-r70.jpg"
				,"overwhite_t52-b52.jpg"
				,"trim_l70-r70.jpg"
				,"trim_l115-r115.jpg"
				,"trim_t52-b52.jpg"
				,"trim_t70-b70.jpg"
				,"trim_l115-r115_t70-b70.jpg"
			);
		$options = array();
		$options['conditions'] = array('Crawler.status' =>200);
		$options['fields']     = array('Crawler.uuid', 'Crawler.image_url', 'Crawler.image_file_path', 'Crawler.status');

		$crawler_list = $this->Crawler->find('all', $options);

		foreach ($crawler_list as $crawler) {
			$this->similar($input_picture_A, $crawler['Crawler']['image_file_path']);
			//$this->in("Enterを押してください", null, null);
		}
	}

	function similar($image_A, $image_B) {
		//$main_path = "/home/ciasol/cake/apps/acurs/webroot/img/hist_img/";
		//$hist_path = "/home/ciasol/cake/apps/acurs/webroot/img/hist/";

		// 処理を記述
		$start_time = $this->microtime_float(); // 計測開始
		//$this->out('処理を開始:'.date("Y/m/d H:i:s", $start_time));

		//$cascade = "/usr/share/opencv/haarcascades/haarcascade_frontalface_alt.xml";
		//$cascade = "/usr/local/share/opencv/haarcascades/haarcascade_frontalface_alt.xml";

		//$this->database();
		$result = similar_image($image_A, $image_B);
//print_r($result);

		$end_time = $this->microtime_float(); // 計測終了
		$this->save_similar_evaluation($image_A, $image_B, $result, ($end_time - $start_time));
		//$this->out('処理を終了:'.date("Y/m/d H:i:s", $end_time).' 経過時間:'.($end_time - $start_time));

	}

	function microtime_float() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	function save_similar_evaluation($image_file_path_A, $image_file_path_B, $similar_evaluation, $elapse_time) {

		$this->Transaction->begin();

		$data = array();
		$data['CvSimilarImage']   = array();
		$data['CvCountour']       = array();
		$data['CvComp']           = array();

		$data['CvSimilarImage']['image_file_path_A'] = $image_file_path_A;
		$data['CvSimilarImage']['image_file_path_B'] = $image_file_path_B;
		$data['CvSimilarImage']['elapse_time']       = $elapse_time;
		$this->CvSimilarImage->create();
		$this->CvSimilarImage->save( $data );

		$data['CvCountour']['image_uid'] = $this->CvSimilarImage->id;
		$data['CvCountour']['contours_match_i1']      = $similar_evaluation[0];
		$data['CvCountour']['contours_match_i2']      = $similar_evaluation[1];
		$data['CvCountour']['contours_match_i3']      = $similar_evaluation[2];

		$data['CvCountour']['bgr_b_comp_correl']         = $similar_evaluation[3];
		$data['CvCountour']['bgr_b_comp_chisqr']         = $similar_evaluation[4];
		$data['CvCountour']['bgr_b_comp_intersect']      = $similar_evaluation[5];
		$data['CvCountour']['bgr_b_comp_bhattacharyya']  = $similar_evaluation[6];
		$data['CvCountour']['bgr_b_calc_emd_l']          = $similar_evaluation[7];

		$data['CvCountour']['bgr_g_comp_correl']         = $similar_evaluation[8];
		$data['CvCountour']['bgr_g_comp_chisqr']         = $similar_evaluation[9];
		$data['CvCountour']['bgr_g_comp_intersect']      = $similar_evaluation[10];
		$data['CvCountour']['bgr_g_comp_bhattacharyya']  = $similar_evaluation[11];
		$data['CvCountour']['bgr_g_calc_emd_l']          = $similar_evaluation[12];

		$data['CvCountour']['bgr_r_comp_correl']         = $similar_evaluation[13];
		$data['CvCountour']['bgr_r_comp_chisqr']         = $similar_evaluation[14];
		$data['CvCountour']['bgr_r_comp_intersect']      = $similar_evaluation[15];
		$data['CvCountour']['bgr_r_comp_bhattacharyya']  = $similar_evaluation[16];
		$data['CvCountour']['bgr_r_calc_emd_l']          = $similar_evaluation[17];


		$data['CvCountour']['hsv_h_comp_correl']         = $similar_evaluation[18];
		$data['CvCountour']['hsv_h_comp_chisqr']         = $similar_evaluation[19];
		$data['CvCountour']['hsv_h_comp_intersect']      = $similar_evaluation[20];
		$data['CvCountour']['hsv_h_comp_bhattacharyya']  = $similar_evaluation[21];
		$data['CvCountour']['hsv_h_calc_emd_l']          = $similar_evaluation[22];

		$data['CvCountour']['hsv_s_comp_correl']         = $similar_evaluation[23];
		$data['CvCountour']['hsv_s_comp_chisqr']         = $similar_evaluation[24];
		$data['CvCountour']['hsv_s_comp_intersect']      = $similar_evaluation[25];
		$data['CvCountour']['hsv_s_comp_bhattacharyya']  = $similar_evaluation[26];
		$data['CvCountour']['hsv_s_calc_emd_l']          = $similar_evaluation[27];

		$data['CvCountour']['hsv_v_comp_correl']         = $similar_evaluation[28];
		$data['CvCountour']['hsv_v_comp_chisqr']         = $similar_evaluation[29];
		$data['CvCountour']['hsv_v_comp_intersect']      = $similar_evaluation[30];
		$data['CvCountour']['hsv_v_comp_bhattacharyya']  = $similar_evaluation[31];
		$data['CvCountour']['hsv_v_calc_emd_l']          = $similar_evaluation[32];


		$data['CvCountour']['hls_h_comp_correl']         = $similar_evaluation[33];
		$data['CvCountour']['hls_h_comp_chisqr']         = $similar_evaluation[34];
		$data['CvCountour']['hls_h_comp_intersect']      = $similar_evaluation[35];
		$data['CvCountour']['hls_h_comp_bhattacharyya']  = $similar_evaluation[36];
		$data['CvCountour']['hls_h_calc_emd_l']          = $similar_evaluation[37];

		$data['CvCountour']['hls_l_comp_correl']         = $similar_evaluation[38];
		$data['CvCountour']['hls_l_comp_chisqr']         = $similar_evaluation[39];
		$data['CvCountour']['hls_l_comp_intersect']      = $similar_evaluation[40];
		$data['CvCountour']['hls_l_comp_bhattacharyya']  = $similar_evaluation[41];
		$data['CvCountour']['hls_l_calc_emd_l']          = $similar_evaluation[42];

		$data['CvCountour']['hls_s_comp_correl']         = $similar_evaluation[43];
		$data['CvCountour']['hls_s_comp_chisqr']         = $similar_evaluation[44];
		$data['CvCountour']['hls_s_comp_intersect']      = $similar_evaluation[45];
		$data['CvCountour']['hls_s_comp_bhattacharyya']  = $similar_evaluation[46];
		$data['CvCountour']['hls_s_calc_emd_l']          = $similar_evaluation[47];

		$this->CvCountour->create();
		$this->CvCountour->save( $data );

		$this->Transaction->commit();

	}

}
