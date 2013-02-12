jQuery(function($){
	if($('.sub_menu').exist()){
		$('.sub_menu').dropdown();
	}
	$('#mobile_menu_select').change(function(){
		$('#mobile_menu_select option:selected').each(function(){
			window.location.href = $(this).attr('value');
		});
	});
	if($('.image_uploader').exist()){
		$('.image_uploader').imageUpload();
	}
	if($('.delete_button').exist()){
		$('.delete_button').bind('click', function(){
			if(window.confirm($(this).text().replace(/\n\t/g, '').replace(/^\s+/g, '').replace(/\s+$/g, '')+' ?')){
				var form = $('<form></form>');
				form.attr('action', $(this).attr('data-prototype'));
				form.attr('method', 'POST');
				form.submit();
			}
		});
	}
	if($('.video_preview_button').exist()){
		$('.video_preview_button').videoPreviewer();
	}
});