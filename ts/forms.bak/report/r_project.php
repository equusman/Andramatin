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



if (isset($_GET['pid'])) {
	include '../../model/project.php';
	include '../../model/user.php';
	
	$params['project'] = $_GET['pid'];
	$project = getProjectDetailByID($params);
	$members = getProjectMemberByID($params);
	$phase = getProjectPhaseByID($params);
	
	$phaseactual = projectPhaseActual($project);
}

if  (isset($_GET['debug'])) {
	$params['debug'] = (int)$_GET['debug'];

	if (($params['debug'] !== null)&&($params['debug'] !== "1"))
	{
        echo '<pre>';
        print_r($phaseactual);
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
		<th align="left">Publishing status</th>
		<td ><?php if($project['Published']=='1'){echo "Published to all";} else {echo 'Not published';} ?></td>
	</tr>
	<tr>
		<th class="verticaltop" align="left" valign="top">Phase</th>
		<td>
				<div id="phasetable" style="width: 100%; height: auto;">
					<table class="table hovered border myClass">
						<thead>
							<tr>
								<th class="text-left">No.</th>
								<th class="text-left">Name</th>
								<th class="text-left">Estimated</th>
								<th class="text-left">Actual</th>
								<th class="text-left">Note</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if (is_array($phaseactual)){
								$c=1 ;
								foreach ($phaseactual as $ph) { 
								//$cek = Array('phase_id'=>$ph['PhaseID'],'project_id'=>$ph['ProjectID']);
								?> 
									<tr>
										<td class="text-left" style="width: 20px;"><?php echo $c;?></td>
										<td class="text-left" style="width: 100px;"><?php echo $ph['Name'];?></td>
										<td class="text-right" style="width: 40px;"><?php echo $ph['EstimatedManHour'];?>hrs</td>
										<td class="text-right" style="width: 40px;"><?php echo floor($ph['subtotals_in_minutes']/60);?>hrs<?php echo floor($ph['subtotals_in_minutes']%60);?>min</td><td><?php if ($ph['EstimatedManHour']>=floor($ph['subtotals_in_minutes']/60)) { echo " GOOD ";}else{echo " BAD ";} ?></td>
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
</table>
</body>
</html>
<?php
	
	
	
} else {
	// display form

	include '../header.php';
		
?>


<h1><a href="/" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Project <small class="on-right">Report</small>
</h1>
<div id="message-bar" ></div>


<div id="message-bar" ></div>
<div class="filter-block">
	<form id="frm1" class="view-list" method="post" action="../../actions/report/r_project.php">
	<table>
		<tr>
			<th>Filter</th>
			<td class="searchbar01">
				<div class="input-control text" data-role="input-control">
					<input type="text" name="filter" id="filter" placeholder="type text" value="<?php echo $params['filter']; ?>">
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
		<th class="verticaltop">Phase</th>
		<td colspan=2>
				<div id="phasetable" style="width: 100%; height: auto;">
					<table class="table hovered border myClass">
						<thead>
							<tr>
								<th class="text-left">No.</th>
								<th class="text-left">Name</th>
								<th class="text-left">Estimated</th>
								<th class="text-left">Actual</th>
								<th class="text-left">Note</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if (is_array($phaseactual)){
								$c=1 ;
								foreach ($phaseactual as $ph) { 
								//$cek = Array('phase_id'=>$ph['PhaseID'],'project_id'=>$ph['ProjectID']);
								?> 
									<tr>
										<td class="text-left" style="width: 20px;"><?php echo $c;?></td>
										<td class="text-left" style="width: 100px;"><?php echo $ph['Name'];?></td>
										<td class="text-right" style="width: 40px;"><?php echo $ph['EstimatedManHour'];?>hrs</td>
										<td class="text-right" style="width: 40px;"><?php echo floor($ph['subtotals_in_minutes']/60);?>hrs<?php echo floor($ph['subtotals_in_minutes']%60);?>min</td><td><?php if ($ph['EstimatedManHour']>=floor($ph['subtotals_in_minutes']/60)) { echo " GOOD ";}else{echo " BAD ";} ?></td>
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
		// $('#filter').live("keypress", function(e) {
				// if (e.keyCode == 13) {
					// alert("Enter pressed");
					// return false; // prevent the button click from happening
				// }
		// });
		// $('#filter').keypress(function (e) {
			// var key = e.which;
			// if(key == 13)  // the enter key code
			// {
				// alert("Enter pressed!!!!!");
				// //$('#btnSelectProject').click();
				// return false;  
			// }
		// }); 
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