$(document).ready(function(){
	$('.block_header').corner("7px,top");
	$('.corner').corner("9px");


	if ( "prsn" === $('input[name="data[UserEditSchema][user_type]"]:checked').val() ){
		$('.corporation_element').hide();
		$('.individual_element').show();
	} else {
		$('.corporation_element').show();
		$('.individual_element').hide();
	}

//	$('.corporation_element').hide();

	$('#UserEditSchemaUserTypePrsn').click(function(){
		$('.corporation_element').hide();
		$('.individual_element').show();
	});
	$('#UserEditSchemaUserTypeCorp').click(function(){
		$('.corporation_element').show();
		$('.individual_element').hide();
	});
});