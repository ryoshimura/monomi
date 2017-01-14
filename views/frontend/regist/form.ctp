<?php
	$html->css(array('common', 'regist_form') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min','frontend/regist'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', 'スタンダード監視プランお申込み');
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

		<img src="/img/pregist.png" alt="新規お申込み" />

		<div class="sp_s"></div>

		<p class="intro">
			初めて物見インフォ有料版をお申込みいただく方向けの申込フォームです。<br />
			過去に有料版をご利用いただいた方は<a href="/users/login/">こちら</a>からログインし「アカウント情報」→「監視開始」からお申込みください。<br />
		</p>

		<div class="caution">
			注意事項：<br />
			・監視したい作品の名称及びサークル・ブランド名が、半角英数で2文字以内もしくは全角1文字の場合、<br />
			　検出精度が著しく低下することから監視ワードとしてご登録いただけません。<br />
			・物見インフォは、ご自身の著作物を監視する目的でのみご利用いただけます。<br />
			　上記以外の目的で使われた場合、別途、著作権保有者確認をさせていただくことがあります。<br />
			・フリーメールでご登録の場合、物見インフォからのお知らせメールが迷惑フォルダに届くことがあります。<br />
		</div>




		<div class="select_payment">
			<h3>決済方法を選択してください</h3>
			<div class="payment_sl paypal border_off">
				<div class="payment_title">
					<img class="paypal" src="/img/checkPoint_off.png" />
					Paypalアカウントをお持ちの方
				</div>
				<div class="logomark_p">
					<!-- PayPal Logo --><table border="0" cellpadding="10" cellspacing="0" align="center"><tr><td align="center"></td></tr><tr><td align="center"><a href="#" onclick="javascript:window.open('https://www.paypal.com/jp/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside','olcwhatispaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=350');"><img  src="https://www.paypal.com/ja_JP/JP/i/bnr/horizontal_solution_4_jcb.gif" border="0" alt="ソリューション画像"></a></td></tr></table><!-- PayPal Logo -->
					<p class="caption">ペイパルアカウントをお持ちでなくても、上記の対応クレジットカードでお支払可能です。</p>
				</div>
			</div>
			<div class="payment_sl epsilon border_off">
				<div class="payment_title">
					<img class="epsilon" src="/img/checkPoint_off.png" />
					その他ご決済を希望の方
				</div>
				<div class="logomark_o">
					<img src="/img/logo_visa.gif" />
					<img src="/img/logo_master.gif" />
					<img src="/img/logo_diners.gif" />
					<img src="/img/logo_japan.gif" />
					<img src="/img/logo_bitcash_square.gif" />
				</div>
			</div>
		</div>


		<div id="sign_block">
		<div class="bread bc_paypal">
			<img src="/img/cb1e.png" alt="申込み" />
			<img src="/img/cb2d.png" alt="決済情報入力" />
			<img src="/img/cb3d.png" alt="申込確認" />
			<img src="/img/cb4d.png" alt="申込完了" />
		</div>
		<div class="bread bc_epsilon">
			<img src="/img/cb1e.png" alt="申込み" />
			<img src="/img/cb2d.png" alt="決済情報入力" />
			<img src="/img/cb3od.png" alt="申込完了" />
		</div>

		<div class="left">
			<div id="left_paypal">
				<p class="caption">
					世界中で利用されているPayPal決済を用意しました。<br />
					ペイパルアカウントをお持ちでなくても、対応のクレジットカードでお支払いいただけます。
				</p>
				<!-- PayPal Logo --><table border="0" cellpadding="10" cellspacing="0" align="center"><tr><td align="center"></td></tr><tr><td align="center"><a href="#" onclick="javascript:window.open('https://www.paypal.com/jp/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside','olcwhatispaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=350');"><img  src="https://www.paypal.com/ja_JP/JP/i/bnr/vertical_solution_4_jcb.gif" border="0" alt="ソリューション画像"></a></td></tr></table><!-- PayPal Logo -->
			</div>
			<div id="left_epsilon">
				<p class="caption">
					イプシロン株式会社に決済機能を委託しております。
				</p>
				<a href="http://www.epsilon.jp/" target="_blank"><img class="epsilon_logo" src="http://www.epsilon.jp/logo_dl/img/logo01_120.gif" width="120" height="60" border="0" alt="クレジットカード決済代行-イプシロン"></a>
			</div>
			<p>
				<img class="ssl" src="/img/RapidSSL_SEAL-90x50.gif" alt="RapidSSL" width="90" height="50" />
			</p>
		</div>

		<div class="right">

			<div class="plan">スタンダード監視プラン 30日間不正サイトを監視します</div>

			<?php echo $form->create(null); ?>
			<div class="plan_msg">招待コードをお持ちのお客様は、招待キャンペーンを選択してください</div>
			<div class="input">
				<label class="select">監視プラン</label>
				<?php echo $form->select('User.plan', $product, null, array('error'=>false, 'empty'=>false)); ?>
			</div>


			<div id="campaign_block" class="input">
				<label for="UserCampaign">招待コード</label>
				<?php echo $form->input('User.campaign', array('type'=>'text', 'class'=>'text', 'label'=>false, 'error'=>false)); ?>
			</div>
			<?php if( $campaign === 'illegal_code' ): ?>
				<div class="error-message_cmp">正しい招待コードを入力してください</div>
			<?php endif; ?>


			<div class="input">
				<?php echo $form->input('User.mail_address', array('type'=>'text', 'label'=>'メールアドレス', 'class'=>'text', 'error'=>false)); ?>
			</div>
			<?php echo $form->error('Regist.mail_address', null, array('wrap'=>'div')); ?>
			<?php if( $email === 'isnotUniq' ): ?>
				<div class="error-message">このメールアドレスは既に使われているため、登録できません<br />以前にご利用されていた方は<a href="/users/login/">こちら</a>からログインし、<br />「アカウント情報」→「監視開始」からお申込みください。</div>
			<?php endif; ?>

			<div class="input">
				<label for="UserPasswd">パスワード</label>
				<?php echo $form->input('User.passwd', array('type'=>'password', 'class'=>'text', 'label'=>false, 'error'=>false)); ?>
			</div>
			<?php echo $form->error('Regist.passwd', null, array('wrap'=>'div')); ?>

			<div class="input">
				<label for="UserPasswdConfirm">パスワード（確認）</label>
				<?php echo $form->input('User.passwd_confirm', array('type'=>'password', 'class'=>'text', 'label'=>false, 'error'=>false)); ?>
			</div>
			<?php echo $form->error('Regist.passwd_confirm', null, array('wrap'=>'div')); ?>

			<div class="rule">
				<?php echo $form->checkbox('User.agreement', array( 'class'=>'checkbox', 'error'=>false)); ?>
				<label class="rule" for="UserAgreement"><a href="/pgs/terms_of_service/" target="_blank">利用規約</a>に同意する</label>
				<?php if( $agreement === 'notAgreement' ): ?>
					<div class="vali">お申込みには利用規約に同意いただく必要があります</div>
				<?php endif; ?>
			</div>
			<p class="btn">
				<?php echo $form->submit('次へ', array('class'=>'submit', 'id'=>'request_btn')); ?>

			</p>
			<?php echo $form->end(); ?>
		</div>

		</div><!-- div#sign_block -->

		<div class="sp_s"></div>
<!-- / .inner --></div>

<!-- / .outer --></div>
<!-- / #main --></div>



<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
