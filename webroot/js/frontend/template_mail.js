(function($){

	$.template = function(params){

		if($('#confirmOverlay').length){
			// A confirm is already shown on the page:
			return false;
		}

		var markup = [
			'<div id="templateOverlay">',
			'<div id="templateBox">',

			'<div id="templateHeader">',
			'<h2 id="templateTitle"></h2>',
			'<img class="tempDel" src="/img/temp_delete.png" alt="削除" width="32" height="32" />',
			'</div>',

			'<div id="templateNotice">',
			'<div id="templateInnerNotice">',
			'<img src="/img/loading_mini.gif" />　テンプレート読込中',
			'</div>',
			'<input id="templateHiddenUid" type="hidden" name="templateHiddenUid" value="">',
			'<input id="templateHiddenMode" type="hidden" name="templateHiddenMode" value="">',
			'</div>',

			'<div id="templateLeft">',
			'<ul>',
			'<li><label>テンプレート</label>',
			'<select id="templateSelect" name="templateSelect">',
			'</select>',
//			'<select name="templateSelect" id="templateSelect"><option value="00001">DMCA準拠書式（英語）</option><option value="00002">簡易削除申請書式（英語）</option><option value="00003">簡易削除申請書式（日本語）</option></select>',
			'</li>',
			'<li><label>送信者</label><input class="tb" id="templateSender" type="text" name="sender" /><img id="tmpSdImg" class="templaeteCopyImg" src="/img/copy.gif" alt="copy" width="50" height="26" /></li>',
//			'<li><label>宛先</label><input id="templateDestination" type="text" name="destination" readonly="readonly" /><img id="tmpDsImg" class="templaeteCopyImg" src="/img/copy.gif" alt="copy" width="50" height="26" /></li>',
//			'<li><label>宛先</label><input id="templateDestination" type="text" name="destination" /><img id="tmpDsImg" class="templaeteCopyImg" src="/img/copy.gif" alt="copy" width="50" height="26" /></li>',
			'<li><label>宛先</label><input class="tb" id="templateDestination" type="text" name="destination" /><img id="tmpDsImg" class="templaeteCopyImg" src="/img/copy.gif" alt="copy" width="50" height="26" /></li>',
			'<li><label>件名</label><input class="tb" id="templateSubject" type="text" name="subject" /><img id="tmpSjImg" class="templaeteCopyImg" src="/img/copy.gif" alt="copy" width="50" height="26" /></li>',
			'</ul>',


//			'<div class="tempInput"><label>テンプレート</label><select name="templateSelect" id="templateSelect"><option value="00001">DMCA準拠書式（英語）</option><option value="00002">簡易削除申請書式（英語）</option><option value="00003">簡易削除申請書式（日本語）</option></select></div>',
//			'<div class="tempInput"><label>送信者</label><input class="tb" id="templateSender" type="text" name="sender" /><img id="tmpSdImg" class="templaeteCopyImg" src="/img/copy.gif" alt="copy" width="50" height="26" /></div>',
//			'<div class="tempInput"><label>宛先</label><input id="templateDestination" type="text" name="destination" readonly="readonly" /><img id="tmpDsImg" class="templaeteCopyImg" src="/img/copy.gif" alt="copy" width="50" height="26" /></div>',
//			'<div class="tempInput"><label>件名</label><input class="tb" id="templateSubject" type="text" name="subject" /><img id="tmpSjImg" class="templaeteCopyImg" src="/img/copy.gif" alt="copy" width="50" height="26" /></div>',

			'</div>',
			'<div id="templateRight">',
			'<p id="templateBtnP"><span id="templateSendMail">メール送信</span></p>',
			'<p id="templateBtnSubP"></p>',
			'<p id="templateBtnInfo"></p>',
			'<p class="templateProf"><a href="/users/template_prof/">プロフィールを編集する</a></p>',
			'</div>',

			'<div id="templateBody">',
			'<textarea id="templateTextBody" name="body"></textarea>',
			'</div>',

			'<div id="templateFooter">',
			'<input id="templateTransfer" type="checkbox" name="templateTransfer" value="transfer" checked="checked"><label for="templateTransfer">送信者アドレスにも送信する</label>',
			'<img id="tmpTbImg" class="templaeteCopyImg" src="/img/copy.gif" alt="copy" width="50" height="26" />',
			'</div>',

			'<div id="templateHidden">',
			'</div>',

			'</div></div>'
		].join('');

		$(markup).hide().appendTo('body').fadeIn();


		$('img.tempDel').click(function(){
			$('#templateOverlay').fadeOut(function(){
				$(this).remove();
			});
		});


		$("#tmpSdImg").zclip({
			path:'/js/jquery/plugin/ZeroClipboard.swf',
			copy:function(){return $('#templateSender').val();}
		});

		$("#tmpDsImg").zclip({
			path:'/js/jquery/plugin/ZeroClipboard.swf',
			copy:function(){return $('#templateDestination').val();}
		});

		$("#tmpSjImg").zclip({
			path:'/js/jquery/plugin/ZeroClipboard.swf',
			copy:function(){return $('#templateSubject').val();}
		});

		$("#tmpTbImg").zclip({
			path:'/js/jquery/plugin/ZeroClipboard.swf',
			copy:function(){return $('#templateTextBody').val();}
		});




		// AJAX初期化
		template_initial( params.uid, params.mode );


		// テンプレート変更
		$("#templateSelect").change(function() {
			template_change( $(this).val() );
		});


		// 送信ボタン押下
		$("#templateSendMail").click(function() {
			var sendText = $("#templateTextBody").val();

			if( sendText.match(/【.*?】/i) != null ){
				$("#templateInnerNotice").html('<span class="red">置換されていない文字があります。<br />【****】を正しい文字に置き換えてください。</span>');
			} else {
				template_send();
			}
		});

	}

//	$.confirm.hide = function(){
	$.template.hide = function(){
		$('#templateOverlay').fadeOut(function(){
			$(this).remove();
		});
	}



})(jQuery);



/**
* template_initial
*
* @param  none
* @return none
*/
function template_initial( uid, mode ){

	var prm = 'mode=' + mode + '&uid=' + uid;

	$.ajax({
		type: "GET",
		url: "/users/aj_template_initial",
		cache : false,
		data: prm,
		success: function(res){
//alert(res);
			var data = eval("("+res+")");
			var tmp  = data['tmp'];
			var list = data['list'];
			var site = data['site'];
			var prof = data['prof'];


			// テンプレート書式プルダウンの初期化
			$("#templateSelect").children().remove();	// プルダウンを一度全てクリア
			select_box = window.document.getElementById("templateSelect");
			var count = 0;
			for (var i = 0; i < list.length; i++) {
				select_box.options[count++] = new Option(list[i]['Template']['template_name'],list[i]['Template']['template_uid']);
			}

			// 送信者初期化 #templateSender
			$('#templateSender').val( prof['sender'] );
			// 宛先初期化 #templateDestination
			if( prof['destination'] == null ){
				prof['destination'] = '';
			}
			$('#templateDestination').val( prof['destination'] );
			// 件名初期化 #templateSubject
			$('#templateSubject').val( tmp['Template']['subject'] );
			// メール本文初期化 #templateTextBody
			$('#templateTextBody').val( tmp['Template']['body_text'] );
			// HiddenUid初期化 #templateHiddenUid
			$('#templateHiddenUid').val( uid );
			// HiddenMode初期化 #templateHiddenMode
			$('#templateHiddenMode').val( mode );


			// windowタイトル
			$('#templateTitle').text( '削除申請： ' + prof['site_name'] );

			// 送信ボタン
			if( prof['destination'] === false || prof['destination'] === '' || prof['destination'] === null ){

				if( prof['contactus'] === false || prof['contactus'] === '' || prof['contactus'] === null ){
					$('#templateBtnP').html( '<span class="red">問合せ手段が見つかりませんでした。</span><br />メールアドレスや問合せフォームなど<br />ご存知であれば、<a href="/users/contact/">こちらまで</a>情報提供<br />お願いします。' );
				} else {
					$('#templateBtnP').html( 'こちらの<a href="'+ prof['contactus'] +'" target="_blank">問合せフォーム</a>に必要項目を<br />コピーして削除申請してください' );
				}

			} else {
				if( prof['contactus'] !== false && prof['contactus'] !== '' && prof['contactus'] !== null ){
					$('#templateBtnSubP').html('または<a href="'+ prof['contactus'] +'" target="_blank">こちら</a>から<br />削除申請してください');
				}

			}

			// templateNotice変更
			$('#templateInnerNotice').html( '必ず著作権侵害を確認してから申請してください。' );

			// アップロードURLをhidden add 2012.10.04
			var aryUrl = tmp['Template']['aryUploadUrl'];
			var aryId;
			var hdnHtml;
			for( i = 0; i < aryUrl.length; i++ ){
				aryId = 'hdn_' + i;
				hdnHtml = '<input type="hidden" class="templateHidden" name="' + aryId + '" value="' + aryUrl[i] + '" />';
				$('#templateHidden').append( hdnHtml );
			}
		}
	});




}



/**
* template_change
*
* @param  none
* @return none
*/
function template_change( template_uid ){

	var prm = 'mode=' + $('#templateHiddenMode').val() + '&uid=' + $('#templateHiddenUid').val() + '&tmp_uid=' + template_uid;

	$.ajax({
		type: "GET",
		url: "/users/aj_template_change",
		cache : false,
		data: prm,
		success: function(res){
//alert(res);
			var data = eval("("+res+")");
			var tmp  = data['tmp'];

			// 件名変更 #templateSubject
			$('#templateSubject').val( tmp['Template']['subject'] );
			// メール本文変更 #templateTextBody
			$('#templateTextBody').val( tmp['Template']['body_text'] );


		}
	});
}



/**
* template_send
*
* @param  none
* @return none
*/
function template_send(){

//	'<p id="templateBtnP"><span id="templateSendMail">メール送信</span></p>',
//	'<p id="templateBtnInfo"></p>',

	var url;

	// 送信中に変更
	$('#templateBtnP').html( '<img src="/img/sending.gif" alt="送信中" width="140" height="60" />' );
	$('#templateBtnInfo').html( '<img src="/img/loading_mini.gif" />　しばらくお待ちください' );
	$('#templateInnerNotice').html( '<p>メール送信中です。</p>' );

//alert( $('#templateTransfer').val() );
//var checked = $('#templateTransfer').attr('checked');
//alert(checked);
//alert('start send');
/*
alert( $('#templateSender').val() );
alert( $('#templateDestination').val() );
alert( $('#templateSubject').val() );
alert( $('#templateTextBody').val() );
alert( $('#templateTransfer').attr('checked') );
*/


	$.ajax({
		type: "POST",
		url: "/users/aj_template_send",
		cache : false,
		data: {
			'templateSender'		: $('#templateSender').val(),
			'templateDestination'	: $('#templateDestination').val(),
			'templateSubject'		: $('#templateSubject').val(),
			'templateTextBody'		: $('#templateTextBody').val(),
			'templateTransfer'		: $('#templateTransfer').attr('checked')
		},
		success: function(res){
//alert(res);

			// 送信したURLに属する「削除要請中」タグをEnableする
			$('input.templateHidden').each(function(i){
//				alert($(this).val());
				url = $(this).val();

				$('span.request').each(function(j){

					if( url === $(this).attr('url') ){
						// ajax_req( ir_id, dr_id, flag )
						ajax_req( $(this).attr('ir_id'), $(this).attr('dr_id'), 'Enable' );
						$(this).attr('class','request_on req');
					}
				});
			});

			// 送信済に変更
			$('#templateBtnP').html( '<img src="/img/send_comp.gif" alt="送信しました" width="140" height="60" />' );
			$('#templateBtnInfo').html( '' );
			$('#templateInnerNotice').html( 'メールを送信しました。右上の×ボタンから戻ってください。' );

		}
	});

}




