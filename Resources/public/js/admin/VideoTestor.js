(function($){
	$.fn.videoPreviewer = function(){

		var _this = this;

		var preview = _this.parent().find('video_preview');

		var textarea = _this.parent().find('textarea');

		var displayError = function (){
			$(['<div class="alert alert-error">',
					'<button type="button" class="close" data-dismiss="alert">&times;</button>',
					'<p>Error !</p>',
					'</div>'].join('')).insertAfter(textarea);
		}

		var displayPreview = function (){
			// Get the iframe :
			var content = textarea.val();
			if(content.search(/^<iframe(.*)/) == -1){
				displayError();
				return;
			}
			var iframe = $(content).eq(0);
			if(iframe.exist() && iframe.is('iframe')){
				textarea.val($('<div></div>').html(iframe).html());
				$('<div class="video_preview"></div>').css({
					'display': 'none', 
					"background-color":"black",
					'width': iframe.attr('width'),
					'height': iframe.attr('height')
				}).html(iframe).insertAfter(textarea).slideDown('slow');
			} else {
				displayError();
			}

		}

		_this.click(function(){
			if(_this.parent().find('alert').exist()){
				_this.parent().find('alert').remove();
			}
			if(_this.parent().find('.video_preview').exist()){
				_this.parent().find('.video_preview').slideUp('slow', function(){
					$(this).remove();
					displayPreview();
				});
			} else {
				displayPreview();
			}
		})

		return _this;

	}
})(jQuery);