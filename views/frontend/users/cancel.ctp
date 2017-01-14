<?php
	$html->css(array('user_common', 'user_cancel') , null, array('inline'=>false));
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
	<h1>解約</h1>
	<div class="help">
		<a href="/users/help/#num15">help</a>
	</div>
</div>

<div id="main">
<div class="inner">
	<p>
		物見インフォの解約について<br />
		<br />
		・ ご利用期限内の場合は、期限が過ぎた後、すみやかにお客様の個人情報含め全ての情報を削除します。<br />
		　　⇒ご利用期限まではそのままお使いいただけます。<br />
		・ すでにご利用期限を過ぎている場合は、お客様の個人情報含め全ての情報をすぐに削除します（体験版ユーザーを除く）。<br />
		<?php if( '9' === $user['User']['payment_status'] ): ?>
		・ ベータテスト版をご利用の場合は、すぐに全ての情報を削除しサービスが利用できなくなります。<br />
		<?php endif; ?>

		<a class="cancel_btn" href="/users/cancelled/">解約する</a>
	</p>



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
