<?php
$step   = @$_POST['step'] ? $_POST['step'] : '0';
$stepv  = false;
$error  = [];
$values = [];
if (version_compare(PHP_VERSION, '5.3.0') < 0) {
	$error[] = "PHP version 5.3.0 or higher is required. Your version is " . PHP_VERSION . ".";
}
if (!function_exists('json_decode')) {
	$error[] = "PHP installation is missing JSON functionality";
}
if (!isset($_SESSION)){
	$error[] = 'Support for sessions is disabled in PHP';
}
if (!is_writable(CONFIG_DIR)) {
	$error[] = "_config folder not writeable";
}

if (!is_dir(CONFIG_DIR . "temp")) {
	if (!@mkdir(CONFIG_DIR . "temp", 0777, true)) {
		$error[] = "_config/temp folder not writeable";
	}
}
function refreshVerificationFile()
{
	global $error;
	delTemp();
	if (@file_put_contents(CONFIG_DIR . "temp/" . mt_rand(10000, 99999), '') === false) {
		$error[] = 'Directory ' . CONFIG_DIR . "temp" . ' is not writeable';
	}
}
switch ($step) {
	case '0':
	refreshVerificationFile();
	break;
	case '1':
        // just the filename confirmation step created on step 0, we do nothing in this step
	break;
	case '2':
	$file_verify = @$_POST['file_verify'];
	if (!preg_match('/^\d{5}$/', $file_verify) || !file_exists(CONFIG_DIR . "temp/" . $file_verify)) {
		refreshVerificationFile();
		$step    = '1';
		$error[] = 'Invalid file name verification. Please enter valid filename as you can see via ftp or your web file browser in ' . CONFIG_DIR . "temp/ directory";
	}
	break;
	case '3':
	$file_verify = @$_POST['file_verify'];
	if(preg_match('/^\d{5}$/', $file_verify) && file_exists(CONFIG_DIR . "temp/" . $file_verify)){
		$username        = @$_POST['username'];
		$password        = @$_POST['password'];
		$verify_password = @$_POST['verify_password'];
		if ("" != $username && "" !== $password) {
			if ($password !== $verify_password) {
				$step    = '2';
				$error[] = "Verification password didn't match.";
			} else {
				$userarray = ['username' => $username, 'password' => hashIt($password)];
				@file_put_contents(CONFIG_DIR . "/userdata.php", '<?php return ' . var_export($userarray, true) . ";");
			}
		}
		$default = (array) include INC_DIR . "config-default.php";
		$fromFile = (array) @include CONFIG_DIR . "config.php";
		$value   = array_merge($default, $fromFile);
	}else{
		refreshVerificationFile();
		$step='1';
	}
	break;
	case '4':
	$file_verify = @$_POST['file_verify'];
	if(preg_match('/^\d{5}$/', $file_verify) && file_exists(CONFIG_DIR . "temp/" . $file_verify)){
		$default = include INC_DIR . "config-default.php";
		$fromFile = @include CONFIG_DIR . "config.php";
		$value   = array_merge($default, $fromFile);
		$value=array_filter($value);
		foreach(array_keys($default) as $key){
			if($key!=""){
				$value[$key]=isset($_POST[$key])?$_POST[$key]:"";
			}
		}
		@file_put_contents(CONFIG_DIR . "/config.php", '<?php return ' . var_export($value, true) . ";");
		@file_put_contents(CONFIG_DIR . "/installed.php", '<?php return true;');
		delTemp();

	}else{
		refreshVerificationFile();
		$step='1';
	}
	break;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>TikTok Downloader | Setup</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/admin.min.css" />
</head>
<body>
	<div class="container">
		<div class="jumbotron">
			<script>
				if (location.protocol=='http:'){
					document.write('<div class="alert alert-danger"><strong>Warning: You are not using a secure connection and data is sent unencrypted.</strong></div>');
				}
			</script>
			<form method="POST" role="form">
				<input type="hidden" name="step" value="<?php echo $stepv ? $stepv : $step + 1; ?>" />
				<?php
				if ($error) {
					foreach ($error as $e) {
						echo "<div class=\"alert alert-danger\">{$e}</div>";
					}
				}
				switch ($step) {
					case "0":
					?>
					<button type="submit" class="btn btn-primary center">Continue Setup</button>
					<?php
					break;
					case "1":
					?>
					<div class="form-group">
						<label>Filename</label>
						<input type="text" class="form-control" placeholder="5 digit filename" name="file_verify">
						<small  class="form-text text-muted">
							Enter filename created in <strong><?php echo CONFIG_DIR . "temp/"; ?></strong> folder. Use FTP or your web file viewer to find the secret 5 digit filename generated in <strong><?php echo CONFIG_DIR . "temp/"; ?></strong> directory.
						</small>
					</div>
					<button type="submit" class="btn btn-primary center">Verify Ownership</button>
					<?php
					break;
					case "2":
					?>
					<input type="hidden" name="file_verify" value="<?php echo @$_POST['file_verify']; ?>">
					<div class="form-group">
						<label>Admin Username</label>
						<input type="text" class="form-control" placeholder="Enter a username" name="username">
						<small  class="form-text text-muted">
							Admin username
						</small>
					</div>
					<div class="form-group">
						<label>Admin Password</label>
						<input type="password" class="form-control" placeholder="" name="password">
						<small  class="form-text text-muted">
							Admin username
						</small>
					</div>
					<div class="form-group">
						<label>Verify Password</label>
						<input type="password" class="form-control" placeholder="" name="verify_password">
						<small  class="form-text text-muted">
							Verify Password
						</small>
					</div>
					<button type="submit" class="btn btn-primary center">Save Setting &amp; Setup Website Info</button>
					<?php
					break;
					case '3':
					?>
					<input type="hidden" name="file_verify" value="<?php echo @$_POST['file_verify']; ?>">
					<div class="form-group">
						<label>RapidAPI Key</label>
						<input type="text" class="form-control" placeholder="" name="rapidapi_key" value="<?php echo $value['rapidapi_key']; ?>">
						<small>Get API key from <a href="https://rapidapi.com/wppressapi-wppressapi-default/api/tiktok9" target="_blank">https://rapidapi.com/wppressapi-wppressapi-default/api/tiktok9</a>
					</div>
					<div class="form-group">
						<label>Website Name</label>
						<input type="text" class="form-control" placeholder="" name="site_name" value="<?php echo $value['site_name']; ?>">
					</div>
					<div class="form-group">
						<label>Website Description</label>
						<textarea name="site_description" class="form-control" rows="5"><?php echo $value['site_description']; ?></textarea>
					</div>
					<h4>SEO Settings</h4>
					<div class="form-group">
						<label>SEO Image</label>
						<input type="text" class="form-control" placeholder="URL to graph image" name="seo_image" value="<?php echo $value['seo_image']; ?>">
					</div>
					<div class="form-group">
						<label>SEO Title</label>
						<input type="text" class="form-control" placeholder="" name="seo_title" value="<?php echo $value['seo_title']; ?>">
					</div>
					<div class="form-group">
						<label>SEO Description</label>
						<input type="text" class="form-control" placeholder="" name="seo_description" value="<?php echo $value['seo_description']; ?>">
					</div>
					<h3>Tracking</h3>
					<div class="form-group">
						<label>Tracking Code (Inside Head Tag)</label>
						<textarea name="tracking_code_1" class="form-control" rows="5"><?php echo $value['tracking_code_1']; ?></textarea>
					</div>
					<div class="form-group">
						<label>Tracking Code (Inside Body Tag)</label>
						<textarea name="tracking_code_2" class="form-control" rows="5"><?php echo $value['tracking_code_2']; ?></textarea>
					</div>
					<h3>Advertisement Embed Code</h3>
					<div class="form-group">
						<label>Ad Space 1</label>
						<textarea name="adv_1" class="form-control" rows="5"><?php echo $value['adv_1']; ?></textarea>
					</div>
					<div class="form-group">
						<label>Ad Space 2</label>
						<textarea name="adv_2" class="form-control" rows="5"><?php echo $value['adv_2']; ?></textarea>
					</div>
					<div class="form-group">
						<label>Ad Space 3</label>
						<textarea name="adv_3" class="form-control" rows="5"><?php echo $value['adv_3']; ?></textarea>
					</div>
					<button type="submit" class="btn btn-primary center">Save</button>
					<?php
					break;
					case '4':
					?>
					<h3 class="text-center">Setup Complete</h3>
					<div class="text-center"><a href="index.php" class="btn btn-primary">View Website</a> <a href="admin.php" class="btn btn-primary" target="_blank">Admin Login</a></div>
					<?php 
					break;
				}
				?>
			</form>
		</div>
	</div>
</body>
</html>