$(window).bind("load", function() {
   
	// Content
	TweenMax.to('.header-fade', 0.75, { alpha:'1', delay:0, ease:Strong.easeInOut, overwrite:5});
	TweenMax.to('.header2-fade', 0.75, { alpha:'1', delay:0.3, ease:Strong.easeInOut, overwrite:5});
	TweenMax.to('.body-fade', 0.75, { alpha:'1', delay:0.5, ease:Strong.easeInOut, overwrite:5});

	// Beer Thumbs
	TweenMax.allTo('.module-beer', 0.75, { alpha:'1', delay:0.1, ease:Cubic.easeInOut},0.12);

});