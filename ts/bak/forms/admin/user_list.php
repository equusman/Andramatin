<?php
include '../../startup.php';
include '../header.php';

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
<h1><a href="#" onclick="history.go(-1); return false;"><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    User <small class="on-right">List</small>
</h1>
<div id="message-bar" ></div>
<div class="button-bar">
	<?php if ($_form->userHasFunction('add')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="user_new.php">
		<div class="icon icon-plus-2 icon-l"></div>
		<div class="icon-label">Add New User</div>
	</button>
	<?php } ?>
	<?php if ($_form->userHasFunction('delete')) { ?>
	<button type="button" id="btnDelete" class="input-btn btn-main alert" data-check-controls="checkItemToggle,checkAllToggle" >
		<div class="icon icon-minus-2 icon-l"></div>
		<div class="icon-label">Delete Selected User</div>
	</button>
	<?php } ?>
</div>
<div class="filter-block">
	<form id="frmDelete" class="hidden" method="post" action="../../actions/admin/user_list.php">
		<input type="hidden" name="ids" value="" />
		<input type="hidden" name="act" value="delete" />
		<input type="hidden" name="filter_username_delete" value="<?php echo $params['filter_username']; ?>" />
		<input type="hidden" name="page" value="<?php echo $params['page']; ?>" />
		<input type="submit" id="btnDelForm" />
	</form>
	<form id="frm1" class="view-list" method="post" action="../../actions/admin/user_list.php">
	<table>
		<tr>
			<th>Username</th>
			<td class="searchbar01">
				<div class="input-control text" data-role="input-control">
					<input type="text" name="filter_username" placeholder="type text" value="<?php echo $params['filter_username']; ?>">
					<button class="btn-clear" tabindex="-1"></button>
				</div>
				<input type="hidden" name="act" value="search" />
				<button type="submit" class="input-btn btn-main" >
					<div class="icon icon-search icon-l"></div>
					<div class="icon-label">Search</div>
				</button>
			</td>
		</tr>	
	</table>	
	</form>
</div>
<div class="pagination">
	<?php echo $_pagination->render(); ?>
</div>
<!--  STARTTABLE -->				
                    <div id="userlisttable"></div>
                    <script>
                        function checkRow(el){
                            $(el).parents("tr").toggleClass("selected");
                        }

                        function checkAll(el){
                            var state = el.checked;
                            $(el).parents("table").find("tbody [type=checkbox]").each(function(index){
                                $(this).prop("checked", state);
                                if (state) {
                                    $(this).parents("tr").addClass("selected");
                                } else {
                                    $(this).parents("tr").removeClass("selected");
                                }
                            });

                        }

                        var table, table_data;

                        table_data = [<?php
							if ($users!==false) {
								foreach ($users as $row) {
								?>
								{checkAll:'<input type="checkbox" class="checkItemToggle" data-parent="checkAllToggle" value="<?php echo $row['vuserid']; ?>"  onclick="pfw_checkItem(this)"/>', employeeid:"<?php echo $row['vuserid']; ?>",name:"<?php echo $row['vdisplayname']; ?>",username:"<?php echo $row['vusername']; ?>",status:"<?php echo $row['status']; ?>",actions:	"<?php if ($_form->userHasFunction('reset')) { ?>&nbsp;<a href=\"user_edit.php?userid=<?php echo $row['vuserid']; ?>\">Edit</a>&nbsp;<?php } ?> 	<?php if ($_form->userHasFunction('edit')) { ?>&nbsp;<a href=\"resetpass.php?userid=<?php echo $row['vuserid']; ?>\">Reset Password</a>&nbsp;<?php } ?> 	<?php if ($_form->userHasFunction('assign')) { ?>&nbsp;<a href=\"user_assign.php?userid=<?php echo $row['vuserid']; ?>\">Assign Role</a>&nbsp;<?php } ?> 	<?php if ($_form->userHasFunction('delete')) { ?>&nbsp;<a href=\"#\" onclick=\"deleteCurrentUser(<?php echo $row['vuserid']; ?>, '<?php echo $row['vusername']; ?>')\">Delete</a>&nbsp;<?php } ?>"},
								<?php
								}
							}
						?>
                        ];

                        $(function(){
                            table = $("#userlisttable").tablecontrol({
                                cls: 'table hovered border myClass',
                                checkRow: true,
                                colModel: [
									{field: 'checkAll', caption: '<input type="checkbox" class="checkAllToggle"  data-children="checkItemToggle" value="<?php echo $row['vuserid']; ?>" onclick="pfw_checkAll(this)" />', width: '10', sortable: false, cls: 'text-center', hcls: ""},
									{field: 'employeeid', caption: 'User Id', width: '100', sortable: false, cls: 'text-center', hcls: ""},
									{field: 'name', caption: 'Display Name', width: '', sortable: false, cls: 'text-left', hcls: "text-left"},
									{field: 'username', caption: 'Username', width: '120', sortable: false, cls: 'text-left', hcls: "text-left"},
									{field: 'status', caption: 'Status', width: '80', sortable: false, cls: 'text-left', hcls: "text-left"}
									<?php if ($_form->userHasFunction('edit') || $_form->userHasFunction('assign')) { ?>,
									{field: 'actions', caption: 'Actions', width: '200', sortable: false, cls: 'text-center', hcls: ""}
									<?php } ?>
									],

                                data: table_data
                            });
                        });

                    </script>
<!--  ENDTABLE -->				
<div class="pagination">
	<?php echo $_pagination->render(); ?>
</div>

<script type="text/javascript">
	function deleteCurrentUser(userid, username){
		if (confirm("You are going to delete user "+username+".\nAre you sure?")) { 
			$('input[name="ids"]').val(userid);
			$('#btnDelForm').click();
		}
	}
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