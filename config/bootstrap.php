<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php
 *
 * This is an application wide file to load any function that is not used within a class
 * define. You can also use this to include or require any files in your application.
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
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * App::build(array(
 *     'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'models' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'views' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'controllers' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'helpers' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 */
 App::build(array(
     'views' => array(VIEWS . 'frontend' . DS, VIEWS . 'homes' . DS),
     'controllers' => array(CONTROLLERS . 'frontend' . DS, CONTROLLERS . 'homes' . DS),
 ));

/**
 * As of 1.3, additional rules for the inflector are added below
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

 define('EMAIL_ADMIN','support@monomi.ciasol.com');
// define('EMAIL_ADMIN','support@monomi.info');
 define('CERT_KEY', 'I8pf8JYpP8Izix19E7VRxvBiV7WjhSOLap19kkxB');



if (preg_match ('|workspace|',ROOT)){ //テスト環境

	define('PAYPAL_API_SERVER', 'https://api-3t.sandbox.paypal.com/nvp');
//	define('PAYPAL_EXPRESS_CHECKOUT_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout');
//	define('PAYPAL_EXPRESS_CHECKOUT_URL', 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout');
	define('PAYPAL_EXPRESS_CHECKOUT_URL', 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=tokenValue');
	define('PAYPAL_API_USERNAME', 'seller_1351587539_biz_api1.ciasol.com');
	define('PAYPAL_API_PASSWORD', '1351587554');
	define('PAYPAL_API_SIGNATURE', 'AiPC9BjkCyDFQXbSkoZcgqH3hpacAqBJN9ouB28iaB1KjStM7HNkMLPG');
//	define('PAYPAL_API_USERNAME', 'sdk-three_api1.sdk.com');
//	define('PAYPAL_API_PASSWORD', 'QFZCWN5HZM8VBG7Q');
//	define('PAYPAL_API_SIGNATURE', 'A-IzJhZZjhg29XQ2qnhapuwxIDzyAZQ92FRP5dqBzVesOkzbdUONzmOU');
	define('HTML_CACHE_PATH', "d:\\scchace\\");

}else{

	define('PAYPAL_API_SERVER', 'https://api-3t.paypal.com/nvp');
	define('PAYPAL_EXPRESS_CHECKOUT_URL', 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout');
	define('PAYPAL_API_USERNAME', 'ryoshimura_api1.ciasol.com');
	define('PAYPAL_API_PASSWORD', 'WM27RJVUDJJUJNYW');
	define('PAYPAL_API_SIGNATURE', 'AsadXp.puXdi0rmCOTLCbLIMXsK5Ac3crRVd0mit0gisD3CHGu0Eh5oN');
	define('HTML_CACHE_PATH','/home/cake/apps/monomi/webroot/sccache/');

}
