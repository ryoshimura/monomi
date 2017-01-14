<?php
	$html->css(array('common', 'pregist') , null, array('inline'=>false));
//	$html->script(array('jquery/jquery-1.4.2.min','frontend/regist'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '仮登録完了');
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

		<img src="/img/pthanks.png" alt="仮登録しました" />
		<p class="thx_intro">
			<?php
				$text = '';
				if( $mode === 'standard' ){
					$text = 'スタンダード監視プラン';
				} else if( $mode === 'trial' ) {
					$text = '体験版';
				} else if( $mode === 'beta' ) {
					$text = 'ベータテスト版';
				}
			?>
			物見インフォ <?php echo $text; ?> にお申込みいただきありがとうございます。<br />
			仮登録が完了しました。<br />
			<br />
			<span>登録いただきましたメールアドレス宛てに自動的にメールが送信されます。</span><br />
			<span>本申込み用URLが記載されていますのでアクセスお願いします。</span><br />
			<br />
			フリーメールの場合、迷惑（スパム）フォルダに届く場合がありますので、ご注意ください。<br />
			<br />
			<a href="/inquiry/form/">お問合せフォーム</a>
			<a href="/">物見インフォTOP</a>
		</p>

		<div class="sp_s"></div>
<!-- / .inner --></div>

<!-- / .outer --></div>
<!-- / #main --></div>



<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
