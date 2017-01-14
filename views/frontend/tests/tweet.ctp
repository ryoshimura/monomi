
<div id="contents_block">
<?php echo $form->create('Test', array('action' => 'tweet')); ?>
<div class="outline" align="center">
	<div class="inline">
		<p>
		<h2><span>つぶやき</span></h2>
		<?php echo $form->textarea('str_typeA', array('style'=>'width:600px;height:100px;')); ?>
		</p>
	</div>
</div>
<?php echo $form->end('Index'); ?>
</div>

