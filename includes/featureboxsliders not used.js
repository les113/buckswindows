	$(document).ready(function(){
	  $('.featslide').mouseover(function() {
			  $(this).find('.featOvly').stop().animate({ left:'0' }, 600);						
		});
	  $('.featslide').mouseout(function() {
			  $(this).find('.featOvly').stop().animate({ left:'170' }, 600);						
		});
	});