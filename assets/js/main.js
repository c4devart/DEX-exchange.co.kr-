$body = $("body");
if (page != 'adnc') {
	$(document).on({
		ajaxStart: function () {
			$body.addClass("loading");
		},
		ajaxStop: function () {
			$body.removeClass("loading");
		}
	});
}
