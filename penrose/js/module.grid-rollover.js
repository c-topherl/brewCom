var s,
ModuleGridRollover = {

	// -------------------------------------------------
	// SETUP
	// -------------------------------------------------

	// Declare Variables
	settings: {
		gridModule: $('.module-rollover')
	},

	// Initialize
	init: function() {
		s = this.settings;
		this.bindUIActions();
	},

	// Bind Actions to Sub-Functions
	bindUIActions: function() {

		// General rollover
		s.gridModule.hover(function() {
	 		ModuleGridRollover.moduleRollover(this);
		}, function() {
		  	ModuleGridRollover.moduleRolloff(this);
		});

	},


	// -------------------------------------------------
	// SUB FUNCTIONS
	// -------------------------------------------------

	// General Thumbnail Rollover
	moduleRollover: function(thumb) {

		// Declare variables
		var moduleHeadline = $(thumb).find('.headline');
		var moduleContent = $(thumb).find('p');
		var moduleLink = $(thumb).find('a');

		TweenMax.to(thumb, .6, { alpha:'1', ease:Linear.easeOut, overwrite:5});
		TweenMax.to(moduleHeadline, .4, { alpha:'1', delay:0.1, ease:Linear.easeOut, overwrite:5});
		TweenMax.to(moduleContent, .6, { alpha:'1', delay:0.4, ease:Linear.easeOut, overwrite:5});
		TweenMax.to(moduleLink, .4, { alpha:'1', delay:0, ease:Linear.easeOut, overwrite:5});
		
	},

	// General Thumbnail Rollovoff
	moduleRolloff: function(thumb) {

		// Declare variables
		var moduleHeadline = $(thumb).find('.headline');
		var moduleContent = $(thumb).find('p');
		var moduleLink = $(thumb).find('a');

		TweenMax.to(moduleHeadline, .3, { alpha:'0', delay:0, ease:Linear.easeOut, overwrite:5});
		TweenMax.to(moduleContent, .3, { alpha:'0', delay:0, ease:Linear.easeOut, overwrite:5});
		TweenMax.to(moduleLink, .1, { alpha:'0', delay:0, ease:Linear.easeOut, overwrite:5});
		TweenMax.to(thumb, .3, { alpha:'0', delay:0.2, ease:Linear.easeOut, overwrite:5});
	},


};