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
                      <div class="col-lg-4">
                          <a href="<?=HTTP_PATH?>iv/newiv" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> New</a>
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
                        <th>IV to</th>
                        <th>IV Type</th>
                        <th>Remsrks</th> 
                        <th width='15%' class="text-right">Action</th>
                        </tr></thead><tbody>
                        <?php   $i=$data['i']; foreach ($data['details'] as $key=>$value ) { $i++  ?>
                        <tr>
                            <td><?=$i?></td>
                            <td class="uppercase"><?=$value->iv_no?> </td>
                            <td><?=$value->iv_date?></td>  
                            <td><?=$value->iv_to?></td>
                            <td><?=$value->cat_name?></td>
                            <td><?=$value->remarks?></td> 
                            <td class="text-right" >
                              <span class="btn btn-sm btn-info" data-html="true" data-toggle="tooltip"  title="<table  class='uppercase' >
                                    <tr><td class='text-left'>User</td><td class='text-center'> : </td><td class='text-left'> <?=$value->service_no.' '.$value->user_rank.' '.$value->name?></td></tr>
                                    <tr><td class='text-left'>IP</td><td class='text-center'> : </td><td class='text-left'> <?=$value->latest_ip?></td></tr>
                                    <tr><td class='text-left'>Date Time</td><td class='text-center'> : </td><td class='text-left'> <?=$value->system_dt?></td></tr>
                                  </table>" ><i class="fa fa-info"></i></span> 

                            <!--  <a href="<?=HTTP_PATH.'crv/viewcrv/'.$value->crv_id?>" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Add crv details" ><i class="fa fa-eye"></i></a> -->

                            <button  onclick="getContent(<?=$value->iv_id?>)" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye"></i></button>

                              <a href="<?=HTTP_PATH.'iv/ivdetails/'.$value->iv_id?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="Add IV details" ><i class="fa fa-bars"></i></a>
                                <?php if($data['admin']->privilage_id>1){  ?>
                                <a href="<?=HTTP_PATH.'iv/newiv/'.$value->iv_id?>" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i></a>
                                 <?php } ?>
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
              <h4 class="modal-title h4 uppercase" id="ModalTitle">Modal title</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xs-6">
                  <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">IV Number <span id="iv_no" class="badge badge-primary badge-pill uppercase"></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">IV Date<span id="iv_date" class="badge badge-primary badge-pill"></span></li>
                  </ul>
                </div>
                <div class="col-xs-6">
                  <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">IV to <span id="iv_to" class="badge badge-primary badge-pill"></span></li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">Remarks<span id="remarks" class="badge badge-primary badge-pill"></span></li> 
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
             $('#iv_no').html('Loading...');
             $('#iv_date').html('Loading...');
             $('#iv_to').html('Loading...');
             $('#remarks').html('Loading...');
            $.ajax({url: "<?=HTTP_PATH.'ajax/view_iv/'?>"+iv, 
                success: function(result){ 
              var i = 0;      
             
             $('#ModalTitle').html('IV Number :<b>'+result['header']['iv_no']+'</b>');
             $('#iv_no').html(result['header']['iv_no']);
             $('#iv_date').html(result['header']['iv_date']);
             $('#iv_to').html(result['header']['iv_to']);
             $('#remarks').html(result['header']['remarks']); 
                           
            if( result.hasOwnProperty('recode')){ 
              $.each( result['recode'], function( key, val ) { i++;
             
               $('#model-table tbody').append('<tr><td>'+i+'</td><td>'+val['item_name']+'<td>'+val['item_bin_no']+'</td>'+'</td><td class="text-right">'+val['issue_qty']+' '+val['unit_name']+'</td><td>'+val['batch_no']+'</td><td>'+val['expire_date']+'</td></tr>');
              }); 
              }else{
                 $('#model-table thead').hide();
                   $('#model-table tbody').append('<tr><th   class="text-center"> Iitems not added!.<br/><a href="<?=HTTP_PATH?>iv/ivdetails/'+result['header']['iv_id']+'" class="btn bnt-sm btn-success"> <i class="fa fa-plus"></i> Add Items</a>  </th></tr>');
                }
              }});
          }

            $('#table_search').keyup(function(){
           var search = this.value;
           if((search).length>2){
                $.ajax({url: "<?=HTTP_PATH.'ajax/iv_search/'?>"+search,
                    success: function(result){ 
                      $('#result-table tbody').empty();                       
                      var i = 1; var btn ='';     
              $.each( result, function( key, val ) {
               
               $('#result-table tbody').append('<tr><td>'+i+'</td><td class="uppercase" >'+val['iv_no']+'</td><td>'+val['iv_date']+'</td><td>'+val['iv_to']+'</td><td>'+val['cat_name']+'</td><td>'+val['remarks']+'</td><td class="text-right" > <span class="btn btn-sm btn-info" data-html="true" data-toggle="tooltip"  title="<table   class=\'uppercase\' ><tr><td class=\'text-left\'>User</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['service_no']+' '+val['user_rank']+' '+val['name']+'</td></tr><tr><td class=\'text-left\'>IP</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['latest_ip']+'</td></tr><tr><td class=\'text-left\' >Date Time</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['system_dt']+'</td></tr></table>" > <i class="fa fa-info"></i></span> <button  onclick="getContent('+val['iv_id']+')" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye"></i></button> <a href="<?=HTTP_PATH.'iv/ivdetails/'?>'+val['iv_id']+'" class="btn btn-sm btn-success" data-toggle="tooltip" title="Add IV details" ><i class="fa fa-bars"></i></a> <a href="<?=HTTP_PATH.'iv/newiv/'?>'+val['iv_id']+'" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i></a></td></tr>');  i++;
             });  $('.pagi').hide();
             }
           });
               
            }else if((search).length==0){
                  
                $.ajax({url: "<?=HTTP_PATH.'ajax/iv_search/'?>",
                    success: function(result){ 
                      $('#result-table tbody').empty();                       
                      var i = 1; var btn ='';     
              $.each( result, function( key, val ) {

             $('#result-table tbody').append('<tr><td>'+i+'</td><td class="uppercase" >'+val['iv_no']+'</td><td>'+val['iv_date']+'</td><td>'+val['iv_to']+'</td><td>'+val['cat_name']+'</td><td>'+val['remarks']+'</td><td class="text-right" > <span class="btn btn-sm btn-info" data-html="true" data-toggle="tooltip"  title="<table   class=\'uppercase\' ><tr><td class=\'text-left\'>User</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['service_no']+' '+val['user_rank']+' '+val['name']+'</td></tr><tr><td class=\'text-left\'>IP</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['latest_ip']+'</td></tr><tr><td class=\'text-left\' >Date Time</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['system_dt']+'</td></tr></table>" > <i class="fa fa-info"></i></span> <button  onclick="getContent('+val['iv_id']+')" class="btn btn-sm btn-warning" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye"></i></button> <a href="<?=HTTP_PATH.'iv/ivdetails/'?>'+val['iv_id']+'" class="btn btn-sm btn-success" data-toggle="tooltip" title="Add IV details" ><i class="fa fa-bars"></i></a> <a href="<?=HTTP_PATH.'bmdiv/newiv/'?>'+val['iv_id']+'" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i></a></td></tr>'); i++;
             });  $('.pagi').show();
          }

           }); 

                }
            
          });
 </script>
