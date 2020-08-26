<?php
session_start();
require_once __DIR__ . "/constants.php";
require_once INC_DIR . "functions.php";

if (!file_exists(CONFIG_DIR . 'installed.php')) {
	require INC_DIR . 'installer.php';
	exit;
}
$error    = [];
$message  = [];
$is_admin = false;
if (isset($_POST['username']) && isset($_POST['password'])) {
	if (doAuth($_POST['username'], $_POST['password']) === false) {
		$error[] = "Invalid username or password";
	}
}
$userdata = include CONFIG_DIR . "userdata.php";
if (isset($_SESSION['is_admin']) && hashIt($userdata['username'] . $userdata['password']) === $_SESSION['is_admin']) {
	$is_admin = true;
}
$action = isset($_GET['action']) ? $_GET['action'] : "setting";
switch ($action) {
	case "logout":
	session_unset();
	header("Location: admin.php?loggedout=true");
	exit;
	break;
	case "setting":
	default:
	if (isset($_POST['update_setting'])) {
		$default = include INC_DIR . "config-default.php";
		$config  = include CONFIG_DIR . "config.php";
		$value   = array_merge($default, $config);

		foreach (array_keys($default) as $key) {
			if ("" != $key) {
				$value[$key] = isset($_POST[$key]) ? $_POST[$key] : "";
			}
		}
		@file_put_contents(CONFIG_DIR . "/config.php", '<?php return ' . var_export($value, true) . ";");
		sleep(3);
		header("Location: admin.php");
	}
	break;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>TikTok Downloader | Admin</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/admin.min.css" />
</head>
<body>

	<?php if ($is_admin) {
		?>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<a class="navbar-brand" href="admin.php">TikTok Downloader Admin</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav">
					<?php if ($is_admin) {?>
						<li class="nav-item">
							<a class="nav-link" href="admin.php">Admin Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo site_url(); ?>" target="_blank">View Website</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="?action=logout">Logout</a>
						</li>
					<?php }?>
				</ul>

			</div>
		</nav>
		<div id="wrap">
			<div class="container">
				<?php
				if ($error) {
					foreach ($error as $e) {
						echo "<div class=\"alert alert-danger\">{$e}</div>";
					}
				}
				?>
				<form method="POST" role="form" action="admin.php">
					<input type="hidden" name="update_setting" value="true" />
					<div class="form-group">
						<label>RapidAPI Key</label>
						<input type="text" class="form-control" placeholder="" name="rapidapi_key" value="<?php echo get_option('rapidapi_key'); ?>">
						<small>Get API key from <a href="https://rapidapi.com/wppressapi-wppressapi-default/api/tiktok9" target="_blank">https://rapidapi.com/wppressapi-wppressapi-default/api/tiktok9</a>
					</div>
					<div class="form-group">
						<label>Website Name</label>
						<input type="text" class="form-control" placeholder="" name="site_name" value="<?php echo get_option('site_name'); ?>">
					</div>
					<div class="form-group">
						<label>Website Description</label>
						<textarea name="site_description" class="form-control" rows="5"><?php echo get_option('site_description'); ?></textarea>
					</div>
					<h4>SEO Settings</h4>
					<div class="form-group">
						<label>SEO Image URL</label>
						<input type="text" class="form-control" placeholder="URL to graph image" name="seo_image" value="<?php echo get_option('seo_image'); ?>">
					</div>
					<div class="form-group">
						<label>SEO Title</label>
						<input type="text" class="form-control" placeholder="" name="seo_title" value="<?php echo get_option('seo_title'); ?>">
					</div>
					<div class="form-group">
						<label>SEO Description</label>
						<input type="text" class="form-control" placeholder="" name="seo_description" value="<?php echo get_option('seo_description'); ?>">
					</div>
					<h3>Tracking</h3>
					<div class="form-group">
						<label>Tracking Code (Inside Head Tag)</label>
						<textarea name="tracking_code_1" class="form-control" rows="5"><?php echo get_option('tracking_code_1'); ?></textarea>
					</div>
					<div class="form-group">
						<label>Tracking Code (Inside Body Tag)</label>
						<textarea name="tracking_code_2" class="form-control" rows="5"><?php echo get_option('tracking_code_2'); ?></textarea>
					</div>
					<h3>Advertisement Embed Code</h3>
					<div class="form-group">
						<label>Ad Space 1</label>
						<textarea name="adv_1" class="form-control" rows="5"><?php echo get_option('adv_1'); ?></textarea>
					</div>
					<div class="form-group">
						<label>Ad Space 2</label>
						<textarea name="adv_2" class="form-control" rows="5"><?php echo get_option('adv_2'); ?></textarea>
					</div>
					<div class="form-group">
						<label>Ad Space 3</label>
						<textarea name="adv_3" class="form-control" rows="5"><?php echo get_option('adv_3'); ?></textarea>
					</div>
					<button type="submit" class="btn btn-primary center">Save</button>
				</form>

			</div>
		</div>
	<?php } else {?>
		<div id="wrap">
			<div class="container">
				<div class="jumbotron">
					<form method="POST" role="form" action="admin.php">
						<h3>Admin Login</h3>
						<div class="form-group">
							<label>Username</label>
							<input type="text" class="form-control" placeholder="Enter a username" name="username">

						</div>
						<div class="form-group">
							<label>Password</label>
							<input type="password" class="form-control" placeholder="" name="password">

						</div>
						<button type="submit" class="btn btn-primary">Login</button>
					</form>
				</div>
			</div>
		</div>
	<?php }?>
</body>
</html>