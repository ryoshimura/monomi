<?php
	$html->css(array('user_common', 'user_profile') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
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
	<h1>アカウント情報</h1>
	<div class="help">
		<a href="/users/help/#num10">help</a>
	</div>
</div>

<div id="main">
<div class="inner">

<div class="inner_agreement">
	<h2>ご契約状況</h2>
	<hr />
		<p><ul><li class="label">プラン</li><li><span class="red">
			<?php
				if( '0' === $user['User']['payment_status'] ){
					echo '監視サービス停止';
				} else if( '1' === $user['User']['payment_status'] ){
					echo 'スタンダード監視プラン';
				} else if( '2' === $user['User']['payment_status'] ){
					echo '体験版';
				} else if( '9' === $user['User']['payment_status'] ){
					echo 'ベータテスト版';
				}
			?>
		</span></li></ul></p>

		<p><ul><li class="label">有効期間</li><li>
			<?php if( $user['User']['payment_status'] === '9' && $user['User']['period_status'] == 1 ): ?>
				<span class="red">正式サービス開始まで</span>
			<?php elseif( $user['User']['period_status'] == 0 ): ?>
				<span class="red">停止中</span>
			<?php else: ?>
				<span class="red">～ <?php echo date( "Y/m/d", strtotime( $user['User']['current_period'] )); ?></span>
			<?php endif; ?>
		</li></ul></p>

		<p><ul><li class="label">監視ワード数</li>
			<?php if( $user['User']['payment_status'] === '0' ): ?>
				<li><span class="red">-</span></li>
			<?php else: ?>
				<li><span class="red"><?php echo $user['User']['current_volume']; ?></span></li>
			<?php endif; ?>
		</ul></p>


	<?php if( $user['User']['payment_status']==1 && $user['User']['period_status']==0 ): ?>
		<p><a class="new_regist" href="/users/resign/">申込</a><div class="btn_caption">スタンダード監視プランのお申込みはこちら</div></p>
	<?php elseif( $user['User']['payment_status'] == 9 ): ?>
		<p><a class="new_regist" href="/users/resign/">申込</a><div class="btn_caption">スタンダード監視プランのお申込みはこちら</div></p>
	<?php elseif( $user['User']['payment_status'] == 2 ): ?>
		<p><a class="new_regist" href="/regist/form/">申込</a><div class="btn_caption">スタンダード監視プランのお申込みはこちら</div></p>
	<?php endif; ?>
</div>

<div class="space"></div>


<?php if( ($user['User']['payment_status'] == 1 ||  $user['User']['payment_status'] == 9) && $user['User']['campaign_code'] != null ): ?>
<div class="inner_campaign">
	<h2>招待キャンペーンコード</h2>
	<hr />
	<p class="notice">
		著作権侵害でお困りの方がいらっしゃいましたら、ぜひ物見infoへご招待ください。<br />
		<a href="/users/campaign/">招待キャンペーンとは？</a>
	</p>
	<ul>
		<li class="label">招待キャンペーンコード</li>
		<li class="code"><?php echo $user['User']['campaign_code']; ?></li>
	</ul>
</div>
<div class="space"></div>
<?php endif; ?>


<?php if( $user['User']['payment_status'] != 2 &&  $user['User']['payment_status'] != 3 ): ?>
<div class="inner_prof">
	<h2>メールアドレス及びパスワードの変更</h2>
	<hr />
	<p class="cml">現在のメールアドレス　 ：　　<?php echo $mail_address; ?></p>
	<?php echo $form->create( 'User', array( 'controller'=>'users', 'action'=>'profile' )); ?>
	<div class="form"><label>新しいメールアドレス</label><?php echo $form->text('User.mail_address', array('class'=>'textbox', 'error'=>false, 'value'=>'')); ?>
		<div class="vmsg"><?php if(isset($vali['mail_address'])){echo $vali['mail_address'];} ?></div></div>
	<div class="form"><label>新しいパスワード</label><?php echo $form->password('User.passwd', array('class'=>'textbox', 'error'=>false, 'value'=>'')); ?>
		<div class="vmsg"><?php if(isset($vali['passwd'])){echo $vali['passwd'];} ?></div></div>
	<div class="form"><label>新しいパスワード（確認）</label><?php echo $form->password('User.passwd_confirm', array('class'=>'textbox', 'error'=>false, 'value'=>'')); ?>
		<div class="vmsg"><?php if(isset($vali['passwd_confirm'])){echo $vali['passwd_confirm'];} ?></div></div>
	<input type="submit" class="submit_btn" />
	<div class="msg"><?php if( isset($msg) ){ echo $msg; } ?></div>
	<?php echo $form->end(); ?>
</div>
<?php endif; ?>

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
	<li class="crt"><a href="/users/profile/">アカウント情報</a></li>
	<li><a href="/users/help/">ヘルプ</a></li>
	<li><a href="/users/contact/">お問合せ</a></li>
	<li><a href="/users/logout/">ログアウト</a></li>
	</ul>
<!-- / #sub --></div>


<!--footer start-->
<?php echo $this->element('footer_block/footer_private'); ?>
<!--footer end-->

</body>
