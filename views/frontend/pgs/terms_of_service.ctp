<?php
	$html->css(array('common', 'policy') , null, array('inline'=>false));
//	$html->script(array('frontend/rollover'), array('inline'=>false));
//	$html->meta('icon', '/img/favicon.ico');

	AppController::set('title_for_layout', '物見インフォ - 利用規約');
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
		<img class="h2gif"src="/img/h2terms.gif" alt="about" />
		<h3 class="toku">物見インフォ利用規約</h3>
		<p>
第1条（定義）<br />
物見インフォ利用規約（以下「本則」といい ます）における用語を以下の通り定義します。<br />
「物見インフォ」とは、運営が提供する著作権侵害の疑いのあるサイトを監視するwebツールをいいます。<br />
「会員」とは、運営が定める手続に従い物見インフォを利用する資格を持つ法人または個人をいいます。<br />
「利用者」とは、会員の持つ物見インフォ利用資格に基づいて、物見インフォを利用することができる個人をいいます。<br />
「ID等」とは、自己の設定するメールアドレス及びパスワード、その他物見インフォを利用するために運営が会員に対して付与する記号または番号をいいます。<br />
「会員情報」とは、物見インフォに関して会員または利用者が運営に対して提供する、著作権保有団体名、氏名等の、会員を認識もしくは特定できる情報をいいます。<br />
「履歴情報」とは、運営に記録される会員および利用者による物見インフォの利用履歴をいいます。<br />
<br />
第2条（本則の適用および変更）<br />
（1）本則は、全ての物見インフォの利用に関し適用されるものとします。<br />
（2）運営は、運営が適当と判断する方法で会員に通知することにより、本則を変更できるものとします。ただし、本則の変更内容の詳細については、運営のサイト上に掲示することにより、会員への通知に代えることができるものとします。その場合、本則の変更に関する通知の日から起算して8日以内に、会員が第4条に従って該当する物見インフォの利用を終了しない場合、会員によってかかる変更は承認されたものとみなします。<br />
<br />
第3条（入会）<br />
（1）物見インフォの利用希望者は、本則を承認した上で、 物見インフォのサイト上にある申込（有料版・体験版）ページの手続に従って利用を申込むものとし、当該手続が完了した時点で該当する物見インフォの利用契約が成立して利用資格を得、会員となるものとします。<br />
（2）本条第1項に定める申込について、利用希望者が以下のいずれかに該当することを運営が確認した場合、運営はその申込を承諾しない場合があり、利用申込者は予めこれを了承するものとします。<br />
利用申込にあたり、虚偽記載、誤記があった場合<br />
利用申込にあたり、決済会社より無効扱いの通知を受けた場合<br />
過去に、物見インフォの利用資格の停止又は失効を受けた場合<br />
その他、業務の遂行上または技術上、支障を来たすと、運営が判断した場合<br />
<br />
第4条（会員への通知）<br />
（1）会員への運営からの通知は、運営サイト上の表示、又は会員の申し出たメールアドレスへの通知で以ってすることとし、各会員はこれに同意するものとします。<br />
（2）運営サイト上の表示、又は会員の申し出たメールアドレスへの通知は、運営サイト上の表示時点又は、会員の申し出たメールアドレスへの送信時点を以って効力が発生するものとし、各会員はこれに同意するものとします。<br />
<br />
第5条（退会）<br />
（1）会員は、運営が別途定める手続に従い、物見インフォの利用を終了することができるものとします。<br />
（2）本則の定めに従って会員が物見インフォの利用資格を失った場合、当該会員は退会したものとみなします。<br />
（3）利用の如何に関わらず、残り契約期間分の返金はしないものとします。<br />
<br />
第6条（物見インフォ利用資格の停止および失効）<br />
（1）以下の各号の一に該当する場合、運営は、事前に通知することなく、直ちに該当する会員の物見インフォ利用資格を停止または失効させることができるものとします。 サービス利用資格を停止または失効した場合、運営で該当会員の情報等、事前の承諾無くして一切のデータを削除できるものとします。<br />
会員または利用者が第10条ならびに第11条各号に定める禁止行為のいずれかを行った場合。<br />
運営が定める著作権保有者確認に応じない又は著作権保有者と判断ができない場合。<br />
運営規定アクセス値以上のアクセスが認められた場合。（通常のご利用では、規定値以上になることはありません）<br />
会員により、物見インフォに関する料金等の支払債務の履行遅延または不履行があった場合。<br />
会員もしくは利用者が本則に違反した場合。<br />
会員が清算された場合、その他会員が権利能力を失った場合。<br />
その他、会員として不適切または物見インフォの提供に支障があると運営が判断した場合。<br />
（2）前項の規定に従い、物見インフォの利用資格が停止または失効した場合、該当する会員は、期限の利益を失いかかる利用資格の停止または失効の日までに発生した物見インフォに関連する運営に対する債務の全額を、運営の指示する方法で一括して支払うものとします。<br />
（3）運営は、会員の物見インフォ利用資格が停止失効または終了した場合であっても、会員によって既に支払われた物見インフォに関する料金等を、一切払戻す義務を負わないものとします。<br />
<br />
第7条（設備等の準備）<br />
（1）会員は、通信機器、ソフトウェア、その他これらに付随して必要となる全ての機器の準備、設置、接続および設定、回線利用契約の締結ならびにアクセスポイントへの接続、インターネット接続サービスへの加入、その他自己の利用する物見インフォを利用するために必要な準備を、自己の費用と責任において行うものとします。<br />
（2）運営は、会員または利用者が物見インフォを利用するにあたり使用する通信機器、ソフトウェアおよびこれらに付随して必要となる全ての機器との互換性を確保するために、運営の管理する設備、システムもしくはソフトウェアを改造、変更または追加したり物見インフォの提供方法を変更する義務を負わないものとします。<br />
<br />
第8条（物見インフォの利用）<br />
（1）物見インフォは、その利用資格を有する会員および利用者のみが利用できるものとします。会員は、物見インフォの利用資格を得た後に、物見インフォの利用条件を変更する場合、運営が別途指定する手続に従うものとします。<br />
（2）会員は、本則および運営が随時通知または運営のサイト上に掲示する条件に従って物見インフォを利用するものとします。但し、かかる通知および掲示の内容が本則に定める内容と異なる場合（ただし、第２条に定める、本則の変更内容の告知を除く）には、本則に定める内容が優先して適用されるものとします。<br />
（3）会員は、物見インフォと同時にまたはこれに関連して物見インフォ以外の各種インターネットサービスを利用する場合であっても、かかるインターネットサービスに関する規約、契約、利用条件等に拘わらず、物見インフォの利用に関しては、本則の内容に従うものとします。<br />
（4）会員は、自己の有する資格に基づいて物見インフォを利用する利用者に対し、本則において自己に課されている義務と同等の義務を課し、これを遵守させるものとし、かつ、運営に対して、利用者による当該義務の違反に関し、当該利用者と連帯して責任を負うものとします。万一、利用者が当該義務に違反した場合、会員は、自己の費用と責任において、運営の指示に従い、当該利用者による物見インフォの利用を中止させ、かつ、再発防止に必要な措置を取るものとします。<br />
（5）会員は、本則にて明示的に定める場合を除き、自己または利用者による物見インフォの利用につき一切の責任を負うものとし、他の会員、第三者および運営に何等の迷惑をかけず、かつ損害を与えないものとします。<br />
（6）物見インフォの利用に関連して、会員もしくは利用者が他の会員、第三者または運営に対して損害を与えた場合、あるいは会員もしくは利用者と他の会員または第三者との間で紛争が生じた場合、当該会員は自己の費用と責任でかかる損害を賠償するかまたはかかる紛争を解決するものとし、運営に何等の迷惑をかけず、かつ損害を与えないものとします。<br />
<br />
第9条（料金および支払い）<br />
（1）会員は、物見インフォの利用にあたって、別途運営が定める使用料等の料金を、別途運営の定める方法により支払うものとします。<br />
（2）運営は、運営が適当と判断する方法で会員に事前に通知することにより、前項に定める料金およびその支払い方法を変更することができるものとします。ただし、料金およびその支払方法の変更の詳細については、運営のサイト上に掲示することにより、会員への通知に代えることができるものとします。その場合、料金およびその支払方法の変更に関する通知の日から起算して8日以内に、会員が本則第4条に従って該当する物見インフォ利用の終了を申し入れない場合、会員によってかかる変更は承認されたものとみなします。<br />
<br />
第10条（著作権）<br />
（1）会員は、物見インフォを通じて運営が会員に提供する情報に関する著作権が、運営または運営に対して当該情報を提供した第三者に帰属するものであることを確認します。<br />
（2）会員は、物見インフォを通じて運営から提供される情報を一般公衆が閲覧できるサイト等への掲載などを行ってはならないものとします。<br />
<br />
第11条（禁止事項）<br />
会員は以下の行為を行ってはならないものとします。<br />
自身の著作物以外を設定しその情報を取得する行為。<br />
運営から事前に承認を得ていない、物見インフォを通じてまたは物見インフォに関連する営利を目的とする行為、またはその準備を目的とする行為。<br />
運営から事前に承認を得ることなく、物見インフォ類似商品の開発をする行為。<br />
物見インフォのリソース（ID等）を不特定多数の第3者に貸与する行為。<br />
物見インフォ、または第三者が管理するサーバ等の設備の運営を妨げる行為。<br />
他の会員になりすまして物見インフォを利用する行為。<br />
前各号に定める行為を助長する行為。<br />
前各号に該当する虞があると運営が判断する行為。<br />
その他、運営が不適切と判断する行為。<br />
<br />
第12条（ID等の管理）<br />
（1）会員は、ID等の管理責任を負うものとします。<br />
（2）会員は、ID等を利用者以外の第三者に利用させたり、貸与、譲渡、売買等をしてはならないものとします。<br />
（3）会員は、自己の設定するパスワードを定期的に変更するものとします。<br />
（4）会員によるID等の管理不十分、使用上の過誤、第三者の使用等による損害は会員が負担するものとし、運営は一切責任を負わないものとします。また、第三者によるID等の使用により発生した物見インフォの料金等については、かかる第三者によるID等の使用が運営の責に帰すべき事由により行われた場合を除き、全て当該ID等の管理責任を負う会員の負担とします。<br />
（5）会員は、ID等の失念があった場合、またはID等が第三者（利用者を除く）に使用されていることが判明した場合、直ちに運営にその旨連絡するとともに、運営からの指示がある場合にはこれに従うものとします。<br />
<br />
第13条（物見インフォの提供）<br />
（1）物見インフォは、本則および運営が随時通知または運営のサイト上に掲示する内容に従って提供されるものとします。<br />
（2）運営は、理由の如何を問わず、会員に事前の通知をすることなく、物見インフォの内容の全部または一部の変更、追加および廃止ができるものとします。<br />
<br />
第14条（データ等の削除）<br />
（1）物見インフォが提供するデータ等が、物見インフォが定める所定の期間または量を超えた場合、物見インフォは事前に通知することなく削除することができるものとします。<br />
（2）物見インフォは前項に基づくデータ等の削除に関し一切の責任を負わないものとします。<br />
<br />
第15条（運営が管理する設備の修理または復旧）<br />
（1）物見インフォの利用中に会員が運営の管理する設備、システムもしくは物見インフォに異常、故障または障害を発見した場合、会員は、会員自身の設備、ソフトウェア等に異常、故障または障害がないことを確認した上、運営の管理する設備もしくはシステムの修理または物見インフォの復旧を運営に依頼できるものとします。<br />
（2）運営の管理する設備、システムもしくはソフトウェアに異常、故障または障害が生じあるいは運営の管理する設備もしくはシステムが滅失または毀損し、物見インフォを提供できないことを運営が知った場合、運営は速やかにその設備もしくはシステムを修理し、物見インフォを復旧するよう努めるものとします。<br />
<br />
第16条（物見インフォの提供の中断）<br />
（1）天災、地変、その他の非常事態が発生し、または発生する虞がある場合、運営の管理する設備もしくはシステムの保守を定期的にまたは緊急に行う場合、あるいは運営の管理する設備またはシステムの障害その他やむを得ない事由が生じた場合、運営は、自らの判断により会員および利用者に対する物見インフォの提供の全部または一部を中断することができるものとします。<br />
（2）前項に定める物見インフォの提供の全部または一部の中断が、電気通信事業法第8条に従い災害の予防または救援、交通、通信もしくは電力の供給の確保等に関する通信を優先的に取扱うために行われた場合、法令または管轄官公庁の求めるところに従って行われた場合、その他運営の責めに帰すべからざる事由により行われた場合、運営はかかる物見インフォの提供の中断によって生じた会員および利用者の損害につき一切責任を負わないものとします。<br />
（3）運営は、本条第1項の規定により物見インフォの提供を中断する場合、運営が適当と判断する方法で事前に会員にその旨を通知または運営のサイト上に掲示するものとします。 但し、かかる物見インフォの提供の中断が緊急に必要な場合、またはやむを得ない事情により通知できない場合には、この限りではないものとします。<br />
<br />
第17条（損害賠償の範囲）<br />
（1）運営は、物見インフォを提供すべき場合において、運営の責に帰すべき理由により、会員に対し、物見インフォを提供しなかったときは、会員が物見インフォを全く利用できない状態にあることを運営が知った時刻から起算して、連続して8日間以上、物見インフォが全く利用できなかったときに限り、損害の賠償をします。<br />
（2）前項の場合において、運営はその料金月における基本料金額を限度として損害の賠償をします。<br />
<br />
第18条（賠償責任）<br />
会員が物見インフォの利用に起因して損害（会員が物見インフォから得た情報等に起因する損害を含むがそれに限定されない）を負うことがあっても、運営は、その原因の如何を問わず、前条（損害賠償の範囲）で規定する責任以外には、一切の賠償責任を負わないものとします。<br />
<br />
第19条（会員情報の取扱い）<br />
（1）会員は、第3条（入会）の諸手続きにおいて、運営からの会員情報の提供の要請に応じて、正確な会員情報を運営に提供するものとします。<br />
（2）会員が既に運営に届出ている会員情報に変更が生じた場合、会員は、運営が別途指示する方法により、速やかに運営に対してかかる変更を届出るものとします。<br />
（3）運営は、会員情報および履歴情報を、善良なる管理者としての注意を払って管理いたします。<br />
（4）会員は、運営が会員情報及び履歴情報を、物見インフォを提供する目的の他に、以下の各号に定める目的に利用し、または第三者に提供することがあることに同意するものとします。<br />
運営が会員または利用者に対し、物見インフォのサービス追加または変更のご案内、または緊急連絡の目的で、電子メールや郵便等で通知する場合。<br />
運営が、物見インフォに関する利用動向を把握する目的で、会員情報の統計分析を行い、個人を識別できない形式に加工して、利用又は提供する場合。<br />
法的な義務を伴う開示要求へ対応する場合。<br />
会員または利用者から事前に同意を得た場合。<br />
<br />
第20条（クッキーの利用）<br />
物見インフォは、会員または利用者の端末を特定する目的でクッキーを設定する場合があります。<br />
<br />
第21条（免責）<br />
（1）運営は、物見インフォの内容、ならびに会員および利用者が物見インフォを通じて得る情報等について、その完全性、正確性、確実性、有用性等につき、いかなる保証も行わないものとします。<br />
（2）物見インフォの提供、遅滞、変更、中止もしくは廃止、物見インフォを通じて登録、提供もしくは収集された会員および利用者の情報の消失、その他物見インフォに関連して発生した会員の損害について、運営は本則にて明示的に定める以外一切責任を負わないものとします。<br />
（3）会員または運営以外の第三者の責に帰すべき事由によって、会員が物見インフォの全部または一部を利用できないことにつき、運営は一切の責任を負いません。<br />
（4）会員が物見インフォを通じて行なった削除申請における事象について、運営は一切の責任を負いません。<br />
<br />
第22条（譲渡禁止）<br />
会員は、会員たる地位ならびに本則上会員が有する権利および義務を運営の事前の同意を得ることなく第三者に譲渡してはならないものとします。<br />
<br />
第23条（準拠法）<br />
本則の成立、効力、履行および解釈に関しては、日本国法が適用されるものとします。<br />
<br />
第24条（協議解決の原則および管轄裁判所）<br />
物見インフォに関連して会員と運営との間で問題が生じた場合には、会員と運営の間で誠意をもって協議するものとします。協議しても解決しない場合、東京地方裁判所を専属管轄裁判所とします。<br />
<br />
<br />
制定 2012年10月1日<br />
改定 2012年11月1日
		</p>
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
