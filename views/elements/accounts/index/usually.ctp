			<p class="contents_title">アカウント</p>
			<div id="status_left">
				<div class="block_header">
					<p>ご契約状況</p>
				</div>
				<div id="status_contents">
<?php
	// 契約プラン表示
	if ( 'Standard Plan' === $view[0]['Plan']['plan_name'] ):
?>
					<p><span class="standard_plan">Standard Plan</span></p>
<?php
	elseif ( 'Light Plan' === $view[0]['Plan']['plan_name'] ):
?>
					<p><span class="light_plan">Light Plan</span></p>
<?php
	endif;
?>
					<p>残り　：　<span class="st_num"><?php echo $view['remaining']; ?></span>日（ <?php echo date( 'Y年m月d日', strtotime($view[0]['License']['end_date']) + 86400 ); ?></>に<?php echo $view[1]['Plan']['fixed_term']; ?>日間の自動更新を行ないます ）</p>
					<p>同時監視作品数　：　<span class="st_num"><?php echo $view[0]['License']['licenses_works']; ?></span></p>
					<p>次回更新時のご請求額　：　￥<span class="st_num"><?php echo number_format( $view[1]['Payment']['payment_price'] ); ?></span></p>
				</div><!-- div#status_contents -->
				<div id="ad_banner_block">
				</div>
			</div>
			<div id="linkmenu_right">
				<p class="first"><a class="corner profil" href="/users/edit/">プロフィールを変更</a></p>
				<p><a class="corner" href="/accounts/cancel/?pid=<?php echo $view[0]['License']['payment_id'] ?>">自動更新しない（解約）</a></span></p>
<?php
	if ( 'Light Plan' === $view[0]['Plan']['plan_name'] ):
?>
				<p><a class="corner" href="/accounts/change/">Standard Planに変更</a></p>
<?php
	elseif ( 'Standard Plan' === $view[0]['Plan']['plan_name'] ):
?>
				<p><a class="corner" href="/accounts/change/">同時監視作品数を変更</a></p>
<?php
	endif;
?>
			</div>
			<div class="dummy_block"></div>