</head>
  <body class="hold-transition skin-blue sidebar-mini"><div class="wrapper">
    <?php  include VIEWS_PATH.'/include/supper-header.php'; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
       <?php  include VIEWS_PATH.'/include/supper-navi.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Last CRV Price report
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Reports</li>
             <li class="active">Last CRV price report</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php'; ?>
            <div class="row"> 
            
              <div class="col-sm-12">
                  <div class="box box-lime">
                    <div class="box-header">        
              <h3 class="box-title"><!--Current  stock  Report --> </h3>

                    <div class="box-tools ">
                          <div class="col-lg-8">  
                          
                        </div>
                    </div>  
                </div>
                    <div class="box-body no-padding">
                         <div class="form-group">
                             <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?=HTTP_PATH.'report/crvprice'?>">
                                <label class="col-sm-3">Item Name<sup class="text-red">*</sup></label>
                                <div class="col-sm-8">
                                    <select class="select2 form-control" id="item" name="item_code" >
                                        <option value="all">All</option>
                                      <?php foreach ($data['items'] as $key => $value) { ?>
                                        <option value="<?=$value->item_code?>" <?= (input::get('item_code')==$value->item_code)?'selected="true"':''?> ><?=$value->item_name.' ('.$value->item_bin_no.') '?> </option>
                                    <?php } ?>
                                    </select>
                                </div>
                                
                             </form>
                            </div>
                           
                    </div>
                    <div class="box-footer clearfix"> 
                       
                       </div>
                </div> 
                  
                <div class="box result box-lime">
                  <div class="box-header">

                    <div class="box-title"><small><?=$data['sum']?></small></div>
                    <div class="box-tools ">
                        <?php $link =(input::get('item_code'))?input::get('item_code'):'all';  ?>
                      <a href="<?=HTTP_PATH.'report/pdf_crvprice/?item_code='.$link?>" target="_new" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Download PDF as PDF"><i class="fa fa-file-pdf-o"></i></a>
                     <!-- <?=$data['pagination']?> -->
                      </div>
                  </div>

                  <div class="box-body no-padding ">                    
                   <table class="table table-hover">
                    <thead>
                     <tr>
                       <th>No</th>
                       <th>CRV No</th>
                       <th>CRV Date</th>
                       <th>Item Name <i class="fa fa-sort-alpha-asc text-lime"></i></th>
                       <th class="text-right">Purchase Quantity</th>
                       <th class="text-right">Price</th>
                       <th class="text-right">Supplier Name</th>
                     </tr>
                     </thead>
                     <tbody>
                       <?php $i=$data['i']; foreach ($data['result'] as $key => $value) { $i++;   ?>
                       <tr  >
                         <td><?=$i?></td>
                         <td><?=$value->crv_no?></td>
                         <td><?=$value->crv_date?></td>
                         <td><?=$value->item_name.' <span class="pull-right">'.$value->item_bin_no.'</span>'?></td>
                         
                         <td class="text-right"><?=$value->qty.' '.$value->unit_name?></td>
                         <td class="text-right"><?=$value->unit_price?></td>
                         <td class="text-right"><?=$value->supp_name?></td>
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
            </div>
            </div>
            
            
            
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      
      <?php  include VIEWS_PATH.'/include/admin-footer.php'; ?>
 
      <script>
          $('.select2').select2();
          $('#item').change(function(){
              $('.form-horizontal').submit();
          })
          </script>