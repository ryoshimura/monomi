<?php
	$html->css(array('common', 'regist_form') , null, array('inline'=>false));
//	$html->script(array('jquery/jquery-1.4.2.min','frontend/registreturn'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', 'お申込みキャンセル');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_public'); ?>
<!--header end-->


<div id="main">
<div class="outer">
<div class="navi">
	<ul>
	<li><a href="/">TOP</a></li>
	</ul>
<!-- / .navi --></div>

<div class="inner">

		<img src="/img/pregist.png" alt="新規お申込み" />

		<p class="intro">
			Paypalからキャンセルの通知を受けました。<br />
			お申込みを中止します。
		</p>
		<p class="link_err">
			<a href="/regist/form/">申込画面へ戻る</a><br />
			<a href="/">TOPへ戻る</a>
		</p>

		<div class="sp_s"></div>
<!-- / .inner --></div>

<!-- / .outer --></div>
<!-- / #main --></div>



<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
