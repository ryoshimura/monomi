<?php
	$html->css(array('common', 'faq') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', 'モノミインフォ - プライバシーポリシー');
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
		<img class="h2gif"src="/img/h2faq.gif" alt="about" />
		<h3 class="toku">よくある質問</h3>

		<div class="sp_s"></div>
		<div class="index">

			<ul>
				<li><a href="#num1">物見インフォは何ができるの？</a></li>
				<li><a href="#num2">どのように監視しているの？</a></li>
				<li><a href="#num3">本当に無断でアップロードされたりするのですか？</a></li>
				<li><a href="#num4">監視対象の不正サイトを教えてほしい</a></li>
				<li><a href="#num5">物見インフォを利用することで、無断アップロードは全て見つかりますか？</a></li>
				<li><a href="#num6">削除申請の経験がないので不安です</a></li>
				<li><a href="#num7">物見インフォを使えば、無断でアップロードされたコンテンツは確実に削除できますか？</a></li>
				<li><a href="#num8">削除申請した不正コンテンツが削除されているか自動で確認してくれますか？</a></li>
				<li><a href="#num9">最適な監視ワード数はいくつですか？</a></li>
				<li><a href="#num10">監視サービスを申込むのはいつがベストですか？</a></li>
				<li><a href="#num11">流出のピークは発売日から何日後くらい？</a></li>
				<li><a href="#num12">著作権保護（ユーザ認証など）を施してるから大丈夫ですよね？</a></li>
				<li><a href="#num13">プロテクトをかけているから大丈夫ですよね？</a></li>
				<li><a href="#num14">解約できますか？</a></li>
				<li><a href="#num15">有料版に申込んだが検出されなかったので返金して欲しい</a></li>
				<li><a href="#num16">パスワードを忘れてしまった</a></li>
				<li><a href="#num17">有料版の監視期限を過ぎてもログインして検出結果を確認できますか？</a></li>
				<li><a href="#num18">複数月の申し込みや自動更新はありますか？</a></li>
				<li><a href="#num19">著作権者ではないですが、利用できますか？</a></li>
				<li><a href="#num20">フリーのメールアドレスで申込みできますか？</a></li>
				<li><a href="#num21">個人情報は厳重に保護されますか？</a></li>
				<li><a href="#num22">不正サイトと関わりありますか？</a></li>
				<li><a href="#num23">削除申請の代行はしてくれないのですか？</a></li>
			</ul>
		</div>

		<div class="faq_body">

			<div class="post" id="num1">
				<p class="qus"><span class="qfont">Q.</span> 物見インフォは何ができるの？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						物見インフォが提供するサービスは、「どこよりも早い無断アップロードコンテンツの検出」と「最適な削除申請方法の案内」になります。<br />
						無断でアップロードしているサイトへの削除申請はお客様に行なっていただきますが、物見インフォをご活用することで経験がなくても簡単に行なえます。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num2">
				<p class="qus"><span class="qfont">Q.</span> どのように監視しているのですか？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						無断掲載の疑いのあるサイトに対し、1日最大24回の頻度で、お客様が登録した監視ワード（作品名・制作団体名など）が含まれているか監視しています。<br />
						残念ながら現時点では、作品名等のテキスト表記のない画像（altタグは監視対象）のみを掲載している不正サイトは監視対象外となっております。<br />
						しかし、このような不正サイトも将来別アプローチで監視する予定です。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num3">
				<p class="qus"><span class="qfont">Q.</span> 本当に無断でアップロードされたりするのですか？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						体験版をご用意しました。<br />
						お客様が過去に制作した作品名や制作団体（サークル・ブランド）名を登録し、検出されるかお試しください。<br />
						検出された場合は、以降発売予定の新作も同様に無断アップロードされる可能性があります。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num4">
				<p class="qus"><span class="qfont">Q.</span> 監視対象の不正サイトを教えてほしい</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						監視対象サイトのジャンル内訳数は、体験版・有料版にお申し込みいただき、さらにログインした先のページでご案内しております。<br />
						ただし、不正サイトのサイト名やURLの公開は、違法ダウンロードを助長する可能性があるため行なっておりません。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num5">
				<p class="qus"><span class="qfont">Q.</span> 物見インフォを利用することで、無断アップロードは全て見つかりますか？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						監視対象の拡大に勤めておりますが、日々増加する不正サイト全ての網羅には至っておりません。<br />
						物見インフォでは更新頻度が高く著作権被害の多い不正サイトを優先的に監視対象にするよう努力しております。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num6">
				<p class="qus"><span class="qfont">Q.</span> 削除申請の経験がないので不安です</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						削除申請はお客様ご自身で行なっていただく必要がありますが、ステップ順に進めることで簡単に削除申請を送ることができます。<br />
						また不正サイト・アップローダー毎に、削除実績のある申請アプローチをナビゲーションしておりますのでご安心ください。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num7">
				<p class="qus"><span class="qfont">Q.</span> 物見インフォを使えば、無断でアップロードされたコンテンツは確実に削除できますか？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						物見インフォでは削除実績のある申請アプローチをナビゲーションしますが、100％確実に削除されるわけではありません。<br />
						一般的に、サイト運営者情報が明記されているアップローダー系の削除対応率が一番高く、次点でDMCA等の窓口を用意している無断掲載サイト。<br />
						最も削除対応率が悪いのは、申請窓口が明記されていないサイトとなります。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num8">
				<p class="qus"><span class="qfont">Q.</span> 削除申請した不正コンテンツが削除されているか自動で確認してくれますか？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						いいえ、削除申請が受理されたかの確認はお客様ご自身で行なってください。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num9">
				<p class="qus"><span class="qfont">Q.</span> 最適な監視ワード数はいくつですか？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						物見インフォでは有料版お申込みの際に、監視ワード枠数を選択することができます。<br />
						監視ワード枠2つは、「作品名」と「制作団体名（サークル・ブランド名）」の設定を推奨しています。<br />
						このワード2つで多くの不正サイトから検出できます。<br />
						より確実な検出を望むのであれば、監視ワード枠数を3つにして下記ワードを設定してください。<br />
						<br />
						デジ同人系の場合、作品解説用のリンクが存在するケースが多いため、ダウンロード販売サイトで割り振られた作品ID（例：RJ09999x）。<br />
						同人誌、商業コミック、コンシューマゲームの場合、ローマ字表記の無断掲載が目立つため、作品名のローマ字。<br />
						商業PCゲームの場合、Getchu.com等で割り振られたコンテンツIDもしくはURL。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num10">
				<p class="qus"><span class="qfont">Q.</span> 監視サービスを申込むのはいつがベストですか？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						デジ同人の場合、発売日当日。可能であれば当日のお昼頃にお申込みいただくと被害拡大を最小限に止めることが可能です。<br />
						同人誌・商業コミックの場合、デジ同人系に比べて流出スピードは緩やかですが、やはり発売日直後のお申込みを推奨します。<br />
						ただし、週刊誌などは発売日の2～3日前から流出することが多いため注意が必要です。<br />
						商業PCゲーム・コンシューマゲームの場合、発売日の前日あたりから流出が始まるため、余裕を持って発売日の1～2日前からのお申込みを推奨します。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num11">
				<p class="qus"><span class="qfont">Q.</span> 流出のピークは発売日から何日後くらい？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						デジ同人の場合、発売日から2週間がピークですが、一ヶ月近く経過してから流出することもあるため、少なくとも発売日から30日間は油断できません。<br />
						ただし、ユーザ認証等でプロテクトされている作品やゲーム等の不具合修正でバージョンアップした場合、通常の作品よりも流出が遅れる場合があります。<br />
						同人誌の流出はデジ同人に比べて傾向にバラツキが見られますが、発売日から1～2ヶ月間は注意が必要です。<br />
						商業PCゲームの場合、発売日から1週間以内に流出するケースが多いようです。<br />
						商業コミック（単行本）は流出タイミングに大きなバラツキがあるため、数ヶ月監視しなければならないケースがあります。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num12">
				<p class="qus"><span class="qfont">Q.</span> 著作権保護（ユーザ認証など）を施してるから大丈夫ですよね？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						残念ながら著作権保護を施した作品の検出が確認されております。<br />
						しかし、著作権保護機能を施した作品は流出が遅れたり、流出しにくい傾向にあるため、抑止力として効果はあると思われます。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num13">
				<p class="qus"><span class="qfont">Q.</span> プロテクトをかけているから大丈夫ですよね？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						残念ながらプロテクト及びアクティベーションともに、クラックされた作品の検出が確認されております。<br />
						さらにプロテクト作品は、「プロテクト解除前」「プロテクト解除後」といったように、複数回にわたってアップロードされるケースが見られます。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num14">
				<p class="qus"><span class="qfont">Q.</span> 解約できますか</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						ログイン後のヘルプより、解約いただくことが可能です。<br />
						有料版の監視期限を過ぎている場合は、解約後すぐにご登録の個人情報等を削除いたします。<br />
						有料版の監視期限が残っている場合は、監視期間終了後にご登録の個人情報等を削除いたします（期限までは通常通りご利用いただけます）。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num15">
				<p class="qus"><span class="qfont">Q.</span> 有料版に申込んだが検出（削除）されなかったので返金して欲しい</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						物見インフォはお客様に代わって不正サイトを監視するサービスですが、無断アップロードの検知やアップロードの削除を保証するものではありません。<br />
						解約は可能ですが、ご契約期日まで不正サイトへの監視は継続されますので、<a href="/pgs/terms_of_service/">利用規約</a>のとおり返金は致しておりません。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num16">
				<p class="qus"><span class="qfont">Q.</span> パスワードを忘れてしまった</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						こちらのページでパスワードの再発行を行なってください。<br />
						<a href="/users/rms/">http://monomi.info/users/rms/</a>
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num17">
				<p class="qus"><span class="qfont">Q.</span> 有料版の監視期限を過ぎてもログインして検出結果は確認できますか？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						監視期限が過ぎても物見インフォの監視が止まるだけで、ログインしてこれまでの検出結果を確認いただけます。<br />
						ただし体験版の場合、期限が過ぎるとログインはできますが検出結果は確認できなくなります。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num18">
				<p class="qus"><span class="qfont">Q.</span> 複数月の申し込みや自動更新はありますか？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						現在ご用意しているお申込みプランは全て30日間のみとなります。<br />
						また自動的に更新することもありません。<br />
						もし監視サービスの継続をご希望の場合は、お手数ですがご契約期間が経過した後に再申込みください。<br />
						なお、法人様で複数の作品を数ヶ月単位で監視されたい場合は、<a href="/inquiry/form/">こちら</a>よりご相談ください。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num19">
				<p class="qus"><span class="qfont">Q.</span> 著作権者ではないですが、利用できますか？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						ご利用いただけません。<br />
						物見インフォは著作権を保有されている方向けのサービスです。<br />
						著作権者以外の利用が確認された場合、アカウントを停止させていただきます。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num20">
				<p class="qus"><span class="qfont">Q.</span> フリーのメールアドレスで申込みできますか？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						フリーメールでもお申込みいただけます。<br />
						ただし、フリーメールを用いた悪質な利用が続く場合、禁止する可能性があります。<br />
						また、物見インフォから送信するメールが迷惑（スパム）フォルダに届く可能性がありますのでご注意ください。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num21">
				<p class="qus"><span class="qfont">Q.</span> 個人情報は厳重に保護されますか？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						こちらの<a href="/pgs/policy/">プライバシーポリシー</a>に沿って、厳重に管理しております。<br />
						お預かりしている個人情報（メールアドレス・監視ワード等）は全て暗号処理をしています。<br />
						また個人情報を取り扱う画面ではRapidSSLによる暗号通信を採用しています。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num22">
				<p class="qus"><span class="qfont">Q.</span> 不正サイトと関わりありますか？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						物見インフォは著作権被害に遭われているサークル様監修の下、不正サイトが横行する現状に一石を投じるべく誕生しました。<br />
						不正サイトとは完全に対立軸にあることから、関わりは一切ありません。<br />
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

			<div class="post" id="num23">
				<p class="qus"><span class="qfont">Q.</span> 削除申請の代行はしてくれないのですか？</p>
				<div class="ans_body">
					<p class="afont">A.</p>
					<p class="ans">
						はい、現在のところ削除申請の代行は行なっておりません。<br />
						不正アップロードの疑いのあるコンテンツから著作物の判定および削除申請を行なうためには、著作権者本人であるか又は著作権者より委任を受けていなければなりません。<br />
						今後、削除代行に関する多くのご要望があれば、法律事務所と提携しお客様より委任状等をいただくことで物見インフォ側で削除申請を行なうプランも検討したく思います。
					</p>
					<div class="post_sp"></div>
					<p class="top"><a href="#">このページのトップへ</a></p>
				</div>
			</div>

		</div>


		<p>
			<a href="/" class="top">TOPへ戻る</a>
		</p>
	</div>
<!-- / .inner --></div>

	<div class="sp_s"></div>

<!-- / .outer --></div>
<!-- / #main --></div>



<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
