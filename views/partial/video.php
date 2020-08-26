<?php if(!defined('BASE_DIR')) die("Cheating?"); ?>
<div class="tiktok-video-item" data-video-id="<%=video.id%>">
	<div class="video-item-inner">
		<% if(video.video.cover){%>
		<div style="background-image:url(<%=video.video.cover%>)" class="tiktok-video-cover"></div>
		<% }else{%>
		<video src="<%=video.video.playAddr%>" id="tiktok-video-el" muted preload="metadata" class="tiktok-video-cover-video">
		</video>
		<%} %>
		<div style="background-image:url(<%=video.video.dynamicCover%>)" class="tiktok-video-cover-dynamic"></div>
		<div class="video-play-count">
			<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M12.3171 7.57538C12.6317 7.77102 12.6317 8.22898 12.3171 8.42462L4.01401 13.5871C3.68095 13.7942 3.25 13.5547 3.25 13.1625V2.83747C3.25 2.44528 3.68095 2.20577 4.01401 2.41285L12.3171 7.57538Z" stroke="white" stroke-width="1.5"/>
			</svg> 
			<%=extra.humanize(video.stats.playCount)%>
		</div>
		<a href="https://www.tiktok.com/@<%=video.author.uniqueId%>/video/<%=video.id%>" class="video-link" target="_blank"></a>
	</div>
</div>