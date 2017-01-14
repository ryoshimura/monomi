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


			$aryPrm = explode("/", $this->Html->url() );
			$base_url = '/users/inbox/[[page]]/';
			if( isset($aryPrm[4]) ){
				$base_url .= $aryPrm[4];
			}

			if( $start_record != 1 ){
				$prev = $page - 1;
				$url = str_replace( '[[page]]', $prev, $base_url );
//				echo '<a class="prev" href="/users/inbox/'. $prev .'/">前へ</a>';
				echo '<a class="prev" href="'. $url .'">前へ</a>';
			} else {
				echo '<span class="prev_off">前へ</span>';
			}

			if( $end_page < $Allcnt ) {
				$next = $page + 1;
				$url = str_replace( '[[page]]', $next, $base_url );
//				echo '<a class="next" href="/users/inbox/'. $next .'/">次へ</a>';
				echo '<a class="next" href="'. $url .'">前へ</a>';
			} else {
				echo '<span class="next_off">次へ</span>';
			}
			echo '</p>';
		?>
	</div>
</div>

<div class="spacer_n"></div>
<div class="inner_sort">
	<ul>
		<li class="caption">並び順：</li>
		<?php if($sort === 'dated'): ?>
			<li>検出日が新しい</li>
		<?php else: ?>
			<li><a href="/users/inbox/1/dated/">検出日が新しい</a></li>
		<?php endif; ?>
		<?php if($sort === 'datea'): ?>
			<li>検出日が古い</li>
		<?php else: ?>
			<li><a href="/users/inbox/1/datea/">検出日が古い</a></li>
		<?php endif; ?>
		<?php if($sort === 'sitea'): ?>
			<li>不正サイト昇順</li>
		<?php else: ?>
			<li><a href="/users/inbox/1/sitea/">不正サイト昇順</a></li>
		<?php endif; ?>
		<?php if($sort === 'sited'): ?>
			<li>不正サイト降順</li>
		<?php else: ?>
			<li><a href="/users/inbox/1/sited/">不正サイト降順</a></li>
		<?php endif; ?>
	</ul>
</div>
<div class="inner_navi">
	<p>外部リンクは不正サイトに足跡がつかないようanonym.toを経由します</p>
	<ul>
		<li><img src="/img/template.png" alt="削除申請" width="18" height="18" />削除申請</li>
<!--		<li><img src="/img/history.png" alt="履歴" width="18" height="18" />メール送信履歴</li>	-->
		<li><img src="/img/trash.png" alt="削除" width="16" height="16" />ゴミ箱</li>
	</ul>
</div>


<div class="inner_list">

	<?php echo $form->hidden('currentUrl', array( 'value'=>$html->url(null) ));?>

<?php
	$i = 0;
	foreach( $data as $val ){

		if( $i == 0 ){
			echo '<div class="postfirst">'. "\n";
			$i++;
		} else {
			if( $i % 2 == 0 ){
				echo '<div class="post even">'. "\n";
			} else {
				echo '<div class="post">'. "\n";
			}
		}

		echo '<div class="post_header">' . "\n";
		echo '<ul>' . "\n";
		echo '<li class="date">'. date("Y/m/d", strtotime($val['IR']['regist_date'])) .'</li>' . "\n";
		echo '<li class="trash"><img class="trash" src="/img/trash.png" alt="削除" width="18" height="18" iruid="'. $val['IR']['illegal_result_uid'] .'" /></li>' . "\n";
		echo '</ul>' . "\n";
		echo '</div>' . "\n";
		echo '<div class="post_url"><a href="http://anonym.to/?'. $val['IR']['illegal_url'] .'" target="_blank">'. $val['IR']['illegal_url'] .'</a></div>' . "\n";
		echo '<div class="post_left">'. $val['Word']['search_word'] .'</div>' . "\n";
		echo '<div class="post_right">' . "\n";

		// 不正掲載サイト
//		echo '<div class="tag_block">' . "\n";
		echo '<div class="tag_block tagfirst">' . "\n";
		echo '<div class="site_name">'. $val['IRS']['site_name'] .'</div>' . "\n";
		echo '<div class="litag">' . "\n";
		echo '<a href="/users/dmca/is/'. $val['IR']['illegal_result_uid'] .'/"><img class="template" src="/img/template.png" alt="削除申請" width="20" height="20" /></a>' . "\n";
//		echo '<img class="template" src="/img/history.png" alt="履歴" width="20" height="20" />' . "\n";

		if( $val['IR']['memo_request'] != 1 ){
			echo '<span class="request tg_off" ir_id="'. $val['IR']['illegal_result_uid'] .'" dr_id="none">削除要請中</span>';
		}else{
			echo '<span class="request tg_on" ir_id="'. $val['IR']['illegal_result_uid'] .'" dr_id="none">削除要請中</span>';
		}
		if( $val['IR']['memo_complete'] != 1 ){
			echo '<span class="completion tg_off" ir_id="'. $val['IR']['illegal_result_uid'] .'" dr_id="none">削除完了</span>';
		}else{
			echo '<span class="completion tg_on" ir_id="'. $val['IR']['illegal_result_uid'] .'" dr_id="none">削除完了</span>';
		}
		echo '</div>' . "\n";
		echo '</div>' . "\n";

		// アップローダ
		$flag_notfound = true;
		foreach( $val['SUB'] as $sub ){

			echo '<div class="tag_block tags">' . "\n";
			if( $sub['DS']['site_name'] == null ){
				echo '<div class="site_name">　アップロード先が特定できません</div>' . "\n";
				echo '<div class="litag">' . "\n";
				echo '<a href="/users/dmcanf/'. $val['IR']['illegal_result_uid'] .'/'. $val['Word']['word_uid'] .'"><img class="template" src="/img/template.png" alt="削除申請" width="20" height="20" /></a>' . "\n";
//				echo '<img class="template" src="/img/history.png" alt="履歴" width="20" height="20" />' . "\n";
//				echo '<span class="notfound" ></span>';
				$flag_notfound = false;

			} else {
				echo '<div class="site_name">　'. $sub['DS']['site_name'] .'</div>' . "\n";
				echo '<div class="site_link"><img src="/img/pin.gif" /><img src="/img/pin.gif" /><img src="/img/pin.gif" /></div>' . "\n";

				echo '<div class="litag">' . "\n";
				echo '<a href="/users/dmca/ds/'. $sub['DR']['download_result_uid'] .'/"><img class="template" src="/img/template.png" alt="削除申請" width="20" height="20" /></a>' . "\n";
//				echo '<img class="template" src="/img/history.png" alt="履歴" width="20" height="20" />' . "\n";
				if( $sub['DR']['memo_request'] != 1 ){
					echo '<span class="request tg_off" ir_id="none" dr_id="'. $sub['DR']['download_result_uid'] .'">削除要請中</span>';
				}else{
					echo '<span class="request tg_on" ir_id="none" dr_id="'. $sub['DR']['download_result_uid'] .'">削除要請中</span>';
				}
				if( $sub['DR']['memo_complete'] != 1 ){
					echo '<span class="completion tg_off" ir_id="none" dr_id="'. $sub['DR']['download_result_uid'] .'">削除完了</span>';
				}else{
					echo '<span class="completion tg_on" ir_id="none" dr_id="'. $sub['DR']['download_result_uid'] .'">削除完了</span>';
				}

				if( $sub['DR']['download_result_url'] !== '' ){
					$flag_notfound = false;
				}
			}

			echo '</div>' . "\n";
			echo '</div>' . "\n";
		}

		if( $flag_notfound == true ){
			echo '<div class="tag_block tags">' . "\n";
			echo '<div class="site_name">　　アップロード先が特定できません</div>' . "\n";
			echo '<div class="litag">' . "\n";
			echo '<a href="/users/dmcanf/'. $val['IR']['illegal_result_uid'] .'/'. $val['Word']['word_uid'] .'"><img class="template" src="/img/template.png" alt="削除申請" width="20" height="20" /></a>' . "\n";
//			echo '<img class="template" src="/img/history.png" alt="履歴" width="20" height="20" />' . "\n";
//			echo '<span class="notfound" ></span>';
			echo '</div>' . "\n";
			echo '</div>' . "\n";
		}


		echo '</div>' . "\n";
//		echo '<div class="post_footer"><label>コメント</label>'. $form->text('UserProfile.creator_url', array('class'=>'textbox', 'error'=>false)) .'<div class="cmbtn">登録</div></div>' . "\n";
//		echo '<div class="post_footer"><div class="cmbtn"></div>'. $form->text('UserProfile.creator_url', array('class'=>'textbox', 'error'=>false)) .'<label>コメント</label></div>' . "\n";
//		echo '<div class="post_footer"><label>コメント</label>'. $form->text('UserProfile.creator_url', array('class'=>'textbox', 'error'=>false)) .'<img class="cbtn" src="/img/comment.png" alt="書き込み" width="24" height="24" /></div>' . "\n";
		echo '<div class="clear"></div>';
		echo '</div>' . "\n";

		$i++;
	}
?>

</div>







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
