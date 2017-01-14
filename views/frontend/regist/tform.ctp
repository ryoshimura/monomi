<?php
	$html->css(array('common', 'regist_form') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min','frontend/regist','frontend/paypal'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', 'スタンダード監視プランお申込み');
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
			<img src="/img/cb1e.png" alt="申込み" />
			<img src="/img/cb2d.png" alt="決済情報入力" />
			<img src="/img/cb3d.png" alt="申込確認" />
			<img src="/img/cb4d.png" alt="申込完了" />
		</div>

		<p class="intro">
			初めて物見インフォにお申込みいただく方向けの申込フォームです。<br />
			<span class="red">過去に体験版やお申込みをいただいた方は<a href="/users/login/">こちら</a>からログインし「アカウント情報」→「監視開始」からお申込みください。</span>
			<br /><br />
			注意事項：<br />
			物見インフォは、ご自身の著作物を監視する目的でのみご利用いただけます。<br />
			上記以外の目的で使われた場合、別途、著作権保有者確認をさせていただくことがあります。<br />
			確認が取れない場合は、アカウントが停止するためご注意ください。<br />
			また、フリーメールでご登録の場合、物見インフォからのお知らせメールが迷惑フォルダに届くことがあります。
		</p>

		<div class="left">
			<p class="caption">
				世界中で利用されているPayPal決済を用意しました。<br />
				ペイパルアカウントをお持ちでなくても、対応のクレジットカードでお支払いいただけます。
			</p>
			<!-- PayPal Logo --><table border="0" cellpadding="10" cellspacing="0" align="center"><tr><td align="center"></td></tr><tr><td align="center"><a href="#" onclick="javascript:window.open('https://www.paypal.com/jp/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside','olcwhatispaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=350');"><img  src="https://www.paypal.com/ja_JP/JP/i/bnr/vertical_solution_4_jcb.gif" border="0" alt="ソリューション画像"></a></td></tr></table><!-- PayPal Logo -->
			<p>
				<img class="ssl" src="/img/RapidSSL_SEAL-90x50.gif" alt="RapidSSL" width="90" height="50" />
			</p>
		</div>

		<div class="right">

			<div class="plan">スタンダード監視プラン 30日間不正サイトを監視します</div>

			<?php echo $form->create(null); ?>
			<div class="input">
				<label class="select">監視ワード数</label>

				<?php echo $form->select('User.plan',array('w2p700'=>'監視ワード数 2個  700円 (税込)', 'w3p900'=>'監視ワード数 3個  900円 (税込)'), '700', array('error'=>false, 'empty'=>false)); ?>
			</div>

			<div class="input">
				<?php echo $form->input('User.mail_address', array('type'=>'text', 'label'=>'メールアドレス', 'class'=>'text', 'error'=>false)); ?>
			</div>
			<?php echo $form->error('Regist.mail_address', null, array('wrap'=>'div')); ?>

			<div class="input">
				<label for="UserPasswd">パスワード</label>
				<?php echo $form->input('User.passwd', array('type'=>'password', 'class'=>'text', 'label'=>false, 'error'=>false)); ?>
			</div>
			<?php echo $form->error('Regist.passwd', null, array('wrap'=>'div')); ?>

			<div class="input">
				<label for="UserPasswdConfirm">パスワード（確認）</label>
				<?php echo $form->input('User.passwd_confirm', array('type'=>'password', 'class'=>'text', 'label'=>false, 'error'=>false)); ?>
			</div>
			<?php echo $form->error('Regist.passwd_confirm', null, array('wrap'=>'div')); ?>

			<p class="btn">
				<?php echo $form->submit('次へ', array('class'=>'submit', 'id'=>'request_btn')); ?>
				<span id="paypalBtn">テスト</span>
				<br />
				<a href="https://www.sandbox.paypal.com/incontext?token=<?php echo $token; ?>">テスト2</a>

			</p>



<a href="https://www.sandbox.paypal.com/incontext?token=<?php echo $token; ?>" id='submitBtn'><img src='https://www.paypal.com/ja_JP/i/btn/btn_dg_pay_w_paypal.gif' border='0' /></a>

<!-- *** PayPalの提供するJavaScriptを埋め込む *** -->
<script src ='https://www.paypalobjects.com/js/external/dg.js' type='text/javascript'></script>
<script>
// "submitBtn" を押すと、Digital Goodsの決済フローが始まる.
var dg = new PAYPAL.apps.DGFlow({
	// the HTML ID of the form submit button which calls setEC
	trigger: "submitBtn"
});
</script>


			<?php echo $form->hidden('User.token', array( 'value'=>$token)); ?>
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
