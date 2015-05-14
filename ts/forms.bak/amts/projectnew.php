<?php
include '../../startup.php';
include '../header.php';

include '../../model/project.php';
include '../../model/user.php';
include '../../model/currency.php';

?>
<h1><a href="projectlist.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Add <small class="on-right">New Project</small>
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
<form id="frm1" class="view-list" method="post" action="../../actions/amts/projectnew.php?act=add">
<div class="form-block">

<table class="input-form">
	<tr>
		<th>Project Number *</th>
		<td colspan=2>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="project_number" value="">
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Project Name *</th>
		<td colspan=2>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="project_name" value="">
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Description</th>
		<td colspan=2>
			<div class="input-control textarea" data-role="input-control">
				<textarea type="text" name="project_desc" value=""></textarea>
			</div>
		</td>
	</tr>
	<tr>
		<th>Project Manager *</th>
		<td colspan=2>
				<div class="input-control select">
					<select id="project_manager" name="project_manager">
						<?php
							foreach(getAllUsers() as $row) {
						?>
						<option value="<?php echo $row['vuserid']; ?>"><?php echo $row['vdisplayname']; ?></option>
						<?php } ?>
					</select>
				</div>
		</td>
	</tr>
	<tr>
		<th>Start Date *</th>
		<td colspan=2>
			<div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>">
				<input type="text" name="startdate" value="">
				<button class="btn-date" tabindex="-1" type="button"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Estimated Man Hour *</th>
		<td colspan=2>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="estimatedmanhour" value="">
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Contract Amount</th>
		<td style="white-space:nowrap;">
			<div class="input-control text" data-role="input-control">
				<input type="text" name="contractamount" value="">
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
		<td>
			<div class="input-control select">
			<select id="currency" name="currency">
				<?php 
				//print_r(getCurrencies(array()));
				foreach(getCurrencies(array()) as $row) { ?>
					<option value="<?php echo $row['idcurrency']; ?>" <?php if ($row['idcurrency']=='IDR'){echo "selected";} ?>><?php echo $row['desc']; ?></option>
				<?php } ?>		
			</select>
			</div>
		</td>
	</tr>
	<tr>
		<th class="verticaltop">Team Member</td>
		<td colspan=2>
				<div id="projectmembertable" style="width: 100%; height: auto;">
					<table class="table hovered border myClass">
						<thead>
							<tr>
								<th class="text-left">No.</th>
								<th class="text-left">Member Name</th>
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
									$('<tr data-id="'+arr.value+'"><td class="text-left" style="width: 20px;"></td><td class="text-left" ><input type="hidden" name="member[]" value="'+arr.value+'" >'+arr.text+'</td>	<td class="text-left" style="width: 10px;"><a class="icon-cancel-2 icon-red" href="#" onclick="removeRow(this); return false;" ></a></td></tr>').appendTo($('#projectmembertable tbody'));
								}
							} else {
								obj.remove();
							}
							pfw_addTableCounter($('#projectmembertable tbody tr'));
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
		<th>Publishing status</th>
		<td colspan=2>
			<div class="input-control checkbox">
				<label>
					<input type="checkbox" name="published" value="1" />
					<span class="check"></span>
					Publish to All
				</label>
			</div>
		</td>
	</tr>
	<tr>
		<th class="verticaltop">Phase</th>
		<td colspan=2>
				<div id="phasetable" style="width: 100%; height: auto;">
					<table class="table hovered border myClass">
						<thead>
							<tr>
								<th class="text-left">No.</th>
								<th class="text-left">Name</th>
								<th class="text-left">Estimated</th>
								<th class="text-left" colspan="3"></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			<div style="
				float: right;
				clear: both;
			">
			<button class="button" type="button" id="addphase"><i class="icon-plus on-left"></i>Add Phase</button>			
			<script>
				$(function(){
					$("#addphase").on('click', function(){
						$.Dialog({
							shadow: true,
							flat: false,
							overlay: false,
							draggable: true,
							icon: '<span class="icon-bus"></span>',
							title: 'Add Phase',
							width: 500,
							height:300,
							padding: 0,
							content: '<div class="modal-content"><table cellpadding="0" cellspacing="0"><tr> <th class="text-left" valign="top">Phase Name</th><th class="text-left" valign="top">&nbsp;:&nbsp;</th><td><div class="input-control text" data-role="input-control"><input class="txt_phasename" type="text" value="" placeholder="input name of phase.."><button class="btn-clear" tabindex="-1" type="button"></button></div></td></tr> <tr><th class="text-left" valign="top">Description</th><th class="text-left" valign="top">&nbsp;:&nbsp;</th><td><div class="input-control textarea"><textarea class="txt_phasedesc"></textarea></div></td></tr> <tr><th class="text-left" valign="top">Estimated ManHour</th><th class="text-left" valign="top">&nbsp;:&nbsp;</th>			<td><div class="input-control text" data-role="input-control"><input type="text" value="" placeholder="" class="txt_phaseint"><button class="btn-clear" tabindex="-1" type="button"></button></div></td></tr> </table></div><div class="modal-buttons"><button class="primary" type="button" onclick="addPhase(this);">Add</button>&nbsp; <button type="button" onclick="$.Dialog.close()"><i class="icon-cancel on-left"></i>Close</button></div>'
						});
					});
				});
				function addPhase(obj){
					var obj = $(obj);
					var prt = obj.closest('.modal-buttons').prev();
					var phasename = $('.txt_phasename',prt).val();
					var phasedesc = $('.txt_phasedesc',prt).val();
					var phaseintv = $('.txt_phaseint',prt).val();
					var data = $('<tr><td class="text-left" style="width: 20px;"></td><td class="text-left" style="width: 100px;">'+phasename+'</td><td class="text-left" style="width: 40px;">'+phaseintv+'</td><td class="text-left" style="width: 30px;"><a class="icon-arrow-up" onclick="moveRowUp(this); return false;"></a></td><td class="text-left" style="width: 30px;"><a class="icon-arrow-down" onclick="moveRowDown(this); return false;"></a></td><td class="text-left" style="width: 30px;"><a class="icon-cancel-2 icon-red" href="#" onclick="removeRow(this); return false;" ></a><input type="hidden" name="phasename[]" value="'+phasename+'" /><input type="hidden" name="phasedesc[]" value="'+phasedesc+'" /><input type="hidden" name="phasemd[]" value="'+phaseintv+'" /></td></tr>');
					$('#phasetable table tbody').append(data);
					pfw_addTableCounter($('#phasetable table tbody tr'));
					$('.txt_phasename',prt).val('').focus();
					$('.txt_phasedesc,.txt_phaseint',prt).val('');
				}
				function moveRowUp(obj) {
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
	<tr><td colspan=4 style="min-height='20px';"></td></tr>
	<tr>
		<td colspan=2></td>
		<td>
			<input type="hidden" name="act" value="add" />
			<button type="submit" class="input-btn btn-main dark" >
				<div class="icon icon-floppy icon-l"></div>
				<div class="icon-label">Save Project</div>
			</button>
		</td>
	</tr>	
</table>
</div>	
</form>

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