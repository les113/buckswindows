	// lightsout - dim slideshow when menu hovered 
	
	$(document).ready(function(){
			$('#cssmenu').mouseenter(function() {
				if(!$('#slideshowcontainer').hasClass('lights-out')){
					$('#slideshowcontainer').addClass('lights-out');
				}
			}).mouseleave(function() {
				$('#slideshowcontainer').removeClass('lights-out');
			});
	});
	
	// dim gallery thumbs when one is hovered 
	
	$(document).ready(function(){
			$('.gallerythumbs').mouseenter(function() {
				if(!$('.gallerythumbs').hasClass('lights-out')){
					$('.gallerythumbs').not(this).addClass('lights-out');			
				}
			}).mouseleave(function() {
				$('.gallerythumbs').removeClass('lights-out');
			});

						
	});	