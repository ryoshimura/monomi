$(document).ready(function(){
	$('.block_header').corner("top");
	$('.corner').corner("9px");

	calprice();

	$('#LicenseLicensesWorks').change(function(ev){
		calprice();
	});

});

function calprice(){
	price = parseInt($('#PlanPlanPrice').val()) + ( parseInt($('#PlanOptionPrice').val()) * ( parseInt($('#LicenseLicensesWorks').val())-1 ) )
	$('span.price').text( addFigure(price) );
}

function addFigure(str) {
	var num = new String(str).replace(/,/g, "");
	while(num != (num = num.replace(/^(-?\d+)(\d{3})/, "$1,$2")));
	return num;
}