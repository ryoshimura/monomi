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
		<div class="link"><a class="corner" href="/watchconfs/detail/?id=<?php echo $rc['Work']['work_uid'] ?>">詳細</a></div>
		<div class="link"><a class="corner" href="/watchconfs/regist/?id=<?php echo $rc['Work']['work_uid'] ?>">変更</a></div>
		<div class="link note">変更は「停止」状態でのみ行なえます</div>
	</div>
</div>
<div class="work_right">
	<div class="status">
		<p>
			<span>停止</span>
			巡回監視を停止してます
		</p>
	</div>
<?php if( ($status['count']['continue'] + $status['count']['continue_cancel']) < $license[0]['License']['licenses_works'] ): ?>
<!--	<div class="link"><a class="corner" href="/watchconfs/?mode=continue&id=<?php echo $rc['Work']['work_uid']; ?>">巡回監視を開始</a></div> -->
	<div class="link"><span class="start corner" url="/watchconfs/?mode=continue&id=<?php echo $rc['Work']['work_uid']; ?>">巡回監視を開始</span></div>
<?php else: ?>
	<span class="over_caption">ご契約本数分が全て監視中です</span>
	<div class="link_over"><span class="corner over">巡回監視を開始</span></div>
<?php endif; ?>
</div>