$(function(){

	$("form").submit(function(ev){
		$("#buy_btn").attr("disabled", true);
		$("p.btn").empty();
		$("p.btn").html('<div class="processed">Paypalと通信中</div>');
	});


});

