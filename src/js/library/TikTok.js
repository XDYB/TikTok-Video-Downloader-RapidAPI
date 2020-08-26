const Humanize = require("./humanize");
const urlTransform = require("./urltransform");
module.exports = () => {
	(($) => {
		let data = {
			user: null,
			items: [],
			next: 0,
			hasMore: true,
		};
		let settings = Object.assign({}, {
			search: false,
		}, WPPress_TikTok_Setting);
		let dataStack = [];
		let currentVideoID = 0;
		let keyword,type;
		const extras = {
			urlhash: text => urlTransform(text),
			humanize: num => Humanize(parseInt(num), 0),
			adv: settings.adv,
		};
		const tpl = {
			video: $(`#video-tpl`),
			popup: $(`#popup-tpl`),
		};
		const elements = {
			form: $("#search-form"),
			resultWrap: $("#result-wrap"),
		};
		const popup = $('<div>')
			.attr({
				id: `tiktok-video-popup`,
				tabindex: '-1',
			})
			.addClass(`tiktok-video-popup`);
		const popupWrapInner = $('<div>')
			.addClass(`tiktok-video-popup-inner`);
		const popupWrap = $('<div>', {
			class: 'modal-content-wrap'
		});
		const popupBody = $('<div>', {
			class: 'modal-content',
		});
		const popupClose = $('<div>', {
				class: 'close-popup',
			})
			.append(`<svg height="512" viewBox="0 0 413.348 413.348" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m413.348 24.354-24.354-24.354-182.32 182.32-182.32-182.32-24.354 24.354 182.32 182.32-182.32 182.32 24.354 24.354 182.32-182.32 182.32 182.32 24.354-24.354-182.32-182.32z"/></svg>`);
		const popupNext = $(`<div>`, {
				class: "popup-next"
			})
			.append(`<svg height="50px" viewBox="0 0 50 50" width="50px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><rect fill="none" height="50" width="50"/><polygon points="15,2.75 12.914,4.836 33.078,25 12.914,45.164 15,47.25 37.25,25 "/></svg>`);
		const popupPrev = $(`<div>`, {
				class: "popup-prev"
			})
			.append(`<svg height="50px" viewBox="0 0 50 50" width="50px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><rect fill="none" height="50" width="50"/><polygon points="35,47.25 37.086,45.164 16.922,25 37.086,4.836 35,2.75 12.75,25 "/><rect fill="none" height="50" width="50"/></svg>`);
		$('body')
			.append(popup.append(popupWrapInner));
		popupWrapInner.append(popupWrap.append(popupClose)
			.append(popupPrev)
			.append(popupBody)
			.append(popupNext));
		const noVideos = $("<div>", {
				class: "alert alert-warning text-center"
			})
			.html(settings.i18n.noVideo);
		const inValidKeyword = $("<div>", {
				class: "alert alert-warning text-center"
			})
			.html(settings.i18n.invalidKeyword);
		const showPopup = async id => {
			$('body, html')
				.css({
					overflow: 'hidden',
				});
			let video = _.findWhere(dataStack, {
				id: currentVideoID,
			});
			let _popup_content = _.template(tpl.popup.html())({
				video: video,
				extra: extras,
			});
			popupBody.html(_popup_content);
			popup.css({
				display: 'flex'
			});
			$('a[href*="http"]', popupBody)
				.attr({
					target: '_blank',
				});
			$('video', popup)
				.each(function () {
					const width = $(this)
						.attr('width');
					const height = $(this)
						.attr('height');
					let ratio = height * 100 / width;
					$(this)
						.closest('.video-inner')
						.css({
							paddingTop: `${ratio}%`
						});
					if (ratio < 120) {
						$('body')
							.addClass('tiktok-wide-video');
					} else {
						$('body')
							.removeClass('tiktok-wide-video');
					}
				});
			popup.scrollTop(0);
			$('.video-download a', popup)
				.each(function (e) {
					const params = $.param({
						url: `https://www.tiktok.com/@${video.author.uniqueId}/video/${video.id}`,
						key: $(this)
							.attr('data-key'),
					});
					$(this)
						.attr({
							href: `download.php?${params}`,
							target: "_self",
						});
				});
			$('.music-download a', popup)
				.each(function (e) {
					const params = $.param({
						url: `https://www.tiktok.com/@${video.author.uniqueId}/video/${video.id}`,
						key: 'music',
					});
					var ext = video.music.playUrl.split(".")
						.slice(-1)[0];
					$(this)
						.attr({
							href: `download.php?${params}`,
							target: "_self",
						});
				});
			if (settings.adv) {
				$('.tiktok-video-info', popup)
					.append($("<div>", {
							class: "video-adv"
						})
						.append(settings.adv));
			}
			popup.focus();
		}
		const popupBrowsePrev = () => {
			let el = $(`.tiktok-video-item[data-video-id="${currentVideoID}"]`, elements.resultWrap)
				.prev('.tiktok-video-item');
			if (el.length < 1) {
				el = $(`.tiktok-video-item`, elements.resultWrap)
					.last()
			}
			currentVideoID = el.attr('data-video-id');
			showPopup();
		};
		const popupBrowseNext = () => {
			let el = $(`.tiktok-video-item[data-video-id="${currentVideoID}"]`, elements.resultWrap)
				.next('.tiktok-video-item');
			if (el.length < 1) {
				el = $(`.tiktok-video-item`, elements.resultWrap)
					.first()
			}
			currentVideoID = el.attr('data-video-id');
			showPopup();
		};
		
		let next = 0,
			maxCursor = 0,
			hasMore = true,
			page = 1,
			totalPages = 1;
		let videoWrap = $("<div>", {
			class: `tiktok-videos`
		});
		let loadMoreWrap = $("<div>", {
				class: `load-more text-center`,
				href: "#load-more"
			})
			.hide();
		let loadMore = $("<button>", {
				type: "button",
				class: 'btn btn-primary btn-md load-more-button'
			})
			.html(settings.i18n.load_more);;
		loadMoreWrap.append(loadMore);
		var paginate = (stack, page, per_page) => {
			return stack.slice((page - 1) * per_page, page * per_page);
		}
		const showHideLoadMore = function () {
			const next = paginate(dataStack, page + 1, settings.per_page);
			if (next.length > 0) {
				loadMoreWrap.show();
			} else {
				loadMoreWrap.hide();
			}
		}
		const parseVideos = () => {
			const paged = paginate(dataStack, page, settings.per_page);
			if (hasMore == true && paged.length < settings.per_page) {
				getVideos()
					.then(() => {
						parseVideos()
					});
				return;
			}
			$('.tiktok-loading', elements.resultWrap)
				.remove();
			$.each(paged, (i, video) => {
				videoWrap.append(_.template(tpl.video.html())({
					video: video,
					extra: extras,
				}));
			});
			if (dataStack.length < 1) {
				elements.resultWrap.append(noVideos);
			}
			watchVideoStack();
		};
		const musicIdFromURL = url => {
			let musicID = null;
			url.replace(/music\/([^\/\?]+)/, (content, id) => {
				musicID = id.split("-")
					.pop();
				return content;
			});
			return musicID;
		};
		const getSearchType = async function (search) {
			if (search.slice(0, 1) == "@") {
				type = "user";
				keyword = search.split('@')[1];
				return new Promise((resolve, reject) => {
					resolve();
				});
			} else if (search.slice(0, 1) == "#") {
				type = "challenge";
				keyword = search.split('#')[1]
				return new Promise((resolve, reject) => {
					resolve();
				});
			} else if (/https?:\/([^]+)\.tiktok\.com\/music/.test(search)) {
				type = "music";
				keyword = musicIdFromURL(search);
				return new Promise((resolve, reject) => {
					resolve();
				});
			} else if (/https?:\/([^]+)\.tiktok\.com\//.test(search)) {
				type = "url";
				keyword = search;
				return new Promise((resolve, reject) => {
					resolve();
				});
			} else {
				return new Promise((resolve, reject) => {
					reject();
				});
			}
		};
		const watchVideoStack = () => {
			const next = paginate(dataStack, page + 1, settings.per_page);
			if (next.length < parseInt(settings.per_page) && hasMore == true) {
				getVideos()
					.then(() => {
						watchVideoStack();
						showHideLoadMore();
					});
			}
			showHideLoadMore();
		}
		const getVideos = async function () {
			return new Promise((resolve, reject) => {
				$.getJSON('ajax.php', {
						search: keyword,
						type: type,
						max: maxCursor,
					}, res => {
						hasMore = res.hasMore;
						maxCursor = res.maxCursor;
						$.each(res.items, (i, item) => {
							const exists = _.find(dataStack, (_item) => _item.id == item.id);
							if (typeof exists == "undefined") {
								dataStack.push(item);
							}
						});
						resolve();
					})
					.fail(err => {
						reject(err);
					});
			});
		};
		elements.resultWrap.on('click','.load-more', e => {
			e.preventDefault();
			page++;
			loadMoreWrap.hide();
			parseVideos();
		});
		elements.resultWrap.on('click.tiktok', 'a.video-link[href*="tiktok.com"]', (e) => {
			e.preventDefault();
			let el = $(e.currentTarget)
				.closest('.tiktok-video-item')
			currentVideoID = el.attr('data-video-id');
			showPopup();
		});
		popupPrev.on('click.tiktok', e => {
			e.preventDefault();
			popupBrowsePrev();
		});
		popupNext.on('click.tiktok', e => {
			e.preventDefault();
			popupBrowseNext();
		});
		popupClose.on('click.tiktok', e => {
			e.preventDefault();
			$('body, html')
				.css({
					overflow: 'unset',
				});
			popupBody.empty();
			popup.hide();
		});
		popup.on('click.tiktok', '.tiktok-mute-unmute', function (e) {
			e.preventDefault();
			if ($(this)
				.hasClass('muted')) {
				$(this)
					.removeClass('muted');
				$('#tiktok-video-el')[0].muted = false;
			} else {
				$(this)
					.addClass('muted');
				$('#tiktok-video-el')[0].muted = true;
			}
		});
		popup.keydown((e) => {
			switch (e.keyCode ? e.keyCode : e.which) {
				case 37:
					popupBrowsePrev();
					break;
				case 39:
					popupBrowseNext();
					break;
			}
		});
		elements.form.on('submit', (e) => {
			e.preventDefault();
			elements.resultWrap.empty();
			videoWrap.empty();
			$('#search-btn')
				.addClass('loading');
			getSearchType($("#search-keyword")
				.val())
				.then(() => {
					dataStack = [];
					maxCursor = 0;
					hasMore = false;
					elements.resultWrap.append(videoWrap);
					elements.resultWrap.removeClass('d-none');
					getVideos()
						.then(() => {
							parseVideos();
							$('html, body')
								.animate({
									scrollTop: elements.resultWrap.offset()
										.top - 80
								}, 500);
							if (settings.enable_pagination) {
								elements.resultWrap.append(loadMoreWrap);
							}
							$('#search-btn')
								.removeClass('loading');
						}, err => {
							elements.resultWrap.append(noVideos);
							$('#search-btn')
								.removeClass('loading');
						});
				}, err => {
					elements.resultWrap.append(inValidKeyword);
					$('#search-btn')
						.removeClass('loading');
				});
		});
	})(jQuery);
};
