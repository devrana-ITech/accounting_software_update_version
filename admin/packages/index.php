<div class="card card-outline card-primary rounded-0 shadow">
	<div class="card-header">
		<h3 class="card-title">List of Packages</h3>
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
					<col width="15%">
					<col width="20%">
					<col width="50%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr class="bg-gradient-primary text-light">
						<th>#</th>
						<th>Date Created</th>
						<th>Package Name</th>
						<th>Package Details</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = $conn->query("SELECT * from `pkg` order by `pack_name` asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
							<td class=""><?php echo $row['pack_name'] ?></td>
							<td class=""><?php echo $row['pack_details'] ?></td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				             <!---       <a class="dropdown-item view_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>  -->
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item edit_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-name="<?php echo $row['pack_name'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
        $('#create_new').click(function(){
			uni_modal("Add New Package","packages/manage_group.php")
		})
        $('.edit_data').click(function(){
			uni_modal("Update Account's Group Details","packages/manage_group.php?id="+$(this).attr('data-id'))
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete '<b>"+$(this).attr('data-name')+"</b>' from Package List permanently?","delete_group",[$(this).attr('data-id')])
		})
		$('.view_data').click(function(){
			uni_modal("Account's Group Details","groups/view_group.php?id="+$(this).attr('data-id'))
		})
		$('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 3 }
            ],
        });
	})
	function delete_group($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_pkg",
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