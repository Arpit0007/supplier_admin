    
<!-- Main content -->
  <section class="content">
  <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-md-12">
         	<div class="box box-primary">
         		<div class="box-header ptbnull">
         			<h3 class="box-title titlefix">Tender : <b><?= $tender_name[0]->name?></b> </h3>
         		</div>
                <div class="box-body">
                    <form class="common_from" action="" name="add_section" method="post" accept-charset="utf-8" enctype="multipart/form-data" data-parsley-validate="">
                        <div class="col-md-12">
                          <div class="col-md-6">
                            <div class="form-group">
                             <label for="exampleInputEmail1">Customer Name </label><small class="req"> *</small>
                             <input type="hidden" name="tender_id" value="<?= $this->uri->segment(3)?>">
                              <input autofocus="" id="section" name="customer_name" placeholder="customer name" type="text" class="form-control" autocomplete="off" required="">
                              <span class="text-danger"><?php echo form_error('customer_name'); ?></span>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <br>
                            <input type="submit" name="add" class="btn btn-info" value="Save">
                          </div>
                        </div>
                    </form>
                </div>
         		<div class="box-body">
                    <span id="message_res"></span>
         			<?php  echo $this->session->flashdata('message'); ?>
         			<div class="table-responsive">
         				<table class="common_table table table-hover">
         					<thead>
	         					<tr>
                                    <th>id</th>
                                    <th width="40%">Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
	         				</thead>  
	         				<tbody>
	         					<?php foreach ($customer_list as $key => $value) { ?>
	         						<tr>
                                        <td><?= $value->id ?></td>
                                        <td>
                                            <form class="common_from" action="<?= base_url()?>tender/update_customer" name="add_section" method="post" accept-charset="utf-8"  data-parsley-validate="">
                                                <input type="hidden" name="id" value="<?= $value->id ?>">
                                                <input type="hidden" name="tender_id"  value="<?= $value->tender_id ?>">
                                                <input type="text" name="customer_name" value="<?= $value->customer_name ?>" class="form-control" >
                                                <input type="submit" name="update" class="btn btn-info" value="Update">
                                            </form>
                                        </td>
                                       
                                        
                                         <td>
                                            <?php
                                             $status = '';
                                             $class = '';
                                            if($value->status==1){
                                                $status = 'Active';
                                                $class = 'bg-green';
                                             }else{
                                                $status = 'Inactive';
                                                $class = 'bg-red';
                                      }
                                     ?>
                                     <p id="status_<?=$value->id;?>" onclick="change_status('<?php echo $value->id; ?>','tender')" class="pointer label <?php echo $class; ?>" title="" data-toggle="tooltip" data-original-title="Change Status"><?=$status?></p>
                                        </td>
                                        
	         							 <td>
                                            <a href="<?=base_url();?>tender/delete/<?=$value->id?>/<?= $value->tender_id ?>" class="btn  btn-xs custom-cross" data-toggle="tooltip" title="" onclick="return confirm('Are you realy want to delete ?');" data-original-title="Delete"><i class="fa fa-remove text-danger"></i>
                                            </a>
                                        </td> 
	         						</tr>
	         						<?php } ?>
	         				</tbody>      				
         				</table>
         			</div>         			
         		</div>	
         	</div>	
         </div>
     </div>