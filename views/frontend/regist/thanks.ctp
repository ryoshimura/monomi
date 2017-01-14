<?php
	$html->css(array('common', 'regist_form') , null, array('inline'=>false));
//	$html->script(array('jquery/jquery-1.4.2.min','frontend/regist'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '申込完了');
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

		<img src="/img/pregist.png" alt="新規お申込み" />

		<div class="sp_s"></div>

		<div class="bread">
			<img src="/img/cb1d.png" alt="申込み" />
			<img src="/img/cb2d.png" alt="決済情報入力" />
			<img src="/img/cb3d.png" alt="申込確認" />
			<img src="/img/cb4e.png" alt="申込完了" />
		</div>

		<p class="intro_thx">
			物見インフォにお申込みいただき、ありがとうございます。<br />
			受領メールを送信しましたので、記載内容をご確認ください。<br />
			なお、フリーメールでのご登録の場合、迷惑フォルダに届くことがありますのでご注意ください。<br />
			<br />
			今すぐ物見インフォをご利用いただく場合は、<a href="/users/login/">こちら</a>よりログインしてください。
		</p>
		<p class="link_thx"><a href="/users/login/">今すぐログインする</a></p>

		<div class="sp_s"></div>
<!-- / .inner --></div>

<!-- / .outer --></div>
<!-- / #main --></div>



<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
