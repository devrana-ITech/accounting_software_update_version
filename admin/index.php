<?php require_once('../config.php'); 

$action = !isset($_GET['page']) ? 'none' : strtolower($_GET['page']);

switch ($action) {
	case '2': 
		$_settings->set_userdata('fy','2021-2022');
		$_settings->set_userdata('from_date','2021-07-01');
		$_settings->set_userdata('to_date','2022-06-30');
		$_settings->set_userdata('year_id','2');
		redirect('admin');
		break; 
	case '3':
		$_settings->set_userdata('fy','2022-2023');
		$_settings->set_userdata('from_date','2022-07-01');
		$_settings->set_userdata('to_date','2023-06-30');
		$_settings->set_userdata('year_id','3');
		redirect('admin');
		break;
	case '4':
		$_settings->set_userdata('fy','2023-2024');
		$_settings->set_userdata('from_date','2023-07-01');
		$_settings->set_userdata('to_date','2024-06-30');
		$_settings->set_userdata('year_id','4');
		redirect('admin');
		break;
	case '5':
		$_settings->set_userdata('fy','2024-2025');
		$_settings->set_userdata('from_date','2024-07-01');
		$_settings->set_userdata('to_date','2025-06-30');
		$_settings->set_userdata('year_id','5');
		redirect('admin');
		break;
	case '6':
		$_settings->set_userdata('fy','2025-2026');
		$_settings->set_userdata('from_date','2025-07-01');
		$_settings->set_userdata('to_date','2026-06-30');
		$_settings->set_userdata('year_id','6');
		redirect('admin');
		break; 
	default:
		break;
}

?>

 <!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
<?php require_once('inc/header.php') ?>
  <body class="sidebar-mini layout-fixed control-sidebar-slide-open layout-navbar-fixed sidebar-mini-md sidebar-mini-xs" data-new-gr-c-s-check-loaded="14.991.0" data-gr-ext-installed="" style="height: auto;">
    <div class="wrapper">
     <?php require_once('inc/topBarNav.php') ?>
     <?php require_once('inc/navigation.php') ?>
     <?php if($_settings->chk_flashdata('success')): ?>
      <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
      </script>
      <?php endif;?>    
     <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home';  ?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper pt-3" style="min-height: 567.854px;">
     
        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <?php 
              if(!file_exists($page.".php") && !is_dir($page)){
                  include '404.html';
              }else{
                if(is_dir($page))
                  include $page.'/index.php';
                else
                  include $page.'.php';

              }
            ?>
          </div>
        </section>
        <!-- /.content -->
  <div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered rounded-0" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
      </div>
      <div class="modal-body">
        <div id="delete_content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-flat" id='confirm' onclick="">Continue</button>
        <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Close</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade rounded-0" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered rounded-0" role="document">
      <div class="modal-content rounded-0">
        <div class="modal-header rounded-0">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body rounded-0">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-flat" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
        <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cancel</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade rounded-0" id="uni_modal_right" role='dialog'>
    <div class="modal-dialog modal-full-height  modal-md rounded-0" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="fa fa-arrow-right"></span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade rounded-0" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-md rounded-0" role="document">
      <div class="modal-content">
              <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
              <img src="" alt="">
      </div>
    </div>
  </div>
      </div>
      <!-- /.content-wrapper -->
      <?php require_once('inc/footer.php') ?>
  </body>
</html>
