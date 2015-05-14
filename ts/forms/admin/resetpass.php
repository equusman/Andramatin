<?php
include '../../startup.php';
include '../header.php';

include '../../model/user.php';

if (isset($_GET['userid']) && !empty($_GET['userid'])) {
	$usr = getUserById((int)$_GET['userid']);
} else {
	header('Location: user_list.php');
}

// echo "<pre>";
// print_r($usr);
// echo "</pre>";



?>
<h1><a href="user_list.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Reset <small class="on-right">Password</small>
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
<form id="frm1" class="view-list" method="post" action="../../actions/admin/resetpass.php">
<div class="form-block">
<table class="input-form">

	<tr>
		<th>Username *</th>
		<td>
			<div class="input-control text" data-role="input-control">
				<input type="text" id="_username" name="_username" value="<?php echo $usr['vusername']; ?>" disabled>
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Display Name *</th>
		<td>
			<div class="input-control text" data-role="input-control">
				<input type="text" id="_displayname" name="_displayname" value="<?php echo $usr['vdisplayname']; ?>" disabled>
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
<!--	<tr>
		<th>Old Password *</th>
		<td>
			<div class="input-control password" data-role="input-control">
				<input type="oldpassword" name="oldpassword" value="">
				<button class="btn-reveal" tabindex="-1"></button>
			</div>
		</td>
	</tr> -->
	<tr>
		<th>New Password *</th>
		<td>
			<div class="input-control password" data-role="input-control">
				<input type="password" name="newpassword" value="">
				<button class="btn-reveal" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Confirm Password *</th>
		<td>
			<div class="input-control password" data-role="input-control">
				<input type="password" name="confirmpassword" value="">
				<button class="btn-reveal" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<input type="hidden" name="userid" value="<?php echo $usr['vuserID']; ?>" />
			<input type="hidden" name="act" value="edit" />
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