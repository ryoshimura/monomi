$(function(){


	$("div#sign_block").hide();



	if( $("#UserPlan").val() !== '00003' ){
		$("#campaign_block").hide();
		$("div.error-message_cmp").hide();
	}

	$("#UserPlan").change(function() {
		if( '00003' ===  $(this).val() ){
			$("#campaign_block").show(300);
			$("div.error-message_cmp").show();
			$("#total_price").html('780');
		} else {
			$("#campaign_block").hide(300);
			$("div.error-message_cmp").hide();
			if( '00001' ===  $(this).val() ){
				$("#total_price").html('780');
			} else if( '00002' ===  $(this).val() ){
				$("#total_price").html('980');
			}
		}

	});


	$("form").submit(function(ev){
		$("#buy_btn").attr("disabled", true);
		$("p.btn").empty();
		$("p.btn").html('<div class="processed">Paypalと通信中</div>');
	});

	$("a.buy_btn").click(function(ev){

		$("div.btn_block").empty();
		$("div.btn_block").html('<div class="processed">Paypalと通信中</div>');

	});


	$("div.paypal").click(function(ev){
		$("div.paypal").removeClass("border_off");
		$("div.paypal").addClass("border_on");
		$("div.epsilon").removeClass("border_on");
		$("div.epsilon").addClass("border_off");

		$("img.paypal").attr('src','/img/checkPoint.png');
		$("img.epsilon").attr('src','/img/checkPoint_off.png');
		$("div#sign_block").show(300);
		$("div.bc_paypal").show();
		$("div.bc_epsilon").hide();
		$("div#left_paypal").show();
		$("div#left_epsilon").hide();

		$("input.hiddenRadio").val( ["paypal"] );

	});
	$("div.epsilon").click(function(ev){
		$("div.epsilon").removeClass("border_off");
		$("div.epsilon").addClass("border_on");
		$("div.paypal").removeClass("border_on");
		$("div.paypal").addClass("border_off");

		$("img.paypal").attr('src','/img/checkPoint_off.png');
		$("img.epsilon").attr('src','/img/checkPoint.png');
		$("div#sign_block").show(300);
		$("div.bc_paypal").hide();
		$("div.bc_epsilon").show();
		$("div#left_paypal").hide();
		$("div#left_epsilon").show();

		$("input.hiddenRadio").val( ["epsilon"] );
	});


});

