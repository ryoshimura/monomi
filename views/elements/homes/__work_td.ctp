<?php
	$vendor = array( 'dlsite' => 'none', 'dmm' => 'none' );

	foreach( $work['Regist'] as $key=>$val ){
		if( $val['vendor_uid'] == 0 ){
			$vendor['dlsite'] = $key;
		}
		else if( $val['vendor_uid'] == 1 ){
			$vendor['dmm'] = $key;
		}
	}

	if( $vendor['dlsite'] !== 'none' ){		// サムネイル画像はDLsiteを優先使用
		$img_url = $work['Regist'][$vendor['dlsite']]['thumbnail_url'];
		$introduction = $work['Regist'][$vendor['dlsite']]['introduction_short'];
	} else {
		$img_url = $work['Regist'][$vendor['dmm']]['thumbnail_url'];
		$introduction = mb_substr( strip_tags( $work['Regist'][$vendor['dmm']]['introduction']), 0, 70 ) . '...';
	}
?>

<td class="works">
	<p class="work_name"><?php echo $work['Work']['work_name']; ?></p>
	<table><tbody>
		<tr>
		<td rowspan="2" class="img"><img src="<?php echo $img_url; ?>"></td>
		<td class="info">
			<p class="circle_name"><span><?php echo $work['Work']['circle_name']; ?></span></p>
			<p class="introduction"><?php echo $introduction; ?></p>
		</td>
		</tr>
		<tr><td class="link">
<?php if( $vendor['dlsite'] !== 'none' ): ?>
			<div class="link_dlsite">
				<p class="date">登録日：&nbsp;<?php echo ereg_replace( '-', '/', $work['Regist'][$vendor['dlsite']]['regist_date'] ); ?></p>
				<p><a href="<?php echo $work['Regist'][$vendor['dlsite']]['original_url']; ?>" target="_blank" class="c_link">&nbsp;<span class="price"><span class="num">DLsite</span>&nbsp;</span></a></p>
			</div>
<?php endif; ?>
<?php if( $vendor['dmm'] !== 'none' ): ?>
			<div class="link_dmm">
				<p class="date">登録日：&nbsp;<?php echo ereg_replace( '-', '/', $work['Regist'][$vendor['dmm']]['regist_date'] ); ?></p>
				<p><a href="<?php echo $work['Regist'][$vendor['dmm']]['original_url']; ?>" target="_blank" class="c_link">&nbsp;<span class="price"><span class="num">DMM</span>&nbsp;</span></a></p>
			</div>
<?php endif; ?>
		</td></tr>
	</tbody></table>
</td>