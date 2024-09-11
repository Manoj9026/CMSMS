</head>
  <body class="hold-transition skin-blue sidebar-mini"><div class="wrapper">
    <?php  include VIEWS_PATH.'/include/supper-header.php'; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
       <?php  include VIEWS_PATH.'/include/supper-navi.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Supplier
            <small>All</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?=HTTP_PATH?>supplier">Suppliers</a></li>
            <li class="active">All Suppliers</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php'; ?>
            <div class="row"> 
                <div class="col-xs-12">
                    <div class="box box-teal">
                <div class="box-header">        
              <h3 class="box-title"><!--All Supliers --><small class="pagi"><?=$data['sum']?></small></h3>

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
                          <a href="<?=HTTP_PATH?>supplier/newsupplier" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> New</a>
                      </div>
                    </div>  
                </div>

                    <div class="box-body no-padding">
                        <table class="table table-condensed" id="result-table"><thead>
                        <tr>
                        <th width='30px'>No</th>
                        <th>Supplier Name</th> 
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th width='15%' class="text-right" >Action</th>
                        </tr></thead><tbody>
                        <?php   $i=$data['i']; foreach ($data['item'] as $key=>$value ) { $i++ ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$value->supp_name?></td> 
                            <td><?=($value->tele!=''&& $value->mobile!='')?$value->tele.' / '.$value->mobile:$value->tele.' '.$value->mobile;?> </td> 
                            <td><?=$value->addr?></td> 
                            <td><?=$value->email?></td> 
                            <td class="text-right" >
                                <span class="btn btn-sm btn-info" data-html="true" data-toggle="tooltip"  title="<table  class='uppercase' >
                                    <tr><td class='text-left'>User</td><td class='text-center'> : </td><td class='text-left'> <?=$value->service_no.' '.$value->user_rank.' '.$value->name?></td></tr>
                                    <tr><td class='text-left'>IP</td><td class='text-center'> : </td><td class='text-left'> <?=$value->latest_ip?></td></tr>
                                    <tr><td class='text-left'>Date Time</td><td class='text-center'> : </td><td class='text-left'> <?=$value->system_dt?></td></tr>
                                  </table>" ><i class="fa fa-info"></i></span> 
                                    <?php if($data['admin']->privilage_id>1){  ?>
                                <a href="<?=HTTP_PATH.'supplier/newsupplier/'.$value->supp_id?>" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i></a> 
                                  <?php } if($data['admin']->privilage_id>2){  if($value->active=='1'){  ?>
                                    <a href="<?=HTTP_PATH.'supplier/?del='.$value->supp_id?>" class="btn btn-sm btn-danger" onclick="return confirmDelete();" data-toggle="tooltip" title="Delete" ><i class="fa fa-trash"></i></a>
                              <?php }elseif($value->active=='0' && $data['admin']->privilage_id>3){ ?>
                                <a href="<?=HTTP_PATH.'supplier/?rep='.$value->supp_id?>" class="btn btn-sm btn-success" onclick="return confirm('Do you need to reactivate this item?');" data-toggle="tooltip" title="Reactivate" ><i class="fa fa-undo"></i></a>
                              <?php } }?>
                            </td>
                        </tr>
                        <?php  } ?></tbody>
                    </table> 
                    </div>
                    <div class="box-footer clearfix pagi"> 
                        <?=$data['pagination']?>
                       </div>
                </div>    
            </div>
            </div>
            
            
            
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      
      <?php  include VIEWS_PATH.'/include/admin-footer.php'; ?>
      <script type="text/javascript">
          $('#table_search').keyup(function(){
           var search = this.value;
           if((search).length>2){
                $.ajax({url: "<?=HTTP_PATH.'ajax/supplier_search/'?>"+search,
                    success: function(result){ 
                      $('#result-table tbody').empty();                       
                      var i = 1;      
              $.each( result, function( key, val ) {
               $('#result-table tbody').append('<tr><td>'+i+'</td><td>'+val['supp_name']+'</td><td>'+val['contact']+'</td><td>'+val['addr']+'</td><td>'+val['email']+'</td><td class="text-right" > <span class="btn btn-sm btn-info" data-html="true" data-toggle="tooltip"  title="<table   class=\'uppercase\' ><tr><td class=\'text-left\'>User</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['service_no']+' '+val['user_rank']+' '+val['name']+'</td></tr><tr><td class=\'text-left\'>IP</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['latest_ip']+'</td></tr><tr><td class=\'text-left\' >Date Time</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['system_dt']+'</td></tr></table>" ><i class="fa fa-info"></i></span> <a href="<?=HTTP_PATH.'supplier/newsupplier/'?>'+val['supp_id']+'" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i></a> <a href="<?=HTTP_PATH.'supplier/?del='?>'+val['supp_id']+'" class="btn btn-sm btn-danger" onclick="return confirmDelete();" data-toggle="tooltip" title="Delete" ><i class="fa fa-trash"></i></a></td></tr>');  i++;
             }); $('.pagi').hide();
          }

           });  }else if((search).length==0){
                  
                $.ajax({url: "<?=HTTP_PATH.'ajax/supplier_search/'?>",
                    success: function(result){ 
                      $('#result-table tbody').empty();                       
                      var i = 1;      
              $.each( result, function( key, val ) {
               $('#result-table tbody').append('<tr><td>'+i+'</td><td>'+val['supp_name']+'</td><td>'+val['contact']+'</td><td>'+val['addr']+'</td><td>'+val['email']+'</td><td class="text-right" > <span class="btn btn-sm btn-info" data-html="true" data-toggle="tooltip"  title="<table   class=\'uppercase\' ><tr><td class=\'text-left\'>User</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['service_no']+' '+val['user_rank']+' '+val['name']+'</td></tr><tr><td class=\'text-left\'>IP</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['latest_ip']+'</td></tr><tr><td class=\'text-left\' >Date Time</td><td class=\'text-center\'> : </td><td class=\'text-left\'> '+val['system_dt']+'</td></tr></table>" ><i class="fa fa-info"></i></span> <a href="<?=HTTP_PATH.'supplier/newsupplier/'?>'+val['supp_id']+'" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i></a> <a href="<?=HTTP_PATH.'supplier/?del='?>'+val['supp_id']+'" class="btn btn-sm btn-danger" onclick="return confirmDelete();" data-toggle="tooltip" title="Delete" ><i class="fa fa-trash"></i></a></td></tr>');  i++;
             }); $('.pagi').show();
          }

           });
                }
            
          });
      </script>

 
