<?php
	$html->css(array('common', 'howto') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', 'ご利用の流れ - 物見インフォ');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_public'); ?>
<!--header end-->

<div id="main">

<div class="outer">
<div class="navi">
	<ul>
	<li class="right"><a href="/users/login/">ログイン</a></li>
	<li><a href="/">TOP</a></li>
	</ul>
<!-- / .navi --></div>

<div class="inner">
	<div class="intro">
		<img class="h2gif"src="/img/h2howto.gif" alt="about" />
		<div class="sp_s"></div>

		<div id="trial"></div>
		<div class="hwtitle">
			<h3>体験版ご利用の流れ</h3>
		</div>
		<p class="caption">
			体験版のお申込みからご利用の流れを説明します。<br />
			有料版ご利用流れは<a href="/pgs/howto/#standard">こちら</a>をご覧ください。
		</p>

		<div class="howto">
			<div class="post">
				<img src="/img/hw_t01.gif" width="260" height="45"  />
				<p>
					メールアドレスとパスワードをご入力ください。<br />
					体験版のお申込みは<a href="/regist/tregist">こちら</a>です。
				</p>
			</div>
			<div class="hw_next"></div>
			<div class="post">
				<img src="/img/hw_t02.gif" width="260" height="45"  />
				<p>
					入力いただいたメールアドレス宛てに確認メールを送信します。<br />
				</p>
			</div>
			<div class="hw_next"></div>
			<div class="post">
				<img src="/img/hw_t03.gif" width="260" height="45"  />
				<p>
					確認メール内のリンクをクリックすると体験版のお申込み完了です。
				</p>
			</div>
			<div class="hw_next"></div>
			<div class="post">
				<img src="/img/hw_t04.gif" width="260" height="45"  />
				<p>
					<a href="/users/login/">こちら</a>よりログインしてください。
				</p>
			</div>
			<div class="hw_next"></div>
			<div class="post">
				<img src="/img/hw_t05.gif" width="260" height="45"  />
				<p>
					お客様の作品名や制作団体名などを入力してください。<br />
					入力いただいたワードをもとに、監視を行ないます。<br />
					<span class="red">体験版は監視ワードを１つだけ設定いただけます。</span>
				</p>
			</div>
			<div class="hw_next"></div>
			<div class="post">
				<img src="/img/hw_t06.gif" width="260" height="45"  />
				<p>
					１日最大24回、不正サイトを監視します。<br />
					監視ワードを含む不正サイトが見つかり次第、メール報告します。<br />
					<span class="red">体験版は見つかった不正サイトの一部のみを報告・表示します。</span>
				</p>
			</div>
			<div class="hw_next"></div>
			<div class="post">
				<img src="/img/hw_t07.gif" width="260" height="45"  />
				<p>
					無断アップロードを確認後、お客様から削除申請を行なってください。<br />
					各不正サイトに応じて、有効な削除申請アプローチを案内します。<br />
					<span class="red">体験版は削除申請の一部機能がご利用いただけません。</span>
				</p>
			</div>
		</div>

		<div class="sp_s"></div>
		<p class="toplink">
			<a href="/" class="top">TOPへ戻る</a>
		</p>
		<div class="sp_l"></div>



		<div id="standard"></div>
		<div class="hwtitle">
			<h3>有料版ご利用の流れ</h3>
		</div>
		<p class="caption">
			有料版お申込みからご利用の流れを説明します。<br />
			体験版ご利用流れは<a href="/pgs/howto/#trial">こちら</a>をご覧ください。
		</p>

		<div class="howto">
			<div class="post">
				<img src="/img/hw_s1.gif" width="260" height="45"  />
				<p>
					過去に有料版をご利用になった方は<a href="/users/login/">ログイン</a>→アカウント情報から<br />お申込みください。<br />
					はじめて物見infoをご利用になる方は<a href="/regist/form">こちら</a>。
				</p>
			</div>
			<div class="hw_nextd"></div>
			<div class="post">
				<img class="dla" src="/img/hw_s2a.gif" width="130" height="45"  />
				<img src="/img/hw_s2b.gif" width="110" height="45"  />
				<p>

				</p>
			</div>
			<div class="hw_nextd"></div>
			<div class="post">
				<img src="/img/hw_s3.gif" width="260" height="45"  />
				<p>
					監視ワード数を選択してください（<a href="/pgs/faq/#num9">オススメのワード数</a>）<br />
					招待コードをお持ちの方は招待キャンペーンプランを選択してください。
				</p>
			</div>
			<div class="hw_next"></div>
			<div class="post">
				<img src="/img/hw_s4.gif" width="260" height="45"  />
				<p>
					世界中でご利用されているPaypal決済を用意しました。<br />
					Paypalアカウントをお持ちでなくても対応のクレジットカードでご決済<br />いただけます。
				</p>
			</div>
			<div class="hw_next"></div>
			<div class="post">
				<img src="/img/hw_t03.gif" width="260" height="45"  />
				<p>
					有料版のお申込みが完了です。
				</p>
			</div>
			<div class="hw_next"></div>
			<div class="post">
				<img src="/img/hw_t04.gif" width="260" height="45"  />
				<p>
					<a href="/users/login/">こちら</a>よりログインしてください。
				</p>
			</div>
			<div class="hw_next"></div>
			<div class="post">
				<img src="/img/hw_t05.gif" width="260" height="45"  />
				<p>
					お客様の作品名や制作団体名などを入力してください。<br />
					入力いただいたワードをもとに、監視を行ないます。
				</p>
			</div>
			<div class="hw_next"></div>
			<div class="post">
				<img src="/img/hw_t06.gif" width="260" height="45"  />
				<p>
					１日最大24回、不正サイトを監視します。<br />
					監視ワードを含む不正サイトが見つかり次第、メール報告します。
				</p>
			</div>
			<div class="hw_next"></div>
			<div class="post">
				<img src="/img/hw_t07.gif" width="260" height="45"  />
				<p>
					無断アップロードを確認後、お客様から削除申請を行なってください。<br />
					各不正サイトに応じて、有効な削除申請アプローチを案内します。
				</p>
			</div>		</div>

		<div class="sp_s"></div>
		<p class="toplink">
			<a href="/" class="top">TOPへ戻る</a>
		</p>
		<div class="sp_l"></div>

	</div>
<!-- / .inner --></div>

	<div class="sp_s"></div>

<!-- / .outer --></div>
<!-- / #main --></div>



<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
