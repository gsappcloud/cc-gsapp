$(function(){
	
	function rollovers(){
		// excerpt rollovers
		var rollTimeout;
		
		$('.view-display-id-excerpt .item').live("mouseover",function(){
			if(rollTimeout != null) clearTimeout(rollTimeout); rollTimeout = null;
			ref = this;
			rollTimeout = setTimeout(function(){
				$('.rollover', ref).fadeIn('fast');
			},200);
		}).live("mouseout",function(){
			clearTimeout(rollTimeout); 
			$('.rollover', this).fadeOut('fast');
		});

		// tags rollovers
		$('.view-display-id-tags .item').live("mouseover",function(){
			if(rollTimeout != null) clearTimeout(rollTimeout); rollTimeout = null;
			ref = this;
			rollTimeout = setTimeout(function(){
				$('.rollover', ref).fadeIn('fast');
			},200);
		}).live("mouseout",function(){
			clearTimeout(rollTimeout); 
			$('.rollover', this).fadeOut('fast');
		});

		// image rollovers
		$('.view-display-id-image .item').live("mouseover",function(){
			if(rollTimeout != null) clearTimeout(rollTimeout); rollTimeout = null;
			ref = this;
			rollTimeout = setTimeout(function(){
				$('.type', ref).fadeIn('fast');
			},200);
		}).live("mouseout",function(){
			clearTimeout(rollTimeout); 
			$('.type', this).fadeOut('fast');
		});
		
	}
	
	rollovers();

	//Get the number of pages from the Views Pager (Use the full pager, it will be hidden with .infinitescroll() anyway.)
	if($(".pager-last").length != 0) {
		lastPageHref = $(".pager-last").find('a').attr('href').toString(); 
		lastPageHref = lastPageHref.split("=");
		numOfPages = parseInt(lastPageHref[1]) + 1;
	} else {
		numOfPages = 0;
	}
	
	$('.view-display-id-image .view-content').masonry({
		columnWidth: 120,
	  itemSelector: '.views-row:visible' 
	});
 
	$('.view-display-id-tags .view-content').masonry({
		columnWidth: 470, 
	  itemSelector: '.views-row:visible' 
	});


	var scrollPageCount = 0;
	var page = 0;
	
	$('.view-content').infinitescroll({
		navSelector  : ".pager",    // selector for the paged navigation 
		nextSelector : ".pager .pager-next a",    // selector for the NEXT link (to page 2)
		itemSelector : ".views-row",       // selector for all items you'll retrieve
		loadingImg : '/sites/all/themes/gsapp/images/ajax-loader.gif',
		donetext  : "No more pages to load.",
		debug: false,
		pages: numOfPages, //NEW OPTION: number of pages in the Views Pager
		currPage: page,
		errorCallback: function() { 
			$('#infscr-loading').animate({opacity: 1},2000).fadeOut('normal');
		}
	},
  	// call masonry as a callback.
  	function() {
		$('.view-display-id-image .view-content, .view-display-id-tags .view-content')
		.masonry({ appendedContent: $(this) });
	});
	
	for (var i=0; i < page; i++) {
		setTimeout(function(){
			$(window).trigger("retrieve.infscr");
		},i*200);
	};
});