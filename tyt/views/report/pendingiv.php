<link rel="stylesheet" href="<?=HTTP_PATH?>public/plugins/daterangepicker/daterangepicker-bs3.css">
</head>
  <body class="hold-transition skin-blue sidebar-mini"><div class="wrapper">
    <?php  include VIEWS_PATH.'/include/supper-header.php'; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
       <?php  include VIEWS_PATH.'/include/supper-navi.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Pending IV's
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Reports</li>
             <li class="active">Pending IV's</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php'; ?>
            <div class="row"> 
                  
                <div class="col-xs-12">
                    <div class="box box-lime">
                     
                    <div class="box-body">   
            				<div class="panel ">
            				<table class="table  table-hover ">
            					<tr>
            						<th>No</th>
                        <th>IV No</th>
            						<th>IV To</th>
            						<th>IV Date <i class="fa fa-sort-numeric-asc text-lime"></i></th>
                        <th>Action</th>
            						</tr>
            						<?php $i=0; foreach ($data['ivs'] as $key => $value) { $i++;?>
            						<tr>
            							<td><?=$i?></td>
                          <td><?=$value->iv_no?></td>
            							<td><?=$value->station_name?></td>
            							<td><?=$value->iv_date?></td>  
                          <td>
                           <button  onclick="getContent(<?=$value->iv_header_id?>)" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye"></i></button>
                           <?php if($data['admin']->station_id=='2'){  ?>
<a href="<?=HTTP_PATH.'bmdiv/ivdetails/'.$value->iv_header_id?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="Add IV details" ><i class="fa fa-bars"></i></a>
<?php } ?>
                          </td>           							
            						</tr>	  
                      <?php } ?>
            					</table>
            				</table> 
            		</div> 
            		<?=$data['pagination']?>
                    </div>

                </div>
            </div>
        </div> 

           
            </div>
            
            
            
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      
      <?php  include VIEWS_PATH.'/include/admin-footer.php'; ?>
     <!-- model for show the details -->
      <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title h4 uppercase" id="ModalTitle">Modal title</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xs-6">
                  <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">IV Number <span id="crv_no" class="badge badge-primary badge-pill uppercase"></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">IV Date<span id="crv_date" class="badge badge-primary badge-pill"></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Issue Status<span id="crv_type" class="badge badge-primary badge-pill"></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Authority<span id="crv_remarks" class="badge badge-primary badge-pill"></span></li>
                  </ul>
                </div>
                <div class="col-xs-6">
                  <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">IV to Station<span id="supplier" class="badge badge-primary badge-pill"></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Address<span id="addr" class="badge badge-primary badge-pill"></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Contact<span id="contact" class="badge badge-primary badge-pill"></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Email<span id="email" class="badge badge-primary badge-pill"></span></li>
                  </ul>
                </div>
              </div>
              <hr/>

             <table id="model-table" class="table table-condensed">
               <thead>
                 <tr>
                  <th>No</th>
                        <th >Item Name</th>
                        <th>Bin card No</th>
                        <th class="text-right">Quantity</th>                       
                        <th>Batch No</th>
                        <th>Exp Date</th>
                        <th>Issue Status</th>
                         </tr>
               </thead>
             <tbody>
               
             </tbody>
             </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

 <script type="text/javascript">
   function getContent(iv){
             $('#model-table tbody').empty(); 
             $('#ModalTitle').html('Loading...');
             $('#crv_no').html('Loading...');
             $('#crv_date').html('Loading...');
             $('#crv_type').html('Loading...');
             $('#crv_remarks').html('Loading...');
             $('#supplier').html('Loading...');
             $('#addr').html('Loading...');
             $('#email').html('Loading...'); 
             $('#contact').html('Loading...');          
            $.ajax({url: "<?=HTTP_PATH.'ajax/view_bmd_iv/'?>"+iv, 
                success: function(result){ 
              var i = 0;      
              var status;
              if(result['header']['iv_issue_status']=='1'){ status='Issued';}else{ status='Not Issued'}
             $('#ModalTitle').html('IV Number :<b>'+result['header']['iv_no']+'</b>');
             $('#crv_no').html(result['header']['iv_no']);
             $('#crv_date').html(result['header']['iv_date']);
             $('#crv_type').html(status);
             $('#crv_remarks').html(result['header']['authority']);
             $('#supplier').html(result['station']['station_name']);
             $('#addr').html(result['station']['addr']);
             if(result['station']['tele']!=''&& result['station']['mobile']!=''){
              $('#contact').html(result['station']['tele']+' / '+result['station']['mobile']);
            }else{
              $('#contact').html(result['station']['tele']+''+result['station']['mobile']);
            }             
             $('#email').html(result['station']['email']);              
            if( result.hasOwnProperty('recode')){ 
              $.each( result['recode'], function( key, val ) { i++;
              if(val['issue_status']=='1'){ status='<lable class="badge bg-green">Issued</lable>';}else{ status='<lable class="badge bg-yellow">Not Issued</lable>'}
               $('#model-table tbody').append('<tr><td>'+i+'</td><td>'+val['item_name']+'<td>'+val['item_bin_no']+'</td>'+'</td><td class="text-right">'+val['issue_qty']+' '+val['unit_name']+'</td><td>'+val['batch_no']+'</td><td>'+val['expire_date']+'</td><td>'+status+'</td></tr>');
              }); 
              }else{
                 $('#model-table thead').hide();
                   $('#model-table tbody').append('<tr><th   class="text-center"> Iitems not added!.<br/><a href="<?=HTTP_PATH?>bmdiv/ivdetails/'+result['header']['iv_header_id']+'" class="btn bnt-sm btn-success"> <i class="fa fa-plus"></i> Add Items</a>  </th></tr>');
                }
              }});
          }
        </script>