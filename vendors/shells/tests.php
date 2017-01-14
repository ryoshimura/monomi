<?php
class TestsShell extends Shell
{
//	var $uses = array('CvSimilarImage', 'CvCountour', 'CvComp');
	var $tasks = array('Similars', 'Crawlers');

	// オーバーライドして、Welcome to CakePHP･･･のメッセージを出さないようにする。
	function startup() {}

	function main() {
	}
}
