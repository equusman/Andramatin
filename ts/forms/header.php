<?php 
/********************************************************************/
/* TOKENS :															*/
/*   - $_PAGE_TITLE   : set page title 								*/
/*   - $_PAGE_CLASSES : additional body classes, separated by space	*/
/*      							   								*/
/********************************************************************/
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title><?php echo $_PAGE_TITLE; ?></title>
		<link href="<?php echo HTTPS_SERVER; ?>assets/css/metro-bootstrap.css" rel="stylesheet">
		<link href="<?php echo HTTPS_SERVER; ?>assets/css/metro-bootstrap-responsive.css" rel="stylesheet">
		<link href="<?php echo HTTPS_SERVER; ?>assets/css/docs.css" rel="stylesheet">
		<!--link href="<?php echo HTTPS_SERVER; ?>assets/fonts/styles.css" rel="stylesheet" type="text/css"-->
		<link href="<?php echo HTTPS_SERVER; ?>assets/css/global.css" rel="stylesheet" type="text/css">
		<link href="<?php echo HTTPS_SERVER; ?>assets/css/mediaqueries.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" >
			var _site_url = '<?php echo HTTPS_SERVER; ?>';
		</script>
		<script type="text/javascript" src="<?php echo HTTPS_SERVER; ?>assets/js/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo HTTPS_SERVER; ?>assets/js/jquery/jquery.widget.min.js"></script>
		<script type="text/javascript" src="<?php echo HTTPS_SERVER; ?>assets/js/metro/metro-loader.js"></script>
		<script type="text/javascript" src="<?php echo HTTPS_SERVER; ?>assets/js/docs.js"></script>
		<script type="text/javascript" src="<?php echo HTTPS_SERVER; ?>assets/js/global.js"></script>
	</head>
	<body class="metro overflowing form-<?php echo basename($_SERVER['PHP_SELF'],'.php'); ?> <?php echo $_PAGE_CLASSES; ?>">
		<header data-load="<?php echo HTTPS_SERVER; ?>forms/menu.php">
		</header>
		<div class="menuspace"></div>
		<div class="amts-form example">
