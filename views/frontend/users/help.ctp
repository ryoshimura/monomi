<?php
	$html->css(array('user_common', 'user_help') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ-ヘルプ-');
	AppController::set('meta_keywords', '');
	AppController::set('meta_description', '');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_private'); ?>
<!--header end-->

<div id="title">
	<h1>ヘルプ</h1>
</div>

<div id="main">
<div class="inner">

<div class="menu">
	<ul>
	<li><div class="num">1.</div><a href="#num1">物見インフォのご利用を開始するには</a></li>
	<li><div class="num">2.</div><a href="#num2">物見インフォができないこと</a></li>
	<li><div class="num">3.</div><a href="#num3">ダッシュボード</a></li>
	<li><div class="num">4.</div><a href="#num4">監視トレイ</a></li>
	<li><div class="num">5.</div><a href="#num5">削除申請</a></li>
	<li><div class="num">6.</div><a href="#num6">アップローダ選択</a></li>

	<li><div class="num">7.</div><a href="#num7">監視ワード</a></li>
	<li><div class="num">8.</div><a href="#num8">監視サイト</a></li>
	<li><div class="num">9.</div><a href="#num9">プロフィール</a></li>
	<li><div class="num">10.</div><a href="#num10">アカウント情報</a></li>
	<li><div class="num">11.</div><a href="#num11">監視サービス申込み</a></li>
	<li><div class="num">12.</div><a href="#num12">お問合せ</a></li>

	<li><div class="num">13.</div><a href="#num13">監視巡回のサイクル</a></li>
	<li><div class="num">14.</div><a href="#num14">正規版と体験版の違い</a></li>
	<li><div class="num">15.</div><a href="#num15">解約</a></li>
	</ul>
</div>

<div id="num1"></div>
<div class="content">
	<h3>物見インフォのご利用を開始するには</h3>
	<p>
		はじめてご利用の方は、最初に監視ワードを登録する必要があります。<br />
		<br />
		　→ <a href="#num5">監視ワードの登録方法について</a><br />
		　→ <a href="/users/words/">監視ワード登録はこちら</a><br /><br />
		なお、監視ワードを登録いただいてもすぐに監視は始まりませんのでご了承ください。<br />
		監視開始タイミングについてはこちらをご覧ください。　→ <a href="#num13">監視巡回のサイクル</a>
	</p>
</div>
<div class="contents_footer"><a href="#">ページトップへ</a></div>

<div id="num2"></div>
<div class="content">
	<h3>物見インフォができないこと</h3>
	<p>
		物見インフォは、ご登録いただいたワードが含まれるコンテンツを検出しますが、以下は対応しておりません。<br />
		<br />
		■検出できないコンテンツ<br />
		・ 監視対象外の不正サイトからは検出できません。<br />
		　 監視対象数は<a href="/users/ilist/">こちら</a>でご確認いただけますが、具体的なサイト名・URLは違法ダウンロード幇助につながる可能性があるため公開しておりません。<br />
		　 監視サイトは逐次追加しておりますので、新たな不正サイトをご存じであれば<a href="/users/ilist/">こちら</a>までお寄せください。<br />
		・ 物見インフォは新着分の監視に特化しているため、不正サイト内にある古いコンテンツからは正確に検出できない場合があります。<br />
		・ 不正サイト側がドメインを変更したりHTMLデザインをリニューアルした場合、一時的に正確な検出ができない場合があります。<br />
		・ ピークタイムにおけるタイムアウトや発信制限などにより、一時的に正確な検出ができない場合があります。<br />
		改善を重ねておりますが、完璧な検出には至っていないことをご了承ください。<br />
		<br />
		■削除要請<br />
		検出された不正サイトへの著作権侵害の削除要請は、ご契約者さまご自身の手で行なっていただく必要があります。<br />
		物見インフォが削除要請を代行することはありません（将来、削除要請まで委託いただくサービスを始める可能性はあります）<br />
		<br />
		■削除が完了したかの確認<br />
		技術的に不可能ではありませんが、不正に掲載しているサイトの多くはアップローダのリンクに広告を挟むケースがあり、<br />
		様々な理由によりアップローダのページをトレースすることが困難です。<br />
		削除完了の確認はご契約者さまご自身の目でご確認ください。<br />
	</p>
</div>
<div class="contents_footer"><a href="#">ページトップへ</a></div>

<div id="num3"></div>
<div class="content">
	<h3>ダッシュボード</h3>
	<p>
		物見インフォからのメッセージが確認いただけます。<br />
		また、不正コンテンツの検出数やご契約ステータスも確認いただけます。
	</p>
</div>
<div class="contents_footer"><a href="#">ページトップへ</a></div>

<div id="num4"></div>
<div class="content">
	<h3>監視トレイ</h3>
	<p>
		ご登録ワードが含まれている不正コンテンツ及びアップローダを一覧でご確認いただけます。<br />
		<br />
		■タグ「削除申請中」「削除完了」<br />
		削除申請及びその確認はお客さまご自身で行なっていただく必要があります。<br />
		削除完了までの過程をこのタグをクリックすることで足跡として残すことができます。<br />
		<br />
		■ソート機能<br />
		デフォルトは検出日の新しい順です。<br />
		検出日、不正サイト名の昇降順で並び替えが可能です。<br />
		<br />
		■検出サイトとアップローダ<br />
		検出サイト・・・不正なアップロード先が掲載されたサイト<br />
		アップローダ・・・あなたの作品がアップロードされているサイト
		<br />
		■ゴミ箱へ<br />
		類似ワードやシステム上の不具合等により、誤検出する場合があります。<br />
		上記理由などにより、一覧から削除したい場合に用いてください。<br />
		なお、削除した検出結果は、ゴミ箱タブより確認・戻すことが可能です。<br />
		<br />
		■削除申請<br />
		削除申請が可能なページへのリンクです。<br />
		<br />
		■アップロード先が特定できません<br />
		不正掲載の疑いは見つかったものの、アップロード先の特定ができない場合に表示されます。<br />
		主な原因は以下です。<br />
		・ アップローダが著しく古いもしくは新しい場合<br />
		・ アップロード先が広告サイトを経由している場合<br />
		・ アップロードURLが短縮URLサービスを利用している場合<br />
		<br />
	</p>
</div>
<div class="contents_footer"><a href="#">ページトップへ</a></div>

<div id="num5"></div>
<div class="content">
	<h3>削除申請</h3>
	<p>
		無断掲載の疑いのあるサイトに対し、削除申請を行なうことができます。<br />
		<br/>
		削除申請のアプローチは、不正サイト内にある申請フォームかメール送信のいずれかになります。<br />
		なお、申請先が不明、削除申請になかなか応じない等の場合は、不正サイトのホスティング先や提携決済会社、そしてFBIへ併せて通知するのも有効です。<br />
		物見インフォでは削除実績のあるアプローチを優先して表示します。<br />
		ただし、これらアプローチを用いても削除されない場合もあることをご承知おきください。
	</p>
</div>
<div class="contents_footer"><a href="#">ページトップへ</a></div>

<div id="num6"></div>
<div class="content">
	<h3>アップローダ選択</h3>
	<p>
		監視トレイで「アップロード先が特定できません」と表示された不正サイトに対しては、このページで削除申請先を選択する必要があります。<br />
		削除申請先のアップローダは該当ページをご確認ください。
	</p>
</div>
<div class="contents_footer"><a href="#">ページトップへ</a></div>

<div id="num7"></div>
<div class="content">
	<h3>監視ワード</h3>
	<p>
		監視するワードを登録いただけます。<br />
		また、設定したワードで検出された不正サイトに対し削除申請を送る際に使用する作品名・作品詳細URLの登録も可能です。<br />
		<br />
		■登録ワードのコツ<br />
		監視ワードには、作品名やサークル・メーカー名を登録するのが一般的です。<br />
		ただし、物見インフォの監視は完全一致検出なため、作品名の文字数が多いと検出できないケースがあります。<br />
		その場合は作品名の一部だけを登録するなど、いろいろお試しください。<br />
		<br />
		■ワードの変更<br />
		監視ワードはご契約期間中であれば何度でも変更いただけます。<br />
		ただし、変更前の監視ワードで検出されたデータは失われます。<br />
		【注意】<br />
		ご自身の作品以外の監視ワードが登録された場合、著作権保有確認をさせていただく場合があります。<br />
		監視ワードの過度な変更は上記の疑いを招くため、ご注意ください。<br />
		<br />
		■応用<br />
		日本語の作品名を掲載せずローマ字や英訳のみの不正サイトや、作品名が掲載されていないサイトもあります。<br />
		このようなケースでは、ローマ字で登録したり、また同人作品なら委託販売サイトの作品ID（DLsiteの場合、RJで始まる文字列）、<br />
		商用ゲームならGetchu.comの掲載URLを登録することで検出率を上げることが可能です。<br />
		<br />
		■作品名・作品詳細URL<br />
		監視ワード設定後に、作品名及び作品を紹介しているURLの項目にご入力いただくと、削除申請テンプレート文に自動的に挿入されます。<br />
		複数のワードで検出した場合は、作品詳細URLが入力されているワードを優先して自動挿入します。<br />
		よって、サークル・ブランド名を監視ワードとして設定した場合は、作品名・作品詳細URLは未入力のほうがよいでしょう。
	</p>
</div>
<div class="contents_footer"><a href="#">ページトップへ</a></div>

<div id="num8"></div>
<div class="content">
	<h3>監視サイト</h3>
	<p>
		物見インフォが監視している対象サイトのカテゴリーと数を確認することができます。<br />
		監視対象サイトの具体的な名前やURLは違法ダウンロード幇助につながる可能性があるため公開しておりません。<br />
		また、不正サイト情報を投稿することができます。<br />
		いただいた情報をもとに、監視対象を増やしてまいります。<br />
		<br />
		■不正掲載サイト<br />
		アップロードされているサイトのリンクや、トレント情報を掲載しているサイト<br />
		有料アップローダのアフィリエイトや広告で収益をあげている。<br />
		サイト運営情報等が明記されていない場合が多く、削除申請の窓口の掲載も少ない。<br />
		<br />
		■アップローダ<br />
		実際に作品データがアップロードされているサイト。<br />
		不正掲載サイトに比べて、削除申請に応じる確率が高い。<br />
		<br />
		■新着監視<br />
		不正掲載サイトが新しく掲載・投稿した情報を監視します。<br />
		<br />
		■全件監視<br />
		不正掲載サイトが過去に掲載・投稿した情報全てを監視します。<br />
		全件監視対象外のサイトであっても、直近数日～数ヶ月分は監視することがあります。
	</p>
</div>
<div class="contents_footer"><a href="#">ページトップへ</a></div>

<div id="num9"></div>
<div class="content">
	<h3>プロフィール</h3>
	<p>
		プロフィールにある入力項目は、不正サイトへの削除要請テンプレートで使用します。<br />
		入力は任意です。<br />
		また不正サイトの中には、申請内容をよく確認せず削除に応じるケースもあるため、必ずしも全て入力する必要はありません。
	</p>
</div>
<div class="contents_footer"><a href="#">ページトップへ</a></div>

<div id="num10"></div>
<div class="content">
	<h3>アカウント情報</h3>
	<p>
		ご契約情報の確認とメールアドレス及びパスワードの変更と、契約期間が過ぎているお客様はここから有料版のお申込みが可能です。<br />
		なお、メールアドレスはログイン時に使用するため、変更の際は十分ご注意ください。
	</p>
</div>
<div class="contents_footer"><a href="#">ページトップへ</a></div>


<div id="num11"></div>
<div class="content">
	<h3>監視サービス申込み</h3>
	<p>
		以前に有料監視プランをご利用されていたお客さまを対象としたお申込みページです。<br />
	</p>
</div>
<div class="contents_footer"><a href="#">ページトップへ</a></div>

<div id="num12"></div>
<div class="content">
	<h3>お問合せ</h3>
	<p>
		物見インフォへのご意見・ご質問の窓口です。<br />
		ご質問の内容によってはご返答をしかねる場合がございます。あらかじめご了承ください。<br />
	</p>
</div>
<div class="contents_footer"><a href="#">ページトップへ</a></div>

<div id="num13"></div>
<div class="content">
	<h3>監視巡回のサイクル</h3>
	<p>
		不正サイトの更新に応じて、新着分に限り１日最大２４回監視を行ないます。
	</p>
</div>
<div class="contents_footer"><a href="#">ページトップへ</a></div>

<div id="num14"></div>
<div class="content">
	<h3>正規版と体験版の違い</h3>
	<p>
		体験版は以下の制限があります。<br />
		<br />
		・ 利用期間は7日間<br />
		・ 監視ワードの登録は1つまで<br />
		・ 監視結果は3サイトまで表示<br />
		・ 削除申請の一部機能を制限
	</p>
</div>
<div class="contents_footer"><a href="#">ページトップへ</a></div>

<div id="num15"></div>
<div class="content">
	<h3>解約</h3>
	<p>
		物見インフォは自動延長を行ないませんので、ご利用期限が過ぎればサービスは停止となります。<br />
		しかし、サービス停止後もメールアドレス等の情報は会員情報として残ったままとなります。<br />
		これら会員情報すべての削除をご希望の場合は、下記からご解約することでご利用期限が過ぎた時点で削除されます。<br />
		※ただし体験版のご解約の場合、体験版の連続お申込みを防止するため、当面の間同メールアドレスでの体験版申込ができなくなります。<br />
		　→ <a href="/users/cancel/">すべての情報を削除して解約する</a><br />
	</p>
</div>
<div class="contents_footer"><a href="#">ページトップへ</a></div>



<!-- / .inner --></div>
<!-- / #main --></div>

<div id="sub">
	<ul>
	<li><a href="/users/dashboard/">ダッシュボード</a></li>
	<li><a href="/users/inbox/">監視トレイ</a></li>
	<li><a href="/users/words/">監視ワード</a></li>
	<li><a href="/users/ilist/">監視サイト</a></li>
	<li><a href="/users/template_prof/">プロフィール</a></li>
	<li><a href="/users/profile/">アカウント情報</a></li>
	<li class="crt"><a href="/users/help/">ヘルプ</a></li>
	<li><a href="/users/contact/">お問合せ</a></li>
	<li><a href="/users/logout/">ログアウト</a></li>
	</ul>
<!-- / #sub --></div>











<!--footer start-->
<?php echo $this->element('footer_block/footer_private'); ?>
<!--footer end-->

</body>