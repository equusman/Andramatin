<?php
include '../../startup.php';
include '../header.php';

include '../../model/properties.php';

$params = array();
if (isset($_GET['filter_setting'])) {
	$params['filter_setting'] = $_GET['filter_setting'];
} else {
	$params['filter_setting'] = '';
}
if (isset($_GET['page'])) {
	$params['page'] = (int)$_GET['page'];
} else {
	$params['page'] = 1;
}

$settings = getApplicationSetting($params);

$_pagination->page = $params['page'];
$_pagination->total = getApplicationSettingTotal($params);

?>
<h1><a href="#" onclick="history.go(-1); return false;"><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Application <small class="on-right">Setting</small>
</h1>
<div id="message-bar" ></div>
<div class="button-bar">
	<?php if ($_form->userHasFunction('add')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="appsettingnew.php">
		<div class="icon icon-plus-2 icon-l"></div>
		<div class="icon-label">Add New Setting</div>
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
	<form id="frmDelete" class="hidden" method="post" action="../../actions/admin/appsetting.php">
		<input type="hidden" name="ids" value="" />
		<input type="hidden" name="act" value="delete" />
		<input type="hidden" name="filter_setting_delete" value="<?php echo $params['filter_setting']; ?>" />
		<input type="hidden" name="page" value="<?php echo $params['page']; ?>" />
		<input type="submit" id="btnDelForm" />
	</form>
	<form id="frm1" class="view-list" method="post" action="../../actions/admin/appsetting.php">
	<table>
		<tr>
			<th>Setting Filter</th>
			<td class="searchbar01">
				<div class="input-control text" data-role="input-control">
					<input type="text" name="filter_setting" placeholder="type text" value="<?php echo $params['filter_setting']; ?>">
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
                    <div id="settinglisttable"></div>
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
							if ($settings!==false) {
								foreach ($settings as $row) {
								?>
								{checkAll:'<input type="checkbox" class="checkItemToggle" data-parent="checkAllToggle" value="<?php echo $row['setting_id']; ?>"  onclick="pfw_checkItem(this)"/>', 
								group:'<?php echo $row['group']; ?>',
								key:'<?php echo $row['key']; ?>',
								value:'<?php echo $row['value']; 
								if ($_form->userHasFunction('edit')) { ?>',actions:'&nbsp;<a href=\"appsettingedit.php?setting_id=<?php echo $row['setting_id']; ?>\">Edit</a>&nbsp;<?php } ?>'},
								<?php
								}
							}
						?>
                        ];

                        $(function(){
                            table = $("#settinglisttable").tablecontrol({
                                cls: 'table hovered border myClass',
                                checkRow: true,
                                colModel: [
									{field: 'checkAll', caption: '<input type="checkbox" class="checkAllToggle"  data-children="checkItemToggle" value="<?php echo $row['setting_id']; ?>" onclick="pfw_checkAll(this)" />', width: '10', sortable: false, cls: 'text-center', hcls: ""},
									{field: 'group', caption: 'Group', width: '150', sortable: false, cls: 'text-center', hcls: ""},
									{field: 'key', caption: 'Key', width: '200', sortable: false, cls: 'text-left', hcls: "text-left"},
									{field: 'value', caption: 'Value', width: '', sortable: false, cls: 'text-left', hcls: "text-left"}
									<?php if ($_form->userHasFunction('edit')) { ?>,
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
		if (confirm("You are going to delete Setting key "+username+".\nAre you sure?")) { 
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