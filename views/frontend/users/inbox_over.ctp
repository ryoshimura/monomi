<?php
	$html->css(array('user_common', 'user_inbox', 'jquery.template.css', 'jquery.confirm.css') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min', 'frontend/inbox', 'frontend/template_mail', 'jquery/plugin/jquery.confirm', 'jquery/plugin/jquery.zclip'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ-監視トレイ-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>監視トレイ</h1>
	<div class="help">
		<a href="/users/help/#num4">help</a>
	</div>
</div>

<div id="main">
<div class="inner">



<div class="msg_block">
	<P>
		不正サイトの検出結果が上限（10,000件）を超えました。<br />
		誤検出が多く含まれておりますので、大変恐れ入りますが、監視ワードを見直してください。<br />
		<br />
		監視ワードの文字数が少なすぎると大量の誤検出が発生いたします。<br />
		なるべく固有かつ一定の文字数以上を監視ワードとして設定お願いいたします。<br />
		<br />
		<a href="/users/words/">監視ワードの設定はこちら</a>
	</p>
</div>



<!-- / .inner --></div>
<!-- / #main --></div>

<div id="sub">
	<ul>
	<li><a href="/users/dashboard/">ダッシュボード</a></li>
	<li class="crt"><a href="/users/inbox/">監視トレイ</a></li>
	<li><a href="/users/words/">監視ワード</a></li>
	<li><a href="/users/ilist/">監視サイト</a></li>
	<li><a href="/users/template_prof/">プロフィール</a></li>
	<li><a href="/users/profile/">アカウント情報</a></li>
	<li><a href="/users/help/">ヘルプ</a></li>
	<li><a href="/users/contact/">お問合せ</a></li>
	<li><a href="/users/logout/">ログアウト</a></li>
	</ul>
<!-- / #sub --></div>











<!--footer start-->
<?php echo $this->element('footer_block/footer_private'); ?>
<!--footer end-->

</body>
