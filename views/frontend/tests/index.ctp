
<div id="contents_block">
<a href="http://lswan.com/">http://lswan.com/</a><br />
<a href="http://lswan.com/homes/">http://lswan.com/homes/</a><br />
<a href="http://lswan.com/translates/index">http://lswan.com/translates/index</a><br />
<a href="http://lswan.com/translates/regist">http://lswan.com/translates/regist</a><br />
<a href="http://lswan.com/translates/confirm">http://lswan.com/translates/confirm</a><br />
<a href="http://lswan.com/translates/check_regist">http://lswan.com/translates/check_regist</a><br />
<a href="http://lswan.com/translates/check_confirm">http://lswan.com/translates/check_confirm</a><br />
<a href="http://lswan.com/results">http://lswan.com/results/</a><br />
<a href="http://lswan.com/users/profile_regist">http://lswan.com/users/profile_regist</a><br />
<a href="http://lswan.com/users/first_evaluate">http://lswan.com/users/first_evaluate</a><br />
<a href="http://lswan.com/users/quit">http://lswan.com/users/quitt</a><br />
<a href="http://lswan.com/users/reissue">http://lswan.com/users/reissue</a><br />
<a href="http://lswan.com/messages/send">http://lswan.com/messages/send</a><br />
<a href="http://lswan.com/messages/received">http://lswan.com/messages/received</a><br />
<a href="http://lswan.com/tutor/homes/">http://lswan.com/tutor/homes/</a><br />
<a href="http://lswan.com/evaluations/index">http://lswan.com/evaluations/index</a><br />
<a href="http://lswan.com/evaluations/regist">http://lswan.com/evaluations/regist</a><br />
<a href="http://lswan.com/users/add/">http://lswan.com/users/add/</a><br />
<a href="http://lswan.com/users/logout/">http://lswan.com/users/logout/</a><br />
<a href="http://lswan.com/admin/users/login">http://lswan.com/admin/users/login/</a><br />
<a href="http://lswan.com/admin/homes">http://lswan.com/admin/homes</a><br />

<?php echo $form->create('Test', array('action' => 'index')); ?>
<div class="outline" align="center">

	<div class="inline">
		<p>
		<h2><span>一次チェック文字列</span><?php if (!empty($mb_str_typeA_len)): ?>&nbsp;<span>文字数：<?php echo $mb_str_typeA_len; ?></span><?php endif; ?></h2>
		<?php echo $form->textarea('str_typeA', array('style'=>'width:600px;height:100px;')); ?>
		</p>
		<br />
		<p>
		<h2><span>二次チェック文字列</span><?php if (!empty($mb_str_typeB_len)): ?>&nbsp;<span>文字数：<?php echo $mb_str_typeB_len; ?></span><?php endif; ?></h2>
		<?php echo $form->textarea('str_typeB', array('style'=>'width:600px;height:100px;')); ?>
		</p>
		<br />
		<br />
		<?php if (!empty($str_A) && !empty($str_B)): ?>
		<p>
		<h1>levenshtein(マルチバイト対応、改良型)</h1>
		<h2 style="color:red;"><span>近似率：<?php echo $mb_percent; ?>%</span>&nbsp;<span>編集距離：<?php echo $mb_distance; ?></span>&nbsp;<span>重複文字数：<?php echo $mb_count_same_letter; ?></span></h2>
		<div style="text-align:left;background-color:#FFE3E3;width:600px;height:100px;"><?php echo $str_A; ?></div>
		<div style="text-align:left;background-color:#E3E3FF;width:600px;height:100px;"><?php echo $str_B; ?></div>
		</p>
		<?php endif; ?>
		<br />
		<?php if (!empty($str_A) && !empty($str_B)): ?>
		<p>
		<h1>levenshtein(マルチバイト非対応、PHP関数)</h1>
		<h2 style="color:red;"><span>近似率：<?php echo $percent; ?>%</span>&nbsp;<span>編集距離：<?php echo $distance; ?></span></h2>
		<div style="text-align:left;background-color:#FFE3E3;width:600px;height:100px;"><?php echo $str_A; ?></div>
		<div style="text-align:left;background-color:#E3E3FF;width:600px;height:100px;"><?php echo $str_B; ?></div>
		</p>
		<?php endif; ?>
		<br />
		<?php if (!empty($str_A) && !empty($str_B)): ?>
		<p>
		<h1>similar_text(マルチバイト非対応、PHP関数)</h1>
		<h2 style="color:red;"><span>近似率：<?php echo $similar_text_pct; ?>%</span></h2>
		<div style="text-align:left;background-color:#FFE3E3;width:600px;height:100px;"><?php echo $str_A; ?></div>
		<div style="text-align:left;background-color:#E3E3FF;width:600px;height:100px;"><?php echo $str_B; ?></div>
		</p>
		<?php endif; ?>
	</div>
</div>
<?php echo $form->end('Index'); ?>
</div>


