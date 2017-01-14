<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @subpackage    cake.cake.libs.controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * This is a placeholder class.
 * Create the same file in app/app_controller.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @link http://book.cakephp.org/view/957/The-App-Controller
 */
class AppController extends Controller {
//	var $components = array( 'Auth', 'Cookie', 'RequestHandler');
//	var $components = array('DebugKit.Toolbar', 'Auth', 'Cookie', 'RequestHandler');
	var $components = array('DebugKit.Toolbar', 'Cookie', 'RequestHandler');

	/**
	* action,render前実行関数
	*
	* @param  none
	* @return none
	*/
	public function beforeFilter() {

	}

//	protected function redirectHome($params, $controller, $action) {
//		if ( !is_null($params) && is_array($params) ) {
//			if ( strcasecmp($params['controller'], $controller) || strcasecmp($params['action'], $action) ) {
//				if ( is_null($this->referer()) ) {
//					//$this->redirect(array('controller' => 'homes', 'action' => 'index'));
//					$this->redirect('/homes/');
//				} else {
//					$this->redirect($this->referer());
//				}
//			}
//		}
//	}

}
