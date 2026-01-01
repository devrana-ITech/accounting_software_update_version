<?php
function format_num($number){
	$decimals = 0;
	$num_ex = explode('.',$number);
	$decimals = isset($num_ex[1]) ? strlen($num_ex[1]) : 0 ;
	return number_format($number,$decimals);
}
?>
<style>
	th.p-0, td.p-0{
		padding: 0 !important;
	}
	.form-container {
	width: 100%;
	display: flex;
	flex-wrap: wrap; /* Allow items to wrap to the next line if needed */
	gap: 10px; /* Adjust spacing between items as needed */
}

/* Style for the side-by-side input boxes */
.side-by-side {
	flex: 1; /* Grow to take available space */
}

/* Style for the full-width input box */
.full-width {
	flex: 2; /* Grow to take more available space */
	width: 100%; /* Take 100% of available width */
}	

	select {
      background: #f0dcf7;
}
table, th, td {
  border: 1px solid #d9d0f7;
  border-collapse: collapse;
  padding: 5px;
}
.select2-results {  background: #f0dcf7; }
.select2-search input { background: #f0dcf7; }
.select2-selection__rendered { background: #f0dcf7; }
.select2-search { background: #f0dcf7; }
.select2-results__option--highlighted { background: #f0dcf7; }
.select2-results__option[aria-selected=true] { background: #f0dcf7; }


.select2-container .select2-selection--single{
	padding: 0px;
	margin-top: 4px;
	height:34px !important;
	width: 100% !important;
}

.select2-container--default .select2-selection--single{
	padding: 0px;
	border: 1px solid blue !important; 
	border-radius: 0px !important;
}
</style>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Credit Voucher Entries</h3>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table style ="width: 50%;">
				<thead>
					<tr>
						<th style="background: #faefe3; text-align: center; width: 10%;">Date</th>
						<th style="background: #faefe3; text-align: center; width: 10%;">Voucher No Fund Type</th>
						<th style="background: #faefe3; text-align: left; width: 40%;">Description</th>
						<th style="background: #faefe3; text-align: right; width: 20%;">Debit</th>
						<th style="background: #faefe3; text-align: right; width: 20%;">Credit</th>
					</tr>
				</thead>
				<tbody>
					<?php
					require_once('../config.php');
					
					$year_id=$_REQUEST['year_id'];
					
					$dli_type=$_REQUEST['dli_type'];
					$total = 0;
					
					$swhere = "";
					/*
					if($_settings->userdata('type') == 1){
						$swhere = " where user_id = '{$_settings->userdata('id')}' and year_id = '$year_id' and journal_type='cv' ";
					}
					*/
					
					if (($dli_type == '0') && ($year_id == 0))
						$swhere = " where journal_type='cv' ";
					if (($dli_type != '0') && ($year_id != 0))
						$swhere = " where year_id = '$year_id' and journal_type='cv' and dli_type='$dli_type' ";
					if (($year_id == 0) && ($dli_type != '0'))
						$swhere = " where journal_type='cv' and dli_type='$dli_type' ";
					if (($dli_type == '0') && ($year_id != 0))
						$swhere = " where year_id = '$year_id' and journal_type='cv' ";
						
					
					$users = $conn->query("SELECT id, username FROM `users` where id in (SELECT `user_id` FROM `journal_entries` {$swhere})");
					$user_arr = array_column($users->fetch_all(MYSQLI_ASSOC),'username','id');
					$journals = $conn->query("SELECT * FROM `journal_entries` {$swhere} order by date(journal_date) asc");
					while($row = $journals->fetch_assoc()):
					?>
					<tr>
						<td style="text-align: center;"><?= date("M d, Y", strtotime($row['journal_date'])) ?></td>
						<td style="text-align: center;"><?= $row['voucher_number'] ?><br><?= $row['dli_type'] ?></td>
						<td colspan="3">
							<table style ="width: 100%;">
								<?php 
								$jitems = $conn->query("SELECT j.*,a.name as account, g.type as `type` FROM `journal_items` j inner join account_list a on j.account_id = a.id inner join group_list g on j.group_id = g.id where j.journal_id = '{$row['id']}' order by g.type asc");
								while($rowss = $jitems->fetch_assoc()):
								?>
								<tr>
								   <?php if ($rowss['type'] == 1 )	{ ?>
										<td style="width: 50%;"><?= $rowss['account'] ?></td> <?php $total = $total + $rowss['amount']; } ?>
									<?php if ($rowss['type'] == 2 )	{ ?>
										<td style="width: 50%;"><?= $rowss['account'] ?></td> <?php } ?>
									<td style="text-align: right; width: 25%;"><?= $rowss['type'] == 1 ? format_num($rowss['amount']) : '' ?></td>
									<td style="text-align: right; width: 25%;"><?= $rowss['type'] == 2 ? format_num($rowss['amount']) : '' ?></td>
								</tr>
								<?php endwhile; ?>
							
								<tr>
									<td style="text-align: left; width: 100%;" colspan="3"><?= $row['description'] ?></td>
								</tr>
							</table>
						</td>
					</tr>
					<?php endwhile; ?>
				</tbody>
				<tr>
					<td colspan="3" style="background: #faefe3; text-align: left; width: 60%;"><b>Total</b></td>
					<td style="background: #faefe3; text-align: right; width: 20%;"><b><?= format_num($total) ?></b></td>
					<td style="background: #faefe3; text-align: right; width: 20%;"><b><?= format_num($total) ?></b></td>
				</tr>
			</table>
		</div>
	</div>
</div>

<hr class="border-border bg-primary">

<script>
	$(document).ready(function(){
		$('#create_new').click(function(){
			uni_modal("New Credit Voucher Entry","journalsCreditVoucher/manage_journal.php",'large')
		})
		$('.edit_data').click(function(){
			uni_modal("Edit Credit Voucher Entry","journalsCreditVoucher/manage_journal.php?id="+$(this).attr('data-id'),"large")
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Journal Entry permanently?","delete_book",[$(this).attr('data-id')])
		})
		
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 2 }
            ],
        });
	})
	
	function delete_book($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_journal",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>