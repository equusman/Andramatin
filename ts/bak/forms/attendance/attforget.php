<?php
include '../../startup.php';
include '../header.php';

include '../../model/properties.php';
include '../../model/task.php';


$datenow = new DateTime();
	// echo "<pre>";
	// print_r($datenow);
	// echo "</pre>";


?>
<h1><a href="attlist.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Forget <small class="on-right">Check In/Out</small>
</h1>
<div id="message-bar" ></div>
<div class="button-bar">
	<?php if ($_form->userHasAccess('attendance/attlist.php')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="attlist.php" >
		<div class="icon icon-list icon-l"></div>
		<div class="icon-label">Attendance List</div>
	</button>
	<?php } ?>
</div>

<form id="frm1" class="view-list" method="post" action="../../actions/attendance/attforget.php">
<div class="form-block">
<table class="input-form">
	
	<tr>
		<th>Check Date *</th>
		<td colspan=2>
			<div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>">
				<input type="text" name="checkdate" value="">
				<button class="btn-date" tabindex="-1" type="button"></button>
			</div>
		</td>
	</tr>

	<tr>
		<th class="verticaltop">Employee</td>
		<td colspan=2>
				<div id="projectmembertable" style="width: 100%; height: auto;">
					<table class="table hovered border myClass">
						<thead>
							<tr>
								<th class="text-left">Id</th>
								<th class="text-left">Employee Name</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			<div style="float: right; clear: both;">
				<button id="btnAddMember" type="button"><i class="icon-plus on-left"></i>Add Member</button>			
				<script type="text/javascript">
					function dlg_user_list_callback(arr) {
						if (arr.value) {
							var obj = $('#projectmembertable tbody tr[data-id="'+arr.value+'"]');
							if (arr.status) {
								if (obj.length<=0) {
									$('#projectmembertable tbody').empty();
									$('<tr data-id="'+arr.value+'"><td class="text-left" style="width: 20px;">'+arr.value+'</td><td class="text-left" ><input type="hidden" name="employeeid" value="'+arr.value+'" >'+arr.text+'</td>	<td class="text-left" style="width: 10px;">&nbsp;</td></tr>').appendTo($('#projectmembertable tbody'));
								}
							} else {
								obj.remove();
							}
							//pfw_addTableCounter($('#projectmembertable tbody tr'));
						}
					}
					function removeRow(obj) {
						var tbl = $(obj).closest('tbody');
						$(obj).closest('tr').remove();
						pfw_addTableCounter($('tr', tbl));
					}
					$(function(){
						$('#btnAddMember').click(function(){
							$.Dialog({
								overlay: false,
								shadow: true,
								flat: false,
								title: 'Select User',
								content: 'Loading...',
								width:600,
								height:200,
								draggable:true,
								onShow: function(_dialog){
									$.get('../lookup/user_list.php',{},function(data){
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
		<th>Chect time *</th>
		<td>
				<div class="input-control select">
					<select id="checkhour" name="checkhour">
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
					<select id="checkmin" name="checkmin">
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
		<th>Check Type *</th>
		<td>
			<div class="input-control radio" data-role="input-control">
				<label> 
					Check In
					<input type="radio" name="checktype" value="in"/>
					<span class="check" />
				</label>
			</div>
		</td>
		<td>
			<div class="input-control radio" data-role="input-control">
				<label> 
					Check Out
					<input type="radio" name="checktype" value="out"/>
					<span class="check" />
				</label>
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
</form>
<?php include '../footer.php'; ?>