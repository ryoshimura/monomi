$(function(){

//	$("tr.sub").hide();
	$("tr.sub").show();



	// 削除
	$('img.trash').click(function(){

		var iruid	= $(this).attr('iruid');
		var currentUrl = $("#currentUrl").val();
		var url = "/users/inbox_delete/?iruid=" + iruid + "&currentUrl=" + currentUrl;

		$.confirm({
			'title'		: '削除の確認',
			'message'	: '削除してもよろしいですか？',
			'buttons'	: {
				'はい'	: {
					'class'	: 'blue',
					'action': function(){
//						ajax_delete( url );
						location.href = url;
					}
				},
				'いいえ'	: {
					'class'	: 'gray',
					'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});

	});



	// 監視トレイに戻す
	$('.restore').click(function(){

		var ir_id	= $(this).attr('ir_id');
		var number	= $(this).attr('number');

		$.confirm({
			'title'		: '確認',
			'message'	: '監視トレイに戻しますか？',
			'buttons'	: {
				'はい'	: {
					'class'	: 'blue',
					'action': function(){
						ajax_restore( number, ir_id );
					}
				},
				'いいえ'	: {
					'class'	: 'gray',
					'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
	});



	// 削除要請中ボタン
	$('span.request').click(function(){
		if( 'request tg_off' === $(this).attr('class') ){
			ajax_req( $(this).attr('ir_id'), $(this).attr('dr_id'), 'Enable' );		// タグを有効にする
			$(this).attr('class','request tg_on');
		} else {
			ajax_req( $(this).attr('ir_id'), $(this).attr('dr_id'), 'Disable' );	// タグを無効にする
			$(this).attr('class','request tg_off');
		}
	});


	// 削除完了ボタン
	$('span.completion').click(function(){
		if( 'completion tg_off' === $(this).attr('class') ){
			ajax_comp( $(this).attr('ir_id'), $(this).attr('dr_id'), 'Enable' );		// タグを有効にする
			$(this).attr('class','completion tg_on');
		} else {
			ajax_comp( $(this).attr('ir_id'), $(this).attr('dr_id'), 'Disable' );	// タグを無効にする
			$(this).attr('class','completion tg_off');
		}
	});





});





/**
* ajax_delete
*
* @param  none
* @return none
*/
function ajax_delete( url ){

//	var main_tr = "tr." + number;
//	var sub_tr	 = "tr." + number.replace("snum", "main");
//alert(sub_tr);
//	prm = 'ir_id=' + ir_id;
	prm = 'url=' + url;

	$.ajax({
		type: "GET",
		url: "/users/aj_inbox_del",
		cache : false,
		data: prm,
		success: function(msg){
			// div#dlsite_imgで囲ったimgと置換
//			$(sub_tr).empty();
//			$(main_tr).empty();
		}
	});


/*
	$.get(
		"/users/aj_inbox_del",
		{
			ir_id : ir_id
		},
		function(data){
//alert(data);

			// div#dlsite_imgで囲ったimgと置換
			$(sub_tr).empty();
			$(main_tr).empty();

		}
	);
*/
}


/**
* ajax_req
*
* @param  none
* @return none
*/
function ajax_req( ir_id, dr_id, flag ){

	$.ajax({
		type: "POST",
		url: "/users/aj_inbox_request",
		cache : false,
		data: {
			'ir_id'	: ir_id,
			'dr_id'	: dr_id,
			'flag'	: flag
		},
		success: function(msg){
//alert(msg);
		}
	});

}


/**
* ajax_comp
*
* @param  none
* @return none
*/
function ajax_comp( ir_id, dr_id, flag ){

	$.ajax({
		type: "POST",
		url: "/users/aj_inbox_comp",
		cache : false,
		data: {
			'ir_id'	: ir_id,
			'dr_id'	: dr_id,
			'flag'	: flag
		},
		success: function(msg){
//alert(msg);
		}
	});

}




/**
* ajax_restore
*
* @param  none
* @return none
*/
function ajax_restore( number, ir_id ){

	var main_tr = "tr." + number;
	var sub_tr	 = "tr." + number.replace("snum", "main");

	prm = 'ir_id=' + ir_id;

	$.ajax({
		type: "GET",
		url: "/users/aj_inbox_restore",
		cache : false,
		data: prm,
		success: function(msg){
//alert(msg);
			// div#dlsite_imgで囲ったimgと置換
//alert(sub_tr);
//alert(main_tr);
			$(sub_tr).empty();
			$(main_tr).empty();
		}
	});
/*
	$.get(
		"/users/aj_inbox_restore",
		{
			ir_id : ir_id
		},
		function(data){
			// div#dlsite_imgで囲ったimgと置換
			$(sub_tr).empty();
			$(main_tr).empty();

		}
	);
*/
}

