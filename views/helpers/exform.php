<?php
/**
 * Automatic generation of HTML FORMs from given data.
 *
 * Used for scaffolding.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.helpers
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Form helper library.
 *
 * Automatic generation of HTML FORMs from given data.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.view.helpers
 * @link http://book.cakephp.org/view/1383/Form
 */
class ExformHelper extends FormHelper {

	// 日本語YMD形式の日付選択
	function dateYMD($fieldName, $selected = null, $attributes = array(), $showEmpty = true) {
		if(!isset($this->options['month'])){
			$this->options['month'] = array();
			for ($i = 1 ; $i <= 12 ; $i++) {
				$this->options['month'][sprintf("%02d", $i)] = $i;
			}
		}
		$sep = array("","","");
		if(isset($attributes['separator'])){
			if(is_array($attributes['separator'])){
				$sep = $attributes['separator'];
				$attributes['separator'] = "";
			}
		}else{
			$attributes['separator'] = "";
			$sep = array(" 年 "," 月 "," 日 ");
		}
		$ret = parent::dateTime($fieldName, 'YMD', 'NONE', $selected, $attributes, $showEmpty);

		$ret = preg_replace('|</select>|', '{/select}'.@$sep[0], $ret, 1);
		$ret = preg_replace('|</select>|', '{/select}'.@$sep[1], $ret, 1);
		$ret = preg_replace('|</select>|', '{/select}'.@$sep[2], $ret, 1);
		$ret = str_replace('{/select}', '</select>', $ret);
		return $ret;
	}
}
?>
