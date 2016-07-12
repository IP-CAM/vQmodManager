<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <!-- <button type="submit" form="form-html" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button> -->
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-dashboard"></i> <?php echo $text_vqdetails; ?></h3>
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <td>File</td>
                <td>Status</td>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach ($vqmods as $vqmods_key => $vqmods_value) {
              ?>
                <tr>
                  <td><b><?php echo $vqmods_key; ?></b></td>
                  <td>                    
                    <?php 
                      if($vqmods_value) {
                    ?>
                      <span class="btn btn-sm btn-success">Active</span>
                    <?php
                      } else {
                    ?>
                      <span class="btn btn-sm btn-danger">Inactive</span>
                    <?php 
                      }
                    ?>
                  </td>
                  <td></td>
                </tr>
              <?php
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-dashboard"></i> <?php echo $text_allmodules; ?></h3>
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left">VqMod</td>
                <td class="text-left">Status</td>
                <td class="text-left">Action</td>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach ($vxml as $vxml_value) {
              ?>
                  <tr>
                    <td><?php echo $vxml_value['id']; ?></td>
                    <td>                    
                      <?php
                        if($vxml_value['extension'])
                        {
                      ?>
                        <span class="btn btn-sm btn-success">Active</span>
                      <?php
                        } else {
                      ?>  
                      <span class="btn btn-sm btn-danger">Inactive</span>
                      <?php
                        }
                      ?>
                    </td>
                    <td class="text-left"><input type="checkbox" <?php echo $vxml_value['extension'] ? 'checked' : ''; ?> data-toggle="toggle" data-size="mini" data-onstyle="success" data-offstyle="danger" class="togglecontrol" data-file="<?php echo $vxml_value['file']; ?>"></td>
                  </tr>              
              <?php
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>


  </div>
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script></div>

<script type="text/javascript">
  $(document).on('change','.togglecontrol', function(ev){
    var fileName = $(this).data('file');
    $.ajax({
      url: '<?php echo $ed_controller_url; ?>',
      type: 'POST',
      data: {fileName : fileName},
      success: function(data) {
        window.location.reload();
      }
    });
  });
</script>

<?php echo $footer; ?>