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

	echo "<pre>";
	print_r($_GET);


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
	$params['startdate'] = $_GET['startdate'];
} else {
	$params['startdate'] = $startdate->setDate($now->format('Y'),1,1)->format('Y-m-d');
}

if (isset($_GET['enddate'])) {
	$params['enddate'] = $_GET['enddate'];
} else {
	$params['enddate'] =  $enddate->setDate($now->format('Y'),12,31)->format('Y-m-d');
}

	print_r($params);


$att = getAttendance($params);

	print_r($att);
	// print_r($params);

	echo "</pre>";



$_pagination->page = $params['page'];
$_pagination->total = getAttendanceTotal($params);

?>
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
<ul class="treeview" data-role="treeview">
    <li class="node">
        <a>2013-10-02</a>
        <ul>
            <li>Lina Madiyana</li>
            <li>budy</li>
            <li>moby</li>
            <li>danar</li>
            <li>bruno mars</li>
            <li class="node">
                <a>makanan sate</a>
                <ul>
                    <li>ayam</li>
                    <li>sapi</li>
                    <li>baso</li>
                    <li>cicak</li>
                    <li>kecoa</li>
                </ul>
            </li>
        </ul>
    </li>
   <li class="node collapsed">
        <a href="#">info@metroui.net</a>
        <ul>
            <li><a href="#">Inbox</a></li>
            <li><a href="#">Outbox</a></li>
            <li><a href="#">Drafts</a></li>
            <li><a href="#">Rss-channels</a></li>
            <li><a href="#">Trash</a></li>
        </ul>
    </li>
</ul>					
					<?php 
						$tanggal = "";
						if ($att!==false) {
						
							echo '<ul class="treeview" data-role="treeview">';
							foreach ($att as $row) {
								
								if ($tanggal == $row['tanggal']) 
								{ 
									echo "<li class='node'><ul><li class='node'><a>".$row['tanggal']."</a></li><li class='node'><a>".$row['name']."</a><br></li></ul></li>";
								}
								else 
								{ 
									echo "<li class='node'><a>".$tanggal."</a><br></li>";
								} //tanggal beda
								
								if (isset($tanggal)||$tanggal=="") { $tanggal = $row['tanggal'];}
								
							}
							echo '</ul>';
						}
					?>
                    <script>
                    </script>
<!--  ENDTABLE -->				
<div class="pagination">
	<?php echo $_pagination->render(); ?>
</div>
<?php include '../footer.php'; ?>