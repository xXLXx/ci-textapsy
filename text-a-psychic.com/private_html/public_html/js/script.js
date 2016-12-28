(function () {
"use strict";

	function getCookie(cname) {
	    var name = cname + "=";
	    var ca = document.cookie.split(';');
	    for(var i = 0; i <ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') {
	            c = c.substring(1);
	        }
	        if (c.indexOf(name) == 0) {
	            return c.substring(name.length,c.length);
	        }
	    }
	    return "";
	}

	$(function () {
		if (getCookie('user')) {
			$('.signin').html('SIGN OUT').on('click', function (e) {
				$.get('//' + window.location.hostname + '/psychics/logout', function (response) {
					window.location.href = '//' + window.location.hostname;
				});
			});
		} else {
			$('.signin').popover({
				content: $('#signin-form').html(),
				placement: 'bottom',
				html: true
			});

			$(document).on('submit', 'form[name="signin"]', function (e) {
				e.preventDefault();
				e.stopPropagation();
				var $ele = $(this);

				$ele.find('[type="submit"]').addClass('disabled');
				$ele.find('.signin-text').removeClass('error default').addClass('default').html('Logging in...');

				$.post('//' + window.location.hostname + '/psychics/login', $(this).serialize(), function (response) {
					if (!response.success) {
						for (var key in response.errors) {
							$ele.find('.signin-text').removeClass('default').addClass('error').html(response.errors[key]);
						}
					} else {
						window.location.href = '//' + window.location.hostname + '/bulletin_board';
					}

					$ele.find('[type="submit"]').removeClass('disabled');
				});
				
			});
		}
	});

})();