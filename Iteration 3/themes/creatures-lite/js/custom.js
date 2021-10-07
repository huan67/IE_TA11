(function( $ ) {
// NAVIGATION CALLBACK
var creatures_lite_ww = jQuery(window).width();
jQuery(document).ready(function() { 
	jQuery(".sitenav li a").each(function() {
		if (jQuery(this).next().length > 0) {
			jQuery(this).addClass("parent");
		};
	})
	jQuery(".toggleMenu").click(function(e) { 
		e.preventDefault();
		jQuery(this).toggleClass("active");
		jQuery(".sitenav").slideToggle('fast');
	});
	creatures_lite_adjustMenu();
})

// navigation orientation resize callbak
jQuery(window).bind('resize orientationcreatures-lite', function() {
	creatures_lite_ww = jQuery(window).width();
	creatures_lite_adjustMenu();
});

var creatures_lite_adjustMenu = function() {
	if (creatures_lite_ww < 1000) {
		jQuery(".toggleMenu").css("display", "block");
		if (!jQuery(".toggleMenu").hasClass("active")) {
			jQuery(".sitenav").hide();
		} else {
			jQuery(".sitenav").show();
		}
		jQuery(".sitenav li").unbind('mouseenter mouseleave');
	} else {
		jQuery(".toggleMenu").css("display", "none");
		jQuery(".sitenav").show();
		jQuery(".sitenav li").removeClass("hover");
		jQuery(".sitenav li a").unbind('click');
		jQuery(".sitenav li").unbind('mouseenter mouseleave').bind('mouseenter mouseleave', function() {
			jQuery(this).toggleClass('hover');
		});
	}
}

jQuery(document).ready(function() {
    if( jQuery( '#slider' ).length > 0 ){
        jQuery('.nivoSlider').nivoSlider({
            effect:'fade',
            animSpeed: 500,
            pauseTime: 3000,
            startSlide: 0,
			directionNav: false,
			controlNav: true,
			pauseOnHover: false,
			controlNavThumbs: true
    	});
    }
});

jQuery(document).ready(function() {
	jQuery('p:empty').remove();
});

})( jQuery );