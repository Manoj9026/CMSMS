</head>
  <body class="hold-transition skin-blue sidebar-mini"><div class="wrapper">
    <?php  include VIEWS_PATH.'/include/supper-header.php'; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
       <?php  include VIEWS_PATH.'/include/supper-navi.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Category wise stock
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Reports</li>
             <li class="active">Curent stock of a item</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php'; ?>
            <div class="row"> 
                <div class="col-xs-12">
                    <div class="box box-lime">
                    <div class="box-header">        
              <h3 class="box-title"><!--Curent stock  Report --></h3>

                    <div class="box-tools ">
                          <div class="col-lg-8">  
                          
                        </div>
                    </div>  
                </div>
                    <div class="box-body">
                     <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?=HTTP_PATH.'report/allitems_station'?>">
                      <input type="hidden" value="<?=token::genarate()?>" name="token">
                         <div class="form-group">
                                <label class="col-sm-3">Item Category <sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                   <select name="item_cat_id" id="item_cat_id" class="form-control select2">
                                    <option></option>
                                       <option value="All"  <?= (input::get('item_cat_id')=='All')?'selected="true"':''; ?> >All</option>
                                       <?php foreach ($data['category'] as $key => $value) { 
                                        ?>
                                       <option value="<?=$value->item_cat_id?>" <?= (input::get('item_cat_id')==$value->item_cat_id)?'selected="true"':''; ?> ><?=$value->cat_name?></option>
                                       <?php } ?>
                                   </select>
                                  </div>

                            

                            <div class="form-group">
                              <div class="col-sm-3"></div>
                              <div class="col-sm-9">
                                <div class="radio">
                              <label><input type="radio" <?=(input::get('type')=='0')?'checked="checked"':''?>  name="type" value="0">All Items</label>
                            </div>
                            <div class="radio">
                              <label><input type="radio" <?=(input::get('type')=='1')?'checked="checked"':''?> name="type" value="1">Available Items only</label>
                            </div>
                            </div>
                            </div>
                            </div> 
                      <div class="col-sm-offset-9"> 
                          <input type="submit" name="search" value="search" class="btn btn-sm btn-primary" />  
                      </div>
                    </form>
                    </div>
                    <div class="box-footer clearfix">
                     
                    </div>
                    
                </div>  
              </div>
              <?php if(isset($data['result'])){ ?>
              <div class="col-sm-12">
                <div class="box result box-lime">
                  <div class="box-header">

                    <div class="box-title"><small><?=$data['sum']?></small></div>
                    <div class="box-tools ">
                      <a href="<?=HTTP_PATH.'report/pdf_allitems_station/?item_cat_id='.base64_encode(input::get('item_cat_id')).'&&type='.input::get('type')?>" target="_new" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Download PDF as PDF"><i class="fa fa-file-pdf-o"></i></a>
                     <!-- <?=$data['pagination']?> -->
                      </div>
                  </div>

                  <div class="box-body no-padding ">                    
                   <table class="table table-hover">
                    <thead>
                     <tr>
                       <th>No</th>
                       <th>Item Name</th>
                       <th class="text-right"> stock</th>
                     </tr>
                     </thead>
                     <tbody>
                       <?php $i=$data['i']; foreach ($data['result'] as $key => $value) { $i++; $class = '';/*($value['rol']>=$value['ablq'])?'danger':'';*/ ?>
                       <tr class="<?=$class?>">
                         <td><?=$i?></td>
                         <td><?=$value['item_name']?></td>
                         <td class="text-right"><?=$value['qoh'].' '.$value['unit']?></td>
                       </tr>
                    <?php   } ?>
                     </tbody>
                     <tfoot>
                       <tr>
                        <td colspan="6"><?=$data['pagination']?></td>
                       </tr>
                     </tfoot>
                   </table>
  
                    
                  </div>

                   <div class="box-footer clearfix"> 
                       
                       </div>
                </div>  
            </div><?php } ?>
            </div>
            
            
            
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      
      <?php  include VIEWS_PATH.'/include/admin-footer.php'; ?>
 
      <script type="text/javascript">
        $('.select2').select2();

        $('#item').change(function(){
           var select = this.value;
           $.ajax({url:"<?=HTTP_PATH.'ajax/item_stock/'?>"+select,
            success:function(result){
              console.log(result['item']['item_name']);
              $('.result .box-title').html(result['item']['item_name']);
              $('#qoh').html(result['item']['qoh']+' '+result['item']['unit']);
               $('#rol').html(result['item']['rol']+' '+result['item']['unit']);
                $('#issue').html(result['item']['issue']+' '+result['item']['unit']);
                 $('#ablq').html(result['item']['ablq']+' '+result['item']['unit']);
                 $('.result').removeClass('hide');
            }})
        });


       </script>