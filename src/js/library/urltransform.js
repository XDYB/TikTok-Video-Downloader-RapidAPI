module.exports= function(text) {
	text = text.replace(/[@]+([\w.-]+)/g, '<a href="https://www.tiktok.com/@$1" target="_blank">@$1</a> ')
	return text.replace(/[#]+([\w.-]+)/g, '<a href="https://www.tiktok.com/tag/$1" target="_blank">#$1</a> ');
};