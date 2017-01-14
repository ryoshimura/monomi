$(function(){

	// 初期化
	template_change( $("#templateSelect").children(':selected').val() );

	if( '' !== $("#TempDestination2").val() ){
		$("#TempDes").attr('class', 'minus');
		$("p.ndes").show();
	} else {
		$("p.ndes").hide();
	}

	if( '' !== $("#TempCC2").val() ){
		$("#TempCC").attr('class', 'minus');
		$("p.ncc").show();
	} else {
		$("p.ncc").hide();
	}



	$("p.ncc").hide();

	// テンプレート変更
	$("#templateSelect").change(function() {
		template_change( $(this).val() );
	});

	// 宛先の開閉
	$("#TempDes").click(function(ev){
		var cls;
		cls = $(this).attr('class');
		if( cls === 'plus' ){	// 開くアクション
			$(this).attr('class', 'minus');
			$("p.ndes").show();
		} else if( cls === 'minus' ){	// 閉じるアクション
			$(this).attr('class', 'plus');
			$("p.ndes").hide();
		}
	});

	// CCの開閉
	$("#TempCC").click(function(ev){
		var cls;
		cls = $(this).attr('class');
		if( cls === 'plus' ){	// 開くアクション
			$(this).attr('class', 'minus');
			$("p.ncc").show();
		} else if( cls === 'minus' ){	// 閉じるアクション
			$(this).attr('class', 'plus');
			$("p.ncc").hide();
		}
	});


	// 送信ボタン押下
	$("#templateSendMail").click(function() {

		var obj = document.forms["UserSendcompForm"];
		var sendText = $("#TempBodyText").val();
		var notice = new String();
		notice = '';

		if( sendText.match(/【.*?】/i) != null ){
			notice += '<span class="red">文中の【****】を正しい文字に置き換えてください。</span><br />';
		}


		if( $("#TempDestination1").val()==='' && $("#TempDestination2").val()==='' && $("#TempDestination3").val()==='' ){
			notice += '<span class="red">宛先が全て空欄です。</span><br />';
		}

		if( notice !== '' ){
			$("#templateBodyNotice").html( notice );
		} else {
			obj.submit();
//			return true;
		}
	});


	$("#tmpSubImg").zclip({
		path:'/js/jquery/plugin/ZeroClipboard.swf',
		copy:function(){return $('#TempSubject').val();}
	});

	$("#tmpBodyImg").zclip({
		path:'/js/jquery/plugin/ZeroClipboard.swf',
		copy:function(){return $('#TempBodyText').val();}
	});

});




/**
* template_change
*
* @param  none
* @return none
*/
function template_change( template_uid ){

//	var prm = 'mode=' + $('#TempHiddenMode').val() + '&uid=' + $('#TempHiddenUid').val() + '&tmp_uid=' + template_uid + '&site_name=' + $('#TempHiddenNotFound').val();
	var prm = 'mode=' + $('#TempHiddenMode').val() + '&uid=' + $('#TempHiddenUid').val() + '&tmp_uid=' + template_uid + '&site_name=' + $('#TempHiddenNotFound').val() + '&iruid=' + $('#TempHiddenIrUid').val();


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
			$('#TempSubject').val( tmp['Template']['subject'] );
			// メール本文変更 #templateTextBody
			$('#TempBodyText').val( tmp['Template']['body_text'] );


		}
	});
}






