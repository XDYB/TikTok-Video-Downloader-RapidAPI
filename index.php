<?php
require_once __DIR__ . "/constants.php";
require_once INC_DIR . "functions.php";
?>
<?php include __DIR__."/views/header.php"; ?>
<div class="container">
	<div class="search-form">
		<div class="jumbotron text-center">
			<h2>TikTok Video &amp; Music Download with No Watermark</h2>
			<p>Enter @username, #hashtag, Music URL or Video URL</p>
			<form id="search-form">
				<div class="row">
					<div class="col-sm-7 offset-sm-1">
						<div class="form-group"><input type="text" class="form-control form-control-lg" id="search-keyword" name="search" placeholder="Enter @username, #hashtag, Music or Video URL" value="@tiktok" />
						</div>
					</div>
					<div class="col-sm-3">
						<button class="btn btn-primary btn-block btn-lg" id="search-btn">Download</button>
					</div>
				</div>
			</form>
			<p><strong>Supported formats:</strong></p>
			<ul class="list-unstyled">
				<li>Username: @tiktok</li>
				<li>Challenge/Hashtag: #foryourpage</li>
				<li>Music: https://www.tiktok.com/music/AhiChallenge-6747751045713201926</li>
				<li>Video: https://www.tiktok.com/@tiktok/video/6801895105885195526 </li>
				<li>Video: https://m.tiktok.com/v/6659547527571836166.html</li>
				<li>Video: https://vt.tiktok.com/DNoWLR</li>
				<li>Video: https://vm.tiktok.com/WH9nkK</li>
			</ul>
		</div>
	</div>
	<?php if (get_option('adv_1') != "") {?>
		<div class="banner">
			<?php echo get_option('adv_1'); ?>
		</div>
	<?php }?>
	<div class="search-result" id="result-wrap"></div>
	<div class="content-wrap">
		<div class="card">
			<div class="card-header text-center">
				About TikTok Video Downloader
			</div>
			<div class="card-body text-center">
				<p>With this tool, you can download any public TikTok videos and music used on the video. You can download <strong>non watermarked</strong> videos.</p>
			</div>
		</div>
	</div>
	<?php if (get_option('adv_2') != "") {?>
		<div class="banner">
			<?php echo get_option('adv_2'); ?>
		</div>
	<?php }?>
	<div class="content-wrap">
		<div class="card">
			<div class="card-header text-center">
				How to Download?
			</div>
			<div class="card-body text-center">
				<div class="row">
					<div class="col-sm-4">
						<h5 class="card-title">Step 1</h5>
						<p>Enter @username, #hashtag, music url or video url and click on "Download"</p>
					</div>
					<div class="col-sm-4">
						<h5 class="card-title">Step 2</h5>
						<p>Preview videos in popup</p>
					</div>
					<div class="col-sm-4">
						<h5 class="card-title">Step 3</h5>
						<p>Pick your appropriate format you want to download. Watermarked, non-watermarked or Music and tap on it</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if (get_option('adv_2') != "") {?>
		<div class="banner">
			<?php echo get_option('adv_2'); ?>
		</div>
	<?php }?>
	<div class="content-wrap">
		<h2 class="text-center">Frequently Answered Questions</h2>
		<div class="accordion" id="faqs">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">
						<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#how-to-download">
							How to download video?
						</button>
					</h5>
				</div>

				<div id="how-to-download" class="collapse show" data-parent="#faqs">
					<div class="card-body">
						<ol>
							<li>By username: prefix username with @ like @tiktok and click on "Download"								
							</li>
							<li>By challenge/hashtag: prefix challenge tag with # like #foryourpage and click on "Download"								
							</li>
							<li>By Music: Enter the music URL in search input field and click on "Download"								
							</li>
							<li>By URL:
								<ul>
									<li>
									Open the video which you want to download on your mobile device or in your web browser.</li>
									<li>Tap "Share" and then tap "Copy link" or copy the URL from location bar</li>
									<li>Paste the url our video download form and click on "Download"</li>
								</ul>
							</li>
							<li>Video preview and links to download in your desired format is available in the video popup.</li>
							<li>Click on approriate format you desire, your download should start and saved in your downloads directory as set in your browser setting.</li>
						</ol>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">
						<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#where-saved">
							Where are my videos saved?
						</button>
					</h5>
				</div>
				<div id="where-saved" class="collapse" data-parent="#faqs">
					<div class="card-body">
						After you clicked on one download button the file will be saved to your "Downloads" folder.
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">
						<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#how-ios">
							How to save videos on iOS device?
						</button>
					</h5>
				</div>
				<div id="how-ios" class="collapse" data-parent="#faqs">
					<div class="card-body">
						<p>If you are using iPhone or iPad you have to download another app which creates a folder structure.</p>
						<p>Open the app and navigate to our website.</p>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">
						<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#do-you-save">
							Do we save videos you downloaded?
						</button>
					</h5>
				</div>
				<div id="do-you-save" class="collapse" data-parent="#faqs">
					<div class="card-body">
						<p>No, we don't store any videos or music on our servers. Every file you will download comes directly from remote server. We cache the requests made to external websites to save resources for repeatitive requests, but we don't store them forever.</p>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">
						<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#need-extension">
							Do I need to install any extensions?

						</button>
					</h5>
				</div>
				<div id="need-extension" class="collapse" data-parent="#faqs">
					<div class="card-body">
						<p>No, you don't have to install any browser extension or app. We want to make it as simple as possible for every user.</p>
						<p>All you have to do is to have something you are looking for and we will do the magic!</p>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">
						<button class="btn btn-link collapsed" type="button " data-toggle="collapse" data-target="#do-pay">
							Do I have to pay to download videos?
						</button>
					</h5>
				</div>
				<div id="do-pay" class="collapse" data-parent="#faqs">
					<div class="card-body">
						<p>No, you don't have to pay for anything on our page. It's 100% free!</p>
						<p>But we want to please you to turn off your ad blocker so we have a little income to cover the server costs and support the further development of this tool.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$js_data = [
	"i18n"              => [
		"load_more"      => "Load More",
		"noVideo"        => "No videos available",
		"invalidKeyword" => "Invalid search keyword",
	],
	'adv'               => get_option("popup_adv", ""),
	'per_page'          => 30,
	'enable_pagination' => true,
	'load_more'         => true,
];
?>
<script type="text/javascript">var WPPress_TikTok_Setting=<?php echo json_encode($js_data); ?>;</script>
<script type="text/template" id="video-tpl">
	<?php include BASE_DIR . "views/partial/video.php";?>
</script>
<script type="text/template" id="popup-tpl">
	<?php include BASE_DIR . "views/partial/popup.php";?>
</script>
<script src="assets/vendor/underscore-min.js" defer></script>
<script src="assets/vendor/moment.min.js" defer></script>
<script src="assets/js/script.min.js" defer></script>
<?php include __DIR__."/views/footer.php"; ?>
