<?php 
include '../../startup.php';
//include '../header.php';

	include '../../model/task.php';
	//include '../model/user.php';
	
	global $_db, $_user;

if (isset($_GET['dat']) && isset($_GET['id'])) {
	$dat = $_GET['dat'];

	$dates = DateTime::createFromFormat('Y-m-d',$dat);
	$currentid = $_GET['id'];
	
	$checkq  = dayCheck($dat,$currentid);
	$activity = dayActivity($dat,$currentid);

?>On <?php echo $dates->format('l, d F Y');?> your Timesheet data are 
<br><table class="table hovered border myClass">
<thead><tr><th colspan=3>Time</th><th>Task</th><th>Result</th></tr></thead>
<tbody>
<tr><td><?php echo $checkq['cin'];?></td> <td>&nbsp;</td><td>&nbsp;</td><td>Check In</td> <td>&nbsp;</td></tr>
<?php 
if ($activity!==false) {
	$cnt = 1;
	foreach($activity as $row)  { ?>
<tr><td><?php //echo substr($row['timefrom'],0,5);
echo $row['timefrom'];
?></td><td>-</td> <td><?php //echo substr($row['timeto'],0,5); 
echo $row['timeto']; 
?></td> <td><?php echo $row['name'];?></td> <td><?php if (strlen($row['result'])<=50) {echo $row['result'];} else {echo substr($row['result'],0,30)."...";}  ?></td></tr>
<?php 
		$cnt++;
	} 
} 
?>
<tr><td>&nbsp;</td><td>&nbsp;</td><td><?php echo $checkq['cout'];?></td> <td>Check Out</td> <td>&nbsp;</td></tr>
</tbody>

</table> 

<?php
} else {
	echo "Invalid Check parameter";
	//$params['dat'] = '';
}

