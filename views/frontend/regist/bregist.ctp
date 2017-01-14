<?php
	$html->css(array('common', 'pregist') , null, array('inline'=>false));
//	$html->script(array('jquery/jquery-1.4.2.min','frontend/regist'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', 'ベータテスト版お申込み');
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

		<img src="/img/pbeta.png" alt="ベータテスト版お申込み" />
		<p class="intro">
			モノミインフォ監視サービスのベータテスト申込みページです。<br />
			正式版と同程度の機能をご利用いただくことができます。（事前に通知することなく機能が制限されたりメンテナンスで停止することがあります）<br />
			ご意見・ご要望も広く募っておりますので、お気付きの点がございましたらログイン後のページよりご連絡ください。
		</p>

		<div class="left">
			<p class="caption">
				・ご利用期間<br />
				　本サービス開始まで<br />
				・監視２ワード<br />
				・無料
			</p>
		</div>

		<div class="right">
			<?php echo $form->create(null); ?>
			<p class="text">
				<?php echo $form->input('User.mail_address', array('type'=>'text', 'label'=>'メールアドレス', 'class'=>'text', 'error'=>false)); ?>
				<div class="vali"><?php echo $form->error('User.mail_address'); ?></div>
			</p>
			<p class="text">
				<label for="UserPasswd" class="pass">パスワード<br /><span>半角英数字4文字以上</span></label>
				<?php echo $form->input('User.passwd', array('type'=>'password', 'class'=>'text', 'label'=>false, 'error'=>false)); ?>
				<div class="vali"><?php echo $form->error('User.passwd'); ?></div>
			</p>
			<p class="text">
				<label for="UserPasswdConfirm">パスワード（確認）</label>
				<?php echo $form->input('User.passwd_confirm', array('type'=>'password', 'class'=>'text', 'label'=>false, 'error'=>false)); ?>
				<div class="vali"><?php echo $form->error('User.passwd_confirm'); ?></div>
			</p>
			<p class="rule">
				<?php echo $form->checkbox('User.agreement', array( 'class'=>'checkbox', 'error'=>false)); ?>
				<label class="rule" for="UserAgreement"><a href="/homes/terms_of_service/" target="_blank">利用規約</a>に同意する</label>
				<?php if( $agreement === 'notAgreement' ): ?>
					<div class="vali">お申込みには利用規約に同意いただく必要があります</div>
				<?php endif; ?>
			</p>
			<p class="btn">
				<?php echo $form->submit('仮登録する', array('class'=>'submit')); ?>
			</p>
			<?php echo $form->end(); ?>
		</div>
		<div class="sp_s"></div>
<!-- / .inner --></div>

<!-- / .outer --></div>
<!-- / #main --></div>



<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
