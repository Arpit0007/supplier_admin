<!-- Main content -->
  <section class="content">
  <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Update ASN Supplier</h3>
          </div> 
          <?php  echo $this->session->flashdata('message'); ?>
          <form class="common_from" action="" name="add_section" method="post" accept-charset="utf-8" enctype="multipart/form-data" data-parsley-validate="">
          <div class="box-body">
            <div class="col-md-12">
              <div class="col-md-6">
                <div class="form-group">
                  <input type="hidden" name="id" value="<?= $user_data[0]->id?>">
                 <label for="exampleInputEmail1">Name </label><small class="req"> *</small>
                  <input autofocus="" id="section" name="name" placeholder="Name" type="text" class="form-control" value="<?= $user_data[0]->name?>" autocomplete="off" required="">
                  <span class="text-danger"><?php echo form_error('name'); ?></span>
                </div>
              </div>
              <div class="col-md-6"> 
                <div class="form-group">
                  <label for="city" class=" form-control-label">Email</label>
                   <input autofocus="" id="section" name="email" placeholder="Email" type="text" class="form-control" value="<?php echo $user_data[0]->email; ?>" autocomplete="off">
                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              
              <div class="col-md-6">  
                <div class="form-group">
                  <label for="country" class=" form-control-label">Mobile No</label>
                  <input autofocus="" id="section" name="mobile_no" placeholder="Mobile No" type="text" class="form-control" value="<?php echo $user_data[0]->mobile_no; ?>" autocomplete="off" >
                    <span class="text-danger"><?php echo form_error('mobile_no'); ?></span>
                </div>
              </div>
               <div class="col-md-6"> 
                <div class="form-group">
                  <label for="latitude" class=" form-control-label">Username</label>
                  <input type="text" name="username" class=" form-control" value="<?php echo $user_data[0]->username; ?>" required />
                  <?php echo form_error('username', '<div class="text-danger">', '</div>'); ?>
                </div>
              </div>
            </div>

            <div class="col-md-12">
              
             
              <div class="col-md-6"> 
                <div class="form-group">
                   <label for="logitude" class=" form-control-label">Password</label>
                  <input type="text" name="password" value="<?php echo $user_data[0]->password; ?>" class=" form-control" required>                
                </div>
              </div>
            </div>
            <div class="col-md-12">
              
              <div class="col-md-6"> 
                <div class="form-group">
                  <div class="box-footer">
                    <input type="submit" name="update" class="btn btn-info pull-right" value="Save">
                  </div>
                </div>
              </div>              
            </div>
          </div>
          
          </form>
        </div>
     </div>
  </div>
     