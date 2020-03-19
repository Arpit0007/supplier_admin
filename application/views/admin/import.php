    
<!-- Main content -->
  <section class="content">
  <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-md-12">
         	<div class="box box-primary">
         		<div class="box-header ptbnull">
         			<h3 class="box-title titlefix">Import CSV</b> </h3>
         		</div>
                <div class="box-body">
                    <form class="common_from" action="" name="add_section" method="post" accept-charset="utf-8" enctype="multipart/form-data" data-parsley-validate="">
                        <div class="col-md-12">
                          <div class="col-md-6">
                            <div class="form-group">
                             <label for="exampleInputEmail1">Select Tender </label><small class="req"> *</small>
                             <select class="form-control"  name="tender" id="tender">
                                <option value="">Select Tender</option>
                                <?php foreach ($tender_list as $key => $value) { ?>
                                    <option value="<?=$value->id?>"><?=$value->name?></option>
                                <?php } ?>
                             </select>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                             <label for="exampleInputEmail1">Select Users </label><small class="req"> *</small>
                             <select class="form-control common_select2" name="users[]" >
                                 
                             </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="col-md-6">
                            <div class="form-group">
                             <label for="exampleInputEmail1">Import </label><small class="req"> *</small>
                             <input type="file" name="csv_file" >
                            </div>
                          </div>
                          <div class="col-md-6">
                          </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <input type="submit" name="add" class="btn btn-info" value="Import">
                            </div>    
                        </div>    
                          </div>
                        </div>
                    </form>
                </div>	
         	</div>	
         </div>
     </div>