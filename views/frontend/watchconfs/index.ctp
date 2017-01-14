<?php
	$html->css(array('private_default', 'watchconfs', 'jquery.confirm') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min', 'jquery/plugin/jquery.corner', 'jquery/plugin/jquery.confirm', 'frontend/watchconfs'), array('inline'=>false));

	AppController::set('title_for_layout', 'ACURS(エクルス)：監視設定');
?>
<body id="m2">
<div id="header_block">
<?php echo $this->element('header_block/header_private', array('breadcrumbs'=>'監視設定')); ?>
</div>
<div id="contents_block">
	<div id="outline">
		<div id="left_menu">
<?php echo $this->element('contents_block/left_menu'); ?>
		</div><!-- div#left_menu -->
		<div id="right_contents">
			<p class="contents_title">監視設定</p>
			<div class="add_block">
				<a class="corner" href="/watchconfs/regist/">作品を追加する</a>
			</div>
			<div class="info_block">
				監視中<span><?php echo $status['count']['continue'] + $status['count']['continue_cancel']; ?></span>作品
			</div>
			<div class="info_block">
				契約数<span><?php echo $license[0]['License']['licenses_works']; ?></span>本
			</div>
			<div class="info_block">
				登録数<span><?php echo $status['count']['regist']; ?></span>作品
			</div>			<div class="block_header">
				<p>作品リスト</p>
			</div>
			<table>
<?php
	foreach( $view as $rc ){
		$element = 'watchconfs/index/' . $rc['Work']['license_status'];		// 作品のlicense_statusによって、適用するelementを設定

		echo $html->tableCells(
			array( $this->element( $element, array( 'rc'=>$rc, 'license'=>$license, 'status'=>$status ) ) ),
			array( 'class'=>'odd' ),
			array( 'class'=>'even' ),
			false
		);
	}
?>
			</table>
			<div class="dummy_block"></div>
		</div><!-- right_contents -->
		<div class="dummy_block"></div>
	</div><!-- div#outline -->
</div>
<div id="footer_block">
<?php echo $this->element('footer_block/footer'); ?>
</div>
</body>
