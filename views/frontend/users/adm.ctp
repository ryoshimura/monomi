<?php
	$html->css(array('user_common', 'user_adm') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ-ヘルプ-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>ヘルプ</h1>
</div>

<div id="main">
<div class="inner">

<table>
	<tr>
		<td>user_uid</td>
		<td>status</td>
		<td>payment</td>
		<td>period</td>
		<td>mail</td>
		<td>paddwd</td>
	</tr>
<?php
	foreach( $users as $user ){

		echo '<tr>';
		echo '<td><a href="/users/adm_word/'. $user['User']['user_uid'] .'/">'. $user['User']['user_uid'] .'</a></td>';
		echo '<td>'. $user['User']['user_status'] .'</td>';
		echo '<td>'. $user['User']['payment_status'] .'</td>';
		echo '<td>'. $user['User']['period_status'] .'</td>';
		echo '<td>'. $user['User']['mail_address'] .'</td>';
		echo '<td>'. $user['User']['passwd'] .'</td>';
		echo '</tr>' . "\n";
	}
?>
</table>

<!-- / .inner --></div>
<!-- / #main --></div>

<div id="sub">
	<ul>
	<li><a href="/users/dashboard/">ダッシュボード</a></li>
	<li><a href="/users/inbox/">監視トレイ</a></li>
	<li><a href="/users/words/">監視ワード</a></li>
	<li><a href="/users/ilist/">監視サイト</a></li>
	<li><a href="/users/template_prof/">プロフィール</a></li>
	<li><a href="/users/profile/">アカウント情報</a></li>
	<li class="crt"><a href="/users/help/">ヘルプ</a></li>
	<li><a href="/users/contact/">お問合せ</a></li>
	<li><a href="/users/logout/">ログアウト</a></li>
	</ul>
<!-- / #sub --></div>











<!--footer start-->
<?php echo $this->element('footer_block/footer_private'); ?>
<!--footer end-->

</body>
