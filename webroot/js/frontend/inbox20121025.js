$(function(){

//	$("tr.sub").hide();
	$("tr.sub").show();




	// 行の開閉
	$("img.open").click(function(ev){

		var img_src;
		var number;

		img_src = $(this).attr('src');
		if( img_src === '/img/plus.gif' ){	// 開くアクション
			$(this).attr('src', '/img/minus.gif');
			number = "tr." + $(this).attr('number');
			$(number).show();
		} else if( img_src === '/img/minus.gif' ){	// 閉じるアクション
			$(this).attr('src', '/img/plus.gif');
			number = "tr." + $(this).attr('number');
			$(number).hide();
		}
	});


	// 行の開閉（全て）
	$("img.open_all").click(function(ev){
		img_src = $(this).attr('src');
		if( img_src === '/img/plus.gif' ){	// 開くアクション
			$(this).attr('src', '/img/minus.gif');
			$("img.open").attr('src', '/img/minus.gif');
			$("tr.sub").show();
		} else if( img_src === '/img/minus.gif' ){	// 閉じるアクション
			$(this).attr('src', '/img/plus.gif');
			$("img.open").attr('src', '/img/plus.gif');
			$("tr.sub").hide();
		}
	});


	// 削除
	$('img.trash').click(function(){

		var ir_id	= $(this).attr('ir_id');
		var number	= $(this).attr('number');

		$.confirm({
			'title'		: '削除の確認',
			'message'	: '削除してもよろしいですか？',
			'buttons'	: {
				'はい'	: {
					'class'	: 'blue',
					'action': function(){
						ajax_delete( number, ir_id );
					}
				},
				'いいえ'	: {
					'class'	: 'gray',
					'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
	});



	// テンプレート表示
	$('img.template').click(function(){

		var uid = $(this).attr('uid')
		var mode = $(this).attr('mode')

//		prm = 'mode=' + mode + '&uid=' + uid;

		$.template({
//			'getPrm'	: prm,
			'uid'		: uid,
			'mode'		: mode
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
	$('span.req').click(function(){
		if( 'request req' === $(this).attr('class') ){
			ajax_req( $(this).attr('ir_id'), $(this).attr('dr_id'), 'Enable' );		// タグを有効にする
			$(this).attr('class','request_on req');
		} else {
			ajax_req( $(this).attr('ir_id'), $(this).attr('dr_id'), 'Disable' );	// タグを無効にする
			$(this).attr('class','request req');
		}
	});


	// 削除完了ボタン
	$('span.comp').click(function(){
		if( 'completion comp' === $(this).attr('class') ){
			ajax_comp( $(this).attr('ir_id'), $(this).attr('dr_id'), 'Enable' );		// タグを有効にする
			$(this).attr('class','completion_on comp');
		} else {
			ajax_comp( $(this).attr('ir_id'), $(this).attr('dr_id'), 'Disable' );	// タグを無効にする
			$(this).attr('class','completion comp');
		}
	});





});





/**
* ajax_delete
*
* @param  none
* @return none
*/
function ajax_delete( number, ir_id ){

	var main_tr = "tr." + number;
	var sub_tr	 = "tr." + number.replace("snum", "main");
//alert(sub_tr);

	prm = 'ir_id=' + ir_id;

	$.ajax({
		type: "GET",
		url: "/users/aj_inbox_del",
		cache : false,
		data: prm,
		success: function(msg){
			// div#dlsite_imgで囲ったimgと置換
			$(sub_tr).empty();
			$(main_tr).empty();
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

	var prm;
	prm = 'ir_id=' + ir_id + '&dr_id=' + dr_id + '&flag=' + flag;

	$.ajax({
		type: "GET",
		url: "/users/aj_inbox_request",
		cache : false,
		data: prm,
		success: function(msg){
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

	var prm;
	prm = 'ir_id=' + ir_id + '&dr_id=' + dr_id + '&flag=' + flag;

	$.ajax({
		type: "GET",
		url: "/users/aj_inbox_comp",
		cache : false,
		data: prm,
		success: function(msg){
		}
	});
/*
	$.get(
		"/users/aj_inbox_comp",
		{
			ir_id : ir_id,
			dr_id : dr_id,
			flag : flag
		},
		function(data){
		}
	);
*/
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

