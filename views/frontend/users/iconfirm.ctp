<?php
	$html->css(array('user_common', 'user_contact') , null, array('inline'=>false));
//	$html->script(array('jquery/jquery-1.4.2.min','frontend/words', 'jquery/plugin/jquery.confirm'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見info - お問合せ確認');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>お問合せ確認</h1>
	<div class="help">
		<a helf="#">help</a>
	</div>
</div>

<div id="main">
<div class="inner">

<p class="conf_intro">
入力内容をご確認ください。<br />
内容にお間違えがなければ、「送信する」ボタンをクリックしてください。
</p>

<?php echo $form->create( null ); ?>
<?php echo $form->textarea('UserInquiry.text', array( 'value'=>$data['UserInquiry']['text'], 'readonly'=>'readonly', 'cols'=>'70', 'rows'=>'15', 'error'=>false)); ?>

<ul>
<li><?php echo $form->submit('確認画面へ', array('class'=>'send_btn')); ?></li>
<li><a class="s04" href="#" onClick="history.back(); return false;">戻る</a></li>
</ul>
<?php echo $form->end(); ?>

<div class="space"></div>

<!-- / .inner --></div>
<!-- / #main --></div>

<div id="sub">
	<ul>
	<li><a href="/users/dashboard/">ダッシュボード</a></li>
	<li><a href="/users/inbox/">監視トレイ</a></li>
	<li><a href="/users/words/">監視ワード</a></li>
	<li><a href="/users/ilist/">監視サイト</a></li>
	<li><a href="/users/template_prof/">プロフィール</a></li>
	<li><a href="/users/profile/">アカウント情報</a></li>
	<li><a href="/users/help/">ヘルプ</a></li>
	<li class="crt"><a href="/users/contact/">お問合せ</a></li>
	<li><a href="/users/logout/">ログアウト</a></li>
	</ul>
<!-- / #sub --></div>








<!--footer start-->
<?php echo $this->element('footer_block/footer_private'); ?>
<!--footer end-->

</body>
