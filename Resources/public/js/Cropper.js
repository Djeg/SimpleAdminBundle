var JCROP = undefined;

(function($){
	$.fn.Cropper = function(){
		var form = this;

		var updateCrop = function(i, c){
			$('.cropper').eq(i).val(JSON.stringify(c));
		}

		form.find('.cropper').each(function(i){
			var cf = window.CROPPER[i];
			var image = form.find('.image_preview');
			if(image.eq(i).exist()){
				image.eq(i).Jcrop($.extend({
					onChange: function(c){
						updateCrop(i, c)
					} 
				}, CROPPER[i]), function(){
					window.JCROP = this;
				});
			}
		});
	}
})(jQuery);