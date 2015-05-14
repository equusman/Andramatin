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

$tasklist = getMyOpenTask($params);

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
<h1><a href="tasklist.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    My Activity <small class="on-right">List</small>
</h1>
<div id="message-bar" ></div>
<div class="filter-block">
	<form id="frm1" class="view-list" method="post" action="../../actions/amts/tasklist.php">
	<table>
		<tr>
			<th>Activity </th>
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
									
								?>{	project:"<?php echo $row['projectnumber']; ?>-<?php echo $row['projectname']; ?>",
									activity:"<?php echo $row['phasename']; ?>-<?php echo $row['activityname']; ?>",
									startdate:"<?php echo $row['startdate']; ?>",
									timed:"<?php echo $row['timefrom']; ?> - <?php echo $row['timeto']; ?>"},
								
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
									{field: 'project', caption: 'Project', width: '100', sortable: true, cls: 'text-center', hcls: ""},
									{field: 'activity', caption: 'Activity', width: '100', sortable: true, cls: 'text-center', hcls: ""},
									{field: 'startdate', caption: 'Date', width: '100', sortable: false, cls: 'text-center', hcls: ""},
									{field: 'timed', caption: 'Time', width: '100', sortable: false, cls: 'text-center', hcls: ""},
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