</head>
  <body class="hold-transition skin-blue sidebar-mini"><div class="wrapper">
    <?php  include VIEWS_PATH.'/include/supper-header.php'; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
       <?php  include VIEWS_PATH.'/include/supper-navi.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Available Items
            <small>All</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Reports</li>
             <li class="active">Available Items</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php'; ?>
            <div class="row"> 
                <div class="col-xs-12">

                  <div class="box box-lime">
                    <div class="box-header">
                    </div>
                    <div class="box-body">
                       <form class="form-horizontal" method="post" enctype="multipart/form-data"> 
                       <div class="form-group">
                                <label class="col-sm-3">Location <sup class="text-red">*</sup></label>
                                <div class="col-sm-9">
                                   <select name="station_id"   id="station_id" class="form-control select2">
                                    <option></option>
                                       
                                       <?php foreach ($data['station'] as $key => $value) { ?>
                                       <option value="<?=$value->station_id?>" <?= ($data['station_id']==$value->station_id)?'selected="true"':''; ?> ><?=$value->station_name?></option>
                                       <?php } ?>
                                   </select>
                                  </div>
                                </div>
                    
                    <div class="form-group">
                      <div class="col-sm-3"></div>
                      <div class="col-sm-9">
                         <input type="submit" name="save" id="submit" value="Search" class="btn btn-sm btn-primary" />  
                      </div>                      
                    </div>
                  </form>
                  </div>
                  </div>
                  
                    <div class="box box-lime">
                    <div class="box-header">        
              <h3 class="box-title">  <small class="pagi"><?=$data['sum']?></small></h3>

                   <div class="box-tools ">
                          <div class="col-lg-2">  
                            <a href="<?=HTTP_PATH.'report/pdf_available/?station_id='.$data['station_id']?>" target="_new" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Download PDF as PDF"><i class="fa fa-file-pdf-o"></i></a> 
                        </div>
                      <div class="col-lg-10">
                       <!--  <?=$data['pagination']?> -->
                      </div>
                    </div>   
                </div>

                    <div class="box-body no-padding">
                        <table class="table table-condensed" id="result-table"><thead>
                        <tr>
                        <th width='5%'>No</th>
                        <th width="55%">Item Name <i class="fa fa-sort-alpha-asc text-lime"></i></th> 
                        <th width="10%" class="text-left">Bin Card</th>
                        <th width="15%" class="text-right">Available Quantity</th>
                        <th width="15%" class="text-right">Re-order Level</th> 
                        </tr></thead><tbody>
                        <?php   $i=$data['i']; foreach ($data['item'] as $key=>$value ) { $i++ ?>
                        <tr>
                            <td><?=$i?></td>
                            <td class="text-left"><?=$value->item_name?></td> 
                            <td class="text-left"><?=$value->item_bin_no?></td> 
                            <td class="text-right"><?=$value->qoh.' '.$value->unit_name?></td>
                            <td class="text-right"><?=$value->rol.' '.$value->unit_name?></td>
                            </td>
                        </tr>
                        <?php  } ?></tbody>
                    </table> 
                    </div>
                    <div class="box-footer clearfix pagi" > 
                        <?=$data['pagination']?>
                       </div>
                </div>    
            </div>
            </div>
            
            
            
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <?php  include VIEWS_PATH.'/include/admin-footer.php'; ?> 
      <script type="text/javascript">
        $('.select2').select2();
      </script>