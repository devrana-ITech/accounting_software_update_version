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
		<h3 class="card-title">List of ADP Entries</h3>
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
					<col width="30%">
					<col width="11%">
					<col width="11%">
					<col width="11%">
					<col width="11%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr class="bg-gradient-primary text-light">
						<th style="text-align: Center">##</th>
						<th>Date Created</th>
						<th>Account Name</th>
						<th style="text-align: right">GoB</th>
						<th style="text-align: right">RPA</th>
						<th style="text-align: right">Expenditure (RPA)</th>
						<th style="text-align: right">Balance (RPA)</th>
						<th style="text-align: center">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$val = $_settings->userdata('year_id');
						//$qry = $conn->query("SELECT *, sum(jt.amount) as Expenditure, aid_amount-sum(jt.amount) as Balance, pr_aid.id as id, pr_aid.date_created as date_created from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id inner join `pr_aid` on pr_aid.account_id = jt.account_id inner join `account_list` on (pr_aid.account_id = account_list.id and pr_aid.account_id = jt.account_id) where je.year_id='$val' and jt.group_id = 1 group by jt.account_id order by `name` asc ");
						
						$qry = $conn->query("SELECT *, pr_aid.id as id, pr_aid.account_id as acc_id, pr_aid.date_created as date_created from `pr_aid` inner join `account_list` where pr_aid.account_id = account_list.id and year_id='$val' order by `name` asc ");
						
						
						$balance = 0;
						$exp = 0;
						$total = 0;
						$total_gov = 0;
						while($row = $qry->fetch_assoc()):
						
						$total = $total + $row['aid_amount'];
						$total_gov = $total_gov + $row['gov_amount'];
						$id_acc = $row['acc_id'];
						
						//$qry_exp = $conn->query("SELECT sum(jt.amount) as Expenditure from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id where je.year_id='$val' and jt.account_id = '$id_acc' and jt.group_id = 1 group by jt.account_id ");
						
						$qry_exp = $conn->query("SELECT (case when sum(case when group_id = 1 then jt.amount end) is null then 0 else sum(case when  group_id = 1 then jt.amount end) end) - (case when sum(case when group_id = 4 then jt.amount end) is null then 0 else sum(case when  group_id = 4 then jt.amount end) end) as Expenditure from `journal_entries` je inner join `journal_items` jt on je.id = jt.journal_id where je.year_id='$val' and jt.account_id = '$id_acc' group by jt.account_id ");
						
												
						
						if ($qry_exp->num_rows > 0){
							while($row_exp = $qry_exp->fetch_assoc())
							{
								$balance = $row['aid_amount'] - $row_exp['Expenditure'];
								$exp = $row_exp['Expenditure'];
							}
						} else {
							$balance = $row['aid_amount'];
							$exp = 0;
						}
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><?php echo date("M d, Y",strtotime($row['date_created'])) ?></td>
							<td class=""><?php echo $row['name'] ?></td>
							<td class="" style="text-align: right"><p class="m-0 truncate-1"><?php if ($row['gov_amount'] <>0) echo number_format($row['gov_amount'],2); else echo "--"; ?></p></td>
							<td class="" style="text-align: right"><p class="m-0 truncate-1"><?php if ($row['aid_amount'] <>0) echo number_format($row['aid_amount'],2); else echo "--"; ?></p></td>
							<td class="" style="text-align: right"><p class="m-0 truncate-1"><?php if ($exp <>0) echo number_format($exp, 2); else echo "--"; ?></p></td>
							<td class="" style="text-align: right"><p class="m-0 truncate-1"><?php if ($balance <>0) echo number_format($balance,2); else echo "--"; ?></p></td>
							
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
					<td colspan="3">Total</td>
					<td style="text-align: right"><?php echo number_format($total_gov, 2) ?></td>
					<td style="text-align: right"><?php echo number_format($total, 2) ?></td>
					<td></td><td></td><td></td></tr>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
        $('#create_new').click(function(){
			uni_modal("Add Account-based Aid","aids/manage_aid.php",'mid-large')
		})
        $('.edit_data').click(function(){
			uni_modal("Update Account-based Details","aids/manage_aid.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete '<b>"+$(this).attr('data-name')+"</b>' from Aid List permanently?","delete_aid",[$(this).attr('data-id')])
		})
		$('.table td, .table th').addClass('py-1 px-2 align-middle')
		
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 6 }
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