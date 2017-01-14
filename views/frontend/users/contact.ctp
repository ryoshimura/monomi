<?php
	$html->css(array('user_common', 'user_contact') , null, array('inline'=>false));
//	$html->script(array('jquery/jquery-1.4.2.min','frontend/words', 'jquery/plugin/jquery.confirm'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ-お問合せ-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>お問合せ</h1>
	<div class="help">
		<a href="/users/help/#num12">help</a>
	</div>
</div>

<div id="main">
<div class="inner">

<p class="intro">
物見インフォへご意見・ご質問などがございましたら、下記の入力フォームよりお問合せください。
</p>
<p class="caption">
・ いただきましたお問い合わせには順次回答をさせていただきます。内容によりましては回答にお時間を頂く場合がございます。<br />
・ ご質問の内容によってはご返答をしかねる場合がございます。あらかじめご了承ください。<br />
</p>

<?php echo $form->create( null ); ?>
<div class="textarea_caption">2,000文字以内</div>
<?php echo $form->textarea('UserInquiry.text', array('cols'=>'70', 'rows'=>'15', 'error'=>false)); ?>
<div class="feed"><?php echo $form->error('UserInquiry.text'); ?></div>
<ul>
<li><?php echo $form->submit('確認画面へ', array('class'=>'submit_btn')); ?></li>
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
