$(function(){
	function tick(){
		$('#news li:first').slideUp( function () { $(this).appendTo($('#news')).slideDown(); });
	}
	setInterval(function(){ tick () }, 5000);
});
