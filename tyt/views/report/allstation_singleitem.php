</head>
  <body class="hold-transition skin-blue sidebar-mini"><div class="wrapper">
    <?php  include VIEWS_PATH.'/include/supper-header.php'; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
       <?php  include VIEWS_PATH.'/include/supper-navi.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Current stock of a item
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Reports</li>
             <li class="active">Current stock of a item</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php'; ?>
            <div class="row"> 
                <div class="col-xs-12">
                    <div class="box box-lime">
                    <div class="box-header">        
              <h3 class="box-title"><!--Curent stock  Report --> </h3>

                    <div class="box-tools ">
                          <div class="col-lg-8">  
                          
                        </div>
                    </div>  
                </div>
                 
                   <form class="form-horizontal" method="post" enctype="multipart/form-data"> 
                    <div class="box-body ">

                         <div class="form-group">
                                <label class="col-sm-3">Item Name<sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                    <select class="select2 form-control" name="item_code" id="item" >
                                      <option></option>
                                      <?php foreach ($data['items'] as $key => $value) { ?>
                                      <option  <?=(input::get('item_code')==$value->item_code)?'selected="true"':''?>  value="<?=$value->item_code?>"><?=$value->item_name.' ('.$value->item_bin_no.')'?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                           <div class="form-group">
                                <label class="col-sm-3"></label>
                                <div class="col-sm-9">
                                  <input type="submit" value="submit" class="btn bnt-sm btn-primary" name="submit">
                                </div>
                                </div> 

                    </div>
                    <div class="box-footer clearfix"> 
                       
                       </div>
                     </form>
                </div>  

                <?php if(isset($data['result'])){ ?>
                <div class="box result  box-lime">
                  <div class="box-header">
                    <div class="box-title"></div>
                  </div>

                  <div class="box-body no-padding "> 
                   <table class="table table-hover">
                     <tr>
                      <th>Location</th>
                      <th class="text-right">Items In Stock</th>
                      <th class="text-right">IV Allocated Qty</th>
                      <th class="text-right">Available</th>
                      <th class="text-right">Re order level</th>
                    </tr>
                    <?php foreach ($data['result'] as $key => $value) { ?>
                    <tr>
                      <td><?=$value['station']?></td>
                      <td class="text-right"><?= $value['qoh'].' '.$value['unit']?></td>
                      <td class="text-right"><?= ($value['issue']!='-')?$value['issue'].' '.$value['unit']:'' ?></td>
                      <td class="text-right"><?= $value['ablq'].' '.$value['unit'] ?></td>
                      <td class="text-right"><?= $value['rol'].' '.$value['unit'] ?></td>
                    </tr>
                  <?php } ?>
                   </table> 

                  </div>

                   <div class="box-footer clearfix"> 
                       
                       </div>
                </div>  
              <?php } ?>
            </div>
            </div>
            
            
            
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      
      <?php  include VIEWS_PATH.'/include/admin-footer.php'; ?>
 
      <script type="text/javascript">
        $('.select2').select2();

     /*   $('#item').change(function(){
           var select = this.value;
           $.ajax({url:"<?=HTTP_PATH.'ajax/item_stock/'?>"+select,
            success:function(result){
              console.log(result['item']['item_name']);
              $('.result .box-title').html(result['item']['item_name']);
              $('#qoh').html(result['item']['qoh']+' '+result['item']['unit']);
               $('#rol').html(result['item']['rol']+' '+result['item']['unit']);
                $('#issue').html('( '+result['item']['issue']+' '+result['item']['unit']+' )');
                 $('#ablq').html(result['item']['ablq']+' '+result['item']['unit']);
                 $('.result').removeClass('hide');
            }})
        }); */


       </script>