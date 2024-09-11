</head>
  <body class="hold-transition skin-blue sidebar-mini"><div class="wrapper">
    <?php  include VIEWS_PATH.'/include/supper-header.php'; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
       <?php  include VIEWS_PATH.'/include/supper-navi.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> IV
            <small>All</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">All IV</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php'; ?>
            <div class="row"> 
                <div class="col-xs-12">
                    <div class="box box-yellow">
                    <div class="box-header">
                        <h3 class="box-title"><!--All IVs --><small class="pagi"><?=$data['sum']?></small></h3>
                        <div class="toolbar pull-left">
                            
                        </div>
                         <div class="box-tools ">
                          <div class="col-lg-8">  
                            <div class="input-group input-group-sm" style="width: 250px;">
                              <input type="text" id="table_search" name="table_search" class="form-control pull-right" placeholder="Search">
                              <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        </div>
                      
                    </div> 

                    </div>
                    <div class="box-body no-padding">
                        <table class="table table-condensed" id="result-table">
                          <thead>
                        <tr>

                        <th width='30px'>No</th>
                        <th>IV number</th>
                        <th>IV date</th>
                        <th>Station</th>
                        <th>IV Type</th>
                        <th>Authority</th> 
                        <th width='18%' class="text-right">Action</th>
                        </tr></thead><tbody>
                        <?php   $i=$data['i']; foreach ($data['details'] as $key=>$value ) { $i++  ?>
                        <tr>
                            <td><?=$i?></td>
                            <td class=""><?=$value->iv_no?> <?=($value->cou!='')?'<sup><span data-toggle="tooltip" title="Not issue '.$value->cou.' items" class="badge bg-yellow">'.$value->cou.'</span></sup>':'';?></td>
                            <td><?=$value->iv_date?></td>  
                            <td><?=$value->station_name?></td>
                            <td><?=$value->cat_name?></td>
                            <td><?=$value->authority?></td> 
                            <td class="text-right" >
                              <span class="btn btn-sm btn-info" data-html="true" data-toggle="tooltip"  title="<table  class='' >
                                    <tr><td class='text-left'>User</td><td class='text-center'> : </td><td class='text-left'> <?=$value->service_no.' '.$value->user_rank.' '.$value->name?></td></tr>
                                    <tr><td class='text-left'>IP</td><td class='text-center'> : </td><td class='text-left'> <?=$value->latest_ip?></td></tr>
                                    <tr><td class='text-left'>Date Time</td><td class='text-center'> : </td><td class='text-left'> <?=$value->system_dt?></td></tr>
                                  </table>" ><i class="fa fa-info"></i></span> 
 
                            <button  onclick="getContent(<?=$value->iv_header_id?>)" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye"></i></button>

                              <a href="<?=HTTP_PATH.'bmdissu/view/'.$value->iv_header_id?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="Add IV details" ><i class="fa fa-arrow-right"></i></a>
                       
                            </td>
                        </tr>
                        <?php  } ?></tbody>
                    </table> 
                    </div>
                    <div class="box-footer">
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
                    <li class="list-group-item d-flex justify-content-between align-items-center">IV Number <span id="crv_no" class="badge badge-primary badge-pill "></span></li>
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
                        <th>Issue Date</th>
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
                cache: false,
                success: function(result){ 
                    console.log(result);
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
              if(val['issue_status']=='1'){ status=val['system_dt'];}else{ status='<lable class="badge bg-yellow">Not Issued</lable>'}
               $('#model-table tbody').append('<tr><td>'+i+'</td><td>'+val['item_name']+'<td>'+val['item_bin_no']+'</td>'+'</td><td class="text-right">'+val['issue_qty']+' '+val['unit_name']+'</td><td>'+val['batch_no']+'</td><td>'+val['expire_date']+'</td><td>'+status+'</td></tr>');
              }); 
              }else{
                 $('#model-table thead').hide();
                   $('#model-table tbody').append('<tr><th   class="text-center"> Iitems not added!.  </th></tr>');
                }
              }});
          }

            $('#table_search').keyup(function(){
           var search = this.value;
           if((search).length>2){
                $.ajax({url: "<?=HTTP_PATH.'ajax/bmdiv_search/'?>"+search,
                    success: function(result){ 
                      $('#result-table tbody').empty();                       
                      var i = 1; var btn ='';     
              $.each( result, function( key, val ) {
                var cou ='';
                if(val['cou']!=''){cou = '<sup><span data-toggle="tooltip" title="Not issue '+val['cou']+' items" class="badge bg-yellow">'+val['cou']+'</span></sup>';}
               
               $('#result-table tbody').append('<tr><td>'+i+'</td><td>'+val['iv_no']+cou+'</td><td>'+val['iv_date']+'</td><td>'+val['station_name']+'</td><td>'+val['cat_name']+'</td><td>'+val['authority']+'</td><td class="text-right" > <span class="btn btn-sm btn-info" data-html="true" data-toggle="tooltip"  title="<table   class=\'\' ><tr><td class=\'text-left\'>User</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['service_no']+' '+val['user_rank']+' '+val['name']+'</td></tr><tr><td class=\'text-left\'>IP</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['latest_ip']+'</td></tr><tr><td class=\'text-left\' >Date Time</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['system_dt']+'</td></tr></table>" > <i class="fa fa-info"></i></span>  <button  onclick="getContent('+val['iv_header_id']+')" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye"></i></button> <a href="<?=HTTP_PATH.'bmdissu/view/'?>'+val['iv_header_id']+'" class="btn btn-sm btn-success" data-toggle="tooltip" title="Add IV details" ><i class="fa fa-arrow-right"></i></a> </td></tr>');  i++;
             });  $('.pagi').hide();
             }
           });
               
            }else if((search).length==0){
                  
                $.ajax({url: "<?=HTTP_PATH.'ajax/bmdiv_search/'?>",
                    success: function(result){ 
                      $('#result-table tbody').empty();                       
                      var i = 1; var btn ='';     
              $.each( result, function( key, val ) {
                 var cou ='';
                if(val['cou']!=''){cou = '<sup><span data-toggle="tooltip" title="Not issue '+val['cou']+' items" class="badge bg-yellow">'+val['cou']+'</span></sup>';}

            $('#result-table tbody').append('<tr><td>'+i+'</td><td>'+val['iv_no']+cou+'</td><td>'+val['iv_date']+'</td><td>'+val['station_name']+'</td><td>'+val['cat_name']+'</td><td>'+val['authority']+'</td><td class="text-right" > <span class="btn btn-sm btn-info" data-html="true" data-toggle="tooltip"  title="<table   class=\'\' ><tr><td class=\'text-left\'>User</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['service_no']+' '+val['user_rank']+' '+val['name']+'</td></tr><tr><td class=\'text-left\'>IP</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['latest_ip']+'</td></tr><tr><td class=\'text-left\' >Date Time</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['system_dt']+'</td></tr></table>" > <i class="fa fa-info"></i></span>  <button  onclick="getContent('+val['iv_header_id']+')" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye"></i></button> <a href="<?=HTTP_PATH.'bmdissu/view/'?>'+val['iv_header_id']+'" class="btn btn-sm btn-success" data-toggle="tooltip" title="Add IV details" ><i class="fa fa-arrow-right"></i></a> </td></tr>');  i++;
              });  $('.pagi').show();
          }

           }); 

                }
            
          });
 </script>
