<?php
include '../../startup.php';
include '../header.php';

include '../../model/properties.php';
include '../../model/task.php';



$now = new DateTime();
$startdate = new DateTime();
$enddate = new DateTime();
$params = array();

	// echo "<pre>";
	// print_r($_GET);


if (isset($_GET['filter_holiday'])) {
	$params['filter_holiday'] = $_GET['filter_holiday'];
} else {
	$params['filter_holiday'] = '';
}

if (isset($_GET['page'])) {
	$params['page'] = $_GET['page'];
} else {
	$params['page'] = 1;
}

if (isset($_GET['startdate'])) {
	$params['startdate'] = $_GET['startdate'];
} else {
	$params['startdate'] = $startdate->setDate($now->format('Y'),1,1)->format('Y-m-d');
}

if (isset($_GET['enddate'])) {
	$params['enddate'] = $_GET['enddate'];
} else {
	$params['enddate'] =  $enddate->setDate($now->format('Y'),12,31)->format('Y-m-d');
}

	// print_r($params);


$settings = getHoliday($params);

	// print_r($settings);
	// print_r($params);
	// echo "</pre>";




$_pagination->page = $params['page'];
$_pagination->total = getHolidayTotal($params);

?>
<h1><a href="#" onclick="history.go(-1); return false;"><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Holiday <small class="on-right">Maintenance</small>
</h1>
<div id="message-bar" ></div>
<div class="button-bar">
	<?php if ($_form->userHasFunction('add')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="holidaynew.php">
		<div class="icon icon-plus-2 icon-l"></div>
		<div class="icon-label">Add New Holiday</div>
	</button>
	<?php } ?>
</div>
<div class="filter-block">
	<form id="frmDelete" class="hidden" method="post" action="../../actions/amts/holiday.php">
		<input type="hidden" name="ids" value="" />
		<input type="hidden" name="act" value="delete" />
		<input type="hidden" name="filter_setting_delete" value="<?php echo $params['filter_holiday']; ?>" />
		<input type="hidden" name="page" value="<?php echo $params['page']; ?>" />
		<input type="submit" id="btnDelForm" />
	</form>
	<form id="frm1" class="view-list" method="post" action="../../actions/amts/holiday.php">
	<table>
		<tr>
			<th>Holiday Filter</th>
			<td class="searchbar01">
				<div class="input-control text" data-role="input-control">
					<input type="text" name="filter_holiday" placeholder="type text" value="<?php echo $params['filter_holiday']; ?>">
					<button class="btn-clear" tabindex="-1"></button>
				</div>
				<input type="hidden" name="act" value="search" />
				<button type="submit" class="input-btn btn-main" >
					<div class="icon icon-search icon-l"></div>
					<div class="icon-label">Search</div>
				</button>
			</td>
		</tr>	
		<tr>
			<th>Start date</th>
			<td class="searchbar01">
				<div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>">
					<input type="text" name="startdate" value="<?php echo $params['startdate']; ?>">
					<button class="btn-date" tabindex="-1" type="button"></button>
				</div>
			</td>
		</tr>
		<tr>
			<th>End date</th>
			<td class="searchbar01">
				<div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>">
					<input type="text" name="enddate" value="<?php echo $params['enddate']; ?>">
					<button class="btn-date" tabindex="-1" type="button"></button>
				</div>
			</td>
		</tr>	
	</table>	
	</form>
</div>
<div class="pagination">
	<?php echo $_pagination->render(); ?>
</div>
<!--  STARTTABLE -->				
                    <div id="holidaylisttable"></div>
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
								{checkAll:'<input type="checkbox" class="checkItemToggle" data-parent="checkAllToggle" value="<?php echo $row['dayid']; ?>"  onclick="pfw_checkItem(this)"/>', 
								holiday:'<?php echo $row['holiday']; ?>',
								comment:'<?php echo $row['comment']; ?>',
								active:'<?php if ($row['active']) { echo "Active";}else{echo "Not Active"; }
								if ($_form->userHasFunction('edit')) { ?>',actions:'&nbsp;<a href=\"holidayedit.php?dayid=<?php echo $row['dayid']; ?>\">Edit</a>&nbsp;<?php } ?>'},
								<?php
								}
							}
						?>
                        ];

                        $(function(){
                            table = $("#holidaylisttable").tablecontrol({
                                cls: 'table hovered border myClass',
                                checkRow: true,
                                colModel: [
									{field: 'checkAll', caption: '<input type="checkbox" class="checkAllToggle"  data-children="checkItemToggle" value="<?php echo $row['holiday']; ?>" onclick="pfw_checkAll(this)" />', width: '10', sortable: false, cls: 'text-center', hcls: ""},
									{field: 'holiday', caption: 'Holiday', width: '100', sortable: false, cls: 'text-center', hcls: ""},
									{field: 'comment', caption: 'Comment', width: '400', sortable: false, cls: 'text-left', hcls: "text-left"},
									{field: 'active', caption: 'Status', width: '50', sortable: false, cls: 'text-left', hcls: "text-left"}
									<?php if ($_form->userHasFunction('edit')) { ?>,
									{field: 'actions', caption: 'Actions', width: '50', sortable: false, cls: 'text-center', hcls: ""}
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