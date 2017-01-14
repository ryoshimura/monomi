<?php

class Inquiry extends AppModel {
	var $name = 'Inquiry';
	var $useTable = false;

    var $_schema = array(
        'client_name'        =>array('type'=>'string', 'length'=>200),
        'mail_address'        =>array('type'=>'string', 'length'=>255),
        'mail_address_confirm'        =>array('type'=>'string', 'length'=>255),
    	'text'    =>array('type'=>'text')
    );

	public $validate = array(
				'client_name' => array(
//					'rule' => array('between', 1, 200),
					'rule' => 'notEmpty',
					'required'	=> true,
					'message' => '必須入力項目です'
				),
			    'text' => array(
					'rule' => array('between', 1, 2000),
					'required'	=> true,
					'message' => '2000文字以内でお問合せ内容を入力してください'
				),
			    'mail_address' => array(
					'rule' => array('email', false),
					'required'	=> true,
					'message' => '正しいメールアドレスを入力してくだい'
				),
			    'mail_address_confirm' => array(
					'rule' => array('checkCompare'),
					'required'	=> true,
					'message' => '同じメールアドレスを入力してくだい'
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