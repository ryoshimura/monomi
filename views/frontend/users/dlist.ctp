<?php
	$html->css(array('user_common', 'user_list') , null, array('inline'=>false));
	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', 'モノミインフォ-アップローダ-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>アップローダ</h1>
	<div class="help">
		<a href="/users/help/#num6">help</a>
	</div>
</div>

<div id="main">
<div class="inner">

<div class="inner_header">
	<div class="tab"><a href="/users/ilist/">監視サイト ： <?php echo $cnt['ilist']; ?></a></div>
	<div class="tab crt">アップローダ ： <?php echo $cnt['dlist']; ?></div>
</div>

<div class="inner_list">
	<table>
<?php
	$i = 1;
	foreach( $list as $list ){
		echo '<tr>'."\n";

		if( $i % 2 == 0 ){
			echo '<td class="name even"><a href="/users/detail/ds/'. $list['DownloadSite']['download_site_uid'] .'/">'. $list['DownloadSite']['site_name'] .'</a></td>'."\n";
			echo '<td class="url even">'. $list['DownloadSite']['site_url'] .'</td>'."\n";

			if( strtotime($list['DownloadSite']['created']) >= strtotime("-7 day") ){
				echo '<td class="notice even">'. date("Y.m.d",strtotime($list['DownloadSite']['created'])) .' 追加</td>'."\n";
			} else {
				echo '<td class="notice even"></td>'."\n";
			}

		} else {
			echo '<td class="name"><a href="/users/detail/ds/'. $list['DownloadSite']['download_site_uid'] .'/">'. $list['DownloadSite']['site_name'] .'</a></td>'."\n";
			echo '<td class="url">'. $list['DownloadSite']['site_url'] .'</td>'."\n";

			if( strtotime($list['DownloadSite']['created']) >= strtotime("-7 day") ){
				echo '<td class="notice">'. date("Y.m.d",strtotime($list['DownloadSite']['created'])) .' 追加</td>'."\n";
			} else {
				echo '<td class="notice"></td>'."\n";
			}
		}

		echo '</tr>'."\n";

		$i++;
	}
?>
	</table>
</div>


<!-- / .inner --></div>
<!-- / #main --></div>

<div id="sub">
	<ul>
	<li><a href="/users/dashboard/">ダッシュボード</a></li>
	<li><a href="/users/inbox/">監視トレイ</a></li>
	<li><a href="/users/words/">監視ワード</a></li>
	<li class="crt"><a href="/users/ilist/">監視サイト</a></li>
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
