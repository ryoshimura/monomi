$(function(){

	// 作品詳細情報入力フォームの表示初期化
	$("input.wordbox").each(function(idx, obj) {
		if( '' === $(obj).val() ){
			var pos		= $(obj).attr('pos');
			var rqpos	= 'div.rqpos' + pos;
			var wkpos	= '#UserWorkName' + pos;
			var ujpos	= '#UserWorkUrlJp' + pos;
			var uepos	= '#UserWorkUrlEn' + pos;
			$(rqpos).hide();
			$(wkpos).val('');
			$(ujpos).val('');
			$(uepos).val('');

		}
	});




	// 作品詳細情報入力フォームの開閉
	$("input.wordbox").change(function() { change_rqbox( this ); });
	$("input.wordbox").keyup(function() { change_rqbox( this ); });


	// 削除
	$('div.submit').click(function(){

//alert('t1');
		var obj = document.forms["UserWordsForm"];

		// 新規 or 変更
		var cnt = $("#UserWordCnt").val();

		var el;
		var flg = false;
		for( icnt=1; icnt<=cnt; icnt++ ){
			el = "#UserWord" + icnt;

			if( $(el).attr("word") !== '' && $(el).attr("word") !== $(el).val() ){
				flg = true;
				break;
			}
		}

		if( flg == false ){	// 変更されていない場合
			obj.submit();
			return true;
		}

		$.confirm({
			'title'		: '注意',
			'message'	: '監視ワードを変更すると、対象ワードの検知履歴はリセットされます。<br />変更してもよろしいですか？',
			'buttons'	: {
				'はい'	: {
					'class'	: 'blue',
					'action': function(){

//						var obj = document.forms["UserWordsForm"];
						obj.submit();

		}
				},
				'いいえ'	: {
					'class'	: 'gray',
					'action': function(){
					}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
	});


});



function change_rqbox( obj ){
	var pos		= $(obj).attr('pos');
	var rqpos	= 'div.rqpos' + pos;
	if( '' !== $(obj).val() ){
		$(rqpos).show(300);
	} else {
		$(rqpos).hide(300);
	}
}

