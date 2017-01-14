$(document).ready(function(){
	$('.block_header').corner("top");
	$('.corner').corner("9px");

	$('.link .start').click(function(){
		var url = $(this).attr('url');

		$.confirm({
			'title'		: '巡回開始の確認',
			'message'	: '巡回監視を開始してもよろしいですか？<br/><br/>【注意】<br/>開始後は、次の更新日まで作品情報を変更できません',
			'buttons'	: {
				'はい'	: {
					'class'	: 'blue',
					'action': function(){
						location.href= url;
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
