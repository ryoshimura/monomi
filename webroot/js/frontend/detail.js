$(function(){



	// 削除
	$('img.delete').click(function(){

		var uid	= $(this).attr('uid');
		var sid	= $(this).attr('sid');
		var mode= $(this).attr('mode');
//alert(uid);
//		var number	= $(this).attr('number');

		$.confirm({
			'title'		: '削除の確認',
			'message'	: '削除してもよろしいですか？',
			'buttons'	: {
				'はい'	: {
					'class'	: 'blue',
					'action': function(){
						// ajax_delete( number, ir_id );
						url = "/users/forum_delete/?uid=" + uid + "&mode=" + mode + "&sid=" + sid;
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

}


/**
* ajax_req
*
* @param  none
* @return none
*/
function ajax_req( $ir_id, $dr_id, $flag ){

	$.get(
		"/users/aj_inbox_request",
		{
			ir_id : $ir_id,
			dr_id : $dr_id,
			flag : $flag
		},
		function(data){
//alert( data );
		}
	);
}


/**
* ajax_comp
*
* @param  none
* @return none
*/
function ajax_comp( $ir_id, $dr_id, $flag ){

	$.get(
		"/users/aj_inbox_comp",
		{
			ir_id : $ir_id,
			dr_id : $dr_id,
			flag : $flag
		},
		function(data){
//alert( data );
		}
	);
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
//alert(sub_tr);

	$.get(
		"/users/aj_inbox_restore",
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

}

