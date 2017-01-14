$(function(){

	$("p.body").hide();

	// 行の開閉
	$("span.detail").click(function(ev){

		var span_class;
		var number;

		span = $(this).text();

		if( span === '詳細' ){	// 開くアクション

			$(this).text("閉じる");
			number = "." + $(this).attr('num');
			$(number).show();

		} else if( span === '閉じる' ){	// 閉じるアクション

			$(this).text("詳細");
			number = "." + $(this).attr('num');
			$(number).hide();

		}
	});




});