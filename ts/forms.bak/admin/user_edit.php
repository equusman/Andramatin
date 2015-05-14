<?php
include '../../startup.php';
include '../../model/user.php';

if (isset($_GET['userid']) && !empty($_GET['userid'])) {
	$usr = getUserById((int)$_GET['userid']);
} else {
	header('Location: user_list.php');
}

include '../header.php';
?>
<h1><a href="user_list.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Edit <small class="on-right">User</small>
</h1>
<div id="message-bar" ></div>
<div class="button-bar">
	<?php if ($_form->userHasAccess('admin/user_list.php')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="user_list.php" >
		<div class="icon icon-list icon-l"></div>
		<div class="icon-label">User List</div>
	</button>
	<?php } ?>
</div>
<form id="frm1" class="view-list" method="post" action="../../actions/admin/user_edit.php">
<div class="form-block">
<table class="input-form">
	<tr>
		<th>Username *</th>
		<td>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="username" value="<?php echo $usr['vusername']; ?>">
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Display Name *</th>
		<td>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="displayname" value="<?php echo $usr['vdisplayname']; ?>">
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Password *</th>
		<td>
			<div class="input-control password" data-role="input-control">
				<input type="password" name="password" value="">
				<button class="btn-reveal" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Begin Effective Date *</th>
		<td>
			<div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>">
				<input type="text" name="begineff" value="<?php echo $usr['dbegineff']; ?>">
				<button class="btn-date" tabindex="-1" type="button"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Last Effective Date *</th>
		<td>
			<div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>">
				<input type="text" name="lasteff" value="<?php echo $usr['dlasteff']; ?>">
				<button class="btn-date" tabindex="-1" type="button"></button>
			</div>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<input type="hidden" name="act" value="edit" />
			<input type="hidden" name="userid" value="<?php if (isset($_GET['userid'])) echo $_GET['userid']; ?>" />
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