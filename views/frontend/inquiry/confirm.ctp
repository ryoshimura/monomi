<?php
	$html->css(array('common', 'inquiry') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ - お問合せ確認');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_public'); ?>
<!--header end-->

<div id="main">
<div class="outer">
<div class="navi">
	<ul>
	<li class="right"><a href="/users/login/">ログイン</a></li>
	<li><a href="/">TOP</a></li>
	</ul>
<!-- / .navi --></div>

<div class="inner">
	<div class="intro">
		<img class="h2gif" src="/img/h2contact.gif" alt="about" />
		<p class="caption">
入力内容をご確認ください。<br />
内容にお間違えがなければ、「送信する」ボタンをクリックしてください。
		</p>
		<div class="form">
			<?php echo $form->create(null, array('type'=>'post', 'url'=>array('controller'=>'inquiry', 'action'=>'thanks'))); ?>
			<p><label class="individual_element">メールアドレス</label></p>
			<p>
				<?php echo $form->text('Inquiry.d_mail_address', array('class'=>'m', 'value'=>$data['Inquiry']['mail_address'], 'readonly'=>'readonly' )); ?>
				<?php echo $form->hidden('Inquiry.mail_address', array('value'=>$data['Inquiry']['mail_address'])); ?>
			</p>
			<br />
			<p><label class="individual_element">お問合せ内容</label></p>
			<p>
				<?php echo $form->textarea('Inquiry.d_text', array('cols'=>'50', 'rows'=>'7', 'class'=>'keyword', 'value'=>$data['Inquiry']['text'], 'readonly'=>'readonly')); ?>
				<?php echo $form->hidden('Inquiry.text', array('value'=>$data['Inquiry']['text'])); ?>
			</p>
			<br />
			<ul>
			<li><?php echo $form->submit('送信する', array('class'=>'submit_send')); ?></li>
			<li><a class="back" href="#" onClick="history.back(); return false;">戻る</a></li>
			</ul>
			<?php echo $form->end(); ?>
			<p class="back"><a href="/" class="top">TOPへ戻る</a></p>
		</div>
	</div>
<!-- / .inner --></div>

	<div class="sp_s"></div>

<!-- / .outer --></div>
<!-- / #main --></div>



<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
