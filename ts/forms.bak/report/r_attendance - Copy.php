<?php
include '../../startup.php';
include '../header.php';

include '../../model/attendance.php';
include '../../model/report.php';

$params['tanggalawal'] = isset($_GET['tanggalawal']) ? $_GET['tanggalawal'] : "";
$params['tanggalakhir'] = isset($_GET['tanggalakhir']) ? $_GET['tanggalakhir'] : "";
$params['filter'] = isset($_GET['filter']) ? $_GET['filter'] : "";
$params['page'] = isset($_GET['page']) ? $_GET['page'] : "1";

$_pagination->page = $params['page'];
$_pagination->total = 1;//getAttendanceTotal($params);

$attendance = reportAttendance($params);



// if (($params['project'] !== null)||($params['project'] !== "X"))
// {
	// //$activity = getProjectActivityByID($params);
	// $members = getProjectMemberByID($params);
	// $phase = getProjectPhaseByID($params);
// }

if  (isset($_GET['debug'])) {
	$params['debug'] = (int)$_GET['debug'];

	if (($params['debug'] !== null)&&($params['debug'] !== "1"))
	{
        echo '<pre>';
        print_r($attendance);
        echo  '</pre>';
	}
}


		
?>


<h1><a href="/" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Attendance <small class="on-right">Report</small>
</h1>
<div id="message-bar" ></div>


<div id="message-bar" ></div>
<div class="filter-block">
	<form id="frm1" class="view-list" method="post" action="../../actions/report/r_attendance.php">
	<table>
		<tr>
			<th>Filter</th>
			<td class="searchbar01">
				<div class="input-control text" data-role="input-control">
					<input type="text" name="filter" placeholder="type text" value="<?php echo $params['filter']; ?>">
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
					<input type="text" name="tanggalawal" value="<?php echo $params['tanggalawal']; ?>">
					<button class="btn-date" tabindex="-1" type="button"></button>
				</div>
			</td>
		</tr>
		<tr>
			<th>End date</th>
			<td class="searchbar01">
				<div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>">
					<input type="text" name="tanggalakhir" value="<?php echo $params['tanggalakhir']; ?>">
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


<script type="text/javascript">
  
        // $(document).ready(function(){
            // $("#report tr:odd:not(.sembarang)").addClass("odd");
            // $("#report tr:not(.odd):not(.sembarang)").hide();
            // $("#report tr:first-child:not(.sembarang)").show();
            
            // $("#report tr.odd").click(function(){
                // $(this).next("tr").toggle();
                // $(this).find(".arrow").toggleClass("up");
            // });
            // //$("#report").jExpand();
        // });
    

</script>

<!--  STARTTABLE -->				
                    <div id="attendancelisttable"></div>
<?php
	if ($attendance!==false) {
	$tgl = "X";//$startdate;
	?> <table id="report"  class="table hovered border myClass">
		<thead>
        <tr style="display: table-row;">
            <th colspan=2>Tanggal</th>
        </tr>
		</thead>
		<tbody>

	<?php
//		echo "<tr style='display: none;'><td colspan=5> <table> <tr><th>Name</th><th>User ID</th><th>Check In Time</th><th>Check Out Time</th> </tr>";
		$previd = 0;
		$newid = -1;
		$newdate = "";
		$prevdate = "";
		$data_array = array();
		foreach($attendance as $row)
		{
			$node = array();
			$newid = $row['userid'];
			$newdate = $row['tanggal'];
			
			if(($newid != $previd) && (empty($newid))) {
				$node['ID'] = $newid;
				array_push($data_array,$node);
			}
			
			if ($newdate != $prevdate) {array_push($data_array,$newdate);}
			
			echo "<tr><td>".$row['tanggal']."</td><td>".$row['userid']."</td><td>".$row['name']."</td><td> newid ".$newid."</td><td> previd ".$previd."</td><td> newdate ".$newdate."</td><td> prevdate ".$prevdate."</td></tr>";
			
			$previd = $newid;
			$prevdate = $newdate;
		}

	echo "</tbody></table>";
	
	echo "<pre>";
	print_r($data_array);
	echo "</pre>";
	}
?>
					
<!--  ENDTABLE -->				



</form>

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