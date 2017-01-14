<?php
	$html->css(array('common', 'index') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_public'); ?>
<!--header end-->





		<?php echo $form->create( 'Admin', array( 'controller'=>'admins', 'action'=>'add' )); ?>
			<p class="text">
				<?php echo $form->input('uuid', array('type'=>'text', 'label'=>'メールアドレス', 'class'=>'text', 'error'=>false, 'value'=>'')); ?>
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
		<?php echo $form->end(); ?>



<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
