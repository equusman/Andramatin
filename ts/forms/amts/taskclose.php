<?php
include '../../startup.php';
include '../header.php';

include '../../model/properties.php';
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

$tasklist = getMyTaskList($params);

$_p = array();
$_par = array();


$_p['group'] = "amts";
$_p['key'] = "weekend";
$_par['dayweek'] = getSettingGroupKey($_p);

$_p['group'] = "amts";
$_p['key'] = "weekend_task_skip";
$_par['weekendskip'] = getSettingGroupKey($_p);

$_p['group'] = "amts";
$_p['key'] = "max_task_entry_days";
$_par['dayback'] = getSettingGroupKey($_p);

$lastdayedit = getLastTaskEntryDate($_par);

$_pagination->page = $params['page'];
$_pagination->total = getMyTaskListTotal($params);

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
									if ($row['tag']=='0') {
										$loopdate = DateTime::createFromFormat('Y-m-d',$row['startdate'] );
									?>{	date:"<strong><?php echo $row['startdate']; ?></strong>"},
									
									<?php
									}else{
									?>{	timestart:"<?php echo $row['timefrom']; ?>",
										timeend:"<?php echo $row['timeto']; ?>",
										project:"Number:<?php echo $row['projectnumber']; ?><br>Name:<?php echo $row['projectname']; ?><br>Phase:<?php echo $row['phasename']; ?>",
										task:"<?php echo $row['activityname']; ?>",
										result:"<?php echo $row['result']; ?>",
										detail:"<?php 
											if ( $loopdate>= $lastdayedit){
											
											?><a href=taskedit.php?taskid=<?php echo $row['taskid']; ?>>Edit</a>"},
									<?php
											}else{
										
										
										?>"},
									<?php }
									}
								}
							}
							
						?>
                        ];

                        $(function(){
                            table = $("#tasklisttable").tablecontrol({
                                cls: 'table hovered border myClass',
                                checkRow: true,
                                colModel: [
									{field: 'date', caption: 'Date', width: '200', sortable: true, cls: 'text-center', hcls: ""},
									{field: 'timestart', caption: 'From', width: '50', sortable: true, cls: 'text-center', hcls: ""},
									{field: 'timeend', caption: 'To', width: '50', sortable: true, cls: 'text-center', hcls: ""},
									{field: 'project', caption: 'Project', width: '400', sortable: true, cls: 'text-center', hcls: ""},
									{field: 'task', caption: 'Task', width: '300', sortable: true, cls: 'text-center', hcls: ""},
									{field: 'result', caption: 'Description', width: '300', sortable: false, cls: 'text-center', hcls: ""},
									{field: 'detail', caption: '', width: '200', sortable: false, cls: 'text-center', hcls: ""},
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