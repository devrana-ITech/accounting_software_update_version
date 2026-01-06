
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
		<h3 class="card-title">Procurement Service Entries</h3>
		<div class="card-tools">
			<button class="btn btn-primary btn-flat btn-sm" id="create_new" type="button"><i class="fa fa-pen-square"></i> Procurement Service Entries</button>
		</div>
	</div>
	<div class="card-body">
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
						<th>Package No.</th>
						<th>Package Descrip</th>
						<th>Unit</th>
						<th>Quantity</th>
						<th>Conpletion Date</th>
						<th>Name Address</th>
						<th>Firm Focal</th>
						<th>Paid Date</th>
						<th>Financial Progress</th>
						<th>Physical Progress</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = $conn->query("SELECT * from `services` order by `id` asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><?php echo ucwords($row['package_no']) ?></td>
							<td class=""><?php echo ucwords($row['package_descrip']) ?></td>
							<td class=""><?php echo $row['unit'] ?></td>
							<td class=""><?php echo $row['quantity'] ?></td>
							<td class=""><?php echo $row['conpletion_date'] ?></td>
							<td class=""><?php echo $row['name_address'] ?></td>
							<td class=""><?php echo $row['firm_focal'] ?></td>
							<td class=""><?php echo $row['paid_date'] ?></td>
							<td class=""><?php echo $row['financial_progress'] ?></td>
							<td class=""><?php echo $row['physical_progress'] ?></td>
							
							
							<td align="center">
								<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
									Action
								<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu" role="menu">
								<div class="dropdown-divider"></div>
								<a class="dropdown-item edit_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
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
			uni_modal("New Procurement Service Entry","Procurement/Services/create_services.php",'large')
		})
		$('.edit_data').click(function(){
			uni_modal("Edit Procurement Service Entry","Procurement/Services/create_services.php?id="+$(this).attr('data-id'),"large")
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this procuement Service Entry permanently?","delete_book",[$(this).attr('data-id')])
		})
		
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		
		$('.table').dataTable({
            columnDefs: [
                { orderable: true,  ordering: true, sorting: true, targets: 2 }
            ],
        });
	})
	
	function delete_book($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_services",
			method:"POST",
			data:{id: $id},
			success:function(resp){
				 if(resp == 1){
                    location.reload();
                }else{
                    alert("Failed to save");
                }
                end_loader();
			},
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
		})
	}
</script>