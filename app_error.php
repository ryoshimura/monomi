<?php
class AppError extends ErrorHandler {

//echo '';
//var $layout     = '../error/error';

//	function error404() {
//	function error() {
//		$layout     = 'base_layout';
		//$this->controller->layout = 'error';
		//echo('aaa');
//		$this->controller->layout = 'error';
//		header("HTTP/1.1 301 Moved Permanently");
//		header("Location: http://www.golf-agora.com/notfound.html");

//		$this->redirect('http://www.golf-agora.com/notfound.html');
//	}

	function error404($params) {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: http://monomi.info/notfound.html");

//		$this->controller->layout = "error404";
//		parent::error404($params);
	}


}

?>