<?php
	$html->css(array('user_common', 'user_dmca') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min', 'frontend/dmca', 'jquery/plugin/jquery.zclip'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ-削除申請-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>削除申請</h1>
	<div class="help">
		<a href="/users/help/#num5">help</a>
	</div>
</div>

<div id="main">
<div class="inner">

<h3><?php echo $send['site_name']; ?> に対し削除申請を行なってください</h3>
<div class="clear"></div>

<p class="disclaimer">
	免責事項：<br />
	物見infoは、皆さまから寄せられた情報と独自調査結果をもとに、削除申請プランを提案します。<br />
	しかし不正サイトの対応は不透明なため、掲載された作品の削除は保証できません。<br />
	また掲載情報には細心の注意を払っておりますが、この削除申請サービスを用いることで生じた諸問題について物見infoは一切の責任を負わないものとします。<br />
	削除申請は著作権者様ご自身の責任で行なってください。
</p>


<?php if( ($send['to_address_1']==='' || $send['to_address_1']==null)&&($send['contact_url']==='' || $send['contact_url']==null) ): ?>
<div class="notice">
	<p>削除申請の受付窓口を見つけることができませんでした。</p>
</div>
<?php endif; ?>


<div class="step">
	<div class="step_header step1"><h2>あなたの著作物か確認してください</h2></div>
	<div class="content">
		<p class="st1p">不正サイトに足跡がつかないようanonym.toを経由します</p>
		<ul>
			<?php
				echo '<li class="st1">掲載サイト　　：　<a href="http://anonym.to/?'. $list['is']['IR']['illegal_url'] .'" target="_blank">'. $list['is']['IRS']['site_name'] .'</a></li>';

				foreach($list['ds'] as $val){
					echo '<li class="st1">アップローダ　：　<a href="http://anonym.to/?'. $val['DR']['download_result_url'] .'" target="_blank">'. $val['DS']['site_name'] .'</a></li>';
				}
			?>
		</ul>
	</div>
</div>

<div class="step">
	<div class="step_header step2"><h2>文章テンプレートを選択してください</h2></div>
	<div class="content">
		<label class="select">テンプレート</label>
		<select id="templateSelect" name="templateSelect">
			<?php
				foreach( $select as $sl ){
					echo '<option value="'. $sl['Template']['template_uid'] .'">'. $sl['Template']['template_name'] .'</option>';
				}
			?>
		</select>
		<?php if( $send['flag_dmca'] == 1 ): ?>
			<p class="step2"><?php echo $send['site_name']; ?> はDMCAに対応しています</p>
		<?php endif; ?>
	</div>
</div>

<div class="clear"></div>

<div class="step">
	<div class="step_header step3"><h2>削除要請文を確認し、必要に応じて修正してください</h2></div>
	<div class="content">
		<?php echo $form->create( null, array('type'=>'post', 'action'=>'sendcomp') ); ?>
		<p class="step3"><label class="subject">件名</label><?php echo $form->text('Temp.subject', array('class'=>'subjectbox', 'error'=>false)); ?><div id="tmpSubImg" class="s3copy">copy</div></p>
		<p class="step3"><?php echo $form->textarea('Temp.body_text', array('class'=>'textarea', 'error'=>false)); ?></p>
		<p class="step3"><div id="tmpBodyImg" class="s3copyTa">copy</div></p>
		<?php echo $form->hidden('Temp.HiddenUid', array('error'=>false, 'value'=>$uid)); ?>
		<?php echo $form->hidden('Temp.HiddenMode', array('error'=>false, 'value'=>$mode)); ?>
		<?php echo $form->hidden('Temp.HiddenRef', array('error'=>false, 'value'=>$ref)); ?>
		<?php echo $form->hidden('Temp.HiddenAryId', array('error'=>false, 'value'=>$list['hidden'])); ?>
		<?php echo $form->hidden('Temp.HiddenNotFound', array('error'=>false, 'value'=>$send['site_name'])); ?>
		<?php echo $form->hidden('Temp.HiddenIrUid', array('error'=>false, 'value'=>$iruid)); ?>
		<?php echo $form->hidden('Temp.HiddenBackLink', array('error'=>false, 'value'=>$backlink)); ?>

		<?php
			if( isset( $dsuid ) ){
				echo $form->hidden('Temp.HiddenDsUid', array('error'=>false, 'value'=>$dsuid));
			}
			if( isset( $worduid ) ){
				echo $form->hidden('Temp.HiddenWordUid', array('error'=>false, 'value'=>$worduid));
			}
		?>

		<?php if( ($send['to_address_1']==='' || $send['to_address_1']==null)&&($send['contact_url']==='' || $send['contact_url']==null) ): ?>
			<ul class="ulbtnStep3">
				<li class="btn"><a class="back" href="<?php echo $backlink; ?>">戻る</a></li>
			</ul>
		<?php endif; ?>

	</div>
</div>




<div class="step">
	<?php if( $send['to_address_1']!=='' && $send['to_address_1']!=null ): ?>
		<div class="step_header step4"><h2>メール送信情報を確認・修正してください</h2></div>
		<div class="content">
			<p class="s4"><label class="s4label">送信者</label><?php echo $form->text('Temp.sender', array('class'=>'s4input', 'error'=>false, 'value'=>$sender)); ?></p>

			<p class="s4"><label class="s4label">宛先</label><?php echo $form->text('Temp.Destination1', array('class'=>'s4input', 'error'=>false, 'value'=>$send['to_address_1'])); ?><div id="TempDes" class="plus">add</div></p>
			<p class="s4 ndes"><label class="s4label">宛先 2</label><?php echo $form->text('Temp.Destination2', array('class'=>'s4input', 'error'=>false, 'value'=>$send['to_address_2'])); ?></p>
			<p class="s4 ndes"><label class="s4label">宛先 3</label><?php echo $form->text('Temp.Destination3', array('class'=>'s4input', 'error'=>false, 'value'=>$send['to_address_3'])); ?></p>

			<p class="s4"><label class="s4label">CC</label><?php echo $form->text('Temp.CC1', array('class'=>'s4input', 'error'=>false, 'value'=>$send['cc_1'])); ?><div id="TempCC" class="plus">add</div></p>
			<p class="s4 ncc"><label class="s4label">CC 2</label><?php echo $form->text('Temp.CC2', array('class'=>'s4input', 'error'=>false, 'value'=>$send['cc_2'])); ?></p>
			<p class="s4 ncc"><label class="s4label">CC 3</label><?php echo $form->text('Temp.CC3', array('class'=>'s4input', 'error'=>false, 'value'=>$send['cc_3'])); ?></p>

			<p class="s4"><?php echo $form->checkbox('Temp.transfer', array('class'=>'chkbox', 'error'=>false, 'checked'=>'checked')); ?><label class="chkboxlabel">送信者アドレスにも送信する</label></p>

			<?php if( $send['contact_url']!=='' && $send['contact_url']!=null ):	// メール送信先だけでなく申請フォームもある場合 ?>
				<p class="s4noticeForm">
					<?php echo $send['site_name']; ?>はメール窓口の他に、申請フォームがあります。<br />
					併せて削除申請することで対応が早まる可能性があります。<br />
				</p>
				<p class="s4noticeFormLink"><a href="http://anonym.to/?<?php echo $send['contact_url']; ?>" target="_blank"><?php echo $send['contact_url']; ?></a></p>
			<?php endif; ?>

			<?php if( $send['note'] !== '' ): ?>
				<p class="shareNoteSendMail"><?php echo $send['note']; ?></p>
			<?php endif; ?>


		</div>

	<?php elseif( ($send['to_address_1']==='' || $send['to_address_1']==null) && ($send['contact_url']!=='' && $send['contact_url']!=null) ): ?>
		<div class="step_header step4"><h2>不正サイトの問合せフォームから申請してください</h2></div>
		<div class="content">
			<p class="s4notice">下記URLから削除申請してください<br />※不正サイトに足跡がつかないようanonym.toを経由します</p>
			<p class="s4noticeHtml"><a href="http://anonym.to/?<?php echo $send['contact_url']; ?>" target="_blank"><?php echo $send['contact_url']; ?></a></p>
			<?php if( $send['note'] !== '' ): ?>
				<p class="shareNote"><?php echo $send['note']; ?></p>
			<?php endif; ?>
			<ul class="ulbtn">
				<li class="btn"><a class="back" href="<?php echo $backlink; ?>">戻る</a></li>
			</ul>
		</div>
	<?php endif; ?>
</div>

<?php if( $send['to_address_1']!=='' && $send['to_address_1']!=null ): ?>
<div class="step">
	<div class="step_header step5"><h2>内容に問題なければ送信してください</h2></div>
	<div class="content">
	<ul class="ulbtn">
		<li class="btn"><div id="templateSendMail">メール送信</div></li>
		<li class="btn"><a class="back" href="<?php echo $backlink; ?>">戻る</a></li>
	</ul>
	<div id="templateBodyNotice"></div>
	<?php echo $form->end(); ?>
	</div>
</div>
<?php endif; ?>






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
