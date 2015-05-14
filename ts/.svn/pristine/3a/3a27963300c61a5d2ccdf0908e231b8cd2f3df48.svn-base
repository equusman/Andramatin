<?php
include '../../startup.php';
include '../../model/user.php';
include '../../model/role.php';

if (isset($_GET['userid']) && !empty($_GET['userid'])) {
	$usr = getUserById((int)$_GET['userid']);
} else {
	header('Location: user_list.php');
}

$roles = getRoles(array());
$selected_roles = getUserRoles($_GET['userid']);

include '../header.php';
?>
<h1><a href="user_list.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Assign <small class="on-right">User Roles</small>
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
<form id="frm1" class="view-list" method="post" action="../../actions/admin/user_assign.php">
<div class="form-block">
<table class="input-form">
	<tr>
		<th>Select Roles</th>
		<td>
			<?php foreach($roles as $role) { 
				if ($role['vroleid']=='0') continue;
			?>
			<div class="input-control checkbox" data-role="input-control">
				<label>
					<input name="role[]" type="checkbox" value="<?php echo $role['vroleid']; ?>" <?php if (in_array($role['vroleid'],$selected_roles)) echo ' checked="checked"'; ?> />
					<span class="check"></span>
					<?php echo $role['vroledesc']; ?>
				</label>
			</div>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<input type="hidden" name="act" value="assign" />
			<input type="hidden" name="userid" value="<?php if (isset($_GET['userid'])) echo $_GET['userid']; ?>" />
			<button type="submit" id="btnAssignTemp" class="input-btn btn-main dark" >
				<div class="icon icon-floppy icon-l"></div>
				<div class="icon-label">Assign Selected Roles</div>
			</button>
		</td>
	</tr>	
</table>
</div>	
</form>
<script type="text/javascript">
	$(function(){
		/*
		$('#btnAssignTemp').click(function(){
				var vals = $.map($(':checkbox[name="role"]:checked'),function(n,i) { return n.value; }).join(',');
				$('input[name="roles"]').val(vals);
				$('#btnAssign').click();
		});
		*/
	});
</script>
<?php include '../footer.php'; ?>