<div id="header_block">
	<a href="/"><img src="/img/logo.png" width="360" height="150" alt="物見.info" /></a>

	<div class="header_left">
		<h1>物見.info（モノミインフォ） ｜ 不正コンテンツ監視＋削除申請支援サービス</h1>

		<div id="count_block">
			<?php if( '/' === $html->url(null) || 'http://monomi.info/' === $html->url(null, true) ): ?>
			<ul>
				<li class="resultcnt"><?php echo number_format( $cnt['res'] ); ?></li>
				<li class="ilglsite"><?php echo $cnt['ilsite']; ?></li>
			</ul>
			<?php endif; ?>
		</div>

		<div class="hspc"></div>
	</div>

	<div class="hspc"></div>
</div>



