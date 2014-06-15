(function($) {

	jQuery('.wwtw-suggestion--form').on('submit', function(evt) {
		evt.preventDefault();
		var $el = $(this),
			$submit = $el.find('input[type="submit"]'),
			$msg = $('.wwtw-suggestion--response'),
			dataString = $el.serialize();

		$submit.slideUp();

		$.post( WWTWSuggestion.ajax_url, {
				data: dataString,
				action: 'wwtw_suggestion'
			}, function(res) {

			var json = JSON.parse(res);

			if ( json.status === 'ok' ) {
				$el.slideUp();
				console.log($msg.length);
				$msg.hide().html( json.message ).slideDown();

			} else {
				$submit.slideDown();
			}

		});

	});

}(jQuery));