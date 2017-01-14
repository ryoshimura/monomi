<?php
	$html->css(array('common', 'pregist') , null, array('inline'=>false));
//	$html->script(array('jquery/jquery-1.4.2.min','frontend/regist'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', 'メール確認完了');
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


		<?php if( $view_flag == 'success' || $view_flag == 'finished' ): ?>

			<img src="/img/hthanks.png" alt="メール確認が完了しました" />
			<p class="thx_intro">
			メール確認が完了しました。<br />
			<br />
			下記URLからログインすることで監視サービスがご利用いただけます。<br /><br />
			<a href="/users/login/">ログイン</a>

		<?php else: ?>

			<img src="/img/invalid.png" alt="無効なページです" />
			<p class="thx_intro">
			すでに24時間以上経過しているか存在しないアドレスです。<br />
			TOPページより再度お申込みください。

		<?php endif; ?>

			<a href="/">モノミインフォTOP</a>
			</p>
			<div class="sp_s"></div>
<!-- / .inner --></div>

<!-- / .outer --></div>
<!-- / #main --></div>



<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
