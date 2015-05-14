<?php
include '../../startup.php';
include '../header.php';

include '../../model/properties.php';
include '../../model/task.php';

global $_user;

$_p = array();
$_par = array();


$_p['group'] = "amts";
$_p['key'] = "weekend";
$_par['dayweek'] = getSettingGroupKey($_p);

$_p['group'] = "amts";
$_p['key'] = "weekend_task_skip";
$_par['weekendskip'] = getSettingGroupKey($_p);

$_p['group'] = "amts";
$_p['key'] = "max_task_entry_days";
$_par['dayback'] = getSettingGroupKey($_p);

$currentid = $_user->getId();

$datenow = new DateTime();
	// echo "<pre>";
	// print_r($_GET['taskid']);
	// echo "</pre>";

if (isset($_GET['taskid']) ) {
	$taskid['taskid'] = $_GET['taskid'];
} else {
	header('Location: taskclose.php');
}	

$thistask = getTaskById($taskid); 
$_activity['activity'] = $thistask['ActivityID'];
$thisactivity = getActivityById($_activity);
$timefrom = DateTime::createFromFormat('H:i:s',$thistask['TimeFrom']);
$timeto = DateTime::createFromFormat('H:i:s',$thistask['TimeTo']);

if  (isset($_GET['debug'])) {
	$params['debug'] = (int)$_GET['debug'];
	if (($params['debug'] !== null)&&($params['debug'] == "1"))
	{
        echo '<pre>';
		print_r($taskid);
		echo '----------------------------';
		print_r($thistask);
		echo '----------------------------';
        print_r($thisactivity);
        echo  '</pre>';
	}
	
}
?>
<script type="text/javascript">
function getCheckData(_par)
{
	//alert(_par.value);
	//$('#checktime').load("../../helper/getCheckData.php?dat="+_par.value+"&id=<?php echo $currentid;?>");
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("checktime").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","../helper/getCheckData.php?id=<?php echo $currentid;?>&dat="+_par.value,true);
	xmlhttp.send();
	
	
}
</script>
<h1><a href="taskclose.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Edit <small class="on-right">Task</small>
</h1>
<div id="message-bar" ></div>
<div class="button-bar">
	<?php if ($_form->userHasAccess('amts/taskclose.php')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="taskclose.php" >
		<div class="icon icon-list icon-l"></div>
		<div class="icon-label">My Task List</div>
	</button>
	<?php } ?>
</div>

<form id="frm1" class="view-list" method="post" action="../../actions/amts/taskedit.php">
<div class="form-block">
<div style="display:inline-block; vertical-align:top;">
<table class="input-form">
	
	<tr>
		<th>Task Date *</th>
		<td colspan=2>
			<div class="input-control select">
				<select id="taskdate" name="taskdate" onchange="getCheckData(this);">
					<?php
						$findvalue = false;
						$defaultdate = new DateTime($thistask['StartDate']);
						foreach(getAvailableTaskEntryDate($_par) as $_datum) {
						$row = explode("|",$_datum,2);
					?>
					<option value="<?php echo $row[0];?>" <?php 
					if ($thistask['StartDate'] == $row[0]) 
					{
						echo " selected";
						$findvalue = true;
					}
					
					?>><?php echo $row[1]; ?></option>
					<?php } 
					if (!$findvalue) { ?>
					<option value="<?php echo $thistask['StartDate']; ?>" selected><?php echo $defaultdate->format('l, j F Y');  ?> </option>
					<?php } ?>
				</select>
			</div>
		</td>
	</tr>


	<tr>
		<th class="verticaltop">Task *</td>
		<td colspan=2>
				<div id="activitytable" style="width: 100%; height: auto;">
					<table class="table hovered border myClass">
						<thead>
							<tr>
								<th class="text-left">Project</th>
								<th class="text-left">Task</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr data-id="1">
								<td class="text-left"><input type="hidden" name="activity_id" value="<?php echo $thisactivity['activityid']; ?>"><?php echo $thisactivity['projectnumber']; ?></td>
								<td class="text-left"><?php echo $thisactivity['projectname']; ?></td>
								<td class="text-left" style="width: 10px;"></td>
							</tr>
						</tbody>
					</table>
				</div>
			<div style="float: right; clear: both;">
				<button id="btnAddActivity" type="button"><i class="icon-plus on-left"></i>Select Task</button>			
				<script type="text/javascript">
					function dlg_activity_list_callback(arr) {
						if (arr.value) {
							var obj = $('#activitytable tbody tr[data-id="'+arr.value+'"]');
							if (arr.status) {
								if (obj.length<=0) {
									$('#activitytable tbody').empty();
									$('<tr data-id="'+arr.value+'"><td class="text-left" ><input type="hidden" name="activity_id" value="'+arr.value+'" >'+arr.text+'</td><td class="text-left" >'+arr.activity+'</td>	<td class="text-left" style="width: 10px;"></td></tr>').appendTo($('#activitytable tbody'));
								}
							} else {
								obj.remove();
							}
							//pfw_addTableCounter($('#activitytable tbody tr'));
						}
					}
					function removeRow(obj) {
						var tbl = $(obj).closest('tbody');
						$(obj).closest('tr').remove();
						pfw_addTableCounter($('tr', tbl));
					}
					$(function(){
						$('#btnAddActivity').click(function(){
							$.Dialog({
								overlay: false,
								shadow: true,
								flat: false,
								title: 'Select Activity',
								content: 'Loading...',
								width:600,
								height:500,
								draggable:true,
								onShow: function(_dialog){
									$.get('../lookup/activity_list.php',{},function(data){
										$.Dialog.content(data);
									});
									
								}
							});
						});
					});
				</script>
			</div>			
		</td>
	</tr>

	<tr>
		<th>Start from *</th>
		<td>
				<div class="input-control select">
					<select id="start_hour" name="start_hour">
						<?php
							for($i=0;$i<=24;$i++) {
						?>
						<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php if (str_pad($i, 2, '0', STR_PAD_LEFT)==$timefrom->format('H')) {echo "selected";}?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
						<?php } ?>
					</select>
				</div>
		</td>
		<td>
				<div class="input-control select">
					<select id="end_min" name="start_min">
						<?php
							for($i=0;$i<=60;$i++) {
						?>
						<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php if (str_pad($i, 2, '0', STR_PAD_LEFT)==$timefrom->format('i')) {echo "selected";}?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
						<?php } ?>
					</select>
				</div>
		</td>
	</tr>
	<tr>
		<th>Ended at *</th>
		<td>
				<div class="input-control select">
					<select id="end_hour" name="end_hour">
						<?php
							for($i=0;$i<=24;$i++) {
						?>
						<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php if (str_pad($i, 2, '0', STR_PAD_LEFT)==$timeto->format('H')) {echo "selected";}?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
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
						<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php if (str_pad($i, 2, '0', STR_PAD_LEFT)==$timeto->format('i')) {echo "selected";}?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
						<?php } ?>
					</select>
				</div>
		</td>
	</tr>
	<tr>
		<th>Description *</th>
		<td colspan=2>
			<div class="input-control textarea" data-role="input-control">
				<textarea type="text" name="task_result" ><?php echo $thistask['Result']; ?></textarea>
			</div>
		</td>
	</tr>
	<tr><td colspan=3 style="min-height='20px';"></td></tr>
	<tr>
		<td></td>
		<td>
			<input type="hidden" name="taskid" value="<?php echo $thistask['TaskID']; ?>" />
			<input type="hidden" name="act" value="edit" />
			<button type="submit" class="input-btn btn-main dark" >
				<div class="icon icon-floppy icon-l"></div>
				<div class="icon-label">Save Data</div>
			</button>
		</td>
	</tr>	
</table>
</div>
<div style="display:inline-block; vertical-align:top;">
<table>
<tr><td>
<div id="checktime"></div>
<!-- asdfasdfasdfasdf -->
</td></tr>
</table>
</div>
</div>	
</form>
<?php include '../footer.php'; ?>