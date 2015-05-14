<?php
include '../../startup.php';
include '../header.php';

include '../../model/project.php';
include '../../model/user.php';
include '../../model/task.php';


if  (isset($_GET['activity'])) {
	$params['activity'] = (int)$_GET['activity'];
} else {
	$params['activity'] = "X";
}

$datenow = new DateTime();

if (($params['activity'] !== null)||($params['activity'] !== "X"))
{
	$activity = getActivityById($params);
	//$activity = getProjectActivityByID($params);
	//$members = getProjectMemberByID($params);
	//$phase = getProjectPhaseByID($params);
}

if  (isset($_GET['debug'])) {
	$params['debug'] = (int)$_GET['debug'];
	if (($params['debug'] !== null)&&($params['debug'] == "1"))
	{

        echo '<pre>';
        print_r($activity);
		echo "----------------";
        echo  '</pre>';
	}
}
		
?>
<h1><a href="taskpick.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Add <small class="on-right">Activity</small>
</h1>
<div id="message-bar" ></div>
<div class="button-bar">
	<?php if ($_form->userHasAccess('amts/taskpick.php')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="taskpick.php" >
		<div class="icon icon-list icon-l"></div>
		<div class="icon-label">Pick Activity</div>
	</button>
	<?php } ?>
</div>
<form id="frm1" class="view-list" method="post" action="../../actions/amts/taskadd.php?act=add">
<div class="form-block">

<table class="input-form" style="min-width: 60%;">
	<tr>
		<th>Project Number</th>
		<td colspan=2>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="project_number" value="<?php echo $activity['projectnumber'];?>" disabled>
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Project Name</th>
		<td colspan=2>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="project_name" value="<?php echo $activity['projectname'];?>" disabled>
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Phase</th>
		<td colspan=2>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="phase_name" value="<?php echo $activity['phasename'];?>" disabled>
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Activity</th>
		<td colspan=2>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="activity_name" value="<?php echo $activity['name'];?>" disabled>
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Hour Used</th>
		<td colspan=2>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="hour_used" value="<?php echo $activity['actualhour']." / " .$activity['estimated']; ?>" disabled>
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Start Date *</th>
		<td colspan=2>
			<div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>">
				<input type="text" name="task_date" value="<?php echo $datenow->format('Y-m-d');?>">
				<button class="btn-date" tabindex="-1" type="button"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Start Hour</th>
		<td>
				<div class="input-control select">
					<select id="start_hour" name="start_hour">
						<?php
							for($i=0;$i<=24;$i++) {
						?>
						<option value="<?php echo $i; ?>" <?php if ($i==$datenow->format('H')) {echo "selected";}?>><?php echo $i; ?></option>
						<?php } ?>
					</select>
				</div>
		</td>
		<td>
				<div class="input-control select">
					<select id="start_min" name="start_min">
						<?php
							for($i=0;$i<=60;$i++) {
						?>
						<option value="<?php echo $i; ?>" <?php if ($i==$datenow->format('s')) {echo "selected";}?>><?php echo $i; ?></option>
						<?php } ?>
					</select>
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
						<option value="<?php echo $i; ?>" <?php if ($i==$datenow->format('H')) {echo "selected";}?>><?php echo $i; ?></option>
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
						<option value="<?php echo $i; ?>" <?php if ($i==$datenow->format('s')) {echo "selected";}?>><?php echo $i; ?></option>
						<?php } ?>
					</select>
				</div>
		</td>
	</tr>
	<tr>
		<th>Result</th>
		<td colspan=2>
			<div class="input-control textarea" data-role="input-control">
				<textarea type="text" name="task_result"></textarea>
			</div>
		</td>
	</tr>
	<tr>
		<th>Auto Close</th>
		<td colspan=2>
			<div class="input-control checkbox">
				<label>
					<input type="checkbox" checked />
					<span class="check"></span>
					Close on end of day
				</label>
			</div>
		</td>
	</tr>
	<tr><td colspan=4 style="min-height='20px';"></td></tr>
	<tr>
		<td>
			<input type="hidden" name="act" value="add" />
			<input type="hidden" name="activity_id" value="<?php echo $activity['activityid'];?>" />
			<input type="hidden" name="phase_id" value="<?php echo $activity['phaseid'];?>" />
			<input type="hidden" name="project_id" value="<?php echo $activity['projectid'];?>" />
			<button type="submit" class="input-btn btn-main dark" >
				<div class="icon icon-floppy icon-l"></div>
				<div class="icon-label">Start Activity</div>
			</button>
		</td>
	</tr>	
</table>
</div>	
</form>

<?php include '../footer.php'; ?>