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
		<h3 class="card-title">List of Planned Funds</h3>
		<div class="card-tools">
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-sm btn-primary"><span class="fas fa-plus"></span>  Add New</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-hover table-striped">
				<colgroup>
					<col width="5%">
					<col width="45%">
					<col width="12%">
					<col width="12%">
					<col width="12%">
					<col width="12%">
					<col width="8%">
				</colgroup>
				<thead>
					<tr class="bg-gradient-primary text-light">
						<th>SN</th>
						<th>Component</th>
						<th>Year</th>
						<th>Quarter Name</th>
						<th>Fund Type</th>
						<th style="text-align: right">Amount</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						//$val = $_settings->userdata('year_id');
						$qry = $conn->query("SELECT *, pl_fund.id as id from `pl_fund` inner join `qtr` on pl_fund.qtr_id = qtr.id inner join fy on fy.id = pl_fund.fy_id inner join tbl_component on tbl_component.id = pl_fund.comp_id order by `fy_id` desc ");
					
					if ($qry->num_rows > 0) {	
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><?php echo $row['com_name'] ?></td>
							<td class=""><?php echo $row['fy'] ?></td>
							<td class=""><?php echo $row['qtr_name'] ?></td>
							<td class=""><?php echo $row['fund_type'] ?></td>
							<td class="" style="text-align: right"><p class="m-0 truncate-1"><?php echo format_num($row['pl_amount']) ?></p></td>
							
							<td align="center">
								<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
									Action
								<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu" role="menu">
								<div class="dropdown-divider"></div>
								<a class="dropdown-item edit_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-name="<?php echo $row['pl_amount'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
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
			uni_modal("Add Planned Fund","plannedfund/manage_aid.php",'mid-large')
		})
        $('.edit_data').click(function(){
			uni_modal("Update Planned Fund","plannedfund/manage_aid.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete planned fund amount '<b>"+$(this).attr('data-name')+"</b>' from Planned Fund permanently?","delete_aid",[$(this).attr('data-id')])
		})
		$('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 3 }
            ],
        });
	})

	function delete_aid($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_plannedfund",
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