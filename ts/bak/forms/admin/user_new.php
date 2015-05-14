<?php
include '../../startup.php';
include '../header.php';

include '../../model/user.php';

?>
<h1><a href="user_list.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Add <small class="on-right">New User</small>
</h1>
<div id="message-bar" ></div>
<div class="button-bar">
	<?php if ($_form->userHasAccess('admin/user_list.php')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="user_list.php" >
		<div class="icon icon-list icon-l"></div>
		<div class="icon-label">User List</div>
	</button>
	<?php } ?>
</div>
<form id="frm1" class="view-list" method="post" action="../../actions/admin/user_new.php">
<div class="form-block">
<table class="input-form">
	<tr>
		<th>User Type *</th>
		<td>
			<div class="input-control radio">
				<label>
					<input type="radio" name="usertype" id="usertype" checked="checked" data-value="indie"/>
					<span class="check"></span>
					Independent
				</label>
			</div>		
			<div class="input-control radio">
				<label>
					<input type="radio" name="usertype" id="usertype" data-value="related"/>
					<span class="check"></span>
					Mesin Absensi
				</label>
			</div>		
		</td>
	</tr>

	
	<tr>
		<th>Username *</th>
		<td>
			<div class="input-control text" data-role="input-control">
				<input type="text" id="username" name="username" value="">
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr class="detailindie">
		<th>Display Name *</th>
		<td>
			<div class="input-control text" data-role="input-control">
				<input type="text" id="displayname" name="displayname" value="">
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr class="detailrelated"  style="display:none;">
		<th>Display Name *</th>
		<td>
			<div class="input-control select">
				<select id="relatedusername" name="relatedusername" disabled>
					<?php
						foreach(getFingerprintUsers() as $row) {
					?>
					<option value="<?php echo $row['userid']; ?>"><?php echo $row['userid']." - ".$row['name']; ?></option>
					<?php } ?>
				</select>
			</div>
		</td>
	</tr>

	<tr>
		<th>Password *</th>
		<td>
			<div class="input-control password" data-role="input-control">
				<input type="password" name="password" value="">
				<button class="btn-reveal" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Begin Effective Date *</th>
		<td>
			<div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>">
				<input type="text" name="begineff" value="">
				<button class="btn-date" tabindex="-1" type="button"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Last Effective Date *</th>
		<td>
			<div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>">
				<input type="text" name="lasteff" value="">
				<button class="btn-date" tabindex="-1" type="button"></button>
			</div>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<input type="hidden" name="act" value="add" />
			<button type="submit" class="input-btn btn-main dark" >
				<div class="icon icon-floppy icon-l"></div>
				<div class="icon-label">Save Data</div>
			</button>
		</td>
	</tr>	
</table>
</div>	
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
		$('#usertype[data-value=indie]').click(function(){
			if($('#usertype').is(':checked')){
					$('.detailindie').show();
					$('.detailrelated').hide();
					//$('#username').prop('disabled',false);
					//$('#displayname').prop('disabled',false);
					$('#relatedusername').prop('disabled',true);			
				}
		});
		$('#usertype[data-value=related]').click(function(){
					$('.detailindie').hide();
					$('.detailrelated').show();
					//$('#username').prop('disabled',true);
					//$('#displayname').prop('disabled',true);
					$('#relatedusername').prop('disabled',false);
		})
	});
</script>
<?php include '../footer.php'; ?>