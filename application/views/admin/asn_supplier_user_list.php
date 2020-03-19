    
<!-- Main content -->
  <section class="content">
  <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-md-12">
         	<div class="box box-primary">
         		<div class="box-header ptbnull">
         			<h3 class="box-title titlefix">Users List</h3>
         		</div>
         		<div class="box-body">
                    <span id="message_res"></span>
         			<?php  echo $this->session->flashdata('message'); ?>
         			<div class="table-responsive">
         				<table class="common_table table table-hover">
         					<thead>
	         					<tr>
                                    <th>id</th>
                                    <th>First Name</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Action</th>
                                </tr>
	         				</thead>  
	         				<tbody>
	         					<?php foreach ($users as $key => $value) {
                                    
                                    ?>
	         						<tr>
                                        <td><?= $value->id ?></td>
                                        <td><?= $value->name ?></td>
                                        <td><?= $value->username ?></td>
                                        <td><?= $value->password ?></td>
                                        <td>
                                            <a href="<?=base_url();?>asupplier_users/update_supplier_user/<?=$value->id?>" class="btn btn-xs" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-pencil text-danger"></i>
                                            </a>
                                            <a href="<?=base_url();?>admin/delete/<?=$value->id?>/users/asupplier_users" class="btn  btn-xs custom-cross" data-toggle="tooltip" title="" onclick="return confirm('Are you realy want to delete ?');" data-original-title="Delete"><i class="fa fa-remove text-danger"></i>
                                            </a>
                                        </td> 
	         						</tr>
	         						<?php
	         					} ?>
	         				</tbody>      				
         				</table>
         			</div>         			
         		</div>	
         	</div>	
         </div>
     </div>