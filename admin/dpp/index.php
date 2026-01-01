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
		<h3 class="card-title">List of DPP Amounts</h3>
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
					<col width="10%">
					<col width="37%">
					<col width="18%">
					<col width="18%">
					<col width="13%">
					<col width="7%">
				</colgroup>
				<thead>
					<tr class="bg-gradient-primary text-light">
						<th>#</th>
						<th>Date Created</th>
						<th>Account Name</th>
						<th>Group Name</th>
						<th>Sub-Group Name</th>
						<th style="text-align: right">Allocated Amount</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$val = $_settings->userdata('year_id');
						$qry = $conn->query("SELECT *, dpp.id as id, dpp.date_created as date_created from `dpp` inner join `account_list` on dpp.account_id = account_list.id inner join imed_report_group i on i.id = dpp.group_id inner join imed_report_sub_group ig on ig.id = dpp.sub_group_id where year_id='$val' order by `name` asc ");
						
						$total_dpp = 0;
						while($row = $qry->fetch_assoc()):
						$total_dpp = $total_dpp + $row['dpp_amount'];
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><?php echo date("M d, Y",strtotime($row['date_created'])) ?></td>
							<td class=""><?php echo $row['name'] ?></td>
							<td class=""><?php echo $row['group_name'] ?></td>
							<td class=""><?php echo $row['sub_group_name'] ?></td>
							<td class="" style="text-align: right"><p class="m-0 truncate-1"><?php echo format_num($row['dpp_amount']) ?></p></td>
							
							<td align="center">
								<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
									Action
								<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu" role="menu">
								<div class="dropdown-divider"></div>
								<a class="dropdown-item edit_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-name="<?php echo $row['name'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
								</div>
							</td>
						</tr>
					<?php endwhile; ?>
					<tr class="bg-gradient-primary text-light">
					<td colspan="5">Total</td>
					<td style="text-align: right"><?php echo format_num($total_dpp) ?></td>
					<td></td></tr>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
        $('#create_new').click(function(){
			uni_modal("Add Account-based DPP","dpp/manage_aid.php",'mid-large')
		})
        $('.edit_data').click(function(){
			uni_modal("Update Account-based DPP Details","dpp/manage_aid.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete '<b>"+$(this).attr('data-name')+"</b>' from DPP List permanently?","delete_aid",[$(this).attr('data-id')])
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
			url:_base_url_+"classes/Master.php?f=delete_aid",
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