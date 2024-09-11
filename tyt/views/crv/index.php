</head>
  <body class="hold-transition skin-blue sidebar-mini"><div class="wrapper">
    <?php  include VIEWS_PATH.'/include/supper-header.php'; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
       <?php  include VIEWS_PATH.'/include/supper-navi.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> CRV
            <small>All</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">All CRV</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php'; ?>
            <div class="row"> 
                <div class="col-xs-12">
                    <div class="box box-green">
                    <div class="box-header">
                        <h3 class="box-title">All CRVs  <small class="pagi"><?=$data['sum']?></small></h3>
                         <div class="box-tools ">
                          <div class="col-lg-8">  
                            <div class="input-group input-group-sm" style="width: 250px;">
                              <input type="text" id="table_search" name="table_search" class="form-control pull-right" placeholder="Search">
                              <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        </div>
                      <div class="col-lg-4">
                          <a href="<?=HTTP_PATH?>crv/newcrv" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> New</a>
                      </div>
                    </div> 
                        
                    <div class="box-body no-padding">
                        <table class="table table-condensed" id="result-table"><thead>
                        <tr>
                        <th width='30px'>No</th>
                        <th>CRV number</th>
                        <th>CRV date</th>
                        <th>Supplier</th>
                        <th>Recieve Type</th>
                        <th>Order No</th> 
                        <th width='18%' class="text-right">Action</th>
                        </tr></thead>
                        <tbody>
                        <?php   $i=$data['i']; foreach ($data['details'] as $key=>$value ) { $i++ ?>
                        <tr>
                            <td><?=$i?></td>
                            <td class=""><?=$value->crv_no?></td>
                            <td><?=$value->crv_date?></td>  
                            <td><?=$value->supp_name?></td>
                            <td><?=$value->crv_type_txt?></td>
                            <td><?=$value->order_no?></td> 
                            <td class="text-right" >
                              <span class="btn btn-sm btn-info" data-html="true" data-toggle="tooltip"  title="<table  class='' >
                                    <tr><td class='text-left'>User</td><td class='text-center'> : </td><td class='text-left'> <?=$value->service_no.' '.$value->user_rank.' '.$value->name?></td></tr>
                                    <tr><td class='text-left'>IP</td><td class='text-center'> : </td><td class='text-left'> <?=$value->latest_ip?></td></tr>
                                    <tr><td class='text-left'>Date Time</td><td class='text-center'> : </td><td class='text-left'> <?=$value->system_dt?></td></tr>
                                  </table>" ><i class="fa fa-info"></i></span> 

                                <a href="<?=HTTP_PATH.'crv/printcrv/'.$value->crv_id?>" target="_new" class="btn btn-sm btn-default" ><i class="fa fa-print"></i></a>

                               <button  onclick="getContent(<?=$value->crv_id?>)" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye"></i></button>

                              <a href="<?=HTTP_PATH.'crv/crvdetails/'.$value->crv_id?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="Add CRV details" ><i class="fa fa-bars"></i></a>

                                <?php if($data['admin']->privilage_id >2 OR 
                                        ($value->latest_user_tbl_id==$data['admin']->user_id AND $value->system_date==date('Y-m-d'))){  ?>
                                <a href="<?=HTTP_PATH.'crv/newcrv/'.$value->crv_id?>" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i></a>
                                <?php } ?> 
                                
                                
                            </td>
                        </tr>
                        <?php  } ?>
                        </tbody>
                    </table> 
                    </div>
                    <div class="box-footer pagi">
                      <?=$data['pagination']?>
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
              <h4 class="modal-title h4 " id="ModalTitle">Modal title</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xs-6">
                  <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">CRV Number <span id="crv_no" class="badge badge-primary badge-pill "></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">CRV Date<span id="crv_date" class="badge badge-primary badge-pill"></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">CRV Type<span id="crv_type" class="badge badge-primary badge-pill"></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Remarks: <span id="crv_remarks" class=""></span></li>
                  </ul>
                </div>
                <div class="col-xs-6">
                  <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">Supplier<span id="supplier" class="badge badge-primary badge-pill"></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center ">Address<span id="addr" class=" " style="max-width:100%; "></span></li>
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
                        <th >Item</th>
                        <th class="text-right">Quantity</th>
                        <th class="text-right">Unit Price <br/>(LKR)</th>
                        <th class="text-right">Amount <br/>(LKR)</th>                        
                        <th>Batch NO</th>
                        <th>Brand</th>
                        <th>MFG Date</th>
                        <th>EXP Date</th> </tr>
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
   function getContent(crv){ 
             $('#model-table tbody').empty(); 
             $('#ModalTitle').html('Loading...');
             $('#crv_no').html('Loading...');
             $('#crv_date').html('Loading...');
             $('#crv_type').html('Loading...');
             $('#crv_remarks').html('Loading...');
             $('#supplier').html('Loading...');
             $('#addr').html('Loading...');
             $('#contact').html('Loading...');
             $('#email').html('Loading...'); 
            $.ajax({url: "<?=HTTP_PATH.'ajax/view_crv/'?>"+crv, 
                cache: false,
                success: function(result){  
              var i = 0;    
             // $('#ModalTitle').html('result');
             $('#ModalTitle').html(result['header']['crv_no']);
             $('#crv_no').html(result['header']['crv_no']);
             $('#crv_date').html(result['header']['crv_date']);
             $('#crv_type').html(result['header']['crv_type']);
             $('#crv_remarks').html(result['header']['remarks']);
             $('#supplier').html(result['supplier']['supp_name']);
             $('#addr').html("  "+result['supplier']['addr']);
              $('#addr').addClass(' text-right');
             if(result['supplier']['tele']!=''&& result['supplier']['mobile']!=''){ 
             $('#contact').html(result['supplier']['tele']+' / '+result['supplier']['mobile']);
             }else{
              $('#contact').html(result['supplier']['tele']+''+result['supplier']['mobile']);
           }
             $('#email').html(result['supplier']['email']);              
             var price = 0;var total =0; var amount =0;var qty = 0
           //  console.log(result['recode'].length); 
              if( result.hasOwnProperty('recode')){ 
              $.each( result['recode'], function( key, val ) { i++;
              //  console.log(val);
               price = val['unit_price'];
               qty = val['qty'];
               amount = price*qty;
               total +=amount;
               qty = Number(qty).toFixed(2).replace(/(\.0+|0+)$/, '');
               $('#model-table tbody').append('<tr><td>'+i+'</td><td>'+val['item_name']+' - '+val['item_bin_no']+' </td><td>'+qty+' '+val['unit_name']+'</td><td class="text-right">'+Number(price)+'</td><td class="text-right">'+Number(amount)+'</td><td>'+val['batch_no']+'</td><td>'+val['brand']+'</td><td>'+val['manufacture_date']+'</td><td>'+val['expire_date']+'</td></tr>');
              });
                $('#model-table tbody').append('<tr><th colspan="4" class="text-right">Total Amount </th><th class="text-right">'+Number(total)+'</th><th colspan="5" class="text-left"> LKR</th></tr>'); $('#model-table thead').show(); }else{
                 $('#model-table thead').hide();
                   $('#model-table tbody').append('<tr><th   class="text-center"> Iitems not added!.<br/><a href="<?=HTTP_PATH?>crv/crvdetails/'+result['header']['crv_id']+'" class="btn bnt-sm btn-success"> <i class="fa fa-plus"></i> Add Items </a> </th></tr>');
                }
              }});
          }


           $('#table_search').keyup(function(){
           var search = this.value;
           if((search).length>2){
                $.ajax({url: "<?=HTTP_PATH.'ajax/crv_search/'?>"+search,
                    success: function(result){ 
                      $('#result-table tbody').empty();                       
                      var i = 1; var btn ='';     
              $.each( result, function( key, val ) {
               
               $('#result-table tbody').append('<tr><td>'+i+'</td><td>'+val['crv_no']+'</td><td>'+val['crv_date']+'</td><td>'+val['supp_name']+'</td><td>'+val['crv_type_txt']+'</td><td>'+val['order_no']+'</td><td class="text-right" > <span class="btn btn-sm btn-info" data-html="true" data-toggle="tooltip"  title="<table   class=\'\' ><tr><td class=\'text-left\'>User</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['service_no']+' '+val['user_rank']+' '+val['name']+'</td></tr><tr><td class=\'text-left\'>IP</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['latest_ip']+'</td></tr><tr><td class=\'text-left\' >Date Time</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['system_dt']+'</td></tr></table>" > <i class="fa fa-info"></i></span> <a href="<?=HTTP_PATH.'crv/printcrv/'?>'+val['crv_id']+'" target="_blank" class="btn btn-sm btn-default" ><i class="fa fa-print"></i></a> <button  onclick="getContent('+val['crv_id']+')" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye"></i></button> <a href="<?=HTTP_PATH.'crv/crvdetails/'?>'+val['crv_id']+'" class="btn btn-sm btn-success" data-toggle="tooltip" title="Add CRV details" ><i class="fa fa-bars"></i></a> <a href="<?=HTTP_PATH.'crv/newcrv/'?>'+val['crv_id']+'" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i></a></td></tr>');  i++;
             });  $('.pagi').hide();
             }
           });
               
            }else if((search).length==0){
                  
                $.ajax({url: "<?=HTTP_PATH.'ajax/crv_search/'?>",
                    success: function(result){ 
                      $('#result-table tbody').empty();                       
                      var i = 1; var btn ='';     
              $.each( result, function( key, val ) {

              $('#result-table tbody').append('<tr><td>'+i+'</td><td>'+val['crv_no']+'</td><td>'+val['crv_date']+'</td><td>'+val['supp_name']+'</td><td>'+val['crv_type_txt']+'</td><td>'+val['order_no']+'</td><td class="text-right" > <span class="btn btn-sm btn-info" data-html="true" data-toggle="tooltip"  title="<table   class=\'\' ><tr><td class=\'text-left\'>User</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['service_no']+' '+val['user_rank']+' '+val['name']+'</td></tr><tr><td class=\'text-left\'>IP</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['latest_ip']+'</td></tr><tr><td class=\'text-left\' >Date Time</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['system_dt']+'</td></tr></table>" > <i class="fa fa-info"></i></span> <a href="<?=HTTP_PATH.'crv/printcrv/'?>'+val['crv_id']+'" target="_blank" class="btn btn-sm btn-default" ><i class="fa fa-print"></i></a> <button  onclick="getContent('+val['crv_id']+')" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye"></i></button> <a href="<?=HTTP_PATH.'crv/crvdetails/'?>'+val['crv_id']+'" class="btn btn-sm btn-success" data-toggle="tooltip" title="Add CRV details" ><i class="fa fa-bars"></i></a> <a href="<?=HTTP_PATH.'crv/newcrv/'?>'+val['crv_id']+'" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i></a></td></tr>'); i++;
             });  $('.pagi').show();
          } 
           });  
                }
            
          });
 </script>
