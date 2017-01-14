<div class="work_left">
	<div class="title">
		<p class="date">登録日：　<?php echo date( 'Y/m/d', strtotime($rc['Work']['created'])); ?></p>
		<p class="work"><?php echo $rc['Work']['work_name_jpn']; ?></p>
<?php if( empty( $rc['Work']['work_name_eng'] ) ): ?>
		<p class="work_blank">&nbsp;</p>
<?php else: ?>
		<p class="work"><?php echo $rc['Work']['work_name_eng']; ?></p>
<?php endif; ?>
		<p class="image">登録イメージ枚数：　<?php echo $rc[0]['num']; ?>枚</p>
		<div class="link"><a class="corner" href="/watchconfs/detail/?id=<?php echo $rc['Work']['work_uid']; ?>">詳細</a></div>
		<div class="link"><span class="change corner">変更</span></div>
		<div class="link note">変更は「停止」状態でのみ行なえます</div>
	</div>
</div>
<div class="work_right">
	<div class="status">
		<p>
			<span>巡回監視中</span>
			<?php echo date( 'Y/m/d', strtotime( $license[1]['License']['start_date'] )); ?>以降も巡回します
		</p>
	</div>
	<div class="link"><a class="corner" href="/watchconfs/?mode=continue_cancel&id=<?php echo $rc['Work']['work_uid']; ?>">次の更新日に監視を停止</a></div>
</div>