(function($){
	$.fn.dropdown = function(){
		var current = this;
		var menu = {
			coordinate : {
				x: current.prev().offset().left,
				y: current.prev().offset().top,
			},
			height: current.prev().outerHeight(true)
		}
		current.prev().mouseenter(function(){
			current.finish();
			current.css({
				'position': 'absolute',
				'top': menu.coordinate.y + menu.height + 20 + 'px',
				'left': menu.coordinate.x-($(current).outerWidth(true)/2)
			}).slideDown('fast');
		});
		current.prev().mouseleave(function(e){
			if(e.pageX > current.offset().left && e.pageX < current.offset().left+current.outerWidth(true)){
				if(e.pageY < current.offset().top + current.outerHeight(true) && e.pageY > current.offset().top-25){
					current.mouseleave(function(){
						current.slideUp('fast');
					});
				} else {	
					current.finish();
					current.slideUp('fast');
				}
			} else {	
				current.finish();
				current.slideUp('fast');
			}
		})
		return current;
	}
	$.fn.exist = function(){return this.length>0;}
})(jQuery)