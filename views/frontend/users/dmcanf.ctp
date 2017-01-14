<?php
	$html->css(array('user_common', 'user_dmcanf') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min', 'frontend/dmca'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ-アップローダ選択-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>アップローダ選択</h1>
	<div class="help">
		<a href="/users/help/#num6">help</a>
	</div>
</div>

<div id="main">
<div class="inner">

<h3>削除申請を送るアップローダを選択してください</h3>
<div class="clear"></div>

<div class="nf_block">
<p>
	物見infoは誤検知を回避するため、アップローダの特定を見送りました。<br />
	恐れ入りますが、掲載サイトをご確認の上、削除申請先のアップローダを選択してください。<br />
	<br />
	掲載サイト（不正サイトに足跡がつかないようanonym.toを経由します）：<br />
	<a href="http://anonym.to/?<?php echo $lr['IllegalResult']['illegal_url']; ?>" target="_blank"><?php echo $lr['IllegalResult']['illegal_url']; ?></a>
</p>


<p>
<?php echo $form->create( null, array('type'=>'get', 'action'=>'dmca') ); ?>
<label>アップローダ：　</label>
<select id="dsuid" name="dsuid">
	<?php
		foreach( $ds as $ds ){
			echo '<option name="dsuid" value="'. $ds['DownloadSite']['download_site_uid'] .'">'. $ds['DownloadSite']['site_name'] .'</option>';
		}
	?>
</select>
<?php echo $form->hidden('iruid', array('error'=>false, 'value'=>$lr['IllegalResult']['illegal_result_uid']) ); ?>
<?php echo $form->hidden('worduid', array('error'=>false, 'value'=>$word_uid) ); ?>
<?php echo $form->hidden('bl', array('error'=>false, 'value'=>$backlink) ); ?>

</p>

<ul>
<li><?php echo $form->submit('次へ', array('class'=>'submit_btn')); ?></li>
<li><a class="s04" href="<?php echo $backlink; ?>">戻る</a></li>
</ul>
<?php echo $form->end(); ?>
</div><!-- div.nf_block -->

<div class="clear"></div>

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
