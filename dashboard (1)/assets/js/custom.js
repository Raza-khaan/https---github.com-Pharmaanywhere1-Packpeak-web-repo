
/********* Custom Js *********/

(function($) {

// Side bar menu //
$('.accordion h4').click(function(event) {
	event.preventDefault();
  $('.accordion > ul > li > ul:visible').not($(this).nextAll('ul')).stop().hide(200).parent().removeClass('open'); //
  $(this).nextAll('ul').slideToggle(200, function() {
    $(this).parent('.sidenav').toggleClass('open');
  });
});

// Small Sidebar //

	$(document).on('click', '#close-btn', function() {
		if($('body').hasClass('mini-sidebar')) {
			$('body').removeClass('mini-sidebar');
		} else {
			$('body').addClass('mini-sidebar');
		}
	});

	$(document).on('click', '#close-btn', function() {
		$('.side-menu').toggleClass('expand-sidebar');
		$('.dashboard-side').toggleClass('expand-dashborad');
	});

	$(document).on('click', '.sidenav h4 a', function() {
		$('.side-menu').removeClass('expand-sidebar');
		$('.dashboard-side').removeClass('expand-dashborad');
		$('body').removeClass('mini-sidebar');
	});

	$(document).on('click', '#menu-bar', function() {
		$('.side-menu').addClass('open-sidebar');
		$('body').addClass('body-overflow');
	});

	$(document).on('click', '#menu-bar-close', function() {
		$('.side-menu').removeClass('open-sidebar');
		$('body').removeClass('body-overflow');
	});

	
})(jQuery);

