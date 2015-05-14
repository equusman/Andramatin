<?php
include '../../startup.php';
include '../../model/task.php';

if (isset($_GET['dayid']) && !empty($_GET['dayid'])) {
	
	$params['dayid'] = (int)$_GET['dayid'];
	$usr = getHolidayById($params);
} else {
	header('Location: holiday.php');
}

include '../header.php';
?>
<h1><a href="holiday.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Edit <small class="on-right">Holiday</small>
</h1>
<div id="message-bar" ></div>
<div class="button-bar">
	<?php if ($_form->userHasAccess('amts/holiday.php')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="holiday.php" >
		<div class="icon icon-list icon-l"></div>
		<div class="icon-label">Holiday List</div>
	</button>
	<?php } ?>
</div>
<form id="frm1" class="view-list" method="post" action="../../actions/amts/holidayedit.php">
<div class="form-block">
<table class="input-form">
	<tr>
		<th>Date *</th>
		<td>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="holiday" value="<?php echo $usr['holiday']; ?>" disabled>
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Description *</th>
		<td>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="description" value="<?php echo $usr['comment']; ?>">
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>&nbsp;</th>
		<td colspan=2>
			<label><input id="active" type="checkbox" checked="" name="active" value="<?php echo $usr['active']; ?>"><span class="check"></span> Active</label>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<input type="hidden" name="act" value="edit" />
			<input type="hidden" name="holiday" value="<?php echo $usr['holiday']; ?>" />
			<input type="hidden" name="dayid" value="<?php echo $usr['dayid']; ?>" />
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