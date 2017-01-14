<?php
	$html->css(array('common', 'pregist') , null, array('inline'=>false));
//	$html->script(array('jquery/jquery-1.4.2.min','frontend/regist'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '体験版お申込み');
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

		<img src="/img/ptrial.png" alt="体験版お申込み" />
		<p class="intro">
			物見インフォ監視サービスを体験することができます。<br />
		</p>
		<div class="caution">
			注意事項：<br />
			・監視したい作品の名称及びサークル・ブランド名が、半角英数で2文字以内もしくは全角1文字の場合、<br />
			　検出精度が著しく低下することから監視ワードとしてご登録いただけません。<br />
			・物見インフォは、ご自身の著作物を監視する目的でのみご利用いただけます。<br />
			　上記以外の目的で使われた場合、別途、著作権保有者確認をさせていただくことがあります。<br />
			・フリーメールでご登録の場合、物見インフォからのお知らせメールが迷惑フォルダに届くことがあります。<br />
		</div>

		<div class="left">
			<p class="caption">
				・ ご利用期間 7日 1ワード<br />
				・ 監視結果の表示制限あり<br />
			</p>
		</div>

		<div class="right">
			<?php echo $form->create(null); ?>
			<p class="text">
				<?php echo $form->input('User.mail_address', array('type'=>'text', 'label'=>'メールアドレス', 'class'=>'text', 'error'=>false)); ?>
				<?php if( $email === 'isnotUniq' ): ?>
					<div class="vali">このメールアドレスは既に使われているため、登録できません</div>
				<?php endif; ?>
			</p>
			<div class="sp_s"></div>

			<p class="text">
				<label for="UserPasswd" class="pass">パスワード<br /><span>半角英数字4文字以上</span></label>
				<?php echo $form->input('User.passwd', array('type'=>'password', 'class'=>'text', 'label'=>false, 'error'=>false)); ?>
				<div class="vali"><?php echo $form->error('Regist.passwd'); ?></div>
			</p>
			<p class="text">
				<label for="UserPasswdConfirm">パスワード（確認）</label>
				<?php echo $form->input('User.passwd_confirm', array('type'=>'password', 'class'=>'text', 'label'=>false, 'error'=>false)); ?>
				<div class="vali"><?php echo $form->error('Regist.passwd_confirm'); ?></div>
			</p>
			<p class="rule">
				<?php echo $form->checkbox('User.agreement', array( 'class'=>'checkbox', 'error'=>false)); ?>
				<label class="rule" for="UserAgreement"><a href="/pgs/terms_of_service/" target="_blank">利用規約</a>に同意する</label>
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
