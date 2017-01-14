<?php
	$html->css(array('common', 'regist_form') , null, array('inline'=>false));
//	$html->script(array('jquery/jquery-1.4.2.min','frontend/paypal'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '申込確認');
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

		<img src="/img/pregist.png" alt="新規お申込み" />

		<div class="sp_s"></div>

		<div class="bread">
			<img src="/img/cb1d.png" alt="申込み" />
			<img src="/img/cb2d.png" alt="決済情報入力" />
			<img src="/img/cb3e.png" alt="申込確認" />
			<img src="/img/cb4d.png" alt="申込完了" />
		</div>

		<p class="intro">
			入力内容をご確認ください。<br />
		</p>

		<div class="confbody">
			<ul class="conf">
				<li>
					<div class="label">メールアドレス</div>
					<div class="item1"><?php echo $byData['User']['mail_address']; ?></div>
				</li>
				<li>
					<div class="label">お申込みプラン</div>
					<div class="item1"><?php echo $payment['Payment']['product_name']; ?></div>
					<div class="item2">\ <?php echo $payment['Payment']['payment_amount']; ?></div>
				</li>
				<li>
					<div class="label">ご利用開始日</div>
					<div class="item1"><?php echo date("Y/m/d", strtotime($payment['Payment']['start_date'])); ?></div>
				</li>
			</ul>


			<div class="btn_block">
				<ul class="confirm_btn">
				<li><a class="back" href="#" onClick="history.back(); return false;">戻る</a></li>
				<li><a href="https://www.sandbox.paypal.com/incontext?token=<?php echo $token; ?>" id='submitBtn'><img src='https://www.paypal.com/ja_JP/i/btn/btn_dg_pay_w_paypal.gif' border='0' /></a></li>
				<li><a class="buy_btn" href="/regist/thanks">購入</a></li>
				</ul>
			</div>

		</div>

		<p class="ssl">
			<img class="ssl" src="/img/RapidSSL_SEAL-90x50.gif" alt="RapidSSL" width="90" height="50" />
		</p>


		<div class="sp_s"></div>
<!-- / .inner --></div>

<!-- / .outer --></div>
<!-- / #main --></div>



<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->


<!-- *** PayPalの提供するJavaScriptを埋め込む *** -->
<script src ='https://www.paypalobjects.com/js/external/dg.js' type='text/javascript'></script>
<script>
	var dg = new PAYPAL.apps.DGFlow({
	trigger: "submitBtn"
});
</script>
</body>
