<?php
	$html->css(array('common', 'inquiry') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ - お問合せフォーム');
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
		<img class="h2gif" src="/img/h2contact.gif" alt="about" />
		<p class="caption">
物見インフォをご覧いただき、ありがとうございます。<br />
ご意見・ご要望等のお問合せがございましたら、下記の入力フォームへご記入いただき、<br />
「確認画面へ」ボタンをクリックしてください。<br />
<br />
・ いただきましたお問い合わせには順次回答をさせていただきます。<br />
・ 内容によりましては回答にお時間を頂く場合がございます。<br />
・ ご質問の内容によってはご返答をしかねる場合がございます。<br />
・ お客様の個人情報は厳重に取り扱い、お問い合わせへの対応および回答にのみ使用させていただきます。<br />
		</p>
		<div class="form">
			<?php echo $form->create( null ); ?>
			<p><label class="individual_element">メールアドレス</label></p>
			<p><?php echo $form->text('Inquiry.mail_address', array('class'=>'m')); ?></p>
			<p class="vali"><?php echo $form->error('mail_address'); ?></p>
			<br />
			<p><label class="individual_element">お問合せ内容</label></p>
			<p><?php echo $form->textarea('Inquiry.text', array('cols'=>'50', 'rows'=>'7', 'class'=>'keyword', 'error'=>false)); ?></p>
			<p class="vali"><?php echo $form->error('text'); ?></p>
			<br />
			<ul>
			<li><?php echo $form->submit('確認画面へ', array('class'=>'submit_btn')); ?></li>
<!--		<li><a class="s04" href="#" onClick="history.back(); return false;">戻る</a></li> -->
			</ul>
			<?php echo $form->end(); ?>
			<p class="back"><a href="/" class="top">TOPへ戻る</a></p>
		</div>
	</div>
<!-- / .inner --></div>

	<div class="sp_s"></div>

<!-- / .outer --></div>
<!-- / #main --></div>



<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
