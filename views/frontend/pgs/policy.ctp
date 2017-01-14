<?php
	$html->css(array('common', 'policy') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ - 個人情報の取扱いについて');
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
		<img class="h2gif"src="/img/h2policy.gif" alt="about" />
		<h3 class="toku">個人情報の取扱いについて</h3>
		<p>
物見インフォ（モノミインフォ）はお客様の個人情報を保護する事が社会的責務と考えております。<br />
以下の個人情報保護方針を定め、個人情報の取扱い、管理、維持に努めます。<br />
<br />
1．個人情報の収集・利用・提供<br />
個人情報の収集・利用・提供にあたっては適法かつ公正な手続によって行います。<br />
物見インフォの収集目的は以下の様になります。<br />
・監視結果含む物見インフォからの情報提供<br />
<br />
2．個人情報の管理<br />
個人情報を厳重に管理する事とし、個人情報への不正アクセス、個人情報の紛失、改ざん、漏洩等の予防に努めます。<br />
<br />
3．個人情報の第三者提供<br />
個人情報をお客様の同意を頂いた場合や法令に定める場合を除き、第三者に公開、提供する事はありません。<br />
<br />
4．個人情報のお問い合わせ<br />
お客様から自己の個人情報の訂正、削除等のご要望があった場合には速やかに対応、処理します。<br />
<br />
【お問い合わせ先】<br />
<a href="/inquiry/form/">お問合わせフォーム</a>をご利用ください<br />
<br />
5．法令・規範の遵守<br />
物見インフォは個人情報の取扱いにあたり、個人情報の保護に関する法令その他の規範を遵守致します。<br />
<br />
制定日：2012年9月1日<br />
		</p>
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
