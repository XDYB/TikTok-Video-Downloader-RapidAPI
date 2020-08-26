<div class="tiktok-popup-inner tiktok-popup-video-<%=video.id%>">
	<div class="tiktok-video-wrap">
		<div class="video-inner">
			<video src="<%=video.video.playAddr%>" id="tiktok-video-el" width="<%=video.video.width%>" height="<%=video.video.height%>" playsinline loop preload="metadata" autoplay>
			</video>
			<div class="video-time"></div>
			<div class="video-overlay"></div>
			<a class="tiktok-mute-unmute">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="40" height="40" viewBox="0 0 40 40">
					<defs>
						<path id="b" d="M23.172 14.734c.276.306.477.542.6.706A7.557 7.557 0 0 1 25.307 20a7.56 7.56 0 0 1-1.577 4.615c-.116.151-.302.368-.558.651l-1.417-1.23c.209-.23.36-.406.453-.53A5.8 5.8 0 0 0 23.383 20a5.79 5.79 0 0 0-1.106-3.406 8.869 8.869 0 0 0-.522-.63l1.417-1.23zm2.368-2.058c.296.326.512.575.649.747C27.639 15.25 28.5 17.525 28.5 20c0 2.485-.867 4.766-2.328 6.598-.133.167-.344.41-.632.726l-1.417-1.23c.25-.273.432-.482.546-.626A8.735 8.735 0 0 0 26.597 20c0-2.05-.73-3.944-1.932-5.467a13.763 13.763 0 0 0-.542-.626l1.417-1.231zM10.752 22.89a.747.747 0 0 1-.752-.755v-4.269c0-.417.341-.755.752-.755H14l3.554-3.423c.799-.77 1.446-.498 1.446.606v11.413c0 1.105-.643 1.38-1.446.606L14 22.89h-3.248z"/>
						<filter id="a" width="202.7%" height="239.6%" x="-51.4%" y="-62.7%" filterUnits="objectBoundingBox">
							<feOffset dy="1" in="SourceAlpha" result="shadowOffsetOuter1"/>
							<feGaussianBlur in="shadowOffsetOuter1" result="shadowBlurOuter1" stdDeviation="3"/>
							<feColorMatrix in="shadowBlurOuter1" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.2 0"/>
						</filter>
					</defs>
					<g fill="none" fill-rule="evenodd">
						<circle cx="20" cy="20" r="20" fill="#000" fill-opacity=".5"/>
						<use fill="#000" filter="url(#a)" xlink:href="#b"/>
						<use fill="#FFF" xlink:href="#b"/>
					</g>
				</svg>
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="40" height="40" viewBox="0 0 40 40">
					<defs>
						<path id="tiktok-mute" d="M10.752 22.89a.747.747 0 0 1-.752-.755v-4.269c0-.417.341-.755.752-.755H14l3.554-3.423c.799-.77 1.446-.498 1.446.606v11.413c0 1.105-.643 1.38-1.446.606L14 22.89h-3.248zM26 18.598l2.804-2.803 1.401 1.401L27.402 20l2.803 2.804-1.401 1.401L26 21.402l-2.804 2.803-1.401-1.401L24.598 20l-2.803-2.804 1.401-1.401L26 18.598z"/>
						<filter id="tiktok-mute-a" width="196%" height="238.5%" x="-47.5%" y="-62.5%" filterUnits="objectBoundingBox">
							<feOffset dy="1" in="SourceAlpha" result="shadowOffsetOuter1"/>
							<feGaussianBlur in="shadowOffsetOuter1" result="shadowBlurOuter1" stdDeviation="3"/>
							<feColorMatrix in="shadowBlurOuter1" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.2 0"/>
						</filter>
					</defs>
					<g fill="none" fill-rule="evenodd">
						<circle cx="20" cy="20" r="20" fill="#000" fill-opacity=".5"/>
						<use fill="#000" filter="url(#tiktok-mute-a)" xlink:href="#tiktok-mute"/>
						<use fill="#FFF" xlink:href="#tiktok-mute"/>
					</g>
				</svg>
			</a>
		</div>
	</div>
	<div class="tiktok-video-info">

		<div class="tiktok-userinfo">
			<div class="tiktok-user-avatar"><img src="<%=video.author.avatarThumb%>" /></div>
			<div class="tiktok-user-detail">
				<div class="tiktok-user-name">
					<%=video.author.nickname%>
					<% if(video.author.verified){%>
					<span class="tiktok-user-verified" title="Verified">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M10 20C15.5228 20 20 15.5228 20 10C20 4.47715 15.5228 0 10 0C4.47715 0 0 4.47715 0 10C0 15.5228 4.47715 20 10 20Z" fill="#20D5EC"/>
							<path fill-rule="evenodd" clip-rule="evenodd" d="M8.11859 12.2863C7.63234 12.7725 7.63234 13.5675 8.11859 14.0525C8.60422 14.5387 9.39859 14.5387 9.88484 14.0525L15.4005 8.53688C15.8861 8.05125 15.8861 7.25688 15.4005 6.77062C15.1573 6.52812 14.8373 6.40625 14.5173 6.40625C14.1967 6.40625 13.8767 6.52812 13.6336 6.77062L8.11859 12.2863Z" fill="white"/>
							<path fill-rule="evenodd" clip-rule="evenodd" d="M11.651 12.286C11.1654 12.7716 10.3704 12.7716 9.88477 12.286L9.00164 11.4029L6.36727 8.76852C5.88164 8.28289 5.08602 8.28289 4.60102 8.76852C4.11477 9.25414 4.11477 10.0491 4.60102 10.5348L8.11852 14.0523C8.60414 14.5385 9.39852 14.5385 9.88477 14.0523L11.651 12.286Z" fill="white"/>
						</svg>
					</span>
					<% } %>
				</div>
				<div class="tiktok-user-id">@<%=video.author.uniqueId%></div>
			</div>
			<a href="https://www.tiktok.com/@<%=video.author.uniqueId%>" target="_blank"></a>

		</div>
		<div class="tiktok-video-date">
			<%=moment.unix(video.createTime).fromNow()%>
		</div>
		<% if(video.desc){%>
		<div class="tiktok-video-tagline">
			<%=extra.urlhash(video.desc)%>
		</div>
		<% } %>

		<% if(video.music.title){%>
		<div class="tiktok-video-sound">
			<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M5.75 10.8335V2.96886L12.25 1.88553V9.25018H10.3333C9.23516 9.25018 8.25 9.86591 8.25 10.8335C8.25 11.8011 9.23516 12.4169 10.3333 12.4169H11.6667C12.7648 12.4169 13.75 11.8011 13.75 10.8335V10.0002V1.00019C13.75 0.536731 13.3338 0.184199 12.8767 0.26039L4.8767 1.59372C4.51506 1.654 4.25 1.96689 4.25 2.33352V10.0835H2.33333C1.23516 10.0835 0.25 10.6993 0.25 11.6669C0.25 12.6345 1.23516 13.2502 2.33333 13.2502H3.66667C4.76484 13.2502 5.75 12.6345 5.75 11.6669V11.3335V10.8335Z" fill="#161823"/>
			</svg>
			<%=video.music.title%>
		</div>
		<% } %>
		<div class="tiktok-video-stats"><%=extra.humanize(video.stats.playCount)%> plays · <%=extra.humanize(video.stats.diggCount)%> likes · <%=extra.humanize(video.stats.commentCount)%> comments</div>
		<div class="video-download">
			<a href="https://www.tiktok.com/@<%=video.author.uniqueId%>/video/<%=video.id%>" class="tiktok-download-link" data-key="video">Download</a>
			
			·
			<a href="https://www.tiktok.com/@<%=video.author.uniqueId%>/video/<%=video.id%>" class="tiktok-download-link" data-key="no-watermark">Download (no watermark)</a>
			
		</div>
		<% if(video.music){%>
		<?php if(!defined('BASE_DIR')) die("Cheating?"); ?>
		<div class="music-download">
			<a href="https://www.tiktok.com/@<%=video.author.uniqueId%>/video/<%=video.id%>" class="tiktok-download-link" data-key="music">Download Music</a>
		</div>
		<% } %>
	</div>
</div>