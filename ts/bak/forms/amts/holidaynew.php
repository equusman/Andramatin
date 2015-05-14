<?php
include '../../startup.php';
include '../header.php';

include '../../model/task.php';
include '../../model/user.php';
include '../../model/currency.php';

?>
<h1><a href="user_list.php" ><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Add <small class="on-right">New Holiday</small>
</h1>
<div id="message-bar" ></div>
<div class="button-bar">
	<?php if ($_form->userHasAccess('amts/holiday.php')) { ?>
	<button type="button" class="input-btn btn-main dark" data-link="holiday.php" >
		<div class="icon icon-list icon-l"></div>
		<div class="icon-label">Holiday List</div>
	</button>
	<?php } ?>
</div>
<form id="frm1" class="view-list" method="post" action="../../actions/amts/holidaynew.php?act=add">
<div class="form-block">

<table class="input-form">
	<tr>
		<th>Holiday Date *</th>
		<td colspan=2>
			<div class="input-control text" data-role="datepicker" data-format="<?php echo DATEPICKER_FORMAT; ?>">
				<input type="text" name="holiday" value="">
				<button class="btn-date" tabindex="-1" type="button"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>Description *</th>
		<td colspan=2>
			<div class="input-control text" data-role="input-control">
				<input type="text" name="description" value="">
				<button class="btn-clear" tabindex="-1"></button>
			</div>
		</td>
	</tr>
	<tr>
		<th>&nbsp;</th>
		<td colspan=2>
			<label><input id="active" type="checkbox" checked="" name="active" value="1"><span class="check"></span> Active</label>
		</td>
	</tr>
	<tr><td colspan=4 style="min-height='20px';"></td></tr>
	<tr>
		<td colspan=2></td>
		<td>
			<input type="hidden" name="act" value="add" />
			<button type="submit" class="input-btn btn-main dark" >
				<div class="icon icon-floppy icon-l"></div>
				<div class="icon-label">Save Holiday</div>
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
	});
</script>
<?php include '../footer.php'; ?>