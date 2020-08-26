module.exports = (function ($) {
	"use strict";
	$(document)
		.ready(() => {
			const switcherDropdown = $("#bootstrap-theme");
			const linkTag = $("#bootstrap-css");
			let availableThemes=[];
			$.getJSON("https://bootswatch.com/api/4.json")
				.then(json => {
					switcherDropdown.find('.dropdown-menu')
						.empty();
					json.themes.forEach(theme => {
						availableThemes.push(theme);
						switcherDropdown.find('.dropdown-menu')
							.append($("<li>",{class:'dropdown-item'})
								.append($("<a>",{href:"#!",class:'theme-pick'})
									.data('theme', theme.name)
									.html(theme.name)));
					});
				});
				switcherDropdown.on('click','.theme-pick',function(e){
					e.preventDefault();
					const theme=availableThemes.find(item=>item.name==$(this).data('theme'));
					linkTag.attr('href',theme.cssCdn);
					switcherDropdown.find('button').html(theme.name);
				})
		});
})(jQuery);
