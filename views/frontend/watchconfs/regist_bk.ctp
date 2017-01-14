<?php
	$html->css(array('private_default', 'watchconfs') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min', 'jquery/jquery.form', 'jquery/plugin/jquery.corner', 'uploadify'), array('inline'=>false));

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
		<?php echo $form->create(null,array('name'=>'uploadForm','id'=>'uploadForm','type'=>'file'));?>

				<div id="form_block">
					<div class="title">
						<p><label>作品名（日本語）</label><input type="text" /></p>
						<p><label>作品名（英語表記）</label><input type="text" /></p>
					</div>
					<div class="image_block">

<?php
	echo $form->input( 'upload_file', array( 'label'=>'Upload Text File ', 'type'=>'file' ));
	echo $form->button('アップロード',array('onClick'=>'$(\'#uploadForm\').ajaxSubmit({target: \'#uploadFile\',url: \'/watchconfs/upload\'}); return false;'));
?>
<div id="uploadFile"></div>

					</div>
				</div>
				<div id="submit_block">
					<input type="submit" id="submit_btn" value="登録する">
				</div>
			<?php echo $form->end(); ?>
		</div><!-- right_contents -->
		<div class="dummy_block"></div>
	</div><!-- div#outline -->
</div>
<div id="footer_block">
<?php echo $this->element('footer_block'); ?>
</div>
</body>
