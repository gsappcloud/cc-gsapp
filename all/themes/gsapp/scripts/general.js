// generic JS fixes

// various JavaScript object.
var gsapp = {};

// jump to the value in a select drop down
gsapp.go = function(e) {
  var destination = e.options[e.selectedIndex].value;
  if (destination && destination != 0) location.href = destination;
};

// prevent users from clicking a submit button twice
gsapp.formCheck = function() {
  // only apply this to node and comment and new user registration forms
  var forms = $("#node-form>div>div>#edit-submit,#comment-form>div>#edit-submit,#user-register>div>#edit-submit");

  // insert the saving div now to cache it for better performance and to show the loading image
  $('<div id="saving"><p class="saving">Saving&hellip;</p></div>').insertAfter(forms);

  forms.click(function() {
    $(this).siblings("input:submit").hide();
    $(this).hide();
    $("#saving").show();

    var notice = function() {
      $('<p id="saving-notice">Not saving? Wait a few seconds, reload this page, and try again. Every now and then the internet hiccups too :-)</p>').appendTo("#saving").fadeIn();
    };

    // append notice if form saving isn't work, perhaps a timeout issue
    setTimeout(notice, 24000);
  });
};

// Global Killswitch.
if (Drupal.jsEnabled) {
  $(document).ready(gsapp.formCheck);
}

/********************************************
 *	START GSAPP JAVASCRIPT
--------------------------------------------*/
var rollTimeout = null;
var ref = null;

$(document).ready(function(){
	
	if ($("#campaignmonitor-subscribe-form")[0]) {
		$("select.form-select").each(function(i,el){
			$(el).prepend("<option value='none' selected='selected'>--</option>");
		})
		setTimeout(function(){
			$("#campaignmonitor-subscribe-form select.form-select").dropkick({theme:"gsapp"});
		},20);
		
		$("#campaignmonitor-subscribe-form").submit(function(event){
			// event.preventDefault();		
			
			var toRemove = $.grep($(this).serializeArray(), function(el){
				return (el.value=="" || el.value=="none")
			})
			$.each(toRemove,function(i,el){
				$("#edit-"+el.name)[0].name = "";
			})
		})
	}
	
	$(".item a").live("click",function(){
		$(this).parents(".item").addClass("visited");
	})
	
	if (window.opener && $(".nid")[0]) {
		var w = window.opener;
		var nid = $(".nid").attr("id");
		$("#item-row-"+nid,w.document).addClass("visited");
	}
	
	
	$('#logo a').draggable();
	
	$('.nav .current').hover(function(){
		$(this).parent('.menu').addClass('active');
		return false;
	});
	$('.nav .menu').mouseleave(function(){
		$(this).removeClass('active');
		return false;
	});
	
	var form = null;
	
	$('#search-link').click(function(){		
		form = "search";
		$('#search-popup').fadeIn();
		$('.nav .menu').removeClass('active');
		$('.custom-search-box').focus();
		return false;
	});
	
	var url = unescape(location.pathname);
	var searchpath = url.substring(url.lastIndexOf('/') + 1);
	if(url.indexOf('search') > -1) {
		$(".custom-search-box").val(searchpath).focus().select();
	};
	
	$('.current').click(function(){
		return false;
	});
	
	
	
	// Close the form slidedowns
	$('.exit').click(function(){
		form = null;
		$(this).parent('.form').fadeOut();
		return false;
	});
	
	//Keyboard commands
	var code = null;
	$(document).keydown(function(e) {
		code = (e.keyCode ? e.keyCode : e.which);
		// escape key closes fullscreen slideshow
    if (code == 27) {
			// close slideshow mode
			$('.slideshow-exit').trigger('click');
			
			// close search box
			if(form) $('.exit').trigger('click');
		}
	});
	
	/**
	 * Slideshows
	 */
	var count = 1;
	var slideshow_position = null;
	var current_slideshow = null;
	var delay_scroll = null;
	
	$('.slideshow').each(function(){
		
		var nid = null;
		var slide = $(this).attr('rel');
		var currentSlide = 1;
		var slideCount = '' + $(".slide-wrapper .slide-item", this).size();
		slideCount = (slideCount > 1) ? '0' + slideCount : slideCount;
		
		ref = this;
		
		nid = null;
		nid = $(this).parents('.nid').attr('id');
		if(nid) { nid = '#' + nid + ' '; }
		else { nid = null; }

		var firsttime = 0;
		$(nid + '.slideshow[rel='+slide+']' + ' .slide-wrapper') 
		.cycle({ 
		    fx:     'fade', 
		    speed:  'fast', 
		    timeout: 0,
				prev: nid + '.slideshow[rel='+slide+']' + ' .prev',
				next: nid + '.slideshow[rel='+slide+']' + ' .next',
				before: function(){
						firsttime++;
						
						if(firsttime > 1) {
							$(this).css({'visibility':'hidden','display':'block'});
							calc = 0;
							calc = (400 - $('img', this).height()) / 2;
							$(this).css({'visibility':'visible','display':'none'});
						} else {
							calc = 0;
							calc = (400 - $('img', this).height()) / 2;
						}
					  $('img', this).css({top: calc});

				}
		});
		
		
		$('.fullscreen .thumbs img:first').addClass('active');
		
		function activeThumb(){
			index = parseInt(currentSlide)-1;

			$('.fullscreen.'+slide+ ' .thumbs img').removeClass('active');
			$('.fullscreen.'+slide+' .thumbs img').eq(index).addClass('active');
		}
		
		$(nid + '.slideshow[rel='+slide+'] .next').click(function(){
			
			if(currentSlide != slideCount) {
				currentSlide++;
				currentSlide = currentSlide.toString();
				if(currentSlide.length == 1) currentSlide = '0' + currentSlide;
			} else {
				currentSlide = '01';
			}

			$(nid + '.slideshow[rel='+slide+'] .current').text(currentSlide);
			
			if($(this).parents(nid + '.slideshow[rel='+slide+']').hasClass('.fullscreen')) {
							activeThumb();	
						}
		});
		
		$(nid + '.slideshow[rel='+slide+'] .prev').click(function(){		

			if(currentSlide != '01') {
				currentSlide--;
				currentSlide = currentSlide.toString();
				if(currentSlide.length == 1) currentSlide = '0' + currentSlide;
			} else {
				currentSlide = slideCount;
			}

			$(nid + '.slideshow[rel='+slide+'] .current').text(currentSlide);

			if($(this).parents("."+slide).hasClass('.fullscreen')) {
							activeThumb();	
						}
		});
		
		
		$('img, .start-slideshow', this).click(function(){
				slideshow_position = $(window).scrollTop();
				
				// set the position of the slideshow
				$('.fullscreen.' + slide + ' .wrapper').css('top', $(window).scrollTop());
			
				current_slideshow = slide;
				
				var docHeight = $('body').height();
				$('.fullscreen.'+ slide).height(docHeight);
				$('.fullscreen.'+ slide).show();
				activeThumb();
			
				return false;
			});
		

		// When the user enters slideshow mode, we need to keep them from scrolling too far
		$(window).scroll(function () {
			clearTimeout(delay_scroll);
			
      if(slideshow_position != null && current_slideshow) {
					
				delay_scroll = setTimeout(function(){
					if($(window).scrollTop() < slideshow_position) {
						$.scrollTo(slideshow_position, 200, {easing:'easeOutQuint'});
					}
					
					if($(window).scrollTop() > slideshow_position+1000) {
						$.scrollTo(slideshow_position, 200, {easing:'easeOutQuint'});
					}
				},50);

			}
    });
		
		$('.fullscreen.'+slide+ ' .thumbs img').click(function(){
			$('.fullscreen.'+slide+ ' .thumbs img').removeClass('active');
			$(this).addClass('active');
			
			index = $('.fullscreen.'+slide+ ' .thumbs img').index(this);
			$('.' + slide + ' .slide-wrapper').cycle(index);
			
			currentSlide = index+1;
			currentSlide = currentSlide.toString();
			if(currentSlide.length == 1) currentSlide = '0' + currentSlide;

			$("."+slide+" .current").text(currentSlide);
			
			return false;
		});

	});
	
	
	// exit fullscreen mode
	$('.slideshow-exit').click(function(){
		$('.fullscreen').hide();
		current_slideshow = null;
		slideshow_position = null;                
		return false;
	});
	
	setTimeout(function(){
		$('.success').fadeOut();
	}, 3000);

	
	$('.magazine-links .share').click(function(){
		$(this).parents('.magazine-links').find('.share-box').fadeIn();
		return false;
	});
	
	$('.share-box .close').click(function(){
		$(this).parent('.share-box').fadeOut();
	});
	
	
	// Add data fetcher link to add/notes form
	if($('#node-form').attr('action') == "/node/add/notes") {
		$('#node-form').prepend('<fieldset class=" collapsible collapsed"><legend class="collapse-processed"><a href="/create-note">Use Data Fetcher</a></legend></fieldset>');
	}
	
	$('.twitter-share').popupWindow({ 
		centerBrowser:1,
		width:550,
		height:450
	});
	
	$('.facebook-share').popupWindow({ 
		centerBrowser:1,
		width:550,
		height:450
	});
	
	
	// facebook login
	$('.facebook-toggle input').click(function(){
		if($(this).attr('checked')) {
			fb_login();
		}
	});
	
	// jump to footnote and add selected class then time out.
	$('#article p a, .forward a').click(function(){
		var hash = this.toString().split("#").pop();
		var $hashli = $('#'+ hash);
	  	$.scrollTo($hashli,800);
		$hashli.siblings('strong').css("color","").addClass('selected');
		setTimeout(function(){
			$hashli.siblings('strong').animate({"color":"#00447f",complete:function(){
				$(this).removeClass('selected');
			}},500);
		},2000);
		return false;
	});
	
	// $('#article strong .selected').live('focus',function(){
	// 	alert('ready!');
	//     });

	
});

function fb_login() {
	FB.login(function(response) {
	  if (response.session) {
	    if (response.perms) {
	      //user is logged in and granted some permissions
	      // perms is a comma separated list of granted permissions
	    } else {
	      //user is logged in, but did not grant any permissions
	    }
	  } else {
	    alert('user is not logged in');
	  }
	});
}


//borrowed from jQuery easing plugin
//http://gsgd.co.uk/sandbox/jquery.easing.php
$.easing.easeOutQuint = function (x, t, b, c, d) {
	return c*((t=t/d-1)*t*t*t*t + 1) + b;
}

// jquery plugin to handle the twitter popup
$.fn.popupWindow = function(instanceSettings){
		
		return this.each(function(){
	
	$(this).click(function(){
	
	$.fn.popupWindow.defaultSettings = {
		centerBrowser:0, // center window over browser window? {1 (YES) or 0 (NO)}. overrides top and left
		centerScreen:0, // center window over entire screen? {1 (YES) or 0 (NO)}. overrides top and left
		height:500, // sets the height in pixels of the window.
		left:0, // left position when the window appears.
		location:0, // determines whether the address bar is displayed {1 (YES) or 0 (NO)}.
		menubar:0, // determines whether the menu bar is displayed {1 (YES) or 0 (NO)}.
		resizable:0, // whether the window can be resized {1 (YES) or 0 (NO)}. Can also be overloaded using resizable.
		scrollbars:0, // determines whether scrollbars appear on the window {1 (YES) or 0 (NO)}.
		status:0, // whether a status line appears at the bottom of the window {1 (YES) or 0 (NO)}.
		width:500, // sets the width in pixels of the window.
		windowName:null, // name of window set from the name attribute of the element that invokes the click
		windowURL:null, // url used for the popup
		top:0, // top position when the window appears.
		toolbar:0 // determines whether a toolbar (includes the forward and back buttons) is displayed {1 (YES) or 0 (NO)}.
	};
	
	settings = $.extend({}, $.fn.popupWindow.defaultSettings, instanceSettings || {});
	
	var windowFeatures =    'height=' + settings.height +
							',width=' + settings.width +
							',toolbar=' + settings.toolbar +
							',scrollbars=' + settings.scrollbars +
							',status=' + settings.status + 
							',resizable=' + settings.resizable +
							',location=' + settings.location +
							',menuBar=' + settings.menubar;

			settings.windowName = this.name || settings.windowName;
			settings.windowURL = this.href || settings.windowURL;
			var centeredY,centeredX;
		
			if(settings.centerBrowser){
					
				if ($.browser.msie) {//hacked together for IE browsers
					centeredY = (window.screenTop - 120) + ((((document.documentElement.clientHeight + 120)/2) - (settings.height/2)));
					centeredX = window.screenLeft + ((((document.body.offsetWidth + 20)/2) - (settings.width/2)));
				}else{
					centeredY = window.screenY + (((window.outerHeight/2) - (settings.height/2)));
					centeredX = window.screenX + (((window.outerWidth/2) - (settings.width/2)));
				}
				window.open(settings.windowURL, settings.windowName, windowFeatures+',left=' + centeredX +',top=' + centeredY).focus();
			}else if(settings.centerScreen){
				centeredY = (screen.height - settings.height)/2;
				centeredX = (screen.width - settings.width)/2;
				window.open(settings.windowURL, settings.windowName, windowFeatures+',left=' + centeredX +',top=' + centeredY).focus();
			}else{
				window.open(settings.windowURL, settings.windowName, windowFeatures+',left=' + settings.left +',top=' + settings.top).focus();	
			}
			return false;
		});
		
	});	
};


(function ($) {
	$.fn.valign = function(container) {
		return this.each(function(i){
			if(container == null) {
				container = 'div';
			}
			$(this).html("<" + container + ">" + $(this).html() + "</" + container + ">");
			var el = $(this).children(container + ":first");
			var elh = $(el).height(); //new element height
			var ph = $(this).height(); //parent height
			var nh = (ph - elh) / 2; //new height to apply
			$(el).css('padding-top', nh);
		});
	};
})(jQuery);


function nodeGoBack() {
	if (history.length>1) history.go(-1);
	setTimeout(function(){
		window.close();
		if (window) location.href = "/";
	},200);
}