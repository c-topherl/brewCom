$(document).ready(function () {
	ModuleGridRollover.init();

	// Initialize custom select menu
	$('.selectFix').selectmenu({
		appendTo:'.select-wrapper',
		width:240
	});

	// Initialze slides for mobile devices 
	$("#slides").slidesjs({
		width:1200,
		height:100,
		pagination: {
		  active:false
		}
	});


	// Update beer log on dropdown change
	$(".selectFix").on('change', function() {

		// Grab value of selected menu item
		var menu = document.getElementById("beerLogMenu");
		var selectedOption = menu.options[menu.selectedIndex].value;
		var ajaxPath = "/ajax/beer-log/category/"+selectedOption;
							
		// Load updated beer log into place
	    $("#ajax-reload").load(ajaxPath, function(){
	        
	        // Determine the number of beers associated with this category
	    	var numSlides = $('#slides').children('.slide-wrapper').length;

	    	// If more than two re-initialize slideshow 
	    	if(numSlides > 1){
	    		
	    		$("#slides").slidesjs({
					width:1200,
					height:100,
					pagination: {
					  active:false
					}
				});
	    	}	    	
	    });
	});

});