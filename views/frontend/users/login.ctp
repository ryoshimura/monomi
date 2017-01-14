<?php
	$html->css(array('common', 'login') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ-ログイン-');
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

		<div class="logo"><h2><img src="/img/h2login.png" alt="ログイン" /></h2></div>
		<?php echo $form->create( 'User', array( 'controller'=>'users', 'action'=>'login' )); ?>
			<p class="text">
				<?php echo $form->input('mail_address', array('type'=>'text', 'label'=>'メールアドレス', 'class'=>'text', 'error'=>false, 'value'=>'')); ?>
			</p>
			<p class="text">
				<?php echo $form->input('passwd', array('type'=>'password', 'label'=>'パスワード', 'class'=>'text', 'error'=>false)); ?>
			</p>

			<div class="vali">
				<?php
					if( $session->check('Message.auth') ){
						echo $session->flash('auth');
					}
				?>
			</div>

			<p class="btn">
				<?php echo $form->submit('ログインする', array('class'=>'submit')); ?>
			</p>
			<p class="caption">
				<a href="/users/rms/">パスワードを忘れた方はこちら</a>
			</p>
		<?php echo $form->end(); ?>
<!-- / .inner --></div>

<!-- / .outer --></div>
<!-- / #main --></div>






<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
