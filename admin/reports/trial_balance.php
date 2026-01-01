<?php
function format_num($number){
	$decimals = 0;
	$num_ex = explode('.',$number);
	$decimals = isset($num_ex[1]) ? strlen($num_ex[1]) : 0 ;
	return number_format($number,$decimals);
}
$from = isset($_GET['from']) ? $_GET['from'] : date("Y-m-d",strtotime(date('Y-m-d')." -1 week"));
$to = isset($_GET['to']) ? $_GET['to'] : date("Y-m-d");
?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Trial Balance</h3>
		<div class="card-tools">
		</div>
	</div>
	<div class="card-body">
        <div class="callout border-primary shadow rounded-0">
            <h4 class="text-muted">Filter Date</h4>
            <form action="" id="filter">
            <div class="row align-items-end">
                <div class="col-md-4 form-group">
                    <label for="from" class="control-label">Date From</label>
                    <input type="date" id="from" name="from" value="<?= $from ?>" class="form-control form-control-sm rounded-0">
                </div>
                <div class="col-md-4 form-group">
                    <label for="to" class="control-label">Date To</label>
                    <input type="date" id="to" name="to" value="<?= $to ?>" class="form-control form-control-sm rounded-0">
                </div>
                <div class="col-md-4 form-group">
                    <button class="btn btn-default bg-gradient-navy btn-flat btn-sm"><i class="fa fa-filter"></i> Filter</button>
			        <button class="btn btn-default border btn-flat btn-sm" id="print" type="button"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>
            </form>
        </div>
        <div class="container-fluid" id="outprint">
        <style>
            th.p-0, td.p-0{
                padding: 0 !important;
            }
        </style>
            <h3 class="text-center"><b><?= $_settings->info('name') ?></b></h3>
            <h4 class="text-center"><b>Trial Balance</b></h4>
            <?php if($from == $to): ?>
            <p class="m-0 text-center"><?= date("M d, Y" , strtotime($from)) ?></p>
            <?php else: ?>
            <p class="m-0 text-center"><?= date("M d, Y" , strtotime($from)). ' - '.date("M d, Y" , strtotime($to)) ?></p>
            <?php endif; ?>
            <hr>
			<table class="table table-hover table-bordered">
                <colgroup>
					<col width="10%">
					<col width="7%">
					<col width="83%">
				</colgroup>
				<thead>
					<tr style="background-color: pink;">
						<th style="text-align: center; background-color: pink;">Date</th>
						<th style="text-align: center; background-color: pink;">Voucher Number</th>
						<td class="p-0">
							<div class="d-flex w-100" style="background-color: pink;">
								<div class="col-8 border" style="background-color: pink;"><b>Description</b></div>
								<div class="col-2 border" style="text-align: right;"><b>Debit</b></div>
								<div class="col-2 border" style="text-align: right;"><b>Credit</b></div>
							</div>
						</td>
					</tr>
				</thead>
                <tbody>
					<?php 
					$total_debit = 0;
					$total_credit = 0;
					$journals = $conn->query("SELECT * FROM `journal_entries` where date(journal_date) BETWEEN '{$from}' and '{$to}' order by date(journal_date) asc");
					while($row = $journals->fetch_assoc()):
					?>
					<tr>
						<td class="text-center"><?= date("M d, Y", strtotime($row['journal_date'])) ?></td>
						<td class="" style="text-align: center;"><?= $row['voucher_number'] ?></td>
						<td class="p-0">
							<?php 
							$jitems = $conn->query("SELECT j.*,a.name as account, g.type as `type` FROM `journal_items` j inner join account_list a on j.account_id = a.id inner join group_list g on j.group_id = g.id where j.journal_id = '{$row['id']}'");
							while($rowss = $jitems->fetch_assoc()):
                                if($rowss['type'] == 1)
                                    $total_debit += $rowss['amount'];
                                else
                                    $total_credit += $rowss['amount'];
							?>
							<div class="d-flex w-100">
								<?php  if($rowss['type'] == 1) { ?>
								<div class="col-8 border"><?= $rowss['account'] ?></span></div>
								<?php } else { ?>
									<div class="col-8 border"><span class="pl-4"><?= $rowss['account'] ?></span></div>
								<?php } ?>
								<div class="col-2 border text-right"><?= $rowss['type'] == 1 ? format_num($rowss['amount']) : '' ?></div>
								<div class="col-2 border text-right"><?= $rowss['type'] == 2 ? format_num($rowss['amount']) : '' ?></div>
							</div>
							<?php endwhile; ?>
							<div class="d-flex w-100">
								<div class="col-12 border"><?= $row['description'] ?></div>
							</div>
						</td>
					</tr>
					<?php endwhile; ?>
				</tbody>
                <tfoot>
                    <th colspan="2" class="text-center"></th>
                    <th class="text-right p-0">
                        <div class="d-flex w-100">
                            <div class="col-8 border text-center">Total</span></div>
                            <div class="col-2 border text-right"><?= format_num($total_debit) ?></div>
                            <div class="col-2 border text-right"><?= format_num($total_credit) ?></div>
                        </div>
                    </th>
                </tfoot>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
        $('#filter').submit(function(e){
            e.preventDefault()
            location.href="./?page=reports/trial_balance&"+$(this).serialize();
        })
        $('#print').click(function(){
            start_loader()
            var _h = $('head').clone();
            var _p = $('#outprint').clone();
            var el = $('<div>')
            _h.find('title').text('Trial Balance - Print View')
            _h.append('<style>html,body{ min-height: unset !important;}</style>')
            el.append(_h)
            el.append(_p)
             var nw = window.open("","_blank","width=900,height=700,top=50,left=250")
             nw.document.write(el.html())
             nw.document.close()
             setTimeout(() => {
                 nw.print()
                 setTimeout(() => {
                     nw.close()
                     end_loader()
                 }, 200);
             }, 500);
        })
		
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
	})
	
</script>