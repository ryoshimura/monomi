<?php

class Product extends AppModel {
	var $name = 'Product';



	public function __construct() {
		$id = array("id" => false,
					"table" => "products",
				   );
		parent::__construct($id);
		$this->primaryKey = "product_uid";
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
	* 商品情報取得
	*
	* @param
	* @return
	*/
	public function getProductList( $genre ) {

		if( $genre === 'public' ){
			$product = $this->find('all', array('conditions'=>array('deleted'=>0, 'OR'=>array(array('genre'=>'normal'),array('genre'=>'campaign')) )));
		} else {
			$product = $this->find('all', array('conditions'=>array('deleted'=>0, 'genre'=>$genre)));
		}


		$data = array();
		foreach( $product as $val ){
			$key = $val['Product']['product_uid'];
			$data[$key] = $val['Product']['view_product_name'];
		}


		return $data;
	}







}

?>