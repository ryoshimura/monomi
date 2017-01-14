<?php
	$html->css(array('common', 'rms') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ-パスワード再発行-');
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

		<div class="logo"><h2><img src="/img/h2qpw.png" alt="パスワード忘れ" /></h2></div>

		<?php if( $view_flag === 'send_mail' ): ?>
			<p class="intro">
			新しいパスワードをメールしました。<br />
			※フリーメールの場合、迷惑メールフォルダに届く場合があります。
			</p>
			<p>
				<a href="/">TOPへ戻る</a>
			</p>
		<?php else: ?>
			<p class="intro">
			新しいパスワードをメールで発行します。<br />
			物見インフォに登録されているメールアドレスを入力してください。<br />
			</p>
			<?php echo $form->create( null ); ?>
				<p class="text">
					<?php echo $form->input('mail_address', array('type'=>'text', 'label'=>'メールアドレス', 'class'=>'text', 'error'=>false, 'value'=>'')); ?>
				</p>
				<div class="vali">
					<?php if( $view_flag === 'none_mail' ): ?>
						登録されているメールアドレスを入力してください。
					<?php endif; ?>
				</div>
				<p class="btn">
					<?php echo $form->submit('再発行', array('class'=>'submit')); ?>
				</p>
			<?php echo $form->end(); ?>
		<?php endif; ?>


<!-- / .inner --></div>

<!-- / .outer --></div>
<!-- / #main --></div>






<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
