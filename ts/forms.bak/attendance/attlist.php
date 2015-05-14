<?php
include '../../startup.php';
include '../header.php';

include '../../model/properties.php';
include '../../model/task.php';
include '../../model/attendance.php';


$now = new DateTime();
$startdate = new DateTime();
$enddate = new DateTime();
$params = array();

	// echo "<pre>";
	// print_r($_GET);


if (isset($_GET['filter'])) {
	$params['filter'] = $_GET['filter'];
} else {
	$params['filter'] = '';
}

if (isset($_GET['page'])) {
	$params['page'] = $_GET['page'];
} else {
	$params['page'] = 1;
}

if (isset($_GET['startdate'])) {
	$startdate = DateTime::createFromFormat('Y-m-d' , $_GET['startdate']);
	$params['startdate'] = $startdate->format('Y-m-d');
} else {
	$params['startdate'] = $startdate->sub(new DateInterval('P30D'))->format('Y-m-d');
}

if (isset($_GET['enddate'])) {
	$enddate = DateTime::createFromFormat('Y-m-d' , $_GET['enddate']);
	$params['enddate'] = $enddate->format('Y-m-d');
} else {
	$params['enddate'] =  $enddate->format('Y-m-d');
}


	// print_r($params);

$att = getAttendance($params);

	// print_r($att);
	// print_r($params);

//	echo "</pre>";



$_pagination->page = $params['page'];
$_pagination->total = getAttendanceTotal($params);





?>
<style type="text/css">

        #report { border-collapse:collapse;}
        #report h4 { margin:0px; padding:0px;}
        #report img { float:right;}
        #report ul { margin:10px 0 10px 40px; padding:0px;}
        #report div.arrow { background:transparent url(../../assets/img/arrows.png) no-repeat scroll 0px -16px; width:16px; height:16px; display:block;}
        #report div.up { background-position:0px 0px;}
    
</style>
<script type="text/javascript">
  
        $(document).ready(function(){
            $("#report tr:odd:not(.sembarang)").addClass("odd");
            $("#report tr:not(.odd):not(.sembarang)").hide();
            $("#report tr:first-child:not(.sembarang)").show();
            
            $("#report tr.odd").click(function(){
                $(this).next("tr").toggle();
                $(this).find(".arrow").toggleClass("up");
            });
            //$("#report").jExpand();
        });
    

</script>

<h1><a href="#" onclick="history.go(-1); return false;"><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Attendance <small class="on-right">List</small>
</h1>
<div id="message-bar" ></div>
<div class="filter-block">
	<form id="frm1" class="view-list" method="post" action="../../actions/attendance/attlist.php">
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
                    <div id="attendancelisttable"></div>
<?php
	if ($att!==false) {
	
	$tbldata = array();
	
	$tgl = "X";//$startdate;
	//$end = $enddate->add(new DateInterval('P1D'));
	?> <table id="report"  class="table hovered border myClass">
		<thead>
        <tr style="display: table-row;">
            <th colspan=2>Tanggal</th>
        </tr>
		</thead>
		<tbody>
	<?php
		foreach($att as $row)
		{
			if (!isset($tbldata[$row['tanggal']])) $tbldata[$row['tanggal']] = array();
			$tbldata[$row['tanggal']][] = $row;
			
			// if ($tgl != $row['tanggal'])
			// {
					// if ($tgl != "X")
					// {
						// echo "</tr></table></td></tr>";
					// }
					// echo "<tr class='odd' style='background-color: rgba(28,183,236,0.6);'><td>".$row['tanggal']."</td><td><div class='arrow' style='float:right'></div></td></tr> ";
					
						// echo "<tr style='display: none;'><td colspan=5> <table> <tr><th>Name</th><th>User ID</th><th>Check In Time</th><th>Check Out Time</th> </tr>";
			// }
				
			// echo "<tr class='sembarang'><td>".$row['name']."</td><td>".$row['userid']."</td><td>".$row['checkin']."</td><td>".$row['checkout']."</td></tr>";
		
			// $tgl = $row['tanggal'];
			
		}
		
		foreach($tbldata as $key=>$val) {
			?>
			<tr class="odd" style="background-color: rgba(28,183,236,0.6);"><td><?php echo $key; ?></td><td><div class="arrow" style="float:right"></div></td></tr>
			<tr style="display: none;">
				<td colspan="2">
					<table>
						<tr>
							<th>Name</th>
							<th>User ID</th>
							<th>Check In Time</th>
							<th>Check Out Time</th> 
						</tr>
						<?php
						foreach	($val as $r) {
							echo "<tr class='sembarang'><td>".$r['name']."</td><td>".$r['userid']."</td><td>".$r['checkin']."</td><td>".$r['checkout']."</td></tr>";
						}
						?>
					</table>
				</td>
			</tr>	
			<?php
		}
?>
		</tbody>
		</table>
<?php	
	}
?>
					
<!--  ENDTABLE -->				
<div class="pagination">
	<?php echo $_pagination->render(); ?>
</div>
<?php include '../footer.php'; ?>