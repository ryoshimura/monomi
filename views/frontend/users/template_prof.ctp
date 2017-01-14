<?php
	$html->css(array('user_common', 'user_temp_prof') , null, array('inline'=>false));
//	$html->script(array('jquery/jquery-1.4.2.min', 'frontend/temp_prof'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ-アカウント情報-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>プロフィール</h1>
	<div class="help">
		<a href="/users/help/#num9">help</a>
	</div>
</div>

<div id="main">
<div class="inner">

<div class="inner_prof">
	<p class="notice">
		このプロフィール情報は、不正サイトへの削除要請テンプレートで使用します。<br />
		入力は任意です。<br />
		※ 一部項目だけで削除申請が通ったという報告もあることから、必ずしも全項目入力する必要はありません。
	</p>
	<div class="form_block">
		<?php echo $form->create( null ); ?>
		<p><label class="lb">サークル・ブランドのURL</label><?php echo $form->text('UserProfile.creator_url', array('class'=>'textbox', 'error'=>false)); ?></p>
		<p><label class="lb">サークル・ブランド名（日本語）</label><?php echo $form->text('UserProfile.creator_name_jp', array('class'=>'textbox', 'error'=>false)); ?></p>
		<p><label class="lb">サークル・ブランド名（英語）</label><?php echo $form->text('UserProfile.creator_name_en', array('class'=>'textbox', 'error'=>false)); ?></p>
		<p><label class="lb">サークル・ブランド代表者名（日本語）</label><?php echo $form->text('UserProfile.representative_jp', array('class'=>'textbox', 'error'=>false)); ?></p>
		<p><label class="lb">サークル・ブランド代表者名（英語）</label><?php echo $form->text('UserProfile.representative_en', array('class'=>'textbox', 'error'=>false)); ?></p>
		<p>
			<label class="lb">サークル・ブランドのメールアドレス</label><?php echo $form->text('UserProfile.creator_mail_address', array('class'=>'textbox', 'error'=>false)); ?>
		</p>
		<p class="btn">
			<input type="submit" class="submit_btn" />
			<?php if( $post_flag === true ): ?>
				<span class="btn_msg">登録しました</span>
			<?php endif; ?>
		</p>
		<?php echo $form->end(); ?>
	</div>

</div><!-- / .inner_prof -->
<div class="space"></div>

<!-- / .inner --></div>
<!-- / #main --></div>

<div id="sub">
	<ul>
	<li><a href="/users/dashboard/">ダッシュボード</a></li>
	<li><a href="/users/inbox/">監視トレイ</a></li>
	<li><a href="/users/words/">監視ワード</a></li>
	<li><a href="/users/ilist/">監視サイト</a></li>
	<li class="crt"><a href="/users/template_prof/">プロフィール</a></li>
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
