<?php
	$html->css(array('user_common', 'user_campaign') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ-招待キャンペーン情報-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>招待キャンペーン</h1>
</div>

<div id="main">
<div class="inner">

	<h2>招待キャンペーンとは</h2>
	<p>
		物見infoは著作権者のみのご利用を想定していることから、クチコミ等による著作権者同士のつながりで広まることを重要視しております。<br />
		もし著作権侵害に困っている方がいらっしゃいましたら、ぜひ物見infoへご招待ください。<br />
	</p>
	<h2>特典</h2>
	<p>
		著作権侵害でお困りの方を物見infoへご招待いただくと下記特典があります。<br />
		<br />
		・ 招待した方　　：　監視期間＋30日延長<br />
		・ 招待された方　：　監視ワード＋１になる特別なプランが選択可能<br />
	</p>
	<h2>招待の流れ</h2>
	<p>
		1.  招待したい著作権者に、あなたの招待キャンペーンコードを渡してください。<br />
		2.  招待された方は、新規申込で招待キャンペーンプランを選択し、招待キャンペーンコードを入力していただきます。<br />
		3.  申込完了の24時間以内に、相互に特典をプレゼントいたします。
	</p>
	<h2>ルール</h2>
	<p>
		招待キャンペーンには以下のルールがあります。<br />
		<br />
		・ 招待キャンペーンの適用は、物見infoを初めてご利用になる方のみです。<br />
		・ 特典の監視期間延長は、最大で＋180日までとなります。<br />
		・ 監視契約中の場合、期間延長は24時間以内に自動的に行なわれます。契約期間外の場合、次回再開時に自動的に付与されます。
	</p>

	<a href="/users/profile/">アカウント情報へ戻る</a>

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
