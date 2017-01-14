<?php
	$html->css(array('user_common', 'user_home') , null, array('inline'=>false));
	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->



<div id="main_block">
<h1>ホーム</h1>

<?php echo $this->element('side/user_menu'); ?>

<div id="right_block">
	<h2>監視情報</h2>
	<h2>ご契約情報</h2>
</div><!--#right_block  -->

</div><!--#main_block  -->

















<!--footer start-->
<?php echo $this->element('footer_block/footer_private'); ?>
<!--footer end-->

</body>
