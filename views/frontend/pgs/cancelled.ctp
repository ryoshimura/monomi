<?php
	$html->css(array('common', 'policy') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ - 解約完了');
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
	<li class="right"><a href="/users/login/">ログイン</a></li>
	<li><a href="/">TOP</a></li>
	</ul>
<!-- / .navi --></div>

<div class="inner">
	<div class="intro">
		<img class="h2gif"src="/img/cancelled.gif" alt="about" />
		<p>
			解約が完了しました。<br />
			物見インフォをご利用いただき、ありがとうございました。
		<p>
			<a href="/" class="top">TOPへ戻る</a>
		</p>
	</div>
<!-- / .inner --></div>

	<div class="sp_s"></div>

<!-- / .outer --></div>
<!-- / #main --></div>



<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
