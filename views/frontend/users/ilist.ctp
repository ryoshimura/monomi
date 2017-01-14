<?php
	$html->css(array('user_common', 'user_list') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ-監視サイト-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>監視サイト</h1>
	<div class="help">
		<a href="/users/help/#num8">help</a>
	</div>
</div>

<div id="main">
<div class="inner">

<div class="report">
	<?php if($view_report == true): ?>

	<?php echo $form->create( null ); ?>
	<p>物見infoでは、無断で掲載している不正サイト及びアップローダの情報を広く求めております。</p>
	<p><?php echo $form->textarea('report', array('class'=>'textbox', 'error'=>false)); ?></p>
	<p class="btn"><?php echo $form->submit('報告する', array('class'=>'send_btn')); ?></p>
	<?php echo $form->end(); ?>

	<?php else: ?>
		<p>情報提供ありがとうございます。<br />引き続き物見infoをよろしくお願いします。</p>
	<?php endif; ?>
</div>


<div class="list">
	<p>監視対象数</p>
	<table>
		<tr>
		<td class="item">不正掲載サイト</td>
		<td class="num"><?php echo $cnt['is']; ?></td>
		<td class="item">アップローダ</td>
		<td class="num"><?php echo $cnt['ds']; ?></td>
		</tr>
		<tr>
		<td class="item">新着監視</td>
		<td class="num"><?php echo $cnt['new']; ?></td>
		<td class="item">全件監視</td>
		<td class="num"><?php echo $cnt['all']; ?></td>
		</tr>
	</table>

	<table>
		<tr>
		<td class="item">デジ同人</td>
		<td class="num"><?php echo $cnt['flag_digi_doujin']; ?></td>
		<td class="item">同人誌</td>
		<td class="num"><?php echo $cnt['flag_doujinshi']; ?></td>
		</tr>
		<tr>
		<td class="item">商業コミック</td>
		<td class="num"><?php echo $cnt['flag_comic']; ?></td>
		<td class="item">音楽</td>
		<td class="num"><?php echo $cnt['flag_music']; ?></td>
		</tr>
		<tr>
		<td class="item">商業PCゲーム</td>
		<td class="num"><?php echo $cnt['flag_pc_game']; ?></td>
		<td class="item">コンシューマゲーム</td>
		<td class="num"><?php echo $cnt['flag_tv_game']; ?></td>
		</tr>
		<tr>
		<td class="item">OVA</td>
		<td class="num"><?php echo $cnt['flag_ova']; ?></td>
		<td class="item">TVアニメ</td>
		<td class="num"><?php echo $cnt['flag_anime']; ?></td>
		</tr>
		<tr>
		<td class="item">AV</td>
		<td class="num"><?php echo $cnt['flag_av']; ?></td>
		<td class="item">その他</td>
		<td class="num"><?php echo $cnt['flag_etc']; ?></td>
		</tr>
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
