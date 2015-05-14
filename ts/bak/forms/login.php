<?php
include '../startup.php';

if ($_user->isLogged()) {
	header('Location: home.php');
}

?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title>Login</title>
		<link href="<?php echo HTTPS_SERVER; ?>assets/css/metro-bootstrap.css" rel="stylesheet">
		<link href="<?php echo HTTPS_SERVER; ?>assets/css/metro-bootstrap-responsive.css" rel="stylesheet">
		<link href="<?php echo HTTPS_SERVER; ?>assets/css/docs.css" rel="stylesheet">
		<!--link href="../assets/fonts/styles.css" rel="stylesheet" type="text/css"-->
		<link href="<?php echo HTTPS_SERVER; ?>assets/css/global.css" rel="stylesheet" type="text/css">
		<link href="<?php echo HTTPS_SERVER; ?>assets/css/mediaqueries.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" >
			var _site_url = '<?php echo HTTPS_SERVER; ?>';
		</script>
		<script type="text/javascript" src="<?php echo HTTPS_SERVER; ?>assets/js/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo HTTPS_SERVER; ?>assets/js/jquery/jquery.widget.min.js"></script>
		<script type="text/javascript" src="<?php echo HTTPS_SERVER; ?>assets/js/metro/metro-loader.js"></script>
		<script type="text/javascript" src="<?php echo HTTPS_SERVER; ?>assets/js/global.js"></script>
	</head>
	<body class="metro bgcol-steel">
		<div class="vert-b-horz-l" ><img src="../assets/img/logo-steel.png"></div>
		<div class="vert-c horz-c" style="height: 200px; background:rgba(0,83,85,0.4) url(../assets/img/login-bg.jpg) repeat-x;">
			<div class="inline loginpad">
				<!--div class="vert-b square-l bgcol-02">
					<div class="layer-light-b ">
					Application Name
					</div>
				</div-->
				<div class="vert-b horz-l">
					<form id="frm1" class="view-list" action="../actions/login.php?act=login">
						<div class="field-row">
							<div class="field-label">
							</div>
							<div class="field-content">
								<input type="text" name="username" class="input-txt" data-icon="user" data-placeholder="Username" />
							</div>
						</div>
						<div class="field-row">
							<div class="field-label">
							</div>
							<div class="field-content">
								<input type="password" name="password" class="input-txt" data-icon="key" data-placeholder="Password" />
							</div>
						</div>
						<div class="field-row">
							<div class="field-label">
							</div>
							<div class="field-content">
							<button type="submit" class="input-btn btn-main" >
								<div class="icon icon-arrow-right-3 icon-l"></div>
								<div class="icon-label">Login</div>
							</button>
							</div>
						</div>
					</form>
				</div>
				<div id="message-bar" ></div>
				<div class="clear"> </div>
			</div>
		</div>
	</body>
</html>	