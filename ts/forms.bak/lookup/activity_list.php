<?php
include '../../startup.php';
include '../../model/task.php';
include '../../model/project.php';
include '../../model/user.php';

$params = array();
if (isset($_GET['filter_activity'])) {
	$params['filter_activity'] = $_GET['filter_activity'];
} else {
	$params['filter_activity'] = '';
}
if (isset($_GET['page'])) {
	$params['page'] = (int)$_GET['page'];
} else {
	$params['page'] = 1;
}
$activity = getMyOpenActivity($params);

// echo "<pre>";
// print_r ($activity);
// echo "</pre>";

$_pagination->page = $params['page'];
$_pagination->total = getMyOpenActivityTotal($params);
?>
<div class="modal-outer container_dlg_activity_list">
<div id="message-bar" ></div>
<div class="filter-block">
	<form id="frm1" class="view-list" method="post" action="../../actions/lookup/activity_list.php">
	<table>
		<tr>
			<th>Filter</th>
			<td class="searchbar01">
				<div class="input-control text" data-role="input-control">
					<input type="text" name="filter_activity" placeholder="type text" value="<?php echo $params['filter_activity']; ?>">
					<button class="btn-clear" tabindex="-1"></button>
				</div>
				<button type="submit" class="input-btn btn-main" >
					<div class="icon icon-search icon-l"></div>
					<div class="icon-label">Search</div>
				</button>
			</td>
		</tr>	
	</table>
		<input type="hidden" name="targetClass" value="container_dlg_activity_list" />
	</form>
</div>
<div class="modal-content">
	<div class="pagination">
		<?php echo $_pagination->render(); ?>
	</div>
	<!--  STARTTABLE -->				
	<div id="activitylisttable"></div>
	<script>
		var table, table_data;

		table_data = [<?php
			if ($activity!==false) {
				foreach ($activity as $row) {
				?>
				{checkAll:'<input type="button" data-val="<?php echo $row['activityid']; ?>" value="Select" onclick="dlg_checkItem(this)"/>', projectname:"<?php echo $row['projectnumber']; ?>",projectdesc:"<?php echo $row['projectname']; ?>",phase:"<?php echo $row['phasename']; ?>",activity:"<?php echo $row['name']; ?>"},
				<?php
				}
			}
		?>
		];

		$(function(){
			table = $("#activitylisttable").tablecontrol({
				cls: 'table hovered border myClass',
				checkRow: true,
				colModel: [
					{field: 'checkAll', caption: '', width: '10', sortable: false, cls: 'text-center', hcls: ""},
					{field: 'projectname', caption: 'Project Name', width: '100', sortable: false, cls: 'text-center', hcls: ""},
					{field: 'projectdesc', caption: 'Project Description', width: '', sortable: false, cls: 'text-left', hcls: "text-left"},
					{field: 'phase', caption: 'Phase', width: '120', sortable: false, cls: 'text-left', hcls: "text-left"},
					{field: 'activity', caption: 'Tasks Offered', width: '80', sortable: false, cls: 'text-left', hcls: "text-left"}
					],

				data: table_data
			});
		});

	</script>
	<!--  ENDTABLE -->				
	<div class="pagination">
		<?php echo $_pagination->render(); ?>
	</div>
</div>
<div class="modal-buttons">
	<button type="button" onclick="$.Dialog.close()"><i class="icon-cancel on-left"></i>Close</button>
</div>
</div>
<script type="text/javascript">
	function dlg_checkItem(obj) {
		if (typeof dlg_activity_list_callback == 'function') {
			dlg_activity_list_callback({value:$(obj).data('val'), 
									text:$(obj).parent().next().text(),
									activity:$(obj).parent().next().next().next().next().text(), 
									status: 1});
		}
	}
	pfw_prep_submit_button($('.container_dlg_activity_list'));
</script>
<?php include '../footer.php'; ?>