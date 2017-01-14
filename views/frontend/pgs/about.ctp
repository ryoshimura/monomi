<?php
	$html->css(array('common', 'about') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '不正アップロードの現状 - 物見インフォ');
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
		<img class="h2gif"src="/img/h2about.gif" alt="about" />
		<div class="sp_s"></div>

		<div id="upload"></div>
		<div class="hwtitle">
			<h3>不正アップロードの現状</h3>
		</div>
		<p class="caption">
			不正アップロードの現状と物見インフォがカバーする監視範囲はこちらとなります。
		</p>
		<img class="about" src="/img/about01.png" alt="不正アップロードと監視範囲" width="820" height="469" />
		<div class="sp_s"></div>
		<p class="toplink">
			<a href="/" class="top">TOPへ戻る</a>
		</p>
		<div class="sp_l"></div>



		<div id="delete"></div>
		<div class="hwtitle">
			<h3>削除申請について</h3>
		</div>
		<p class="caption">
			削除申請アプローチと物見インフォがサポートする範囲はこちらとなります。
		</p>
		<img class="about" src="/img/about02.png" alt="削除申請アプローチ" width="820" height="469" />

		<div class="sp_s"></div>
		<p class="toplink">
			<a href="/" class="top">TOPへ戻る</a>
		</p>
		<div class="sp_l"></div>

	</div>
<!-- / .inner --></div>

	<div class="sp_s"></div>

<!-- / .outer --></div>
<!-- / #main --></div>



<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
