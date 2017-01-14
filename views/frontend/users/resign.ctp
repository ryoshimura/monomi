<?php
	$html->css(array('user_common', 'user_resign') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min','frontend/resign'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ-申込-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>監視サービス申込</h1>
	<div class="help">
		<a href="/users/help/#num11">help</a>
	</div>
</div>

<div id="main">
<div class="inner">

	<h2>監視プランを選択してください。</h2>

	<div class="bread">
		<img src="/img/cb1e.png" alt="申込み" />
		<img src="/img/cb2d.png" alt="決済情報入力" />
		<img src="/img/cb3d.png" alt="申込確認" />
		<img src="/img/cb4d.png" alt="申込完了" />
	</div>

	<p class="intro">
		注意事項：<br />
		物見インフォは、ご自身の著作物を監視する目的でのみご利用いただけます。<br />
		上記以外の目的で使われた場合、別途、著作権保有者確認をさせていただくことがあります。<br />
		確認が取れない場合は、アカウントが停止するためご注意ください。<br />
	</p>

	<div class="paypal_logo">
		<!-- PayPal Logo --><table border="0" cellpadding="10" cellspacing="0" align="center"><tr><td align="center"></td></tr><tr><td align="center"><a href="#" onclick="javascript:window.open('https://www.paypal.com/jp/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside','olcwhatispaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=350');"><img  src="https://www.paypal.com/ja_JP/JP/i/bnr/horizontal_solution_4_jcb.gif" border="0" alt="ソリューション画像"></a></td></tr></table><!-- PayPal Logo -->
	</div>

	<p class="payment_notice">
		物見infoのお申込みは世界中で利用されている安全なPaypal決済となります。<br />
		ペイパルアカウントをお持ちでなくても、対応のクレジットカードでお支払いいただけます。
	</p>


	<div class="plan">スタンダード監視プラン 30日間不正サイトを監視します</div>

	<?php echo $form->create(null); ?>
	<p class="select">
		<?php echo $form->select('User.plan', $product, '00001', array('error'=>false, 'empty'=>false)); ?>
	</p>
	<p class="btn">
		<?php echo $form->submit('次へ', array('class'=>'submit', 'id'=>'request_btn')); ?>
	</p>
	<?php echo $form->end(); ?>




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
