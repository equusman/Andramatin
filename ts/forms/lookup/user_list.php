<?php
include '../../startup.php';
include '../../model/user.php';

$params = array();
if (isset($_GET['filter_username'])) {
	$params['filter_username'] = $_GET['filter_username'];
} else {
	$params['filter_username'] = '';
}
if (isset($_GET['page'])) {
	$params['page'] = (int)$_GET['page'];
} else {
	$params['page'] = 1;
}
$users = getUsers($params);

$_pagination->page = $params['page'];
$_pagination->total = getUsersTotal($params);
?>
<div class="modal-outer container_dlg_user_list">
<div id="message-bar" ></div>
<div class="filter-block">
	<form id="frm1" class="view-list" method="post" action="../../actions/lookup/user_list.php">
	<table>
		<tr>
			<th>Username</th>
			<td class="searchbar01">
				<div class="input-control text" data-role="input-control">
					<input type="text" name="filter_username" placeholder="type text" value="<?php echo $params['filter_username']; ?>">
					<button class="btn-clear" tabindex="-1"></button>
				</div>
				<button type="submit" class="input-btn btn-main" >
					<div class="icon icon-search icon-l"></div>
					<div class="icon-label">Search</div>
				</button>
			</td>
		</tr>	
	</table>
		<input type="hidden" name="targetClass" value="container_dlg_user_list" />
	</form>
</div>
<div class="modal-content">
	<div class="pagination">
		<?php echo $_pagination->render(); ?>
	</div>
	<!--  STARTTABLE -->				
	<div id="userlisttable">
		<table class="table hovered border myClass">
			<thead>
				<tr>
					<th></th>
					<th>User Id</th>
					<th class="text-left">Display Name</th>
					<th class="text-left">Username</th>
					<th class="text-left">Status</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if ($users!==false) {
				foreach ($users as $row) {
			?>
				<tr>
					<td class="text-center" style="width: 10px;"><input type="button" data-val="<?php echo $row['vuserid']; ?>" value="Select" onclick="dlg_checkItem(this)"></td>
					<td class="text-center" style="width: 100px;"><?php echo $row['vuserid']; ?></td>
					<td class="text-left"><?php echo $row['vdisplayname']; ?></td>
					<td class="text-left" style="width: 120px;"><?php echo $row['vusername']; ?></td>
					<td class="text-left" style="width: 80px;"><?php echo $row['status']; ?></td>
				</tr>
			<?php
				}
			}
			?>	
			</tbody>
		</table>
	</div>
	<?php /*
	<script>
		<?php $uid = uniqid(); ?>
		var table_<?php echo $uid; ?>, table_data_<?php echo $uid; ?>;

		table_data_<?php echo $uid; ?> = [<?php
			if ($users!==false) {
				foreach ($users as $row) {
				?>
				{checkAll:'<input type="button" data-val="<?php echo $row['vuserid']; ?>" value="Select" onclick="dlg_checkItem(this)"/>', employeeid:"<?php echo $row['vuserid']; ?>",name:"<?php echo $row['vdisplayname']; ?>",username:"<?php echo $row['vusername']; ?>",status:"<?php echo $row['status']; ?>",actions:	"<?php if ($_form->userHasFunction('reset')) { ?>&nbsp;<a href=\"user_edit.php?userid=<?php echo $row['vuserid']; ?>\">Edit</a>&nbsp;<?php } ?> 	<?php if ($_form->userHasFunction('assign')) { ?>&nbsp;<a href=\"user_assign.php?userid=<?php echo $row['vuserid']; ?>\">Assign Role</a>&nbsp;<?php } ?> 	<?php if ($_form->userHasFunction('delete')) { ?>&nbsp;<a href=\"#\" onclick=\"deleteCurrentUser(<?php echo $row['vuserid']; ?>, '<?php echo $row['vusername']; ?>')\">Delete</a>&nbsp;<?php } ?>"},
				<?php
				}
			}
		?>
		];

		$(function(){
			table_<?php echo $uid; ?> = $("#userlisttable").tablecontrol({
				cls: 'table hovered border myClass',
				checkRow: true,
				colModel: [
					{field: 'checkAll', caption: '', width: '10', sortable: false, cls: 'text-center', hcls: ""},
					{field: 'employeeid', caption: 'User Id', width: '100', sortable: false, cls: 'text-center', hcls: ""},
					{field: 'name', caption: 'Display Name', width: '', sortable: false, cls: 'text-left', hcls: "text-left"},
					{field: 'username', caption: 'Username', width: '120', sortable: false, cls: 'text-left', hcls: "text-left"},
					{field: 'status', caption: 'Status', width: '80', sortable: false, cls: 'text-left', hcls: "text-left"}
					],

				data: table_data_<?php echo $uid; ?>
			});
		});

	</script>
	*/ ?>
	<!--  ENDTABLE -->				
	<div class="pagination">
		<?php echo $_pagination->render(); ?>
	</div>
</div>
<div class="modal-buttons">
	<button type="button" onclick="$.Dialog.close()"><i class="icon-cancel on-left"></i>Close</button>
</div>
<script type="text/javascript">
	function dlg_checkItem(obj) {
		if (typeof dlg_user_list_callback == 'function') {
			dlg_user_list_callback({value:$(obj).data('val'), text:$(obj).parent().next().next().text(), status: 1});
		}
	}
	pfw_prep_submit_button($('.container_dlg_user_list'));
</script>
</div>
<?php //include '../footer.php'; ?>