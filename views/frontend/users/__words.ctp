<?php
	$html->css(array('user_common', 'user_words', 'jquery.confirm.css') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min','frontend/words', 'jquery/plugin/jquery.confirm'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', 'モノミインフォ-監視ワード-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>監視ワード</h1>
	<div class="help">
		<a href="/users/help/#num5">help</a>
	</div>
</div>

<div id="main">
<div class="inner">
<?php echo $form->create( null ); ?>
<?php
	for( $i=1; $i<=$word_count; $i++ ){
		echo '<p class="form"><label>ワード ' . $i . '</label>';
//		echo $form->text($i, array('class'=>'textbox', 'word'=>$this->data['User'][$i]));
		$key = 'word' . $i;
		echo $form->text( $key, array('class'=>'textbox', 'word'=>$this->data['User'][$key]));
		echo '</p>'."\n";
	}

?>

<?php echo $form->hidden('WordCnt', array('value'=>$word_count)); ?>


<!--
<input type="submit" class="submit_btn" />
-->
<div class="submit">登録</div>

<?php if( isset( $regist_flag ) ): ?>
<div class="message">登録しました</div>
<?php endif; ?>


<?php echo $form->end(); ?>

<div class="space"></div>
<div class="notice">
監視ワードには、作品名やサークル・メーカー名を登録するのが一般的ですが、下記にご注意ください。<br />
<br />
・モノミインフォの監視は完全一致検出なため、作品名の文字数が多いと検出できないケースがあります。<br />
　作品名の一部のみを登録することで検出率を高めることが可能です。<br /><br />
・監視ワードはご契約期間中であれば何度でも変更いただけます。<br />
　ただし、変更前の監視ワードで検出されたデータは失われます。<br /><br />
・例えばDLsiteで委託販売されている場合、その販売サイト上の作品IDを監視ワードとするのも有効です。<br /><br />
・日本語の作品名を掲載せず、ローマ字や英訳のみの不正サイトも稀にあります。<br />

</div>


<!-- / .inner --></div>
<!-- / #main --></div>

<div id="sub">
	<ul>
	<li><a href="/users/dashboard/">ダッシュボード</a></li>
	<li><a href="/users/inbox/">監視トレイ</a></li>
	<li class="crt"><a href="/users/words/">監視ワード</a></li>
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
