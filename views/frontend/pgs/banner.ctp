<?php
	$html->css(array('common', 'banner') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min','frontend/banner'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', 'リンクについて - 物見インフォ');
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
		<img class="h2gif"src="/img/h2link.gif" alt="about" />
		<div class="sp_s"></div>

		<div class="btitle">
			<h3>リンクについて</h3>
		</div>
		<p class="caption">
			物見インフォはリンクフリーです。報告の必要もありません。
		</p>

		<div class="post">
			<p class="size">サイズ： 164 x 80</p>
			<p class="img"><img src="/img/bn164x80.png" alt="著作権者の作品を守る不正サイト監視サービス物見info" title="著作権者の作品を守る不正サイト監視サービス物見info" width="164" height="80" border="0" /></p>
			<p class="textbox">
				<textarea style="width:665px;" readonly="readonly" class="source" rows="3"><a href="http://monomi.info/" target="_blank"><img src="http://monomi.info/img/bn164x80.png" alt="著作権者の作品を守る不正サイト監視サービス物見info" title="著作権者の作品を守る不正サイト監視サービス物見info" width="164" height="80" border="0" /></a></textarea>
			</p>
		</div>

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
