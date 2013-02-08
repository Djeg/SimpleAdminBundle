(function($){
	$.fn.imageUpload = function(){
		// Test if the file upload API is supported :
		if(!window.File || !window.FileReader || !window.FileList || !window.Blob){
			alert('You web navigator doesn\'t support HTML 5 file API. Please download a new navigator like Firefox, Chrome or Opera for enable this features !');
		}

		var _this = this;

		var fileInput = _this.find('.uploader').eq(0);

		var cropper = _this.parent().parent().parent().find('.cropper').eq(0);


		var updateCrop = function(c, image){
			var img = _this.find('.image_preview').eq(0);
			if(image.height > img.height()){
				var r = image.height/img.height();
				c.y = c.y * r;
				c.y2 = c.y2 * r;
				c.h = c.h * r;
			}
			if(image.width > img.width()){
				var r = image.width/img.width();
				c.x = c.x * r;
				c.x2 = c.x2 * r;
				c.w = c.w * r;
			}
			cropper.val(JSON.stringify(c));
		}

		var changeImage = function (){
			_this.find('.change_image').slideUp('slow', function(){
				$(this).remove();
			});
			_this.find('.jcrop-holder').slideUp('slow', function(){
				$(this).remove();
			})
			if(cropper.exist()){
				cropper.val('');
			}
			_this.find('.image_preview').slideUp('slow', function(){
				$(this).remove();
				fileInput.html(fileInput.val('').html());
				_this.find('.uploader').slideDown('fast');
			})
		}

		fileInput.change(function(){
			var file = this.files[this.files.length-1];
			// Test file type :
			if(!file.type.match('image.*')){
				return;
			}
			fileInput.slideUp('slow', function(e){
				var reader = new FileReader();
				reader.onloadend = function(e){
					var output = [
						'<img src="',
						e.target.result,
						'" title="',
						escape(file.name),
						'" class="image_preview" style="display:none;" />',
						'<input type="button" class="btn change_image" value="Change image" style="display:none;"/>'
					];
					_this.prepend(fileInput.html() + output.join(''));
					var imagePreview = _this.find('.image_preview').eq(0);
					var image = new Image();
					image.src = imagePreview.attr('src');
					image.onload = function(){
						var imgHeight = imagePreview.outerHeight(true) + 30;
						console.log('Image Height : '+imgHeight);
						var height = imagePreview.parent().height();
						var marginTop = (height-imgHeight)/2;
						if( (imgHeight + marginTop) > height){
							marginTop = 0;
						}
						imagePreview.css({'margin-top': marginTop+'px'});
						_this.find('.image_preview')
							.slideDown('slow', function(){
								$(this).Jcrop($.extend(
									{onChange: function(c){ updateCrop(c, image)}},
									$.parseJSON(cropper.attr('data-prototype'))
								));
								_this.find('.change_image')
									.slideDown('slow')
									.bind('click', changeImage);
						});
					}
				}
				reader.readAsDataURL(file);
			});
		});

		if(_this.find('.change_image').exist()){
			_this.find('.change_image').bind('click', changeImage);
		}

		if(_this.find('.image_preview').exist() && $('.cropper').exist()){

		}

		return _this;
	}
})(jQuery);