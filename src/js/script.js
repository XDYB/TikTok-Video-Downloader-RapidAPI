module.exports = (function ($) {
	"use strict";
	const TikTok = require("./library/TikTok");
	$(document)
		.ready(() => {
			new TikTok();
			
		});
})(jQuery);