<?php
function format_num($number){
	$decimals = 0;
	$num_ex = explode('.',$number);
	$decimals = isset($num_ex[1]) ? strlen($num_ex[1]) : 2 ;
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
		<h3 class="card-title">Journal Voucher Entries</h3>
		<div class="card-tools">
			<button class="btn btn-primary btn-flat btn-sm" id="create_new" type="button"><i class="fa fa-pen-square"></i> Journal Voucher Entry</button>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered">
				<colgroup>
					<col width="10%">
					<col width="10%">
					<col width="15%">
					<col width="55%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th style="background: #faefe3; text-align: center;">Date</th>
				    	<th style="background: #faefe3; text-align: center;">V. No</th>
						<th style="background: #faefe3; text-align: center;">F Type</th>
						<th class="p-0" style="background: #faefe3;">
							<div class="d-flex w-100">
								<div class="col-6 border">Description</div>
								<div class="col-3 border" style="text-align: right;">Debit</div>
								<div class="col-3 border" style="text-align: right;">Credit</div>
							</div>
						</th>
				<!--		<th>Encoded By</th>   -->
						<th style="text-align: center; background: #faefe3;">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$swhere = ""; //userdata('type') != 1 related to user type admin and others
					if($_settings->userdata('type') == 1){
						$swhere = " where user_id = '{$_settings->userdata('id')}' and year_id = '{$_settings->userdata('year_id')}' and journal_type='jv' ";
					}
					
					$users = $conn->query("SELECT id,username FROM `users` where id in (SELECT `user_id` FROM `journal_entries` {$swhere})");
					$user_arr = array_column($users->fetch_all(MYSQLI_ASSOC),'username','id');
					$journals = $conn->query("SELECT * FROM `journal_entries` {$swhere} order by date(journal_date) desc");
					while($row = $journals->fetch_assoc()):
					?>
					<tr>
						<td class="text-center"><?= date("M d, Y", strtotime($row['journal_date'])) ?></td>
						<td class="text-center"><?= $row['voucher_number'] ?></td>
						<td class="text-center"><?= $row['dli_type'] ?><br>Component - <?= $row['component_number'] ?></td>
						
						<td class="p-0">
							
							<?php 
							$jitems = $conn->query("SELECT j.*,a.name as account, g.type as `type` FROM `journal_items` j inner join account_list a on j.account_id = a.id inner join group_list g on j.group_id = g.id where j.journal_id = '{$row['id']}'  order by g.type asc");
							
							while($rowss = $jitems->fetch_assoc()):
							?>
							<div class="d-flex w-100">
							   <?php if ($rowss['type'] == 1 )	{ ?>
									<div class="col-6 border"><?= $rowss['account'] ?></span></div> <?php } ?>
								<?php if ($rowss['type'] == 2 )	{ ?>
									<div class="col-6 border"><span class="pl-4"><?= $rowss['account'] ?></span></div> <?php } ?>
								<div class="col-3 border text-right"><?= $rowss['type'] == 1 ? format_num($rowss['amount']) : '' ?></div>
								<div class="col-3 border text-right"><?= $rowss['type'] == 2 ? format_num($rowss['amount']) : '' ?></div>
							</div>
							<?php endwhile; ?>
							
							<div class="d-flex w-100">
								<div class="col-12 border"><i><?= $row['description'] ?></i></div>
							<!--	<div class="col-2 border"></div>
								<div class="col-2 border"></div> -->
							</div>
						</td>
		
						<td class="text-center">
							<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
									Action
								<span class="sr-only">Toggle Dropdown</span>
							</button>
							<div class="dropdown-menu" role="menu">
								<a class="dropdown-item edit_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item duplicate_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-copy text-primary"></span> Duplicate</a>
								<div class="dropdown-divider"></div> 
								<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-code="<?php echo $row['code'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
							</div>
						</td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<hr class="border-border bg-primary">


<script>
	$(document).ready(function(){
		$('#create_new').click(function(){
			uni_modal("New Journal Entry","journalsVoucher/manage_journal.php",'large')
		})
		$('.edit_data').click(function(){
			uni_modal("Edit Journal Entry","journalsVoucher/manage_journal.php?id="+$(this).attr('data-id'),"large")
		})
		$('.duplicate_data').click(function(){
			uni_modal("Duplicate Journal Entry","journalsVoucher/manage_journal_dup.php?id="+$(this).attr('data-id'),"large")
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Journal Entry permanently?","delete_book",[$(this).attr('data-id')])
		})
		
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: true, targets: 2 }
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