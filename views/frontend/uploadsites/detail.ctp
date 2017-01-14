<?php
	$html->css(array('private_default', 'uploadsites_detail') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min', 'jquery/plugin/jquery.corner', 'frontend/uploadsites'), array('inline'=>false));

	AppController::set('title_for_layout', 'ACURS(エクルス)：巡回サイト詳細');
?>
<body id="m3">
<div id="header_block">
<?php echo $this->element('header_block/header_private', array('breadcrumbs'=>'巡回サイト　＞　巡回サイト詳細')); ?>
</div>
<div id="contents_block">
	<div id="outline">
		<div id="left_menu">
<?php echo $this->element('contents_block/left_menu'); ?>
		</div><!-- div#left_menu -->
		<div id="right_contents">
			<p class="contents_title"><?php echo $view['Site']['site_name'] ?></p>
			<p><?php echo $html->link( $view['Site']['site_url'] ) ?></p>
			<div id="thumbnail_block">
				<a href="<?php echo $view['Site']['site_url'] ?>"><img title="<?php echo $view['Site']['site_name'] ?>" src="http://capture.heartrails.com/medium?<?php echo $view['Site']['site_url'] ?>" alt="<?php echo $view['Site']['site_url'] ?>" width="200" height="150" /></a>
			</div>
			<div id="results_block">
				<div class="block_header">
					<p>検知実績</p>
				</div>
				<div id="results_left">
					<span>1</span>位/52サイト　検知数：123,456
				</div>
				<div id="results_right">
					<p><span>3</span>位/52サイト　作品名検知数：123,456</p>
					<p><span>2</span>位/52サイト　画像検知数：123,456</p>
				</div>
			</div>
			<div class="dummy_block"></div>
			<div id="delete_block">
				<div class="block_header">
					<p>削除要請</p>
				</div>
				<p class="delete_contents">
					<?php echo $view['Site']['site_info'] ?>
				</p>
				<div id="mail_block">
					削除要請の連絡先
					<div id="mail_contents">
						<a href="mailto:abuse@hotfile.com">abuse@hotfile.com</a>
					</div>
					<div id="template_dl_block">
						<a class="corner" href="">削除要請テンプレートをダウンロード</a>
					</div>
				</div>
			</div>
			<div id="forum_block">
				<div class="block_header">
					<p>削除要請の対応情報</p>
				</div>
				<p class="forum_info">
					違法アップロードサイト撲滅のため、削除要請の仕方や対応レスポンスなど、「  HongFire.com アニメネットワーク 」に関する情報をお寄せください。<br/>
					ご投稿いただいた情報は、今後の対策に役立てさせて頂きます。
				</p>
				<form>
					<div id="forum_left">
						<p class="textarea_caption">コメント（500文字以内）</p>
						<p><textarea></textarea></p>
					</div>
					<div id="forum_right">
						<p><input type="radio" name="category" value="public" id="public" checked /><label for="public">投稿コメントをご契約ユーザーに公開する</label></p>
						<p><input type="radio" name="category" value="private" id="private" /><label for="private">ACURS事務局だけに提供する</label></p>
						<p><input id="submit_btn" type="submit" id="submit_btn" value="送信"></p>
					</div>
				</form>
				<div class="dummyshort_block"></div>
				<div id="bbs_block">
					<p>xxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>
					<hr/><p>xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>
					<hr/><p>xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>
				</div>
			</div>
		</div><!-- right_contents -->
		<div class="dummy_block"></div>
	</div><!-- div#outline -->
</div>
<div id="footer_block">
<?php echo $this->element('footer_block/footer'); ?>
</div>
</body>
