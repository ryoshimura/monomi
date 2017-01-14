<?php
	$html->css(array('user_common', 'user_dmca') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min', 'frontend/dmca'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', 'モノミインフォ-削除申請完了-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>削除申請</h1>
	<div class="help">
	</div>
</div>

<div id="main">
<div class="inner">

<?php if( $success_flag == 1 ): ?>
	<p>削除申請を送信しました。</p>
	<p class="cmpa"><a href="<?php echo $backlink; ?>">監視トレイに戻る</a></p>
<?php elseif( $success_flag == 0 ): ?>
	<p>削除申請の送信に失敗しました。<br /></p>
	<p class="cmpa">
		<a href="/users/contact/">問合せする</a><br />
		<a href="<?php echo $backlink; ?>">監視トレイに戻る</a>
	</p>
<?php endif; ?>



<div class="clear"></div>

<!-- / .inner --></div>
<!-- / #main --></div>

<div id="sub">
	<ul>
	<li><a href="/users/dashboard/">ダッシュボード</a></li>
	<li class="crt"><a href="/users/inbox/">監視トレイ</a></li>
	<li><a href="/users/words/">監視ワード</a></li>
	<li><a href="/users/ilist/">監視サイト</a></li>
	<li><a href="/users/template_prof/">プロフィール</a></li>
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
