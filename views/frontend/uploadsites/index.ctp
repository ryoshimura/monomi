<?php
	$html->css(array('private_default', 'uploadsites_index') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min', 'jquery/plugin/jquery.corner', 'frontend/uploadsites'), array('inline'=>false));

	AppController::set('title_for_layout', 'ACURS(エクルス)：巡回サイト');
?>
<body id="m3">
<div id="header_block">
<?php echo $this->element('header_block/header_private', array('breadcrumbs'=>'巡回サイト')); ?>
</div>
<div id="contents_block">
	<div id="outline">
		<div id="left_menu">
<?php echo $this->element('contents_block/left_menu'); ?>
		</div><!-- div#left_menu -->
		<div id="right_contents">
			<p class="contents_title">巡回サイト</p>
			<div class="block_header">
				<p>巡回サイト一覧<span>累計検知数</span></p>
			</div>
			<table>
<?php
	foreach( $view as $record ){

		if ( $license['element'] !== 'unsigned' ) {
			echo $html->tableCells(
						array(
							 $html->link( $record['Site']['site_name'], "/uploadsites/detail/?id=" . $record['Site']['site_uid'] )
							,$record['Site']['total_detect']
							,$html->link( "詳細", "/uploadsites/detail/?id=" . $record['Site']['site_uid'] )
						),
						array( 'class'=>'odd' ),
						array( 'class'=>'even' ),
						true
			);
		} else {
			echo $html->tableCells(
						array(
							 $html->link( $record['Site']['site_name'], "/uploadsites/detail/?id=" . $record['Site']['site_uid'] )
							,$record['Site']['total_detect']
							,''
						),
						array( 'class'=>'odd' ),
						array( 'class'=>'even' ),
						true
			);
		}
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
