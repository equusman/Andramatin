<?php
include '../../startup.php';
include '../header.php';

include '../../model/task.php';
include '../../model/user.php';
include '../../model/currency.php';


if  (isset($_GET['taskid'])) {
	$params['taskid'] = (int)$_GET['taskid'];
} else {
	$params['taskid'] = "X";
}

if (($params['taskid'] !== null)||($params['taskid'] !== "X"))
{
	$task = getMyOpenTaskById($params);
}

if  (isset($_GET['debug'])) {
	$params['debug'] = (int)$_GET['debug'];
	if (($params['debug'] !== null)&&($params['debug'] == "1"))
	{
        echo '<pre>';
        print_r($task);
		echo "----------------";
        print_r($params);
		echo "----------------";
		echo  '</pre>';
	}
}

$datenow = new DateTime();

?>
<h1><a href="taskclose.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Activity <small class="on-right">Closing</small>
</h1>
<div id="message-bar" ></div>
<div class="button-bar">
	<?php if ($_form->userHasAccess('amts/taskclose.php')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="taskclose.php" >
		<div class="icon icon-list icon-l"></div>
		<div class="icon-label">Activity List</div>
	</button>
	<?php } ?>
</div>
<form id="frm1" class="view-list" method="post" action="../../actions/amts/taskstop.php?act=edit">
<div class="form-block">

<table class="input-form">
	<tr>
		<th>Project Number</th>
		<td colspan=2>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="project_number" value="<?php echo $task['projectnumber'];?>" disabled>
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Project Name</th>
		<td colspan=2>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="project_name" value="<?php echo $task['projectname'];?>" disabled>
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Phase</th>
		<td colspan=2>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="phase_name" value="<?php echo $task['phasename'];?>" disabled>
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Activity</th>
		<td colspan=2>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="activity_name" value="<?php echo $task['activityname'];?>" disabled>
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Start Date *</th>
		<td colspan=2>
			<div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>">
				<input type="text" name="task_date" value="<?php echo $task['startdate'];?>" disabled>
				<!--<button class="btn-date" tabindex="-1" type="button"></button>-->
			</div>
		</td>
	</tr>
	<tr>
		<th>Start Hour</th>
		<td>
			<div class="input-control text" data-role="input-control">
						<?php
								$_starttime = explode(':',$task['timefrom']);
						?>
				<input type="text" name="activity_name" value="<?php echo $_starttime[0];?>" disabled>
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
		<td>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="activity_name" value="<?php echo $_starttime[1];?>" disabled>
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>End Hour</th>
		<td>
				<div class="input-control select">
					<select id="end_hour" name="end_hour">
						<?php
							for($i=0;$i<=24;$i++) {
						?>
						<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php if ($i==$datenow->format('H')) {echo "selected";}?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
						<?php } ?>
					</select>
				</div>
		</td>
		<td>
				<div class="input-control select">
					<select id="end_min" name="end_min">
						<?php
							for($i=0;$i<=60;$i++) {
						?>
						<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php if ($i==$datenow->format('s')) {echo "selected";}?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
						<?php } ?>
					</select>
				</div>
		</td>
	</tr>
	<tr>
		<th>Result</th>
		<td colspan=2>
			<div class="input-control textarea" data-role="input-control">
				<textarea type="text" name="task_result" ><?php echo $task['result']; ?></textarea>
			</div>
		</td>
	</tr>
	<tr><td colspan=4 style="min-height='20px';"></td></tr>
	<tr>
		<td></td>
		<td>
			<input type="hidden" name="act" value="edit" />
			<input type="hidden" name="task_id" value="<?php echo $task['taskid'];?>" />
			<button type="submit" class="input-btn btn-main dark" >
				<div class="icon icon-floppy icon-l"></div>
				<div class="icon-label">Save Data</div>
			</button>
		</td>
	</tr>	
</table>
</div>	
</form>

<?php include '../footer.php'; ?>