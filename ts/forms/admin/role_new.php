<?php
include '../../startup.php';
include '../header.php';

include '../../model/role.php';

?>
<h1><a href="user_list.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Add <small class="on-right">New Role</small>
</h1>
<div id="message-bar" ></div>
<div class="button-bar">
	<?php if ($_form->userHasAccess('admin/user_list.php')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="role_list.php" >
		<div class="icon icon-list icon-l"></div>
		<div class="icon-label">Role List</div>
	</button>
	<?php } ?>
</div>
<form id="frm1" class="view-list" method="post" action="../../actions/admin/role_new.php">
<div class="form-block">
<table class="input-form">
	<tr>
		<th>Role Description *</th>
		<td>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="roledesc" value="">
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Begin Effective Date *</th>
		<td>
			<div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>">
				<input type="text" name="begineff" value="">
				<button class="btn-date" tabindex="-1" type="button"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Last Effective Date *</th>
		<td>
			<div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>">
				<input type="text" name="lasteff" value="">
				<button class="btn-date" tabindex="-1" type="button"></button>
			</div>
		</td>
	</tr>
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