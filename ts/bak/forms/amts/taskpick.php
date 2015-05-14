<?php
include '../../startup.php';
include '../header.php';

include '../../model/properties.php';
include '../../model/task.php';

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


$datenow = new DateTime();
	// echo "<pre>";
	// print_r($datenow);
	// echo "</pre>";


?>
<h1><a href="tasklist.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Entry <small class="on-right">Activity</small>
</h1>
<div id="message-bar" ></div>
<div class="button-bar">
	<?php if ($_form->userHasAccess('amts/tasklist.php')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="tasklist.php" >
		<div class="icon icon-list icon-l"></div>
		<div class="icon-label">Activity List</div>
	</button>
	<?php } ?>
</div>

<form id="frm1" class="view-list" method="post" action="../../actions/amts/taskpick.php">
<div class="form-block">
<div style="display:inline-block; vertical-align:top;">
<table class="input-form">
	
	<tr>
		<th>Activity Date *</th>
		<td colspan=2>
			<div class="input-control select">
				<select id="taskdate" name="taskdate" >
					<?php
						foreach(getAvailableTaskEntryDate($_par) as $_datum) {
						$row = explode("|",$_datum,2);
					?>
					<option value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?></option>
					<?php } ?>
				</select>
			</div>
		</td>
	</tr>


	<tr>
		<th class="verticaltop">Activity *</td>
		<td colspan=2>
				<div id="activitytable" style="width: 100%; height: auto;">
					<table class="table hovered border myClass">
						<thead>
							<tr>
								<th class="text-left">Project</th>
								<th class="text-left">Activity</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			<div style="float: right; clear: both;">
				<button id="btnAddActivity" type="button"><i class="icon-plus on-left"></i>Select Activity</button>			
				<script type="text/javascript">
					function dlg_user_list_callback(arr) {
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
						<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php if (str_pad($i, 2, '0', STR_PAD_LEFT)==$datenow->format('H')) {echo "selected";}?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
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
						<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php if (str_pad($i, 2, '0', STR_PAD_LEFT)==$datenow->format('i')) {echo "selected";}?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
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
						<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php if (str_pad($i, 2, '0', STR_PAD_LEFT)==$datenow->format('H')) {echo "selected";}?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
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
						<option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php if (str_pad($i, 2, '0', STR_PAD_LEFT)==$datenow->format('i')) {echo "selected";}?>><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
						<?php } ?>
					</select>
				</div>
		</td>
	</tr>
	<tr>
		<th>Description *</th>
		<td colspan=2>
			<div class="input-control textarea" data-role="input-control">
				<textarea type="text" name="task_result" > </textarea>
			</div>
		</td>
	</tr>
	<tr><td colspan=3 style="min-height='20px';"></td></tr>
	<tr>
		<td></td>
		<td>
			<input type="hidden" name="act" value="add" />
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
<!-- asdfasdfasdfasdf -->
</td></tr>
</table>
</div>
</div>	
</form>
<?php include '../footer.php'; ?>