<?php
include '../../startup.php';

include '../../model/attendance.php';
include '../../model/report.php';

$params['filter'] = isset($_GET['filter']) ? $_GET['filter'] : "";

// $_pagination->page = $params['page'];
// $_pagination->total = 1;//getAttendanceTotal($params);

$attendance = reportAttendanceSummary($params);



// if (($params['project'] !== null)||($params['project'] !== "X"))
// {
	// //$activity = getProjectActivityByID($params);
	// $members = getProjectMemberByID($params);
	// $phase = getProjectPhaseByID($params);
// }


if (isset($_GET['pid'])) {
	include '../../model/project.php';
	include '../../model/user.php';
	
	$params['project'] = $_GET['pid'];
	$project = getProjectDetailByID($params);
	$members = getProjectMemberByID($params);
	$phase = getProjectPhaseByID($params);
}


if  (isset($_GET['debug'])) {
	$params['debug'] = (int)$_GET['debug'];

	if (($params['debug'] !== null)&&($params['debug'] !== "1"))
	{
        echo '<pre>';
        print_r($a_ts);
        echo  '</pre>';
	}
}



if (isset($_GET['export']) && ($_GET['export']=='1')) {
	// start export

header('Content-Type: application/msexcel');
header('Content-Disposition: attachment; filename=report_project.xls');
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
.tblHeader {background:#ebebeb;}
-->
</style>  
</head>
<body>
<table class="input-form html_response">
	<tr>
		<td colspan="9" align="left"><h1>Project Report</h1></td>
	</tr>
	<tr>
		<th align="left">Project Number </th>
		<td><?php echo $project['ProjectNumber'];?></td>
	</tr>
	<tr>
		<th align="left">Project Name </th>
		<td><?php echo $project['Name'];?></td>
	</tr>
	<tr>
		<th align="left">Description</th>
		<td ><?php echo $project['Description'];?></td>
	</tr>
	<tr>
		<th align="left">Project Manager </th>
		<td >
			<?php
				foreach(getAllUsers(array()) as $row) {
					if ($row['vuserid']==$project['ProjectManager']) { echo $row['vdisplayname']; break; }
				} 
			?>
		</td>
	</tr>
	<tr>
		<th align="left">Start Date </th>
		<td align="left"><?php echo $project['StartDate'];?></td>
	</tr>
	<tr>
		<th align="left">Estimated Man Hour </th>
		<td align="left"><?php echo $project['EstimatedManHour'];?></td>
	</tr>
	<tr>
		<th align="left">Contract Amount</th>
		<td style="white-space:nowrap;"><?php if ($project['ContractAmount']!='') echo $project['idcurrency'].' '.$project['ContractAmount'];?></td>
	</tr>
	<tr>
		<th class="verticaltop" align="left" valign="top">Team Member</td>
		<td >
				<div id="projectmembertable" style="width: 100%; height: auto;">
					<table class="table hovered border myClass" style="border:1px solid #ccc;">
						<thead>
							<tr>
								<th class="tblHeader">No.</th>
								<th class="tblHeader">Member Name</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if (is_array($members)){
								$c=1 ;
								foreach ($members as &$member) { ?> 
									<tr >
									<td class="text-left" style="width: 20px;"><?php echo $c;?></td>
									<td class="text-left"><?php echo $member['vdisplayname'];?></td>
									</tr>						
									<?php 
									$c++;
								}
							}  ?>
						</tbody>
					</table>
				</div>
		</td>
	</tr>
	<tr>
		<th>Publishing status</th>
		<td colspan=2><?php if($project['Published']=='1'){echo "Published to all";} else {echo 'Not published';} ?></td>
	</tr>
	<tr>
		<th class="verticaltop">Employee vs Phase</th>
		<td colspan=2>
				<div id="phasetable" style="width: 100%; height: auto;">
					<table class="table hovered border myClass">
						<thead>
							<tr>
								<th class="tblHeader">Name</th>
								<?php foreach ($phase as $ph) { 
									?><th class="text-left tblHeader" style="width: 100px;"><?php echo $ph['NAME'];?></th><?php
								}?>
								<th class="tblHeader">Sub Total</th>
								<!--<th class="tblHeader">Estimated</th>
								<th class="tblHeader">Activity</th>-->
							</tr>
						</thead>
						<tbody>
						<?php
							if (is_array($phase)){
								$emptask = reportTimesheetSummary($params);
								$ar_data = array();
								foreach ($emptask as $et) { 
									
									if (!isset($ar_data[$et['EmployeeID']])) $ar_data[$et['EmployeeID']] = array($et['EmpName']);
									foreach ($phase as $ph) 
										if (!isset($ar_data[$et['EmployeeID']][$ph['PhaseID']]) || ($ar_data[$et['EmployeeID']][$ph['PhaseID']]=='-'))	
											$ar_data[$et['EmployeeID']][$ph['PhaseID']] = $et['PhaseID']==$ph['PhaseID']?  $et['summinute'] : '-';
									
									ksort($ar_data[$et['EmployeeID']]);
								}
								ksort($ar_data);
								
								// echo'<pre>';
								// print_r($ar_data);
								// echo'</pre>';
								
								$e = 1;
								$phase_idx = 0;
								$subtotal_phase = array();
								foreach ($ar_data as $d) {
									echo '<tr>';
									$subtotal_emp = 0;
									$phase_idx = 0;
									foreach ($d as $d1) {
										echo '<td>'.$d1.'</td>';
										if ($d1!='-') {
											if ($phase_idx > 0) { 
												if (!isset($subtotal_phase[$phase_idx])) $subtotal_phase[$phase_idx] = 0;
												$subtotal_phase[$phase_idx] += $d1;
											}
											$subtotal_emp = $subtotal_emp + $d1;
										}
										$phase_idx++;
									$e++;
									}
									echo '<td><b>'.$subtotal_emp.'</b></td></tr>';
								}
								
								echo '<tr><td>Sub Total</td>';
								$gt=0;
								foreach ($subtotal_phase as $stp=>$val) {
									echo '<td><b>'.$val.'</b></td>';
									$gt+= $val;
								}
								echo '<th>'.$gt.'</th></tr>';
							}  ?>

						</tbody>
					</table>
				</div>
			</td>
	</tr>
</table>
</body>
</html>
<?php
	
	
	
} else {
	// display form

	include '../header.php';
		
?>


<h1><a href="/" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Timesheet <small class="on-right">Report</small>
</h1>
<div id="message-bar" ></div>


<div id="message-bar" ></div>
<div class="filter-block">
	<form id="frm1" class="view-list" method="post" action="../../actions/report/r_timesheet.php">
	<table>
		<tr>
			<th>Filter</th>
			<td class="searchbar01">
				<div class="input-control text" data-role="input-control">
					<input type="text" name="filter" placeholder="type text" value="<?php echo $params['filter']; ?>">
					<button class="btn-clear" tabindex="-1"></button>
				</div>
				<input type="hidden" name="act" id="txtact" value="search" />
				<button type="button" id="btnSelectProject" class="input-btn btn-main" onclick="$('#txtact').val('search')" >
					<div class="icon icon-search icon-l"></div>
					<div class="icon-label">Search</div>
				</button>
				<button type="button" id="btnExportProject" class="input-btn btn-main" onclick="$('#txtact').val('export')" >
					<div class="icon icon-file-excel icon-l"></div>
					<div class="icon-label">Export to Excel</div>
				</button>
			</td>
		</tr>	
	</table>	
	</form>
</div>
<div id="projectdetail">
<?php
if (isset($_GET['pid'])) {
?>
<table class="input-form html_response">
	<tr>
		<th>Project Number </th>
		<td colspan=2><?php echo $project['ProjectNumber'];?></td>
	</tr>
	<tr>
		<th>Project Name </th>
		<td colspan=2><?php echo $project['Name'];?></td>
	</tr>
	<tr>
		<th>Description</th>
		<td colspan=2><?php echo $project['Description'];?></td>
	</tr>
	<tr>
		<th>Project Manager </th>
		<td colspan=2>
			<?php
				foreach(getAllUsers(array()) as $row) {
					if ($row['vuserid']==$project['ProjectManager']) { echo $row['vdisplayname']; break; }
				} 
			?>
		</td>
	</tr>
	<tr>
		<th>Start Date </th>
		<td colspan=2><?php echo $project['StartDate'];?></td>
	</tr>
	<tr>
		<th>Estimated Man Hour </th>
		<td><?php echo $project['EstimatedManHour'];?></td>
	</tr>
	<tr>
		<th>Contract Amount</th>
		<td style="white-space:nowrap;" colspan=2><?php if ($project['ContractAmount']!='') echo $project['idcurrency'].' '.$project['ContractAmount'];?></td>
	</tr>
	<tr>
		<th class="verticaltop">Team Member</td>
		<td colspan=2>
				<div id="projectmembertable" style="width: 100%; height: auto;">
					<table class="table hovered border myClass">
						<thead>
							<tr>
								<th class="text-left">No.</th>
								<th class="text-left">Member Name</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if (is_array($members)){
								$c=1 ;
								foreach ($members as &$member) { ?> 
									<tr data-id="<?php echo $c;?>"><td class="text-left" style="width: 20px;"><?php echo $c;?></td><td class="text-left">
									<input type="hidden" name="member[]" value="<?php echo $member['memberid'];?>"><?php echo $member['vdisplayname'];?></td>
									</tr>						
									<?php 
									$c++;
								}
							}  ?>
						</tbody>
					</table>
				</div>
		</td>
	</tr>
	<tr>
		<th>Publishing status</th>
		<td colspan=2><?php if($project['Published']=='1'){echo "Published to all";} else {echo 'Not published';} ?></td>
	</tr>
	<tr>
		<th class="verticaltop">Employee vs Phase</th>
		<td colspan=2>
				<div id="phasetable" style="width: 100%; height: auto;">
					<table class="table hovered border myClass">
						<thead>
							<tr>
								<th class="tblHeader">Name</th>
								<?php 
									foreach ($phase as $ph) { 
											?><th class="text-right" style="width: 100px;text-align:right !important;"><?php echo $ph['NAME'];?></th><?php
								}?>
								<th class="tblHeader text-right">Sub Total</th>
								<!--<th class="tblHeader">Estimated</th>
								<th class="tblHeader">Activity</th>-->
							</tr>
						</thead>
						<tbody>
						<?php
							if (is_array($phase)){
								$emptask = reportTimesheetSummary($params);
								$ar_data = array();
								foreach ($emptask as $et) { 
									
									if (!isset($ar_data[$et['EmployeeID']])) $ar_data[$et['EmployeeID']] = array($et['EmpName']);
									foreach ($phase as $ph) 
										if (!isset($ar_data[$et['EmployeeID']][$ph['PhaseID']]) || ($ar_data[$et['EmployeeID']][$ph['PhaseID']]=='-'))	
											$ar_data[$et['EmployeeID']][$ph['PhaseID']] = $et['PhaseID']==$ph['PhaseID']?  $et['summinute'] : '-';
									
									ksort($ar_data[$et['EmployeeID']]);
								}
								ksort($ar_data);
								
								// echo'<pre>';
								// print_r($ar_data);
								// echo'</pre>';
								
								$phase_idx = 0;
								$subtotal_phase = array();
								foreach ($ar_data as $d) {
									echo '<tr>';
									$firstrow = 1;
									$subtotal_emp = 0;
									$phase_idx = 0;
									foreach ($d as $d1) {
										echo ($firstrow == 1 ? '<td class="text-left"><b>'.$d1.'</b></td>' :  '<td class="text-right">'.$d1.'</td>') ;
										$firstrow = 0;
										if ($d1!='-') {
											if ($phase_idx > 0) { 
												if (!isset($subtotal_phase[$phase_idx])) $subtotal_phase[$phase_idx] = 0;
												$subtotal_phase[$phase_idx] += $d1;
											}
											$subtotal_emp += $d1;
										}
										$phase_idx++;
									}
									echo '<td class="info text-right"><b>'.$subtotal_emp.'</b></td></tr>';
								}
								
								echo '<tr><td class="info text-left"><b>Sub Total</b></td>';
								$gt=0;
								foreach ($subtotal_phase as $stp=>$val) {
									echo '<td class="info text-right"><b>'.$val.'</b></td>';
									$gt+= $val;
								}
								echo '<td class="text-right"><b>'.$gt.'</b></td></tr>';
							}  ?>

						</tbody>
					</table>
				</div>
			</td>
	</tr>
</table>	
<?php
}
?>

</div>



</form>

<script type="text/javascript">
	$(function(){
		$('#frm1').unbind('submit').submit(function(){
			$('#btnSelectProject').click(); return false;
		});
		$('#btnSelectProject').click(function(){
			var _filt = $('input[name="filter"]').val()!=''? '?filter_activity='+$('input[name="filter"]').val() : '';
			$.Dialog({
				overlay: false,
				shadow: true,
				flat: false,
				title: 'Select Project',
				content: 'Loading...',
				width:600,
				height:400,
				draggable:true,
				onShow: function(_dialog){
					$.get('../lookup/projecttitle_list.php'+_filt,{},function(data){
						$.Dialog.content(data);
					});
					
				}
			});
			return false;
		});
		$('#btnExportProject').click(function(){
			document.location.href = '<?php echo $_SERVER['PHP_SELF']; ?>?pid=<?php echo $_GET['pid'];?>&export=1';
		});
	});
	function dlg_projecttitle_list_callback(arr){
		var pid = arr['id']; 
		//console.log($('#frm1').attr('action'));
		document.location.href = '<?php echo $_SERVER['PHP_SELF']; ?>?pid='+pid;
	}
</script>
<?php include '../footer.php'; ?>
<?php 
	} // end display form
?>