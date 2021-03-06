/* ----------------- Start JS Document ----------------- */
jQuery(document).ready(function() {

});


function prepareList() {
	$('#expList').find('li:has(ul)').not('.active')
	.prepend('<span class="fa fa-plus-circle"></span>')
	.children('ul').hide();
	$('#expList').find('li.active:has(ul)')
	.prepend('<span class="fa fa-minus-circle"></span>');
	$('#expList').find('.fa').click(function(event) {
		if ($(this).hasClass('fa-plus-circle')) {
			$(this).removeClass('fa-plus-circle');
			$(this).addClass('fa-minus-circle');
		} else {
			$(this).addClass('fa-plus-circle');
			$(this).removeClass('fa-minus-circle');
		}
		$(this).parent().children('ul').toggle('medium');
		return false;
	});
};

// Page Loader
$(window).load(function () {
	"use strict";
	
	$('#loader').hide();
});

$(document).ready(function ($) {
	"use strict";
	
	
	prepareList();

////	Hidder Header

	
	var headerEle = function () {
		var $headerHeight = $('header').height();
		$('.hidden-header').css({ 'height' : $headerHeight	+ "px" });
	};
	
	$(window).load(function () {
		headerEle();
	});
	
	$(window).resize(function () {
		headerEle();
	});

	$('a[href*=#]:not([href=#])').click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
			$('html,body').animate({
				scrollTop: target.offset().top
			}, 1000);
			return false;
			}
		}
		});
	
	$("li a").click(function(){
		
		$("li").removeClass("active");
		$(this).parent().addClass("active");
	});
	
	
	$('body').scrollspy({ target: '#navbar-menu' })
	
	/*----------------------------------------------------*/
	/*	Nice-Scroll
	/*----------------------------------------------------*/
	
	$("html").niceScroll({
		scrollspeed: 100,
		mousescrollstep: 35,
		cursorwidth: 9,
		cursorborder: 0,
		cursorcolor: '#333',
		autohidemode: false,
		zindex: 999999999,
		horizrailenabled: false,
		cursorborderradius: 0,
	});
		
	
	
	
	
	/*----------------------------------------------------*/
	/*	Nav Menu & Search
	/*----------------------------------------------------*/
	
	$(".nav > li:has(ul)").addClass("drop");
	$(".nav > li.drop > ul").addClass("dropdown");
	$(".nav > li.drop > ul.dropdown ul").addClass("sup-dropdown");
	
	$('.show-search').click(function() {
		$('.search-form').fadeIn(300);
		$('.search-form input').focus();
	});
	$('.search-form input').blur(function() {
		$('.search-form').fadeOut(300);
	});
	
	
	
	
	
	/*----------------------------------------------------*/
	/*	Back Top Link
	/*----------------------------------------------------*/
	
	var offset = 200;
	var duration = 500;
	$(window).scroll(function() {
		if ($(this).scrollTop() > offset) {
			$('.back-to-top').fadeIn(400);
		} else {
			$('.back-to-top').fadeOut(400);
		}
	});
	$('.back-to-top').click(function(event) {
		event.preventDefault();
		$('html, body').animate({scrollTop: 0}, 600);
		return false;
	});
	
	
	
	
	
	/*----------------------------------------------------*/
	/*	Css3 Transition
	/*----------------------------------------------------*/
	
	$('*').each(function(){
		if($(this).attr('data-animation')) {
			var $animationName = $(this).attr('data-animation'),
				$animationDelay = "delay-"+$(this).attr('data-animation-delay');
			$(this).appear(function() {
				$(this).addClass('animated').addClass($animationName);
				$(this).addClass('animated').addClass($animationDelay);
			});
		}
	});
	
	
	
	
	
	/*----------------------------------------------------*/
	/*	Nivo Lightbox
	/*----------------------------------------------------*/
	
	$('.read-more').append('<i class="fa fa-angle-right"></i>');
	
	
	
	
	/*----------------------------------------------------*/
	/*	Tooltips & Fit Vids & Parallax & Text Animations
	/*----------------------------------------------------*/
	
	//$("body").fitVids();
	
	$('.itl-tooltip').tooltip();
	
	
	
	/*----------------------------------------------------*/
	/*	Sticky Header
	/*----------------------------------------------------*/
	
	(function() {
		
		var docElem = document.documentElement,
			didScroll = false,
			changeHeaderOn = 100;
			document.querySelector( 'header' );
			
		function init() {
			window.addEventListener( 'scroll', function() {
				if( !didScroll ) {
					didScroll = true;
					setTimeout( scrollPage, 250 );
				}
			}, false );
		}
		
		function scrollPage() {
			var sy = scrollY();
			if ( sy >= changeHeaderOn ) {
				$('.top-bar').slideUp(300);
				$("header").addClass("fixed-header");
				$('.navbar-brand').css({ 'padding-top' : 27 + "px", 'padding-bottom' : 27 + "px" });
				
				if (/iPhone|iPod|BlackBerry/i.test(navigator.userAgent) || $(window).width() < 479 ){
					$('.navbar-default .navbar-nav > li > a').css({ 'padding-top' : 0 + "px", 'padding-bottom' : 0 + "px" })
				}else{
					$('.navbar-default .navbar-nav > li > a').css({ 'padding-top' : 18 + "px", 'padding-bottom' : 18 + "px" })
					$('.search-side').css({ 'margin-top' : -7 + "px" });
				};
				
			}
			else {
				$('.top-bar').slideDown(300);
				$("header").removeClass("fixed-header");
				$('.navbar-brand').css({ 'padding-top' : 27 + "px", 'padding-bottom' : 27 + "px" });
				
				if (/iPhone|iPod|BlackBerry/i.test(navigator.userAgent) || $(window).width() < 479 ){
					$('.navbar-default .navbar-nav > li > a').css({ 'padding-top' : 0 + "px", 'padding-bottom' : 0 + "px" })
				}else{
					$('.navbar-default .navbar-nav > li > a').css({ 'padding-top' : 18 + "px", 'padding-bottom' : 18 + "px" })
					$('.search-side').css({ 'margin-top' : -7	+ "px" });
				};
				
			}
			didScroll = false;
		}
		
		function scrollY() {
			return window.pageYOffset || docElem.scrollTop;
		}
		
		init();
		
		
		
	})();
});




