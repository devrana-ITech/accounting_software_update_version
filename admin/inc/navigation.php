
<!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-light elevation-4 sidebar-no-expand bg-gradient-navy">
        <!-- Brand Logo -->
        <a href="<?php echo base_url ?>admin" class="brand-link bg-transparent text-sm shadow-sm bg-gradient-navy">
        <img src="<?php echo validate_image($_settings->info('logo'))?>" alt="Store Logo" class="brand-image img-circle elevation-3 bg-black" style="width: 1.8rem;height: 1.8rem;max-height: unset;object-fit:scale-down;object-position:center center">
        <span class="brand-text font-weight-light"><?php echo $_settings->info('short_name') ?></span>
        </a>
        <!-- Sidebar -->
        <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">
          <div class="os-resize-observer-host observed">
            <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
          </div>
          <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
            <div class="os-resize-observer"></div>
          </div>
          <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 646px;"></div>
          <div class="os-padding">
            <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
              <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
                <!-- Sidebar user panel (optional) -->
                <div class="clearfix"></div>
                <!-- Sidebar Menu -->
                <nav class="">
                   <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact nav-flat nav-child-indent nav-collapse-hide-child text-dark" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item dropdown">
                      <a href="./" class="nav-link text-light nav-home">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                          Dashboard
                        </p>
                      </a>
                    </li>
					<li class="nav-header">Entry Modules</li>
					<li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=journalsCreditVoucher" class="nav-link text-light nav-journalsCreditVoucher">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          Credit Voucher Entries
                        </p>
                      </a>
                    </li>
					<li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=journalsDebitVoucher" class="nav-link text-light nav-journalsDebitVoucher">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          Debit Voucher Entries
                        </p>
                      </a>
                    </li>
					<li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=journalsVoucher" class="nav-link text-light nav-journalsVoucher">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          Journal Entries
                        </p>
                      </a>
                    </li>
					<li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=govVoucher" class="nav-link text-light nav-govVoucher">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          GoB Journal Entries
                        </p>
                      </a>
                    </li>
					<li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=aids" class="nav-link text-light nav-aids">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          ADP Entries
                        </p>
                      </a>
                    </li>
					<li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=radp" class="nav-link text-light nav-radp">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          RADP Entries
                        </p>
                      </a>
                    </li>
					<li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=dpp" class="nav-link text-light nav-dpp">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          DPP Allocation Entries
                        </p>
                      </a>
                    </li>
					<li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=cashforecast" class="nav-link text-light nav-cashforecast">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          Cash Forecast Entries
                        </p>
                      </a>
                    </li>
					<li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=plannedfund" class="nav-link text-light nav-plannedfund">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          Planned Fund Entries
                        </p>
                      </a>
                    </li>
					<li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=payee" class="nav-link text-light nav-payee">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          Payee Info Entries
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=journalsDLINonDLI" class="nav-link text-light nav-journalsDLINonDLI">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          DLI-NonDLI Adjustment
                        </p>
                      </a>
                    </li>

                     <li class="nav-header">Procurement</li>

                <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=Procurement/Goods" class="nav-link text-light nav-good">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          Goods
                        </p>
                      </a>
                    </li>

                <li class="nav-item">
                  <a href="<?php echo base_url ?>admin/?page=Procurement/Services" class="nav-link text-light nav-service">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                      Services
                    </p>
                  </a>
                </li>

                <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=Procurement/Works" class="nav-link text-light nav-work">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          Works
                        </p>
                      </a>
                    </li>

                    <li class="nav-header">Reports</li>
					
					<li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=PROCM" class="nav-link text-light nav-procurement">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          Procurement
                        </p>
                      </a>
                    </li>

					<li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=iufr" class="nav-link text-light nav-iufr">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          IUFRs
                        </p>
                      </a>
                    </li>
					
					<li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=report" class="nav-link text-light nav-report">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          Cash Book, Ledger & TB
                        </p>
                      </a>
                    </li>
					<li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=vat" class="nav-link text-light nav-vat">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          VAT & IT
                        </p>
                      </a>
                    </li>
					<li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=summary" class="nav-link text-light nav-summary">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                          Summary Report
                        </p>
                      </a>
                    </li>
 <!--                   <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=reports/working_trial_balance" class="nav-link text-light nav-reports_working_trial_balance">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                          Working Trial Balance
                        </p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=reports/trial_balance" class="nav-link text-light nav-reports_trial_balance">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                          Trial Balance
                        </p>
                      </a>
                    </li> --->
                    <?php if($_settings->userdata('type') == 1): ?>
                    <li class="nav-header">Maintenance</li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=groups" class="nav-link text-light nav-groups">
                        <i class="nav-icon fas fa-th-list"></i>
                        <p>
                          Group List
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=accounts" class="nav-link text-light nav-accounts">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                          Accounts List
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=user/list" class="nav-link text-light nav-user_list">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                          User List
                        </p>
                      </a>
                    </li>
					<li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=packages" class="nav-link text-light nav-packages">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                          Package List
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=system_info" class="nav-link text-light nav-system_info">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                          Settings
                        </p>
                      </a>
                    </li>
                    <?php endif; ?>

                  </ul>
                </nav>
                <!-- /.sidebar-menu -->
              </div>
            </div>
          </div>
          <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
              <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
            </div>
          </div>
          <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
              <div class="os-scrollbar-handle" style="height: 55.017%; transform: translate(0px, 0px);"></div>
            </div>
          </div>
          <div class="os-scrollbar-corner"></div>
        </div>
        <!-- /.sidebar -->
      </aside>
      <script>
        var page;
    $(document).ready(function(){
      page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
      page = page.replace(/\//gi,'_');

      if($('.nav-link.nav-'+page).length > 0){
             $('.nav-link.nav-'+page).addClass('active')
            $('.nav-link.nav-'+page).removeClass('text-light text-dark text-primary')
            $('.nav-link.nav-'+page).addClass('text-reset')
        if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').removeClass('text-light text-dark text-primary')
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('text-reset')
          $('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
        }
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }

      }
      
		$('#receive-nav').click(function(){
      $('#uni_modal').on('shown.bs.modal',function(){
        $('#find-transaction [name="tracking_code"]').focus();
      })
			uni_modal("Enter Tracking Number","transaction/find_transaction.php");
		})
    })
  </script>