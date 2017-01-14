<?php
	$html->css(array('common', 'index') , null, array('inline'=>false));
	$html->script(array('jquery/jquery-1.4.2.min','frontend/index'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ - 不正サイト監視＋削除申請支援サービス');
	AppController::set('meta_keywords', '物見,モノミ,監視,著作権侵害,DMCA');
	AppController::set('meta_description', '物見インフォはあなたに代わって作品の不正アップロードを監視し、削除申請をサポートします');
?>
<link rel="shortcut icon" href="/img/favicon.ico">
<body>
<!--header start-->
<?php echo $this->element('header_block/header_public'); ?>
<!--header end-->





<div id="main">
<div class="outer">


<div id="social_block">
	<a href="http://www.facebook.com/sharer.php?u=http%3a%2f%2fmonomi%2einfo%2f&amp;t=%e4%b8%8d%e6%ad%a3%e3%82%a2%e3%83%83%e3%83%97%e3%83%ad%e3%83%bc%e3%83%89%e7%9b%a3%e8%a6%96%ef%bc%8b%e5%89%8a%e9%99%a4%e7%94%b3%e8%ab%8b%e6%94%af%e6%8f%b4%e3%82%b5%e3%83%bc%e3%83%93%e3%82%b9%2d%e7%89%a9%e8%a6%8b%e3%82%a4%e3%83%b3%e3%83%95%e3%82%a9" target="_blank">
	<img src="/img/fb.png" width="35" height="35" />
	</a>
	<a href="http://twitter.com/share?text=%e4%b8%8d%e6%ad%a3%e3%82%a2%e3%83%83%e3%83%97%e3%83%ad%e3%83%bc%e3%83%89%e7%9b%a3%e8%a6%96%ef%bc%8b%e5%89%8a%e9%99%a4%e7%94%b3%e8%ab%8b%e6%94%af%e6%8f%b4%e3%82%b5%e3%83%bc%e3%83%93%e3%82%b9%2d%e7%89%a9%e8%a6%8b%e3%82%a4%e3%83%b3%e3%83%95%e3%82%a9&url=http%3a%2f%2fmonomi%2einfo%2f" target="_blank">
	<img src="/img/twitter.png" width="35" height="35" />
	</a>
	<a href="http://b.hatena.ne.jp/add?mode=confirm&url=http%3a%2f%2fmonomi%2einfo%2f&title=%e4%b8%8d%e6%ad%a3%e3%82%a2%e3%83%83%e3%83%97%e3%83%ad%e3%83%bc%e3%83%89%e7%9b%a3%e8%a6%96%ef%bc%8b%e5%89%8a%e9%99%a4%e7%94%b3%e8%ab%8b%e6%94%af%e6%8f%b4%e3%82%b5%e3%83%bc%e3%83%93%e3%82%b9%2d%e7%89%a9%e8%a6%8b%e3%82%a4%e3%83%b3%e3%83%95%e3%82%a9" target="_blank">
	<img src="/img/hatena.png" width="35" height="35" />
	</a>
	<a href="https://plus.google.com/share?url=http%3a%2f%2fmonomi%2einfo%2f" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
	<img src="/img/gplus.png" width="35" height="35" />
	</a>
	<a href="javascript:void(0);" onclick="window.open('http://mixi.jp/share.pl?u=http%3a%2f%2fmonomi%2einfo%2f&k=c945c5b4413deb775a0d74a29eae9d3b5f3f1d0d','share',['width=632','height=456','location=yes','resizable=yes','toolbar=no','menubar=no','scrollbars=no','status=no'].join(','));">
	<img src="/img/mixi.png" width="35" height="35" />
	</a>
</div>




<div class="navi">
	<ul>
	<li class="right"><a href="/users/login/">ログイン</a></li>
	</ul>
<!-- / .navi --></div>

<div class="inner">
	<div class="intro">
		<img class="h2gif"src="/img/h2about.gif" alt="about" />
		<img class="title" src="/img/title_about.gif" alt="about" />
		<p class="caption">
			「発売したばかりのデジタル作品が、わずか数時間後、無断でアップロードサイトに掲載されていた」<br />
			最も売上が期待できる発売直後にコレではやりきれません。<br />
			<br />
			2012年初め、それまで猛威をふるっていた不正アップローダが取締りにより相次いで閉鎖となりました。<br />
			しかし、いまだに沈静化することなく、１日に１万人以上が訪問する不正サイトもある始末。<br />
			売上に影響が出るのは当然です。<br />
			<br />
			この現状の中、マンパワーで監視していた著作権者様のご意見から誕生したのが物見インフォです。<br />
			<br />
			物見インフォは、あなたに代わってこれらサイトを監視し、不正掲載されたコンテンツをリストアップ。<br />
			さらに面倒な削除要請も簡単に行なえるように、DMCA含め複数の文書テンプレートと不正サイト毎に<br />最適な申請先をナビゲーションします。<br />
			<br />
			準備は簡単。<br />
			アカウント登録後に監視したいキーワード（作品名など）を設定するだけ。<br />
			まずは体験版で物見インフォの実力をお試しください。
		</p>

		<div class="otherlink">
			<ul>
				<li><a class="regist" href="/pgs/about/#upload">不正アップロードの現状</a></li>
				<li><a class="regist" href="/pgs/about/#delete">削除申請について</a></li>
				<li><a class="regist" href="/pgs/faq/">よくある質問</a></li>
			</ul>
		</div>
	</div>
</div><!-- / .inner -->


<div class="inner">
	<div class="feature">
		<img class="h2gif"src="/img/h2feature.gif" alt="about" />
		<img class="title" src="/img/title_feature.png" alt="about" />

		<div class="table_block">
		<table>
			<tr>
				<td class="item">
					<img src="/img/ksite.png" alt="監視サイト" />
					ゲーム、音楽、画像、動画など、商業・同人問わずあらゆるデジタル作品を守るため、Googleアラートなど検索エンジンでは見つけにくいコンテンツもリストアップ。<br />
					監視対象サイトは<?php echo date("Y.m.d"); ?>時点で<?php echo $cnt['ilsite']; ?>サイト。<br />利用者さまのご要望により随時増やしてまいります。
				</td>
				<td class="csp"></td>
				<td class="item">
					<img src="/img/kansihindo.png" alt="監視頻度" />
					検索エンジンはその性質上、オーガニックに掲載されるまでタイムラグがあります。<br />
					物見インフォは、<span>１日最大２４回</span>監視を行なうことで、どこよりも早く不正ページを見つけてメールで報告。<br />
					著作権被害を最小限に止めます。
				</td>
			</tr>
			<tr>
				<td class="item">
					<img src="/img/kenti.png" alt="不正検知後の対処" />
					削除申請の経験がなくても大丈夫。<br />
					著作権侵害が検出されても、<span>わずかな手順で簡単に、そして効果的な削除申請が行なえます。</span><br />
					なお、DMCAなど著作権侵害についての詳細は、こちらのサイト「 <a href="http://www35.atwiki.jp/dldojin/pages/13.html" target="_blank">海賊対策wiki</a> 」をご覧ください。
				</td>
				<td class="csp"></td>
				<td class="item">
					<img src="/img/kakaku.png" alt="料金" />
					体験版は<span>無料</span>で7日間ご利用いただけます。<br />
					有料版は<span>監視ワード2枠30日間 780円（監視ワード3枠 980円）</span> で監視します。<br />
					<a href="/pgs/faq/#num10">→最適なお申込みタイミング</a>
				</td>
			</tr>
			<tr>
				<td class="item">
					<img src="/img/krange.png" alt="監視範囲" />
					新たに掲載されたページだけでなく、過去含め全て確認します（ただし、一部の不正サイトは過去チェックが難しいため新規更新分のみとなります）。<br />
					なお、削除要請に応じて掲載を取りやめたページの検出は現時点では行なっておりません。
				</td>
				<td class="csp"></td>
				<td class="item">
					<img src="/img/trial.png" alt="体験版" />
					多くの著作権者さまにご利用いただけるよう体験版を用意しました。<br />
					<span>まずは体験版で物見インフォの実力をお試しください。</span><br />
				</td>
			</tr>
		</table>
		</div>
	</div>
<!-- / .inner --></div>

<!-- / .outer --></div>
<!-- / #main --></div>



<!--footer start-->
<?php echo $this->element('footer_block/footer'); ?>
<!--footer end-->

</body>
