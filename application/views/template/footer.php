</div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2019 <a href="#">ASN Supplier Admin</a>.</strong> All rights
    reserved.
  </footer>
<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>

<!-- fancy Text -->
<script src="<?php echo base_url(); ?>assets/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<!-- Select2 -->
<script src="<?php echo base_url(); ?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/dataTables.bootstrap.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url(); ?>assets/js/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/js/adminlte.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/draganddrop.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
<!-- fullCalendar -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" ></script>

<script src="<?php echo base_url(); ?>assets/js/moment-with-locales.js"></script>
<script src="<?php echo base_url(); ?>assets/js/custom_chart.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/parsley.min.js"></script>
<script>
  $('#tender').on('change', function() {
     $.ajax({
              url: '<?php echo site_url('import/tender_users');?>',
              type: "POST",
              data:{id:$(this).val()},
              dataType: "json",
              success:function(data) {
                $('select[name="users[]"]').empty();
                $('select[name="users[]"]').append('<option value="">Select</option>');
                    $.each(data, function(key, value) {
                      $('select[name="users[]"]').append('<option value="'+ value.customer_name +'">'+ value.customer_name +'</option>');

                  });
              }
          });
      });
    $( function() {
    $( "#datepicker" ).datepicker();
  } );
  $("#nat").change(function(){
    var nat = $('#nat').val();
    $.ajax({
        url: '<?php echo base_url('message/show_data');?>',
        type: "POST",
        data:{nat:nat},
        dataType: "JSON",
        success:function(data) {
          $("#emp_list").html();
          $("#emp_list").html(data.data);

        }
      });
    if($('#nat').val() != ''){
      $('#emp').prop('disabled',true);
    }else{
      $('#emp').prop('disabled',false);
      
    }
  
}); 
  $("#emp").change(function(){
    var emp = $('#emp').val();
    $.ajax({
        url: '<?php echo base_url('message/show_data');?>',
        type: "POST",
        data:{emp:emp},
        dataType: "JSON",
        success:function(data) {
          $("#emp_list").html();
          $("#emp_list").html(data.data);

        }
      });
    if($('#emp').val() != ''){
      $('#nat').prop('disabled',true);
    }else{
      $('#nat').prop('disabled',false);
    }
  
}); 
  $('.common_select2').select2({
    multiple: true
  });
  $(".fancyboxb").fancybox({
        openEffect  : 'none',
        closeEffect : 'none',

        helpers : {
          title : {
            type : 'over'
          }
        }
      });
   function change_status(id,table_name){
      $.ajax({
        url: '<?php echo base_url('admin/change_status');?>',
        type: "POST",
        data:{id:id,table_name:table_name},
        dataType: "JSON",
        success:function(data) {
            $("#status_"+id).removeClass('bg-green');
            $("#status_"+id).removeClass('bg-red');
            $("#status_"+id).text();
            $("#status_"+id).text(data.status_text);
            $("#status_"+id).addClass(data.class);
          }
      });
  }
  function remove(count,id){
    $.ajax({
          url: '<?php echo base_url('employee/remove_doc');?>',
          type: "POST",
          data:{id:id,count:count},
          dataType: "JSON",
          success:function(data) {
            location.reload();
            //$("#remove_"+count).hide();
            }
        });
  }
   /** add active class and stay opened when selected */
var url = window.location;

// for sidebar menu entirely but not cover treeview
$('ul.sidebar-menu a').filter(function() {
   return this.href == url;
}).parent().addClass('active');

// for treeview
$('ul.treeview-menu a').filter(function() {
   return this.href == url;
}).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');

function check_exist(value,id,table_name,column){
  $.ajax({
      type: "POST",
      url: "<?=base_url();?>admin/check_exist",
      data: {value : value,id:id,table_name:table_name,column:column},
      cache: false,
      dataType:"JSON",
      success: function(data){ 
        if(data.status==0){
           $(".res").text(data.message);
            $('#set_data').val(1);
         }else{
           $(".res").text('');
            $('#set_data').val(0);
         }
        }      
    });
}
$(".onsubmit").on( 'click', function (e) {
    var is_valid = $("#set_data").val();
    if(is_valid==0){
      
    }else{
       e.preventDefault();
    }
   
});

  $(function () {
    $('.common_table').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      "aaSorting": []
    });
    $('.common_from').parsley().on('field:validated', function() {
      var ok = $('.parsley-error').length === 0;
      $('.bs-callout-info').toggleClass('hidden', !ok);
      $('.bs-callout-warning').toggleClass('hidden', ok);
    });
   
});

</script>
</body>
</html>
