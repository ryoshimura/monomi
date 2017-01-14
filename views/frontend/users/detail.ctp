<?php
	$html->css(array('user_common', 'user_detail', 'jquery.confirm.css') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min','frontend/detail', 'jquery/plugin/jquery.confirm'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', 'モノミインフォ-監視サイト詳細-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>監視サイト詳細</h1>
	<div class="help">
		<a href="/users/help/#num7">help</a>
	</div>
</div>

<div id="main">
<div class="inner">

<?php
	if( $mode === 'is' ) {
		$site_id   = $data['IllegalSite']['site_uid'];
		$site_name = $data['IllegalSite']['site_name'];
		$site_url  = $data['IllegalSite']['site_url'];
	} else {
		$site_id   = $data['DownloadSite']['download_site_uid'];
		$site_name = $data['DownloadSite']['site_name'];
		$site_url  = $data['DownloadSite']['site_url'];
	}
?>


<?php if( $mode === 'is' ): ?>
	<a class="back" href="/users/ilist/">戻る</a>
<?php else: ?>
	<a class="back" href="/users/dlist/">戻る</a>
<?php endif; ?>


<?php if( 1 != 1 ): ?>
	<div class="left"><a href="<?php echo $site_url; ?>" target="_blank"><img src="http://capture.heartrails.com/300x300/shadow/round/shorten?<?php echo $site_url; ?>" width="300" height="300" /></a></div>
<?php endif; ?>

<div class="right">
	<div class="title">
		<ul>
		<li><div class="label">サイト名</div><div class="val"><?php echo $site_name; ?></div></li>
		<li><div class="label">URL</div><div class="val"><?php echo $site_url; ?></div></li>
		</ul>
	</div>
	<div class="category">
	<?php if( $mode === 'is' ): ?>
		<ul>
			<li class="<?php if( $data['IllegalSite']['flag_new'] ){ echo 'on'; }else{ echo 'off'; } ?>">新着監視</li>
			<li class="<?php if( $data['IllegalSite']['flag_tv_game'] ){ echo 'on'; }else{ echo 'off'; } ?>">TVゲーム</li>
			<li class="<?php if( $data['IllegalSite']['flag_pc_game'] ){ echo 'on'; }else{ echo 'off'; } ?>">PCゲーム</li>
			<li class="<?php if( $data['IllegalSite']['flag_doujin'] ){ echo 'on'; }else{ echo 'off'; } ?>">同人</li>
			<li class="<?php if( $data['IllegalSite']['flag_comic'] ){ echo 'on'; }else{ echo 'off'; } ?>">コミック</li>
		</ul>
		<div class="ulsp"></div>
		<ul>
			<li class="<?php if( $data['IllegalSite']['flag_all'] ){ echo 'on'; }else{ echo 'off'; } ?>">全件監視</li>
			<li class="<?php if( $data['IllegalSite']['flag_music'] ){ echo 'on'; }else{ echo 'off'; } ?>">音楽</li>
			<li class="<?php if( $data['IllegalSite']['flag_anime'] ){ echo 'on'; }else{ echo 'off'; } ?>">アニメ</li>
			<li class="<?php if( $data['IllegalSite']['flag_av'] ){ echo 'on'; }else{ echo 'off'; } ?>">ＡＶ</li>
			<li class="<?php if( $data['IllegalSite']['flag_etc'] ){ echo 'on'; }else{ echo 'off'; } ?>">その他</li>
		</ul>
	<?php endif; ?>
	</div>
	<div class="notice">
<?php
	$notice_msg = '';
	if( $mode === 'is' ) {

		if( $data['IllegalSite']['contact_mail'] != null || $data['IllegalSite']['contact_mail'] != '' ){
			$notice_msg = $data['IllegalSite']['contact_mail'];
		} else if( $data['IllegalSite']['contact_url'] != null || $data['IllegalSite']['contact_url'] != '' ){
			$notice_msg = $data['IllegalSite']['contact_url'];
		}

	} else {

		if( $data['DownloadSite']['contact_mail'] != null || $data['DownloadSite']['contact_mail'] != '' ){
			$notice_msg = $data['DownloadSite']['contact_mail'];
		} else if( $data['DownloadSite']['contact_url'] != null || $data['DownloadSite']['contact_url'] != '' ){
			$notice_msg = $data['DownloadSite']['contact_url'];
		}

	}
?>


	<?php if( $notice_msg != '' ): ?>
		削除要請などの連絡先：<br />
		<?php echo $notice_msg; ?>
		<br /><br /><span class="bikou">※ 掲載情報に間違いがありましたら、<a href="/users/contact/">お問合せフォーム</a>もしくは下記フォーラムにご投稿お願いします。</span></p>
	<?php endif; ?>
	</div>
</div>

<div class="submission">
	<h3>情報共有フォーラム</h3>


	<?php echo $form->create( 'User', array('controller'=>'users', 'action'=>'detail', 'url'=>array('id'=>null, $mode, $uid) ) ); ?>

	<div class="textbox">
		<?php echo $form->textarea('Forum.text', array('cols'=>'60', 'rows'=>'9', 'error'=>false)); ?>
		<div class="feed"><?php echo $form->error('Forum.text'); ?></div>
	</div>
	<div class="caption">
		<p>
			<?php echo $site_name; ?>について、著作権侵害被害者の間で<br />
			情報交換していただくためのフォーラムです。<br />
			お気軽にご利用ください。<br />
			※ ご自身の書き込みコメントは削除できます。<br />
		</p>
		<?php echo $form->submit('書き込み', array('class'=>'submit_btn')); ?>
	</div>
	<?php echo $form->end(); ?>
</div>
<div class="forum">
<?php if( $msg === false || empty($msg) ): ?>
	<div class="report">
		<div class="text">このサイトについて何かしら情報（削除方法や経験談など）をお持ちでしたら、情報提供お願いします。</div>
		<div class="date"></div>
	</div>
<?php else: ?>
	<?php
		foreach( $msg as $val ){

			$text = str_replace( array( "\r\n","\r","\n"), '<br />', $val['Forum']['text'] );

			echo '<div class="report">'."\n";
			echo '<div class="text">'. $text .'</div>'."\n";

			if( $user['User']['user_uid'] === $val['Forum']['user_uid'] ){
				echo '<div class="tag"><img class="delete" uid="'. $val['Forum']['forum_uid'] .'" mode="'.$mode.'" sid="'.$site_id.'" src="/img/delete.png" alt="削除" width="16" height="16" /></div>'."\n";
			}
			echo '<div class="date">'. date("Y.m.d",strtotime($val['Forum']['created'])) .'</div>'."\n";
			echo '</div>'."\n";
		}
	?>
<?php endif; ?>
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
