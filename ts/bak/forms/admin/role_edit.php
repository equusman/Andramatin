<?php
include '../../startup.php';
include '../../model/role.php';

if (isset($_GET['roleid']) && !empty($_GET['roleid'])) {
	$item = getRoleById((int)$_GET['roleid']);
} else {
	header('Location: role_list.php');
}

include '../header.php';
?>
<h1><a href="user_list.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Edit <small class="on-right">Role</small>
</h1>
<div id="message-bar" ></div>
<div class="button-bar">
	<?php if ($_form->userHasAccess('admin/role_list.php')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="role_list.php" >
		<div class="icon icon-list icon-l"></div>
		<div class="icon-label">Role List</div>
	</button>
	<?php } ?>
	<?php if ($_form->userHasAccess('admin/role_assign.php')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="role_assign.php?roleid=<?php echo $item['vroleid']; ?>" >
		<div class="icon icon-list icon-l"></div>
		<div class="icon-label">Assign Users</div>
	</button>
	<?php } ?>
</div>
<form id="frm1" class="view-list" method="post" action="../../actions/admin/role_edit.php">
<div class="form-block">
<table class="input-form">
	<tr>
		<th>Role Description *</th>
		<td>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="roledesc" value="<?php echo $item['vroledesc']; ?>">
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Begin Effective Date *</th>
		<td>
			<div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>">
				<input type="text" name="begineff" value="<?php echo $item['dbegineff']; ?>">
				<button class="btn-date" tabindex="-1" type="button"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Last Effective Date *</th>
		<td>
			<div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>">
				<input type="text" name="lasteff" value="<?php echo $item['dlasteff']; ?>">
				<button class="btn-date" tabindex="-1" type="button"></button>
			</div>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<input type="hidden" name="act" value="edit" />
			<input type="hidden" name="roleid" value="<?php if (isset($_GET['roleid'])) echo $_GET['roleid']; ?>" />
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