<?php
include '../startup.php';
$_PAGE_TITLE = 'Home';
$_PAGE_CLASSES = 'base-page';
include 'header.php';
include '../model/task.php';
$param = [];
$totalactivity = getMyOpenActivityTotal($param);
?>



<div class="menuspace"></div>
<div class="amts-menu">
	<div class="tile bg-teal" onclick="window.location.href='attendance/attlist.php';" style="cursor: hand;">
		<div class="tile-content icon">
			<i class="icon-stats-up"></i>
		</div>	
	    <div class="tile-status">
			<span class="name">Attendance</span>
		</div>
	</div>
	<!--<div class="tile bg-crimson">
		<div class="tile-content icon">
			<i class="icon-foursquare"></i>
		</div>	
	    <div class="tile-status">
			<span class="name">Finish It!</span>
		</div>
	</div>-->
	<div class="tile double bg-amber" onclick="window.location.href='amts/taskpick.php'" style="cursor : hand;">
		<div class="tile-content icon">
			<i class="icon-basket"></i>
		</div>	
		<div class="brand">
			<div class="badge bg-red"><strong><?php echo $totalactivity; ?></strong></div>
		</div>
	    <div class="tile-status">
			<span class="name">Task Picker</span>
		</div>	
	</div>
	<div class="tile bg-darkMagenta" onclick="window.location.href='amts/taskclose.php'" style="cursor : hand;">
		<div class="tile-content icon">
			<i class="icon-clipboard-2"></i>
		</div>	
	    <div class="tile-status">
			<span class="name">My List.</span>
		</div>	
	</div>
<!--	<div class="tile half bg-emerald"><div class="brand"><div class="badge bg-red"><strong>27</strong></div></div></div>-->
<!--	<div class="tile bg-darkGreen">
		<div class="tile-content icon">
			<i class="icon-key-2"></i>
		</div>	
	    <div class="tile-status">
			<span class="name">My Activity</span>
		</div>	
	</div> -->
	<a href="<?php echo HTTPS_SERVER; ?>actions/login.php?act=logout" data-type="post"><div class="tile bg-darkBlue" style="cursor : hand;" data-type="post">
		<div class="tile-content icon">
			<i class="icon-unlocked"></i>
		</div>	
	    <div class="tile-status">
			<span class="name">Logout</span>
		</div>	
	</div></a>
</div>

<?php include 'footer.php'; ?>