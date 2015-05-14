<?php 
include '../startup.php';
//include '../header.php';

	include '../model/task.php';
	//include '../model/user.php';
	
	global $_db, $_user;

if (isset($_GET['dat']) && isset($_GET['id'])) {
	$dat = $_GET['dat'];

	$dates = DateTime::createFromFormat('Y-m-d',$dat);
	$currentid = $_GET['id'];
	
	$checkq  = dayActivity($dates->format('Y-m-d'),$currentid);
?>

On <?php echo $dates->format('l, d F Y');?> your Timesheet data is 
<table>
<tr>
<th>Check In</th><td><?php echo $checkq['cin'];?></td>
</tr>
<tr>
<th>Check Out</th><td><?php echo $checkq['cout'];?></td>
</tr>
</table> 

<?php
} else {
	echo "Invalid Check parameter";
	
	//$params['dat'] = '';
}

