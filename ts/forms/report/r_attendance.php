<?php
include '../../startup.php';

include '../../model/attendance.php';
include '../../model/report.php';

$params['tanggalawal'] = isset($_GET['tanggalawal']) ? $_GET['tanggalawal'] : "";
$params['tanggalakhir'] = isset($_GET['tanggalakhir']) ? $_GET['tanggalakhir'] : "";
$params['filter'] = isset($_GET['filter']) ? $_GET['filter'] : "";
$params['page'] = isset($_GET['page']) ? $_GET['page'] : "1";

$_pagination->page = $params['page'];
$_pagination->total = 1;//getAttendanceTotal($params);

$attendance = reportAttendanceSummary($params);



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

if (isset($_GET['export']) && ($_GET['export']=='1')) {
	// start export

header('Content-Type: application/msexcel');
header('Content-Disposition: attachment; filename=report_attendance.xls');
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
 
<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252"/>
<meta name=ProgId content=Excel.Sheet/>
<meta name=Generator content="Microsoft Excel 11"/>
 
<!--[if gte mso 9]><xml>
 <x:excelworkbook>
  <x:excelworksheets>
   <x:excelworksheet>
    <x:name>Report</x:name>
    <x:worksheetoptions>
     <x:selected></x:selected>
     <x:activepane>0</x:activepane>
     <x:panes>
      <x:pane>
       <x:number>3</x:number>
      </x:pane>
      <x:pane>
       <x:number>1</x:number>
      </x:pane>
      <x:pane>
       <x:number>2</x:number>
      </x:pane>
      <x:pane>
       <x:number>0</x:number>
      </x:pane>
     </x:panes>
     <x:protectcontents>False</x:protectcontents>
     <x:protectobjects>False</x:protectobjects>
     <x:protectscenarios>False</x:protectscenarios>
    </x:worksheetoptions>
   </x:excelworksheet>
  </x:excelworksheets>
  <x:protectstructure>False</x:protectstructure>
  <x:protectwindows>False</x:protectwindows>
 </x:excelworkbook>
</xml><![endif]-->
<style>
<!--
th {border:1px solid black;}
.left {border:1px solid black; text-align:left;}
.right {border:1px solid black; text-align:right;}
.center {border:1px solid black; text-align:center;}
-->
</style>  
</head>
<body>
      <table class="list">
		<tbody>
			<tr>
				<td colspan="10"><h1>Attendance Report</h1></td>
			</tr>	
			<tr>
				<td colspan="5">Periode : <?php echo $params['tanggalawal']; ?> s/d <?php echo $params['tanggalakhir']; ?></td>
			</tr>	
			<tr>
				<td colspan="5">Filter : <?php echo $params['filter']; ?></td>
			</tr>	
			<tr>
				<td colspan="5"> </td>
			</tr>	
		</tbody>
		<thead>
        <tr style="display: table-row;">
            <th align="left">Tanggal</th><th align="left">Name</th><th align="left">User ID</th><th align="left">On Duty</th><th align="left">Check In Time</th><th align="left">Off Duty</th><th align="left">Check Out Time</th> 
        </tr>
		</thead>
		<tbody>
<?php		
	if ($attendance!==false) {
	$tgl = "X";//$startdate;
	?> 

	<?php
		// $previd = 0;
		// $newid = -1;
		$newdate = "";
		$prevdate = "";
		//$gantinode =  false;
		// $data_array = array();
		// $node = array();
		$echo_userid = "";
		foreach($attendance as $row)
		{
			// entry loop initialization
			$emptydate = ($row['badgenumber']!="") ? $row['name'] : "";
			// $newid = $row['userid'];
			$newdate = $row['tanggal'];
			//$gantinode = $newdate != $prevdate ? true : false;
			
			
			//if ($gantinode) 
			//{
				echo "<tr ><td>".$row['tanggal'];
			//}else {
			//	echo "<tr ><td>&nbsp;";
			//}
			//tanggal/nama/userid/timetable/on duty/off duty/clock in/clockout/
			echo "</td><td>".$emptydate."</td><td>".$row['badgenumber']."</td><td>".$row['starttime']."</td><td>".$row['checkin']."</td><td>".$row['endtime']."</td><td>".$row['checkout']."</td> </tr>";
			$prevdate = $newdate;
			//$gantinode = false;
		}
		
	}
?>	
        </tbody>
      </table>
</body>
</html>
<?php
	
	
	
} else {
	// display form

	include '../header.php';
		
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
				<input type="hidden" name="act" id="txtact" value="search" />
				<button type="submit" class="input-btn btn-main" onclick="$('#txtact').val('search')" >
					<div class="icon icon-search icon-l"></div>
					<div class="icon-label">Search</div>
				</button>
				<button type="submit" class="input-btn btn-main" onclick="$('#txtact').val('export')" >
					<div class="icon icon-file-excel icon-l"></div>
					<div class="icon-label">Export to Excel</div>
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
	?> <table id="report"  class="table hovered border ">
		<thead>
        <tr style="display: table-row;">
            <th align="left">Tanggal</th><th align="left">Name</th><th align="left">User ID</th><th align="left">On Duty</th><th align="left">Check In Time</th><th align="left">Off Duty</th><th align="left">Check Out Time</th> 
        </tr>
		</thead>
		<tbody>

	<?php
		// $previd = 0;
		// $newid = -1;
		$newdate = "";
		$prevdate = "";
		$gantinode =  false;
		// $data_array = array();
		// $node = array();
		$echo_userid = "";
		foreach($attendance as $row)
		{
			// entry loop initialization
			$emptydate = ($row['badgenumber']!="") ? $row['name'] : "[- None -]";
			// $newid = $row['userid'];
			$newdate = $row['tanggal'];
			$gantinode = $newdate != $prevdate ? true : false;
			
			
			if ($gantinode) 
			{
				// array_push($data_array,$node);
				//echo "<pre>";
				//print_r($node);
				//echo "</pre>";
				//$node = array();
				//echo "<tr ><td colspan=5>&nbsp;</td></tr>"
				echo "<tr ><td>".$row['tanggal'];
			}else {
				echo "<tr ><td>&nbsp;";
			}
			echo "</td><td>".$emptydate."</td><td>".$row['badgenumber']."</td><td>".$row['starttime']."</td><td>".$row['checkin']."</td><td>".$row['endtime']."</td><td>".$row['checkout']."</td> </tr>";
			// $node[$newdate][$newid]['ID']=$newid;
			// $node[$newdate][$newid]['Name']=$row['name'];
			

			// echo "<tr><td>".$row['tanggal']."</td><td>".$row['userid']."</td><td>".$row['name']."</td><td> newid ".$newid."</td><td> previd ".$previd."</td><td> newdate ".$newdate."</td><td> prevdate ".$prevdate."</td><td> gantihari ".$gantinode."</td></tr>";

			// //prepare next loop
			// $previd = $newid;
			$prevdate = $newdate;
			$gantinode = false;
		}
			// array_push($data_array,$node);
			// echo "<pre>";
			// print_r($data_array);
			// echo "</pre>";

		
	echo "</tbody></table>";
	
	// echo "<pre>";
	// print_r($data_array);
	// echo "</pre>";
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
<?php 
	} // end display form
?>