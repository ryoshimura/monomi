<?php
	$html->css(array('user_common', 'user_words', 'jquery.confirm.css') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min','frontend/words', 'jquery/plugin/jquery.confirm'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ-監視ワード-');
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
		<a href="/users/help/#num7">help</a>
	</div>
</div>

<div id="main">
<div class="inner">
	<p class="info">
		監視したいワードを入力して、登録ボタンをクリックしてください。<br />
		作品名やサークル・ブランド名を設定するのが効果的です。<br />
		※ サークル・ブランド名を設定した際は、作品名・作品URLを入力する必要はありません。<br /><br />
		その他、入力の注意点は<a href="/users/words/#caution">こちら</a>をご覧ください。
	</p>

<?php echo $form->create( null ); ?>
<?php
	for( $i=1; $i<=$word_count; $i++ ){
		$keySW = 'word' . $i;
		$keyWK = 'work_name' . $i;
		$keyUJ = 'work_url_jp' . $i;
		$keyUE = 'work_url_en' . $i;
		$rqpos = 'rqpos' . $i;
		$wkpos = 'reqbox wkpos' . $i;
		$ujpos = 'reqbox ujpos' . $i;
		$uepos = 'reqbox uepos' . $i;

		echo '<div class="word_block">';
		echo '<div class="left">ワード '. $i .'</div>';
		echo '<div class="right">';
		echo $form->text( $keySW, array('class'=>'wordbox', 'word'=>$this->data['User'][$keySW], 'pos'=>$i));
		echo '<div class="request '. $rqpos .'">';
		echo '<p class="rqinfo">【任意】 このワードで検出されたサイトへの削除申請で使用する情報を入力してください</p>';
		echo '<p class="rq"><label>作品名</label>';
//		echo $form->text( $keyWK, array('class'=>'reqbox', 'word'=>$this->data['User'][$keyWK]));
		echo $form->text( $keyWK, array('class'=>$wkpos));
		echo '</p>';
		echo '<p class="rq"><label>作品URL(国内向け)</label>';
//		echo $form->text( $keyUJ, array('class'=>'reqbox', 'word'=>$this->data['User'][$keyUJ]));
		echo $form->text( $keyUJ, array('class'=>$ujpos));
		echo '</p>';
		echo '<p class="rq"><label>作品URL(国外向け)</label>';
//		echo $form->text( $keyUE, array('class'=>'reqbox', 'word'=>$this->data['User'][$keyUE]));
		echo $form->text( $keyUE, array('class'=>$uepos));
		echo '</p>';
		echo '</div>';
		echo '</div>';
		echo '<div class="sp_wb"></div>';
		echo '</div>';

	}

?>

<!--
<div class="word_block">
	<div class="left">ワード 1</div>
	<div class="right">
		<?php echo $form->text( $key, array('class'=>'wordbox', 'word'=>$this->data['User'][$key])); ?>
		<div class="request">
			<p class="rqinfo">【任意】 このワードで検出されたサイトへの削除申請で使用する情報を入力してください</p>
			<p class="rq"><label>作品名</label><?php echo $form->text( $key, array('class'=>'reqbox', 'word'=>$this->data['User'][$key])); ?></p>
			<p class="rq"><label>作品URL(国内向け)</label><?php echo $form->text( $key, array('class'=>'reqbox', 'word'=>$this->data['User'][$key])); ?></p>
			<p class="rq"><label>作品URL(国外向け)</label><?php echo $form->text( $key, array('class'=>'reqbox', 'word'=>$this->data['User'][$key])); ?></p>
		</div>
	</div>
	<div class="sp_wb"></div>
</div>
-->

<?php echo $form->hidden('WordCnt', array('value'=>$word_count)); ?>



<div id="submit_block">
	<div class="submit">登録</div>
	<?php if( isset( $regist_flag ) ): ?>
	<div class="message">登録しました</div>
	<?php endif; ?>
</div>

<?php echo $form->end(); ?>

<div class="space"></div>
<div id="caution"></div>
<div class="notice">
<h2>監視ワードご入力時の注意点</h2><br />
・物見インフォの監視は完全一致検出なため、作品名の文字数が多いと検出できないケースがあります。<br />
　作品名の一部のみを登録することで検出率を高めることが可能です。<br /><br />
・文字数が著しく少ない又は類似ワードが多数存在する場合、正確に検出できない場合があります。<br />
　半角アルファベット又は数字のみの場合、3文字以上を設定するようお願いします。<br />
　例）サークル名がアルファベット2文字の場合、[サークル名] といったように [ ] でくくってください。<br /><br />
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
