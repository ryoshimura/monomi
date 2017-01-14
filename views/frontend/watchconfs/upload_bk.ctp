<?php header("Content-Type: text/html;charset=UTF-8"); ?>
<?php if (!empty($error)): ?>
<p><?php echo $error;?></p>
<?php else: ?>
<p>画像をアップロードしました。</p>
<?php echo $html->image($file_path); ?>
<img src="<?php echo $file_path?>">
<?php echo $form->input('Work.image', array('type' => 'text', 'label' => '画像パス', 'value' => $file_path)); ?>
<?php endif; ?>