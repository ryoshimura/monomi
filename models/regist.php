<?php

class Regist extends AppModel {
	var $name = 'Regist';
	var $useTable = false;

    var $_schema = array(
        'client_name'        =>array('type'=>'string', 'length'=>200),
        'mail_address'        =>array('type'=>'string', 'length'=>255),
        'mail_address_confirm'        =>array('type'=>'string', 'length'=>255),
    	'text'    =>array('type'=>'text')
    );

	public $validate = array(
			    'passwd' => array(
					array(
						'rule' => array('between', 4, 200),
						'required'	=> true,
						'message' => 'パスワードは4文字以上の半角英数字で入力してください'
					),
					array(
						'rule' => 'alphaNumeric',
						'required'	=> true,
						'message' => 'パスワードは4文字以上の半角英数字を入力してください'
					)
				),
			    'passwd_confirm' => array(
					'rule' => array('checkCompare'),
					'required'	=> true,
					'message' => '同じパスワードを入力してくだい'
				),
				'mail_address' => array(
					array(
						'rule' => array('email', false),
						'required'	=> true,
						'message' => '正しいメールアドレスを入力してくだい'
					)
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