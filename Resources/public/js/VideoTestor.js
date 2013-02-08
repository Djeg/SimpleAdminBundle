var VideoTestor = function(){

	var testVideo = function(){
		var self = this;
		var display = self.prev();
		if(!display.exist()){
			$('<div></div>').attr('class', 'video_preview').insertBefore(self);
			display = self.prev();
		}

		var textarea = self.prev().prev();
		var iframe = $(textarea.val());
		if(iframe.exist()){
			
		} else {
			textarea.insertAfter(['<div class="alert" style="display:none;">',
				'<button type="button" class="close" data-dismiss="alert">&times;</button>',
				'<strong>Bad link !</strong>',
				'</div>'].join(''));
			textarea.find('.alert').slideDown('slow');
		}
	}


	$('.test_button').bind('click', testVideo);
}