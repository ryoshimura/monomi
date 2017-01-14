$(document).ready(function(){
	$('.block_header').corner("top");
	$('.corner').corner("9px");

	// if で works_idがsetされている場合、登録済みworks_imageをAJAXでcall

	// デリートボタン押下処理
	$('.imageDelete').click(function(ev){
		procDelete();
	});

});

function dltBtnLoad( id ){
	$(function(){
		var baseElement = 'div#' + id;
		$( baseElement + ' .imageDelete' ).click(function(ev){
			procDelete( id, $( baseElement + ' .progressHidden input' ).val() );
		});
	});
}

function procDelete( id, img_id ){
	$(function(){
		$('#submit_block input').css({ 'display':'none' });
		var element = '/watchconfs/img_delete/?img_id=' + img_id;

		$.get( element, {
		}, function(rs){
			if(rs){
				$( 'div#' + id ).css({ 'opacity':'0', 'height':'0px', 'display':'none' });	// 対象サムネイルを非表示
				$( 'div#' + id + ' .progressHidden' ).empty();								// post用hiddenを削除
				swfu.setFileUploadLimit( parseInt(swfu.settings.file_upload_limit) + 1 );	// アップロード制限数を＋１
				$('#submit_block input').css({ 'display':'inline' });
			} else {
				alert( '削除に失敗しました' );
				$('#submit_block input').css({ 'display':'inline' });
			}
		});
	});

}

var swfu;
var site_url = "http://acurs.jp/";
window.onload = function() {

	var workid;
	$(function(){
		workid = $( 'input#WorkWorkUid' ).val();
	});

  var settings = {
    flash_url : site_url + "js/swfupload/swfupload.swf",
    upload_url: site_url + "watchconfs/img_upload/",	// Relative to the SWF file
//    post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
//    post_params: {"worksid" : "<?php echo $worksid; ?>"},
    post_params: {"worksid" : workid },
    file_size_limit : "3 MB",
//    file_size_limit : "100 KB",
    file_types : "*.jpg;*.jpeg;*.gif;*.png",
    file_types_description : "Image Files",
    file_upload_limit : 300,						// 第二フェーズにて、アップロード制限数を解放
    file_queue_limit : 0,
    custom_settings : {
      progressTarget : "fsUploadProgress",
//      cancelButtonId : "btnCancel"
    },
    debug: false,

    // Button settings
    button_image_url: site_url + "img/swfupload_btn.png",	// Relative to the Flash file
    button_width: "270",
    button_height: "30",
    button_placeholder_id: "spanButtonPlaceHolder",
//    button_text: '<span class="theFont">イメージファイルを選択</span>',
    button_text_style: ".theFont { font-size: 16; }",
    button_text_left_padding: 12,
    button_text_top_padding: 3,

    // The event handler functions are defined in handlers.js
    file_queued_handler : fileQueued,
    file_queue_error_handler : fileQueueError,
    file_dialog_complete_handler : fileDialogComplete,
    upload_start_handler : uploadStart,
    upload_progress_handler : uploadProgress,
    upload_error_handler : uploadError,
    upload_success_handler : uploadSuccess,
    upload_complete_handler : uploadComplete,
    queue_complete_handler : queueComplete	// Queue plugin event
  };

  swfu = new SWFUpload(settings);
};


