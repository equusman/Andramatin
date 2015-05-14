<?php
include '../../startup.php';
include '../header.php';

include '../../model/project.php';
include '../../model/task.php';
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

$activitylist = getMyOpenActivity($params);

$_pagination->page = $params['page'];
$_pagination->total = getMyOpenActivityTotal($params);

if  (isset($_GET['debug'])) {
	$params['debug'] = (int)$_GET['debug'];
	if (($params['debug'] !== null)&&($params['debug'] == "1"))
	{
        echo '<pre>';
		print_r($activitylist);
        print_r($params['filter_activity']);
        echo  '</pre>';
	}
	
}

?>
<h1><a href="projectlist.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Pick <small class="on-right">a Task</small>
</h1>
<div id="message-bar" ></div>
<div class="filter-block">
	<form id="frm1" class="view-list" method="post" action="../../actions/amts/taskpick.php">
	<table>
		<tr>
			<th>Activity </th>
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
	</form>
</div>

<div class="pagination">
	<?php echo $_pagination->render(); ?>
</div>
<!--  STARTTABLE -->				
                    <div id="activitylisttable"></div>
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
							if ($activitylist!==false) {
								foreach ($activitylist as $row) {
								?>{	checkAll:'<input type="checkbox" class="checkItemToggle" data-parent="checkAllToggle" value="<?php echo $row['phaseid']; ?>"  onclick="pfw_checkItem(this)"/>', 
									projectnumber:"<?php echo $row['projectnumber']; ?>",
									projectname:"<?php echo $row['projectname']; ?>",
									phase:"<?php echo $row['phasename']; ?>",
									activity:"<?php echo $row['name']; ?>",
									startdate:"<?php echo $row['schedulestart']; ?>",
									enddate:"<?php echo $row['scheduleend']; ?>",
									hourleft:"<?php echo $row['actualhour']." / " .$row['estimated']; ?>",
									pick:"<a href=taskadd.php?activity=<?php echo $row['activityid']; ?>>Pick</a>"},
								
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
									{field: 'checkAll', caption: '<input type="checkbox" class="checkAllToggle"  data-children="checkItemToggle" value="<?php echo $row['activityid']; ?>" onclick="pfw_checkAll(this)" />', width: '10', sortable: false, cls: 'text-center', hcls: ""},
									{field: 'projectnumber', caption: 'Project Number', width: '100', sortable: true, cls: 'text-center', hcls: ""},
									{field: 'projectname', caption: 'Project Name', width: '100', sortable: true, cls: 'text-center', hcls: ""},
									{field: 'phase', caption: 'Phase', width: '100', sortable: true, cls: 'text-center', hcls: ""},
									{field: 'activity', caption: 'Activity', width: '100', sortable: true, cls: 'text-center', hcls: ""},
									{field: 'startdate', caption: 'Start Date', width: '100', sortable: false, cls: 'text-center', hcls: ""},
									{field: 'enddate', caption: 'End Date', width: '100', sortable: false, cls: 'text-center', hcls: ""},
									{field: 'hourleft', caption: 'Hour used', width: '100', sortable: false, cls: 'text-center', hcls: ""},
									{field: 'pick', caption: 'Pick', width: '100', sortable: false, cls: 'text-center', hcls: ""},
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