$(function(){


	$("#paypalBtn").click(function(){
		ajax_restore( $("#UserToken").val() );
	});

});


/**
* ajax_restore
*
* @param  none
* @return none
*/
function ajax_restore( token ){

	var url = 'https://www.sandbox.paypal.com/incontext?token=' + token;

	// Sample AJAX code using jQuery (any AJAX
	// library works)
	$.ajax({
		url: url,
		type: 'GET',
		error: function () {
			// Handle error cases
alert("error");
		},
		success: function (response){
			// Replace content on page, initiate
			// download, etc.
alert("success");
		}
	});

}