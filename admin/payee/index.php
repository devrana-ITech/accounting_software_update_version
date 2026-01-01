<?php
function format_num($number){
	$decimals = 0;
	$num_ex = explode('.',$number);
	$decimals = isset($num_ex[1]) ? strlen($num_ex[1]) : 2 ;
	return number_format($number,$decimals);
}
?>
<style>
    .img-thumb-path{
        width:100px;
        height:80px;
        object-fit:scale-down;
        object-position:center center;
    }
</style>
<div class="card card-outline card-primary rounded-0 shadow">
	<div class="card-header">
		<h3 class="card-title">List of Payees</h3>
		<div class="card-tools">
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-sm btn-primary"><span class="fas fa-plus"></span>  Add New</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-hover table-striped">
				<colgroup>
					<col width="4%">
					<col width="16%">
					<col width="12%">
					<col width="10%">
					<col width="10%">
					<col width="12%">
					<col width="12%">
					<col width="6%">
					<col width="6%">
					<col width="6%">
					<col width="6%">
				</colgroup>
				<thead>
					<tr class="bg-gradient-primary text-light">
						<th>SN</th>
						<th>Payee Name</th>
						<th>Prior/Post Review</th>
						<th style="text-align: right">Contract Value BDT</th>
						<th style="text-align: right">Contract Value USD</th>
						<th style="text-align: right">Bank Percent</th>
						<th>Package Number</th>
						<th>Contract Date</th>
						<th>Selection Method</th>
						<th>Contract Currency</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						//$val = $_settings->userdata('year_id');
						$qry = $conn->query("SELECT *, payee.id as id from `payee` inner join `pkg` on payee.pack_number = pkg.id order by `payee_name` asc");
					
					if ($qry->num_rows > 0) {	
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><?php echo $row['payee_name'] ?></td>
							<td class=""><?php echo $row['iufr_flag'] ?></td>
							<td class="" style="text-align: right"><p class="m-0 truncate-1"><?php echo format_num($row['contract_value']) ?></p></td>
							<td class="" style="text-align: right"><p class="m-0 truncate-1"><?php echo format_num($row['contract_value_us']) ?></p></td>
							<td class="" style="text-align: right"><p class="m-0 truncate-1"><?php echo format_num($row['percent_bank']) ?></p></td>
							<td class=""><?php echo $row['pack_name'] ?></td>
							<td class=""><?php echo date("M d, Y", strtotime($row['contract_date'])) ?></td>
							<td class=""><?php echo $row['selection_method'] ?></td>
							<td class=""><?php echo $row['contract_currency'] ?></td>
							
							<td align="center">
								<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
									Action
								<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu" role="menu">
								<div class="dropdown-divider"></div>
								<a class="dropdown-item edit_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-name="<?php echo $row['payee_name'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
								</div>
							</td>
						</tr>
					<?php endwhile; } ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
        $('#create_new').click(function(){
			uni_modal("Add Payee Info","payee/manage_aid.php",'mid-large')
		})
        $('.edit_data').click(function(){
			uni_modal("Update Payee Info","payee/manage_aid.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete payee '<b>"+$(this).attr('data-name')+"</b>'permanently?","delete_aid",[$(this).attr('data-id')])
		})
		$('.table td, .table th').addClass('py-1 px-2 align-middle')
		
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 4 }
            ],
        });
	})

	function delete_aid($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_payee",
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