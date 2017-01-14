<?php
	$html->css(array('private_default', 'watchconfs') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min', 'jquery/jquery.form', 'jquery/plugin/jquery.corner', 'frontend/watchconfs_regist',
	'swfupload/swfupload',
	'swfupload/swfupload.queue',
	'swfupload/fileprogress',
	'swfupload/handlers'), array('inline'=>false));

	AppController::set('title_for_layout', 'ACURS(エクルス)：作品登録');
?>
<body id="m2">
<div id="header_block">
<?php echo $this->element('header_block/header_private', array('breadcrumbs'=>'作品登録')); ?>
</div>
<div id="contents_block">
	<div id="outline">
		<div id="left_menu">
<?php echo $this->element('contents_block/left_menu'); ?>
		</div><!-- div#left_menu -->
		<div id="right_contents">
			<p class="contents_title">作品を登録してください</p>
			<?php echo $form->create(null, array('type'=>'post', 'url'=>array('controller'=>'watchconfs', 'action'=>'regist'))); ?>
				<?php echo $form->hidden('Work.work_uid', array('value'=>$worksid)); ?>
				<div id="form_block">
					<div class="title">
						<p>
							<label class="imageDelete">作品名（日本語）</label><?php echo $form->text('Work.work_name_jpn'); ?>
							<br/><span class="validate"><?php echo $form->error('Work.work_name_jpn'); ?></span>
						</p>
						<p>
							<label>作品名（英語表記）</label><?php echo $form->text('Work.work_name_eng'); ?>
							<br/><span class="label_caption">※作品が英語表記のみの場合は、「作品名（日本語）」に入力してください</span>
							<input type="text" name="dummy" style="position:absolute;visibility:hidden;">
						</p>
					</div>
				</div>

				<div class="image_block">
					<div id="file_dlg_block">
						<span id="spanButtonPlaceHolder"></span>
						<p>１つのイメージファイルにつき、データサイズは○○MBまでとなります</p>
						<p>複数のファイルをまとめて選択できます</p>
					</div>
					<div class="fieldset flash clearfix" id="fsUploadProgress">
<!--
<div class="progressWrapper" id="SWFUpload_0_0" style="opacity: 1;"><div class="progressContainer blue"><a class="progressCancel" href="#" style="visibility: hidden;"> </a><div class="progressName">acurs.png</div><div class="progressBarComplete" style=""></div><div class="progressThumbnail"><img height="114" width="138" style="text-align: center;" src="http://acurs.jp/upload/4d477ddc-dff8-4bc9-8a0b-0dfc61c44b3f_acurs.png"></div><div><input type="hidden" value="4d477ddc-dff8-4bc9-8a0b-0dfc61c44b3f_acurs.png" name="data[Work][4d477ddc-dff8-4bc9-8a0b-0dfc61c44b3f_acurs.png]"></div><span class="imageDelete"> </span></div></div>
<div class="progressWrapper" id="SWFUpload_0_1" style="opacity: 1;"><div class="progressContainer blue"><a class="progressCancel" href="#" style="visibility: hidden;"> </a><div class="progressName">acurs.png</div><div class="progressBarComplete" style=""></div><div class="progressThumbnail"><img height="114" width="138" style="text-align: center;" src="http://acurs.jp/upload/4d477ddc-dff8-4bc9-8a0b-0dfc61c44b3f_acurs.png"></div><div><input type="hidden" value="4d477ddc-dff8-4bc9-8a0b-0dfc61c44b3f_acurs.png" name="data[Work][4d477ddc-dff8-4bc9-8a0b-0dfc61c44b3f_acurs.png]"></div></div></div>
-->
					</div>
				</div>

				<div class="dummyshort_block"></div>
				<div id="submit_block">
<!--					<input type="submit" id="submit_btn" value="登録する">	-->
					<input type="button" id="submit_btn" value="登録する" onClick="submit();" >
				</div>
			<?php echo $form->end(); ?>
		</div><!-- right_contents -->
		<div class="dummy_block"></div>




	</div><!-- div#outline -->
</div>
<div id="footer_block">
<?php echo $this->element('footer_block/footer'); ?>
</div>
</body>
