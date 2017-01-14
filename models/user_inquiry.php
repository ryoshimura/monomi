<?php

class UserInquiry extends AppModel {
	var $name = 'UserInquiry';
	var $useTable = false;

/*
    var $_schema = array(
        'client_name'        =>array('type'=>'string', 'length'=>200),
        'mail_address'        =>array('type'=>'string', 'length'=>255),
        'mail_address_confirm'        =>array('type'=>'string', 'length'=>255),
    	'text'    =>array('type'=>'text')
    );
*/

	public $validate = array(
			    'text' => array(
					'rule' => array('between', 1, 2000),
					'required'	=> true,
					'message' => '2,000文字以内でお問合せ内容を入力してください'
				)
			);
/*
	public function __construct() {
		$id = array("id" => false,
					"table" => "users",
				   );
		parent::__construct($id);
		$this->primaryKey = "user_uid";
	}

	public function beforeFind(&$queryData) {

		return true;
	}

	public function afterFind($results) {

		return $results;
	}
*/

	/*
	 * 2010.12.29 add
	 * 確認入力用バリデーションメソッド
	 */
	function checkCompare($field) {
		foreach( $field as $key => $value ){
			if (preg_match('/^(.+)_confirm$/', $key, $regs)) {
				return $this->data[$this->name][$regs[1]] == $this->data[$this->name][$key];
		    }
		}
	}

}

?>