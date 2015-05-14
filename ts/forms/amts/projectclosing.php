<?php
include '../../startup.php';
include '../header.php';

include '../../model/project.php';
include '../../model/user.php';

$params = array();
if (isset($_GET['filter_project'])) {
	$params['filter_project'] = $_GET['filter_project'];
} else {
	$params['filter_project'] = '';
}


if  (isset($_GET['project'])) {
	$params['project'] = (int)$_GET['project'];
} else {
	$params['project'] = "X";
}

if  (isset($_GET['phase'])) {
	$params['phase'] = (int)$_GET['phase'];
} else {
	$params['phase'] = "X";
}

if (($params['project'] !== null)||($params['project'] !== "X")||($params['phase'] !== null)||($params['phase'] !== "X"))
{
	$project = getProjectById($params);
	$phase = getPhaseDetail($params);
	$activity = getProjectActivity($params);
	$isActive = isProjectActive($params);
}

if  (isset($_GET['debug'])) {
	$params['debug'] = (int)$_GET['debug'];

	if (($params['debug'] !== null)&&($params['debug'] !== "1"))
	{
			echo '<pre>';
			print_r($params);
			echo '---------------';
			print_r($project);
			echo '---------------';
			print_r($phase);
			echo '---------------';
			print_r($activity);
			echo '---------------';
			print_r($_user);
			echo '---------------';
			print_r($isActive);
			echo '---------------';			
			print_r($_user->getId());
			echo  '</pre>';
	}
	
}

?>
<style type="text/css">
.window.shadow { overflow:visible !important; }
</style>
<h1><a href="projectlist.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Phase <small class="on-right">Closing</small>
</h1>
<div id="message-bar" ></div>
<!--<div class="button-bar">
	<?php //if ($_form->userHasFunction('add')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="activitynew.php">
		<div class="icon icon-plus-2 icon-l"></div>
		<div class="icon-label">Add New Activity</div>
	</button>
	<?php //} ?>
</div>-->
<!--<div class="filter-block">
	<form id="frm1" class="view-list" method="post" action="../../actions/amts/projectlist.php">
	<table>
		<tr>
			<th>Activity </th>
			<td class="searchbar01">
				<div class="input-control text" data-role="input-control">
					<input type="text" name="filter_project" placeholder="type text" value="<?php echo $params['filter_project']; ?>">
					<button class="btn-clear" tabindex="-1"></button>
				</div>
				<button type="submit" class="input-btn btn-main" >
					<div class="icon icon-search icon-l"></div>
					<div class="icon-label">Search</div>
				</button>
			</td>
		</tr>	
	</table>	
	</form>
</div>-->

<div class="form-block">
<form id="frm1" class="view-list" method="post" action="../../actions/amts/projectclosing.php">
<table class="input-form" style="width: 80%;">
	<tr>
		<th style="width: 200px; white-space:nowrap;">Project Number</th>
		<td>
			<?php echo $project['projectnumber']; ?>
			<input type="hidden" name="projectid" value="<?php echo $project['projectid']; ?>" />
			<input type="hidden" name="phaseid" value="<?php echo $project['phaseid']; ?>" />			
		</td>
	</tr>
	<tr>
		<th>Project Name</th>
		<td>
			<?php echo $project['prname']; ?>
		</td>
	</tr>	
	<tr>
		<th>Description</th>
		<td>
			<?php echo $project['prdesc']; ?>
		</td>
	</tr>	
	<tr>
		<th>Start Date</th>
		<td>
			<?php echo $project['startdate']; ?>
		</td>
	</tr>	
	<tr>
		<th>Estimated</th>
		<td>
			<?php echo $project['estimatedmanhour']; ?>
		</td>
	</tr>	
	<tr>
		<th>Phase Name</th>
		<td>
			<?php echo $project['phname']; ?>
		</td>
	</tr>	
	<tr>
		<th>Phase Description</th>
		<td>
			<?php echo $project['phdesc']; ?>
		</td>
	</tr>		
	<tr>
		<th>Phase Estimated</th>
		<td>
			<?php echo $project['phestimatedmanhour']; ?>
		</td>
	</tr>
	<tr>
		<th>&nbsp;</th>
		<td></td>
	</tr>
	<tr>
		<th style="vertical-align: top;">Activity List</th>
		<td>				
				<div id="activitytable" style="width: 80%; height: auto;">
					<table class="table hovered border myClass">
						<thead>
							<tr>
								<th class="text-left">No.</th>
								<th class="text-left">Name</th>
								<th class="text-left">Description</th>
								<th class="text-left">Actual</th>
								<th class="text-left">Start</th>
								<th class="text-left">End</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if (is_array($activity)){
								$c=1 ;
								foreach ($activity as $act) { ?> 
									<tr><td class="text-left" style="width: 20px;"><?php echo $c;?></td>
									<td class="text-left" style="width: 100px;white-space:nowrap;"><?php echo $act['Name'];?></td>
									<td class="text-left" style="width: 80%;"><?php echo $act['Description'];?></td>
									<td class="text-left" style="width: 40px;"><?php echo $act['actualhour'];?></td>
									<td class="text-left" style="width: 100px;white-space:nowrap;"><?php echo $act['ScheduleStart'];?></td>
									<td class="text-left" style="width: 100px;white-space:nowrap;"><?php echo $act['ScheduleEnd'];?></td>
							<?php 
									$c++;
								}
							} ?>
						</tbody>
					</table>
				</div>
		</td>
	</tr>	

	<tr><td colspan=2 style="min-height='20px';"></td></tr>
	<tr>
		<td></td>
		<td>
		<?php if ($isActive!=0) { ?>
			<input type="hidden" name="act" value="edit" />
			<input type="hidden" name="project_id" value="<?php echo $params['project'];?>" />
			<input type="hidden" name="phase_id" value="<?php echo $params['phase'];?>" />
			<button type="submit" class="input-btn btn-main dark" >
				<div class="icon icon-floppy icon-l"></div>
				<div class="icon-label">Save Data</div>
			</button>
		<?php  }else{ ?>
			<button type="submit" class="input-btn btn-main dark" disabled>
				<div class="icon-label" disabled>Close phase</div>
			</button>
		
		<?php  } ?>
		</td>
	</tr>	
</table>
</form>
</div>



<?php include '../footer.php'; ?>