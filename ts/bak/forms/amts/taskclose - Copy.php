<?php
include '../../startup.php';
include '../header.php';

include '../../model/project.php';
include '../../model/task.php';
include '../../model/user.php';

$params = array();
if (isset($_GET['filter_task'])) {
	$params['filter_task'] = $_GET['filter_task'];
} else {
	$params['filter_task'] = '';
}

if (isset($_GET['page'])) {
	$params['page'] = (int)$_GET['page'];
} else {
	$params['page'] = 1;
}

$tasklist = getMyTaskListDateView($params);

$_pagination->page = $params['page'];
$_pagination->total = getMyOpenTaskTotal($params);

if  (isset($_GET['debug'])) {
	$params['debug'] = (int)$_GET['debug'];
	if (($params['debug'] !== null)&&($params['debug'] == "1"))
	{
        echo '<pre>';
		print_r($tasklist);
		echo '----------------------------';
        print_r($params['filter_task']);
        echo  '</pre>';
	}
	
}

?>
<h1><a href="taskpick.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    My Task <small class="on-right">List</small>
</h1>
<div id="message-bar" ></div>
<div class="filter-block">
	<form id="frm1" class="view-list" method="post" action="../../actions/amts/taskclose.php">
	<table>
		<tr>
			<th>Task </th>
			<td class="searchbar01">
				<div class="input-control text" data-role="input-control">
					<input type="text" name="filter_task" placeholder="type text" value="<?php echo $params['filter_task']; ?>">
					<button class="btn-clear" tabindex="-1"></button>
				</div>
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
                    <div id="tasklisttable"></div>
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
							if ($tasklist!==false) {
								foreach ($tasklist as $row) {
									
								?>{	projectnumber:"<?php echo $row['projectnumber']; ?>",
									projectname:"<?php echo $row['projectname']; ?>",
									phase:"<?php echo $row['phasename']; ?>",
									activity:"<?php echo $row['activityname']; ?>",
									result:"<?php echo $row['result']; ?>",
									startdate:"<?php echo $row['startdate']; ?>",
									timefrom:"<?php echo $row['timefrom']; ?>",
									timeto:"<?php echo $row['timeto']; ?>",
									close:"<a href=taskstop.php?taskid=<?php echo $row['taskid']; ?>>Close</a>"},
								
								<?php
									
								}
							}
							
						?>
                        ];

                        $(function(){
                            table = $("#tasklisttable").tablecontrol({
                                cls: 'table hovered border myClass',
                                checkRow: true,
                                colModel: [
									{field: 'projectnumber', caption: 'Project Number', width: '100', sortable: true, cls: 'text-center', hcls: ""},
									{field: 'projectname', caption: 'Project Name', width: '100', sortable: true, cls: 'text-center', hcls: ""},
									{field: 'phase', caption: 'Phase', width: '100', sortable: true, cls: 'text-center', hcls: ""},
									{field: 'activity', caption: 'Activity', width: '100', sortable: true, cls: 'text-center', hcls: ""},
									{field: 'result', caption: 'Result', width: '100', sortable: true, cls: 'text-center', hcls: ""},
									{field: 'startdate', caption: 'Task Date', width: '100', sortable: false, cls: 'text-center', hcls: ""},
									{field: 'timefrom', caption: 'Time Start', width: '100', sortable: false, cls: 'text-center', hcls: ""},
									{field: 'timeto', caption: 'Time End', width: '100', sortable: false, cls: 'text-center', hcls: ""},
									{field: 'close', caption: 'Close', width: '100', sortable: false, cls: 'text-center', hcls: ""},
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