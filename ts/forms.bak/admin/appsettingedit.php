<?php
include '../../startup.php';
include '../../model/properties.php';

if (isset($_GET['setting_id']) && !empty($_GET['setting_id'])) {
	
	$params['setting_id'] = (int)$_GET['setting_id'];
	$usr = getSettingById($params);
} else {
	header('Location: appsetting.php');
}

include '../header.php';
?>
<h1><a href="appsettinglist.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Edit <small class="on-right">User</small>
</h1>
<div id="message-bar" ></div>
<div class="button-bar">
	<?php if ($_form->userHasAccess('admin/appsetting.php')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="appsetting.php" >
		<div class="icon icon-list icon-l"></div>
		<div class="icon-label">Application Setting List</div>
	</button>
	<?php } ?>
</div>
<form id="frm1" class="view-list" method="post" action="../../actions/admin/appsettingedit.php">
<div class="form-block">
<table class="input-form">
	<tr>
		<th>Group *</th>
		<td>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="group" value="<?php echo $usr['group']; ?>" disabled>
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Setting Key *</th>
		<td>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="key" value="<?php echo $usr['key']; ?>" disabled>
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Value *</th>
		<td>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="value" value="<?php echo $usr['value']; ?>">
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<input type="hidden" name="act" value="edit" />
			<input type="hidden" name="setting_id" value="<?php if (isset($_GET['setting_id'])) echo $_GET['setting_id']; ?>" />
			<input type="hidden" name="group" value="<?php echo $usr['group']; ?>" />			
			<input type="hidden" name="key" value="<?php echo $usr['key']; ?>" />
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