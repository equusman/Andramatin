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

// if (isset($_GET['page'])) {
	// $params['page'] = (int)$_GET['page'];
// } else {
	// $params['page'] = 1;
// }

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
	$activity = getProjectActivity($params);
}

if  (isset($_GET['debug'])) {
	$params['debug'] = (int)$_GET['debug'];

if (($params['debug'] !== null)&&($params['debug'] !== "1"))
	{
        echo '<pre>';
        print_r($project);
        print_r($activity);
        echo  '</pre>';
	}
}

?>
<style type="text/css">
.window.shadow { overflow:visible !important; }
</style>
<h1><a href="projectlist.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Activity <small class="on-right">List</small>
</h1>
<div id="message-bar" ></div>
<div class="button-bar">
	<?php if ($_form->userHasAccess('amts/projectlist.php')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="projectlist.php" >
		<div class="icon icon-list icon-l"></div>
		<div class="icon-label">Project List</div>
	</button>
	<?php } ?>
</div>

<!--<div class="button-bar">
	<?php //if ($_form->userHasFunction('add')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="activitynew.php">
		<div class="icon icon-plus-2 icon-l"></div>
		<div class="icon-label">Add New Activity</div>
	</button>
	<?php //} ?>
</div>-->

<div class="form-block">
<form id="frm1" class="view-list" method="post" action="../../actions/amts/activity.php">
<table class="input-form" style="width: 60%;">
	<tr>
		<th style="width: 150px;">Project Number</th>
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
		<th>Project Estimated Hour</th>
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
		<th>Phase Estimated Hour</th>
		<td>
			<?php echo $project['phestimatedmanhour']; ?>
		</td>
	</tr>
	<tr>
		<th>Phase Status</th>
		<td>
			<?php echo getStatus((int)$project['phstatus']); ?>
		</td>
	</tr>
	<tr>
		<th></th>
		<td></td>
	</tr>
	<tr>
		<th style="vertical-align: top;">Activity List</th>
		<td>				
				<div id="activitytable" style="width: 100%; height: auto;">
					<table class="table hovered border myClass">
						<thead>
							<tr>
								<th class="text-left">No.</th>
								<th class="text-left">Name</th>
								<th class="text-left">Description</th>
								<th class="text-left">Start</th>
								<th class="text-left">End</th>
								<th class="text-left" colspan="3"></th>
							</tr>
						</thead>
						<tbody>
						<?php
							$c=1 ;
							if ($activity!==false) {
								foreach ($activity as $act) { ?> 
									<tr><td class="text-left" style="width: 20px;"><?php echo $c;?></td><td class="text-left" style="width: 100px;"><?php echo $act['Name'];?></td><td class="text-left" style="width: 40px;"><?php echo $act['Description'];?></td><td class="text-left" style="width: 100px;white-space:nowrap;"><?php echo $act['ScheduleStart'];?></td><td class="text-left" style="width: 100px;white-space:nowrap;"><?php echo $act['ScheduleEnd'];?></td>
										<td class="text-left" style="width: 30px;">
										<a class="icon-arrow-up" onclick="moveRowUp(this); return false;"></a></td>
										<td class="text-left" style="width: 30px;"><a class="icon-arrow-down" onclick="moveRowDown(this); return false;"></a></td>
										<td class="text-left" style="width: 30px;">
										<a class="icon-cancel-2 icon-red" href="#" onclick="removeRow(this); return false;" ></a>
										<input type="hidden" name="activityname[]" value="<?php echo $act['Name'];?>" />
										<input type="hidden" name="activitydesc[]" value="<?php echo $act['Description'];?>" />
										<input type="hidden" name="activityid[]" value="<?php echo $act['ActivityID'];?>" />
										<input type="hidden" name="activitystart[]" value="<?php echo $act['ScheduleStart'];?>" />
										<input type="hidden" name="activityend[]" value="<?php echo $act['ScheduleEnd'];?>" /> </td>
										
									</tr>						
							<?php 
									$c++;
								}  
							} ?>
						</tbody>
					</table>
				</div>
			<div style="
				float: right;
				clear: both;
			">

			<button class="button" type="button" id="addactivity" <?php if(($project['phactive']=='F')||((int)$project['phstatus']==3)) {echo "disabled";} ?>><i class="icon-plus on-left"></i>Add Activity</button>			
			<script>
				$(function(){
					$("#addactivity").on('click', function(){
						$.Dialog({
							shadow: true,
							flat: false,
							overlay: false,
							draggable: true,
							icon: '<span class="icon-bus"></span>',
							title: 'Add Activity',
							width: 500,
							height:300,
							padding: 0,
							content: '<div class="modal-content" id="modalAdd"><table cellpadding="0" cellspacing="0"><tr> <th class="text-left" valign="top">Activity Name</th><th class="text-left" valign="top">&nbsp;:&nbsp;</th><td><div class="input-control text" data-role="input-control"><input class="txt_activityname" type="text" value="" placeholder="input name of activity.."><button class="btn-clear" tabindex="-1" type="button"></button></div></td></tr> <tr><th class="text-left" valign="top">Description</th><th class="text-left" valign="top">&nbsp;:&nbsp;</th><td><div class="input-control textarea"><textarea class="txt_activitydesc"></textarea></div></td></tr> <tr><th class="text-left" valign="top">Start Date</th><th class="text-left" valign="top">&nbsp;:&nbsp;</th>			<td><div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>"><input type="text" value="" placeholder="" class="txt_startdate" name="startdate"><button class="btn-date" tabindex="-1" type="button"></button></div></td></tr> <tr> <th class="text-left" valign="top">End Date</th><th class="text-left" valign="top">&nbsp;:&nbsp;</th><td><div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>"><input type="text" value="" placeholder="" class="txt_enddate" name="enddate"><button class="btn-date" tabindex="-1" type="button"></button></div></td></tr></table></div><div class="modal-buttons"><button class="primary" type="button" onclick="addActivity(this);">Add</button>&nbsp; <button type="button" onclick="$.Dialog.close()"><i class="icon-cancel on-left"></i>Close</button></div>',
							onShow: function(_dialog){
								$('#modalAdd [data-role="datepicker"]').datepicker();
							}
						});
					});
				});


				function addActivity(obj){
					var obj = $(obj);
					var prt = obj.closest('.modal-buttons').prev();
					var activityname = $('.txt_activityname',prt).val();
					var activitydesc = $('.txt_activitydesc',prt).val();
					var activitystart = $('.txt_startdate',prt).val();
					var activityend = $('.txt_enddate',prt).val();
					var data = $('<tr><td class="text-left" style="width: 20px;"></td><td class="text-left" style="width: 100px;">'+activityname+'</td><td class="text-left" style="width: 40px;">'+activitydesc+'</td><td class="text-left" style="width: 100px;white-space:nowrap;">'+activitystart+'</td><td class="text-left" style="width: 100px;white-space:nowrap;">'+activityend+'</td><td class="text-left" style="width: 30px;"><a class="icon-arrow-up" onclick="moveRowUp(this); return false;"></a></td><td class="text-left" style="width: 30px;"><a class="icon-arrow-down" onclick="moveRowDown(this); return false;"></a></td><td class="text-left" style="width: 30px;"><a class="icon-cancel-2 icon-red" href="#" onclick="removeRow(this); return false;" ></a><input type="hidden" name="activityname[]" value="'+activityname+'" /><input type="hidden" name="activitydesc[]" value="'+activitydesc+'" /><input type="hidden" name="activitystart[]" value="'+activitystart+'" /><input type="hidden" name="activityend[]" value="'+activityend+'" /></td></tr>');
					$('#activitytable table tbody').append(data);
					pfw_addTableCounter($('#activitytable table tbody tr'));
					$('.txt_activityname',prt).val('').focus();
					$('.txt_activitydesc,.txt_startdate,.txt_enddate',prt).val('');
				}

				function removeRow(obj) {
					var tbl = $(obj).closest('tbody');
					$(obj).closest('tr').remove();
					pfw_addTableCounter($('tr', tbl));
				}

				function moveRowUp(obj) {  //harus dipikirkan kalo dia overlap activity yang sudah ada (dipentokin)
					var o = $(obj).closest('tr');
					if (o.prev().is('tr')) {
						o.prev().before(o);
					}
				}
				
				function moveRowDown(obj) {
					var o = $(obj).closest('tr');
					if (o.next().is('tr')) {
						o.next().after(o);
					}
				}
			</script>
			</div>			

		</td>
	</tr>	
	<tr><td colspan=2 style="min-height='20px';"></td></tr>
	<tr>
		<td>
			<input type="hidden" name="act" value="add" />
			<button type="submit" class="input-btn btn-main dark">
				<div class="icon icon-floppy icon-l"></div>
				<div class="icon-label">Save Data</div>
			</button>
		</td>
	</tr>	
</table>
</form>
</div>



<script type="text/javascript">
	$(function(){
		$('#btnDelete').click(function(){
			if (confirm("You are going to delete "+$('.checkItemToggle:checked').length+" item(s).\nAre you sure?")) {
				var dt = '';
				$('.checkItemToggle:checked').each(function(){
					dt += (dt==''?'':',')+$(this).val();
				});
				//console.log(dt);
				$('input[name="ids"]').val(dt);
				$('#btnDelForm').click();
			}
		});
	});
</script>
<?php include '../footer.php'; ?>