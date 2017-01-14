<?php
	$html->css(array('user_common', 'user_trash', 'jquery.confirm.css') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min','frontend/inbox', 'jquery/plugin/jquery.confirm'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ-ゴミ箱-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>監視トレイ ゴミ箱</h1>
	<div class="help">
	</div>
</div>

<div id="main">
<div class="inner">


<div class="inner_header">
	<div class="tab"><a href="/users/inbox/">すべて</a></div>
	<div class="tab crt"><a href="/users/trash/">ゴミ箱</a></div>

	<div class="navi_block">
		<?php
/*			$end_page = $start_record + $limit_count - 1;
			if( $end_page > $Allcnt ){
				$end_page = $Allcnt;
			}

			if( $Allcnt == 0 ){
				echo '<p class="text"></p>';
			} else {
				echo '<p class="text">検出数　'. $start_record .'～'. $end_page .' / '. $Allcnt .'</p>';
			}
			echo '<p class="page">';

			if( $start_record != 1 ){
				$prev = $page - 1;
				echo '<a class="prev" href="/users/inbox/'. $prev .'/">前へ</a>';
			}

			if( $end_page < $Allcnt ) {
				$next = $page + 1;
				echo '<a class="next" href="/users/inbox/'. $next .'/">次へ</a>';
			}
			echo '</p>';
*/
		?>
	</div>



</div>



<div class="inner_list">
<?php if( isset($data) ): ?>
	<table>
		<tr>
			<th class="date">検出日</th>
			<th class="name">検出サイト</th>
			<th class="word">監視ワード</th>
			<th class="trash">監視トレイに戻す</th>
		</tr>

<?php
	$i = 1;
	$num = 0;
	foreach( $data as $data ){

		$num++;
		$dr_id = 'none';
		$ir_id = $data['IR']['illegal_result_uid'];

		if( $i % 2 == 0 ){
			echo '<tr class="even main_'. $num .'">'."\n";
		} else {
			echo '<tr class="odd main_'. $num .'">'."\n";
		}

		echo '<td>'. str_replace( '-', '/', $data['IR']['regist_date'] ) .'</td>'."\n";
		echo '<td class="name"><a href="http://anonym.to/?'. $data['IR']['illegal_url'] .'" target="_blank">'. $data['IRS']['site_name'] .'</a> 【 <a href="http://www.google.com/url?q='. $data['IR']['illegal_url'] .'" target="_blank">google経由</a> 】</td>'."\n";
		echo '<td>'. $data['Word']['search_word'] .'</td>'."\n";
		echo '<td><img class="restore" number="snum_'. $num .'" src="/img/restore.png" alt="削除" width="20" height="20" ir_id="'. $ir_id .'" /></td>'."\n";

		$i++;

		echo '</tr>'."\n";

	}
?>

	</table>
<?php endif; ?>
</div>



<!-- / .inner --></div>
<!-- / #main --></div>

<div id="sub">
	<ul>
	<li><a href="/users/dashboard/">ダッシュボード</a></li>
	<li class="crt"><a href="/users/inbox/">監視トレイ</a></li>
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
