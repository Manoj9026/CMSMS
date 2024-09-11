</head>
  <body class="hold-transition skin-blue sidebar-mini"><div class="wrapper">
    <?php  include VIEWS_PATH.'/include/supper-header.php'; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
       <?php  include VIEWS_PATH.'/include/supper-navi.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Measure Units
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">All Measure Units</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php'; ?>
            <div class="row"> 
                <div class="col-xs-12">
                    <div class="box box-blue">
                    <div class="box-header">        
              <h3 class="box-title"><!--All  Measure Units --><small class="pagi"><?=$data['sum']?></small></h3>

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
                          <a href="<?=HTTP_PATH?>measure/newmeasure/" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> New</a>
                      </div>
                    </div>  
                </div>
                    <div class="box-body no-padding">
                        <table class="table table-condensed" id="result-table"><thead>
                        <tr>
                        <th width='30px'>No</th>
                        <th>Unit Name</th> 
                        <th width=20%' class="">Action</th>
                        </tr></thead><tbody>
                        <?php   $i=$data['i']; foreach ($data['measure'] as $key=>$value ) { $i++ ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$value->unit_name?></td> 
                            <td class="">
                                  <?php if($data['admin']->privilage_id>1){  ?>
                                <a href="<?=HTTP_PATH.'measure/newmeasure/'.$value->measure_unit_id?>" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i></a>
                              <?php } if($data['admin']->privilage_id>2){ if($value->active=='1'){?>
                                <a href="<?=HTTP_PATH.'measure/?del='.$value->measure_unit_id?>" class="btn btn-sm btn-danger" onclick="return confirmDelete();" data-toggle="tooltip" title="Delete" ><i class="fa fa-trash"></i></a>
                                 <?php }elseif ($value->active=='0' && $data['admin']->privilage_id>3 ) { ?>
                                    <a href="<?=HTTP_PATH.'measure/?rep='.$value->measure_unit_id?>" class="btn btn-sm btn-success" onclick="return confirm('Do you need to reactivate this item?');" data-toggle="tooltip" title="Reactivate" ><i class="fa fa-undo"></i></a>
                              <?php } } ?>
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
           if((search).length>0){
                $.ajax({url: "<?=HTTP_PATH.'ajax/measure_search/'?>"+search,
                    success: function(result){ 
                      $('#result-table tbody').empty();                       
                      var i = 1;      
              $.each( result, function( key, val ) {
               $('#result-table tbody').append('<tr><td>'+i+'</td><td>'+val['unit_name']+'</td><td class="text-right" > <a href="<?=HTTP_PATH.'measure/newmeasure/'?>'+val['measure_unit_id']+'" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i></a> <a href="<?=HTTP_PATH.'measure/?del='?>'+val['measure_unit_id']+'" class="btn btn-sm btn-danger" onclick="return confirmDelete();" data-toggle="tooltip" title="Delete" ><i class="fa fa-trash"></i></a></td></tr>');  i++;
             });  $('.pagi').hide();
             }
           });
               
            }else if((search).length==0){
                  
                $.ajax({url: "<?=HTTP_PATH.'ajax/measure_search/'?>",
                    success: function(result){ 
                      $('#result-table tbody').empty();                       
                      var i = 1;      
              $.each( result, function( key, val ) {
               $('#result-table tbody').append('<tr><td>'+i+'</td><td>'+val['unit_name']+'</td><td class="text-right" > <a href="<?=HTTP_PATH.'measure/newmeasure/'?>'+val['measure_unit_id']+'" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit" ><i class="fa fa-pencil"></i></a> <a href="<?=HTTP_PATH.'measure/?del='?>'+val['measure_unit_id']+'" class="btn btn-sm btn-danger" onclick="return confirmDelete();" data-toggle="tooltip" title="Delete" ><i class="fa fa-trash"></i></a></td></tr>');  i++;
             });  $('.pagi').show();
          }

           }); 

                }
            
          });
      </script>