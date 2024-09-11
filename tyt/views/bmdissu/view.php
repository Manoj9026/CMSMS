<link rel="stylesheet" href="<?=HTTP_PATH?>public/plugins/jQueryUI/jquery-ui.css">
</head>
  <body class="hold-transition skin-blue sidebar-mini"><div class="wrapper">
    <?php  include VIEWS_PATH.'/include/supper-header.php'; ?>
      
      <!-- Left side column. contains the logo and sidebar -->
       <?php  include VIEWS_PATH.'/include/supper-navi.php'; ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Issue IV Details 
            <small class=""><?=$data['ivheader'][0]->iv_no?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=HTTP_PATH?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?=HTTP_PATH?>bmdiv">IV</a></li>
            <li class="active">Add items to <span class=""><?=$data['ivheader'][0]->iv_no?></span></li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <?php  include VIEWS_PATH.'/include/admin-alert.php';  ?>
            <div class="row"> 
                <div class="col-xs-12">
                    

                   <div class="box box-yellow">
                    <div class="box-header">
                        <a href="<?=HTTP_PATH.'bmdissu'?>" class="btn btn-xs btn-default">  <i class="fa fa-arrow-left"> Back</i></a>
                        <h3 class="box-title">
                          <label class="label label-primary "> IV No : <?=$data['ivheader'][0]->iv_no?></label>
                          <label class="label label-success "><?= $data['station'][0]->station_name; ?></label>
                        </h3> 
                        <span class="pull-right"> <h3 class="box-title"> <label class="label label-info"> Date : <?=$data['ivheader'][0]->iv_date?></label></h3></span>
                    </div> 
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                        <tr>
                        <th>No</th>
                        <th >Item</th>
                        <th class="text-right">Quantity</th>                      
                        <th>Batch NO</th>
                        <th>EXP Date</th> 
                        <th>Issue Date time</th>
                           
                        <th width='20%' class="text-right">Action</th>
                        </tr> 
                        <?php   
                        $total= $i=0; foreach ($data['details'] as $key=>$value ) { $i++;
                         ?>
                          <tr>
                            <td><?=$i?></td>
                            <td><?=$value->item_name.' ('.$value->item_bin_no.') '?></td>
                            <td class="text-right" ><?=$value->issue_qty.' '.$value->unit_name?></td>
                            <td><?=$value->batch_no?></td>
                            <td><?=$value->expire_date?></td>
                            <td><?=($value->issue_status=='0')?'':$value->issu_by ?></td>
                            
                            <td  class="text-right">
                                  <span class="btn btn-sm btn-info" data-html="true" data-toggle="tooltip"  title="<table  class='' >
                                    <tr><td class='text-left'>User</td><td class='text-center'> : </td><td class='text-left'> <?=$value->service_no.' '.$value->user_rank.' '.$value->name?></td></tr>
                                    <tr><td class='text-left'>IP</td><td class='text-center'> : </td><td class='text-left'> <?=$value->latest_ip?></td></tr>
                                    <tr><td class='text-left'>Date Time</td><td class='text-center'> : </td><td class='text-left'> <?=$value->system_dt?></td></tr>
                                  </table>" ><i class="fa fa-info"></i></span>   

                                  <?php if($value->issue_status=='0'){  ?>
                                    <a href="<?=HTTP_PATH.'bmdissu/view/'.$value->iv_header_id.'/?issue='.$value->bmd_iv_detail_id?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="Issue">ISSUE</a>
                                     <?php } ?>  

                            </td>
                          </tr>
                        <?php  } ?> 
                      </table>
                    </div> 


                </div>    
            </div>
            </div>
            
            
            
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->   
      
      <?php  include VIEWS_PATH.'/include/admin-footer.php'; ?>
      <script src="<?=HTTP_PATH?>public/plugins/jQueryUI/jquery-ui.js" type="text/javascript"></script>
       
           



