<?php
	$html->css(array('user_common', 'user_inbox', 'jquery.template.css', 'jquery.confirm.css') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min', 'frontend/inbox', 'frontend/template_mail', 'jquery/plugin/jquery.confirm', 'jquery/plugin/jquery.zclip'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', 'モノミインフォ-監視トレイ-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>監視トレイ</h1>
	<div class="help">
		<a href="/users/help/#num4">help</a>
	</div>
</div>

<div id="main">
<div class="inner">

<?php if( false != $view_flg ): ?>


<div class="inner_header">
	<div class="tab crt"><a href="/users/inbox/">すべて</a></div>

	<?php if( '2' !== $payment_status ): ?>
	<div class="tab"><a href="/users/trash/">ゴミ箱</a></div>
	<?php endif; ?>

	<div class="navi_block">
		<?php
			$end_page = $start_record + $limit_count - 1;
			if( $end_page > $Allcnt ){
				$end_page = $Allcnt;
			}

			if( $Allcnt == 0 ){
				echo '<p class="text"> </p>';
			} else {
				echo '<p class="text">検出数　'. $start_record .'～'. $end_page .' / '. $Allcnt .'</p>';
			}
			echo '<p class="page">';

			if( $start_record != 1 ){
				$prev = $page - 1;
				echo '<a class="prev" href="/users/inbox/'. $prev .'/">前へ</a>';
			} else {
				echo '<span class="prev_off">前へ</span>';
			}

			if( $end_page < $Allcnt ) {
				$next = $page + 1;
				echo '<a class="next" href="/users/inbox/'. $next .'/">次へ</a>';
			} else {
				echo '<span class="next_off">次へ</span>';
			}
			echo '</p>';
		?>
	</div>
</div>

<?php if( 0 < $Allcnt ): ?>
<div class="spacer_n"></div>
<div class="inner_notice">
	<p>
		不正掲載の疑いのあるコンテンツが見つかりました。<br />
		あなたの著作物か確認してください。
	</p>
</div>
<?php endif; ?>


<div class="inner_list">
	<table>
		<tr>
		<th class="date">
			<div class="title">検出日</div>
			<div class="sort">
				<a class="<?php if( $sort === 'datea' ){echo 'up_on';}else{echo 'up';} ?>" href="/users/inbox/1/datea/">UP</a>
				<a class="<?php if( $sort === 'dated' ){echo 'down_on';}else{echo 'down';} ?>" href="/users/inbox/1/dated/">DOWN</a>
			</div>
		</th>
		<th class="name">
			<img class="open_all" src="/img/minus.gif" alt="開く" width="16" height="16" />
			<div class="title">検出サイト/<font color="#2C78BA">アップローダ</font></div>
			<div class="sort">
				<a class="<?php if( $sort === 'sitea' ){echo 'up_on';}else{echo 'up';} ?>" href="/users/inbox/1/sitea/">UP</a>
				<a class="<?php if( $sort === 'sited' ){echo 'down_on';}else{echo 'down';} ?>" href="/users/inbox/1/sited/">DOWN</a>
			</div>
		</th>
		<th class="url">サイト<br />解説</th>
		<th class="word">
			<div class="title">監視ワード</div>
			<div class="sort">
				<a class="<?php if( $sort === 'worda' ){echo 'up_on';}else{echo 'up';} ?>" href="/users/inbox/1/worda/">UP</a>
				<a class="<?php if( $sort === 'wordd' ){echo 'down_on';}else{echo 'down';} ?>" href="/users/inbox/1/wordd/">DOWN</a>
			</div>
		</th>


		<?php if( '2' !== $payment_status ): ?>
			<th class="trash">ゴミ箱</th>
		<?php else: ?>
			<th class="trash"> </th>
		<?php endif; ?>

		<th class="template">削除<br />要請</th>
		<th class="tag">メモタグ</th>
		</tr>

<?php
	$cnt = 0;
	$i = 1;
	$num = 0;
	foreach( $data as $key => $val ){

		if( $val['IR']['sub_flag'] == 0 ){

			$num++;
			$dr_id = 'none';
			$ir_id = $val['IR']['illegal_result_uid'];
			$url   = $val['IR']['illegal_url'];

			if( $i % 2 == 0 ){
				echo '<tr class="even main_'. $num .'">'."\n";
			} else {
				echo '<tr class="odd main_'. $num .'">'."\n";
			}

			echo '<td>'. str_replace( '-', '/', $val['IR']['regist_date'] ) .'</td>'."\n";
			echo '<td class="name"><img number="snum_'. $num .'" class="open" src="/img/minus.gif" alt="開く" width="16" height="16" /><a class="name" href="'. $val['IR']['illegal_url'] .'" target="_blank">'. $val['IRS']['site_name'] .'</a></td>'."\n";
			echo '<td><a href="/users/detail/is/'. $val['IRS']['site_uid'] .'"><img src="/img/detailsite.png" alt="解説" width="16" height="16" /></a></td>'."\n";
			echo '<td>'. $val['Word']['search_word'] .'</td>'."\n";


			if( '2' !== $payment_status ){
				echo '<td><img class="trash" number="snum_'. $num .'" src="/img/trash.png" alt="削除" width="16" height="16" ir_id="'. $ir_id .'" /></td>'."\n";
			} else {
				echo '<td> </td>'."\n";
			}

			// add 2012.09.28 テンプレート
			echo '<td><img class="template" mode="is" uid="'. $ir_id .'" src="/img/template.png" alt="テンプレート" width="16" height="16" /></td>'."\n";


			echo '<td class="tag">';

			if( $val['IR']['memo_request'] == 0 ){
				echo '<span class="request req" dr_id="'. $dr_id .'" ir_id="'. $ir_id .'" url="'. $url .'">削除要請中</span>';
			}else{
				echo '<span class="request_on req" dr_id="'. $dr_id .'" ir_id="'. $ir_id .'" url="'. $url .'">削除要請中</span>';
			}

			if( $val['IR']['memo_complete'] == 0 ){
				echo '<span class="completion comp" dr_id="'. $dr_id .'" ir_id="'. $ir_id .'" url="'. $url .'">削除完了</span></td>'."\n";
			}else{
				echo '<span class="completion_on comp" dr_id="'. $dr_id .'" ir_id="'. $ir_id .'" url="'. $url .'">削除完了</span></td>'."\n";
			}

			$i++;

		} else if( $val['IR']['sub_flag'] == 1 ){

			$dr_id = $val['DR']['download_result_uid'];
			$ir_id = 'none';
			$url   = $val['DR']['download_result_url'];

			if( !empty($val['DR']['download_result_uid']) ){	// add 2012.09.16 ダウンロードリザルトが存在しない場合に備えて改修
				echo '<tr class="sub snum_'. $num .'">'."\n";
				echo '<td> </td>'."\n";
				echo '<td class="name"><a class="name" href="'. $val['DR']['download_result_url'] .'" target="_blank">'. $val['DS']['site_name'] .'</a></td>'."\n";

				if( $cnt != 0 ){	// add 2012.09.16 全レコードと同じDS.site_nameの場合は、tdをブランクとする
					if( $data[$key-1]['IR']['sub_flag'] == 1 && $val['DS']['site_name'] === $data[$key-1]['DS']['site_name'] ){
						echo '<td> </td>'."\n";
					} else {
						echo '<td><a href="/users/detail/ds/'. $val['DS']['download_site_uid'] .'"><img src="/img/detailsite.png" alt="解説" width="16" height="16" /></a></td>'."\n";
					}
				}

				echo '<td> </td>'."\n";
				echo '<td> </td>'."\n";

				// add 2012.09.28 テンプレート
				if( $cnt != 0 ){	// add 2012.09.16 全レコードと同じDS.site_nameの場合は、tdをブランクとする
					if( $data[$key-1]['IR']['sub_flag'] == 1 && $val['DS']['site_name'] === $data[$key-1]['DS']['site_name'] ){
						echo '<td> </td>'."\n";
					} else {
						echo '<td><img class="template" mode="ds" uid="'. $dr_id .'" src="/img/template.png" alt="テンプレート" width="16" height="16" /></td>'."\n";
					}
				}



				echo '<td class="tag">';

				if( $val['DR']['memo_request'] == 0 ){
					echo '<span class="request req" dr_id="'. $dr_id .'" ir_id="'. $ir_id .'" url="'. $url .'">削除要請中</span>';
				}else{
					echo '<span class="request_on req" dr_id="'. $dr_id .'" ir_id="'. $ir_id .'" url="'. $url .'">削除要請中</span>';
				}

				if( $val['DR']['memo_complete'] == 0 ){
					echo '<span class="completion comp" dr_id="'. $dr_id .'" ir_id="'. $ir_id .'" url="'. $url .'">削除完了</span></td>'."\n";
				}else{
					echo '<span class="completion_on comp" dr_id="'. $dr_id .'" ir_id="'. $ir_id .'" url="'. $url .'">削除完了</span></td>'."\n";
				}

			} else {	// ダウンロードリザルトが存在しない場合
				echo '<tr class="sub snum_'. $num .'">'."\n";
				echo '<td> </td>'."\n";
				echo '<td class="name"><span>アップロード先が特定できません</span></td>'."\n";
				echo '<td> </td>'."\n";
				echo '<td> </td>'."\n";
				echo '<td> </td>'."\n";
				echo '<td><img class="template" mode="ds" uid="" src="/img/template.png" alt="テンプレート" width="16" height="16" /></td>'."\n";
				echo '<td class="tag"> </td>'."\n";
			}
		}


		echo '</tr>'."\n";
		$cnt++;		// 初回レコードを見極めるだけの変数
	}
?>

	</table>
</div>


<?php endif; ?>


<div class="msg_block">
	<?php if( '2' === $payment_status && 5 < $Allcnt && $view_flg == true ): ?>
		<P>
			<?php echo $Allcnt; ?>サイトを検知しましたが、体験版のため上位5サイトのみの表示となります。<br />
			すべて表示するためには、スタンダード監視プランへのお申込みが必要です。<br />
			<a href="/users/sdrequest/">いますぐスタンダード監視プランに申し込む</a>
		</p>
	<?php elseif( '0' === $payment_status || $view_flg == false ): ?>
		<div class="spacer"></div>
		<P>
			ご利用期限が過ぎているため、検出結果はご覧いただけません。<br />
			<a href="/users/sdrequest/">いますぐスタンダード監視プランに申し込む</a>
		</p>
		<div class="spacer"></div>
	<?php elseif( 0 == $Allcnt ): ?>
		<P>
			不正サイトへの掲載はありません。<br />
			<br />
			はじめてご利用の方は、監視ワードをご登録ください。<br />
			監視ワードを登録・変更した場合、次の監視サイクルまで約１時間ほどかかります。<br />
			また、発売から２週間以上経過しても検出されない場合は、監視ワードを変更してみるのも有効です。<br />

		</p>
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
