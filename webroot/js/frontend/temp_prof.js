$(function(){


	$("#UserProfilePriorityWord").click(function(ev){

		var checked = $(this).attr('checked');

		if( checked == true ){
			// 作品名・作品URLボックスをreadonlyにしてCSSを変更
			$("#UserProfileWorkNameJp").attr("readonly", true);
			$("#UserProfileWorkUrlJp").attr("readonly", true);
			$("#UserProfileWorkUrlEn").attr("readonly", true);
			$("#UserProfileWorkNameJp").addClass("readonly");
			$("#UserProfileWorkUrlJp").addClass("readonly");
			$("#UserProfileWorkUrlEn").addClass("readonly");
		} else {
			// 作品名・作品URLボックスをnormal戻してCSSを変更
			$("#UserProfileWorkNameJp").attr("readonly", false);
			$("#UserProfileWorkUrlJp").attr("readonly", false);
			$("#UserProfileWorkUrlEn").attr("readonly", false);
			$("#UserProfileWorkNameJp").removeClass("readonly");
			$("#UserProfileWorkUrlJp").removeClass("readonly");
			$("#UserProfileWorkUrlEn").removeClass("readonly");
		}



	});




});