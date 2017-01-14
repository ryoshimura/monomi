<?php
	$html->css(array('user_common', 'user_dashboard') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min','frontend/dashboard'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ-ダッシュボード-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>ダッシュボード</h1>
	<div class="help">
		<a href="/users/help/#num3">help</a>
	</div>
</div>

<div id="main">
<div class="inner">
	<div class="message">
		<h2>メッセージ</h2>
		<?php
			$i = 1;
			foreach( $msg as $msg ){
				if( $i == 1 ){
					echo '<div class="first">';
				} else {
					echo '<div>';
				}
				echo '<p class="title">' . $msg['Message']['title'] . '<span class="detail open" num="num'.$i.'">詳細</span><span class="date">'. date("Y.m.d",strtotime($msg['Message']['regist_date'])) .'</span></p>' . "\n";
				echo '<p class="body num'. $i .'">' . $msg['Message']['text'] . '</p></div>' . "\n";
				$i++;
			}

		?>
	</div>

	<div class="status">
		<table>
			<tr>
				<td class="left">プラン<div>
					<?php
						if( $user['User']['payment_status'] === '1' ){	// Standardプラン
							echo 'スタンダード監視プラン';
						} else if( $user['User']['payment_status'] === '2' ){	// 体験版
							echo '体験版';
						} else if( $user['User']['payment_status'] === '9' ){	// β版
							echo 'β Test';
						}
					?>
				</div></td>
				<td>有効期限<div>
					<?php
						if( $user['User']['payment_status'] === '9' && $user['User']['period_status'] == 1 ){
							echo '正式サービス開始まで';
						} else if( $user['User']['period_status'] == 0 ){
							echo '停止中';
						} else {
							echo '～ ' . date("Y.m.d", strtotime($user['User']['current_period']));
						}
					?>
				</div></td>

				<td>最終監視日時<div>
					<?php echo date("Y/m/d H:i", strtotime($lastupdate)); ?>
				</div></td>
				<td>検出サイト<div><?php echo $monitoring['ilcnt']; ?></div></td>
<!--				<td>検出アップローダ<div><?php /* echo $monitoring['dlcnt']; */ ?></div></td>-->

			</tr>
		</table>
	</div>


<!-- / .inner --></div>
<!-- / #main --></div>

<div id="sub">
	<ul>
	<li class="crt"><a href="/users/dashboard/">ダッシュボード</a></li>
	<li><a href="/users/inbox/">監視トレイ</a></li>
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
