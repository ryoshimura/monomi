<?php
	$html->css(array('common', 'policy') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ - 特定商取引法に基づく表示');
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
		<img class="h2gif"src="/img/h2law.gif" alt="about" />

		<h3 class="toku">特定商取引法に基づく表示</h3>

		<table>
		<tr><td class="caption">店舗名</td><td>物見インフォ</td></tr>
		<tr><td class="caption">販売業者名</td><td>（物見インフォ） 吉村　竜一</td></tr>
		<tr><td class="caption">運営統括責任者</td><td>吉村　竜一</td></tr>
		<tr><td class="caption">所在地</td><td><img src="/img/address.gif" height="11" width="195" /></td></tr>
		<tr><td class="caption">電話番号</td><td><img src="/img/tel.gif" /> <span>（お問合せは下記よりお願い致します）</span></td></tr>
		<tr><td class="caption">問合せ先</td><td><span><a href="/inquiry/form/">お問い合わせ</a> もしくは </span><img src="/img/mail.gif" /><span>までお願いします</span></td></tr>
		<tr><td class="caption">販売価格</td><td>サービスページにて表示</td></tr>
		<tr><td class="caption">お支払方法</td><td>Paypal</td></tr>
		<tr><td class="caption">商品引き渡し時期</td><td>即時</td></tr>
		<tr><td class="caption">キャンセル・変更</td><td>原則としてキャンセル・返品不可能です</td></tr>
		</table>

		<p>
			<a href="/" class="top">TOPへ戻る</a>
		</p>
	</div>
<!-- / .inner --></div>

	<div class="sp_s"></div>

<!-- / .outer --></div>
<!-- / #main --></div>



<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
