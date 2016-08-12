/* jquery.cycle slideshow settings*/

$(document).ready(function() {
    $('.slideshow').cycle({
		fx: 'scrollHorz', // choose your transition type, ex: fade, scrollUp, shuffle, etc...
		prev:   '#prevBtn',     
		next:   '#nextBtn',
	});
	// touch
	$('.slideshow').touchwipe({
		wipeLeft: function() {
		$('.slideshow').cycle("next");
		},
		wipeRight: function() {
		$('.slideshow').cycle("prev");
		}
	});
});