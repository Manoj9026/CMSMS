<aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
                <span class=" bg-blue " style="font-size:25px; display:inline-block; width:40px; padding-top: 5px; height:40px; text-align:center; border-radius:50%; line-height:100%;" ><i class="fa fa-medkit"></i></span>
                            </div>
            <div class="pull-left info">
              <p><?=$data['admin']->user_name?></p>
              
            </div>
          </div> 
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li> 
            <?php if($this->data['admin']->station_id >2){  ?>
            <li class="treeview <?= (App::getRouter()->getController()=='rv')?'active':'';?> ">
              <a href="#" >
                <i class="fa fa-hospital-o text-green"></i>
                <span>RV</span>
                <i class="fa fa-angle-left pull-right text-green"></i>
              </a>
              <ul class="treeview-menu ">
                <li class="<?= ((App::getRouter()->getController()=='rv')&&(App::getRouter()->getAction()=='index'))?'active':'';?>"><a href="<?=HTTP_PATH?>rv"><i class="fa fa-circle-o text-green"></i> All RV</a></li>
                <li class="<?= ((App::getRouter()->getController()=='rv')&&(App::getRouter()->getAction()=='newrv'))?'active':'';?>"><a href="<?=HTTP_PATH?>rv/newrv"><i class="fa fa-circle-o text-green"></i> New RV</a></li>
              </ul>
            </li> 
            <li class="treeview <?= (App::getRouter()->getController()=='iv')?'active':'';?>">
              <a href="#">
                <i class="fa fa-external-link text-yellow"></i>
                <span>Ledger </span>
                <i class="fa fa-angle-left pull-right text-yellow"></i>
              </a>
              <ul class="treeview-menu">
                <li class="<?= ((App::getRouter()->getController()=='iv')&&(App::getRouter()->getAction()=='index'))?'active':'';?>"><a href="<?=HTTP_PATH?>iv"><i class="fa fa-circle-o text-yellow"></i> All IV</a></li>
                <li class="<?= ((App::getRouter()->getController()=='iv')&&(App::getRouter()->getAction()=='newiv'))?'active':'';?>"><a href="<?=HTTP_PATH?>iv/newiv"><i class="fa fa-circle-o text-yellow"></i> New IV</a></li>
              </ul>
            </li>
            <?php  } ?>


            <?php if($this->data['admin']->station_id ==2){ 
                if(in_array($this->data['admin']->section,array(1,4))){ ?>
            <li class="treeview <?= (App::getRouter()->getController()=='crv')?'active':'';?> ">
              <a href="#" >
                <i class="fa fa-hospital-o text-green"></i>
                <span>CRV</span>
                <i class="fa fa-angle-left pull-right text-green"></i>
              </a>
              <ul class="treeview-menu ">
                <li class="<?= ((App::getRouter()->getController()=='crv')&&(App::getRouter()->getAction()=='index'))?'active':'';?>"><a href="<?=HTTP_PATH?>crv"><i class="fa fa-circle-o text-green"></i> All CRV</a></li>
                <li class="<?= ((App::getRouter()->getController()=='crv')&&(App::getRouter()->getAction()=='newcrv'))?'active':'';?>"><a href="<?=HTTP_PATH?>crv/newcrv"><i class="fa fa-circle-o text-green"></i> New CRV</a></li>
              </ul>
            </li>
              <?php  } if(in_array($this->data['admin']->section,array(2,4))){ ?>
            <li class="treeview <?= (App::getRouter()->getController()=='bmdiv')?'active':'';?>">
              <a href="#">
                <i class="fa fa-external-link text-yellow"></i>
                <span>Ledger</span>
                <i class="fa fa-angle-left pull-right text-yellow"></i>
              </a>
              <ul class="treeview-menu">
                <li class="<?= ((App::getRouter()->getController()=='bmdiv')&&(App::getRouter()->getAction()=='index'))?'active':'';?>"><a href="<?=HTTP_PATH?>bmdiv"><i class="fa fa-circle-o text-yellow"></i> All IV</a></li>
                <li class="<?= ((App::getRouter()->getController()=='bmdiv')&&(App::getRouter()->getAction()=='newiv'))?'active':'';?>"><a href="<?=HTTP_PATH?>bmdiv/newiv"><i class="fa fa-circle-o text-yellow"></i> New IV</a></li>
              </ul>
            </li>
             <?php }
             if(in_array($this->data['admin']->section,array(3,4))){ ?>
            <li class="treeview <?= (App::getRouter()->getController()=='bmdissu')?'active':'';?>">
              <a href="#">
                <i class="fa fa-truck text-yellow"></i>
                <span>Issuing Bay</span>
                <i class="fa fa-angle-left pull-right text-yellow"></i>
              </a>
              <ul class="treeview-menu">
                <li class="<?= ((App::getRouter()->getController()=='bmdissu')&&(App::getRouter()->getAction()=='index'))?'active':'';?>"><a href="<?=HTTP_PATH?>bmdissu"><i class="fa fa-circle-o text-yellow"></i> All IV</a></li>
                </ul>
            </li>
             <?php }} ?>
            <?php /* BMD AND DAMPS */ if($this->data['admin']->station_id >0 ){ 
                if(in_array($this->data['admin']->section,array(1,2,4))){ ?>
            <li class="treeview <?= (App::getRouter()->getController()=='report')?'active':'';?>">
              <a href="#">
                <i class="fa fa-file-text-o text-lime"></i>
                <span>Reports</span>
                <i class="fa fa-angle-left pull-right text-lime"></i>
              </a>

          <ul class="treeview-menu">
            <li class="treeview <?=( (App::getRouter()->getController()=='report')&&(in_array(App::getRouter()->getAction(), array('allstation_singleitem','nill','available','rol','expire'))))?'active':''?>">
              <a href="#"><i class="fa fa-circle-o text-lime"></i> General
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right text-lime"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="<?= ((App::getRouter()->getController()=='report')&&(App::getRouter()->getAction()=='allstation_singleitem'))?'active':'';?>"><a href="<?=HTTP_PATH?>report/allstation_singleitem"><i class="fa fa-circle-o text-lime"></i> Current stock of item</a></li>
                <li class="<?= ((App::getRouter()->getController()=='report')&&(App::getRouter()->getAction()=='nill'))?'active':'';?>"><a href="<?=HTTP_PATH?>report/nill"><i class="fa fa-circle-o text-lime"></i> Nill Items</a></li>
                <li class="<?= ((App::getRouter()->getController()=='report')&&(App::getRouter()->getAction()=='available'))?'active':'';?>"><a href="<?=HTTP_PATH?>report/available"><i class="fa fa-circle-o text-lime"></i> Available Items</a></li>
                <li class="<?= ((App::getRouter()->getController()=='report')&&(App::getRouter()->getAction()=='rol'))?'active':'';?>"><a href="<?=HTTP_PATH?>report/rol"><i class="fa fa-circle-o text-lime"></i> Items below ROL</a></li>
                <li class="<?= ((App::getRouter()->getController()=='report')&&(App::getRouter()->getAction()=='expire'))?'active':'';?>"><a href="<?=HTTP_PATH?>report/expire"><i class="fa fa-circle-o text-lime"></i>Nearly Expire</a></li>
              </ul>
            </li>

            <li class="treeview <?=( (App::getRouter()->getController()=='report')&&(in_array(App::getRouter()->getAction(), array('singleitem','crviv','iv','crvprice','allitems','stock','pendingiv'))))?'active':''; ?> ">
              <a href="#"><i class="fa fa-circle-o text-lime"></i> BMD
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right text-lime"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="<?= ((App::getRouter()->getController()=='report')&&(App::getRouter()->getAction()=='singleitem'))?'active':'';?>"><a href="<?=HTTP_PATH?>report/singleitem"><i class="fa fa-circle-o text-lime"></i>Current stock of item</a></li>
                <li class="<?= ((App::getRouter()->getController()=='report')&&(App::getRouter()->getAction()=='crviv'))?'active':'';?>"><a href="<?=HTTP_PATH?>report/crviv"><i class="fa fa-circle-o text-lime"></i>CRV / IV details of item</a></li> 
                <li class="<?= ((App::getRouter()->getController()=='report')&&(App::getRouter()->getAction()=='iv'))?'active':'';?>"><a href="<?=HTTP_PATH?>report/iv"><i class="fa fa-circle-o text-lime"></i>Item issued for a station</a></li> 
                <li class="<?= ((App::getRouter()->getController()=='report')&&(App::getRouter()->getAction()=='crvprice'))?'active':'';?>"><a href="<?=HTTP_PATH?>report/crvprice"><i class="fa fa-circle-o text-lime"></i>Last CRV Price</a></li> 
                <li class="<?= ((App::getRouter()->getController()=='report')&&(App::getRouter()->getAction()=='allitems'))?'active':'';?>"><a href="<?=HTTP_PATH?>report/allitems"><i class="fa fa-circle-o text-lime"></i> Stock Category wise</a></li>
                <li class="<?= ((App::getRouter()->getController()=='report')&&(App::getRouter()->getAction()=='stock'))?'active':'';?>"><a href="<?=HTTP_PATH?>report/stock"><i class="fa fa-circle-o text-lime"></i> Stock </a></li>
                <li class="<?= ((App::getRouter()->getController()=='report')&&(App::getRouter()->getAction()=='pendingiv'))?'active':'';?>"><a href="<?=HTTP_PATH?>report/pendingiv"><i class="fa fa-circle-o text-lime"></i> Pending IVs</a></li>
              </ul>
            </li>  
           
                <?php  if($this->data['admin']->station_id > 2){ /*station reports*/ ?>
                  <li class="treeview <?=( (App::getRouter()->getController()=='report')&&(in_array(App::getRouter()->getAction(), array('single_station','expire_station','allitems_station','rviv'))))?'active':''?>">
              <a href="#"><i class="fa fa-circle-o text-lime"></i> Station
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right text-lime"></i>
                </span>
              </a>
               <ul class="treeview-menu"> 
                     <li class="<?= ((App::getRouter()->getController()=='report')&&(App::getRouter()->getAction()=='single_station'))?'active':'';?>"><a href="<?=HTTP_PATH?>report/single_station"><i class="fa fa-circle-o text-lime"></i>Current stock of item</a></li>
                    
                      <li class="<?= ((App::getRouter()->getController()=='report')&&(App::getRouter()->getAction()=='rviv'))?'active':'';?>"><a href="<?=HTTP_PATH?>report/rviv"><i class="fa fa-circle-o text-lime"></i>RV & IV of item</a></li>
                      <li class="<?= ((App::getRouter()->getController()=='report')&&(App::getRouter()->getAction()=='expire_station'))?'active':'';?>"><a href="<?=HTTP_PATH?>report/expire_station"><i class="fa fa-circle-o text-lime"></i>Nearly Expire</a></li>
                       <li class="<?= ((App::getRouter()->getController()=='report')&&(App::getRouter()->getAction()=='allitems_station'))?'active':'';?>"><a href="<?=HTTP_PATH?>report/allitems_station"><i class="fa fa-circle-o text-lime"></i>Stock Category wise</a></li>
                     </ul>
                     </li>
                  <?php } ?>
                   </ul> 
             
            </li>
            <?php }} if($this->data['admin']->station_id==2){ 
              if(in_array($this->data['admin']->section,array(1,4))){ ?>
            
            <li class="treeview <?= (App::getRouter()->getController()=='item')?'active':'';?>">
              <a href="#">
                <i class="fa fa-cubes text-aqua"></i>
                <span>Items</span>
                <i class="fa fa-angle-left pull-right text-aqua"></i>
              </a>
              <ul class="treeview-menu">
                <li class="<?= ((App::getRouter()->getController()=='item')&&(App::getRouter()->getAction()=='index'))?'active':'';?>"><a href="<?=HTTP_PATH?>item"><i class="fa fa-circle-o text-aqua"></i> All Items</a></li>
                <li class="<?= ((App::getRouter()->getController()=='item')&&(App::getRouter()->getAction()=='newitem'))?'active':'';?>"><a href="<?=HTTP_PATH?>item/newitem"><i class="fa fa-circle-o text-aqua"></i> New Item</a></li>
              </ul>
            </li>
            
            <li class="treeview <?= (App::getRouter()->getController()=='category')?'active':'';?>">
              <a href="#">
                <i class="fa fa-building-o text-green"></i>
                <span>Category</span>
                <i class="fa fa-angle-left pull-right text-green"></i>
              </a>
              <ul class="treeview-menu">
                <li class="<?= ((App::getRouter()->getController()=='category')&&(App::getRouter()->getAction()=='index'))?'active':'';?>"><a href="<?=HTTP_PATH?>category"><i class="fa fa-circle-o text-green"></i> All Categories</a></li>
                  <?php if($data['admin']->privilage_id>1){  ?>
                <li class="<?= ((App::getRouter()->getController()=='category')&&(App::getRouter()->getAction()=='newcategory'))?'active':'';?>"><a href="<?=HTTP_PATH?>category/newcategory"><i class="fa fa-circle-o text-green"></i> New category</a></li>
              <?php } ?>
                
              </ul>
            </li>

             <li class="treeview <?= (App::getRouter()->getController()=='measure')?'active':'';?>">
              <a href="#">
                <i class="fa  fa-bars text-blue"></i>
                <span>Measure Units</span>
                <i class="fa fa-angle-left pull-right text-blue"></i>
              </a>
              <ul class="treeview-menu">                
                <li class="<?= ((App::getRouter()->getController()=='measure')&&(App::getRouter()->getAction()=='index'))?'active':'';?>"><a href="<?=HTTP_PATH?>measure"><i class="fa fa-circle-o text-blue"></i> All Measure Units</a></li>
                  <?php if($data['admin']->privilage_id>1){  ?>
                <li class="<?= ((App::getRouter()->getController()=='measure')&&(App::getRouter()->getAction()=='newmeasure'))?'active':'';?>"><a href="<?=HTTP_PATH?>measure/newmeasure"><i class="fa fa-circle-o text-blue"></i> New Measure Unit</a></li>
              <?php } ?>
              </ul>
            </li>
            

             <li class="treeview <?= (App::getRouter()->getController()=='supplier')?'active':'';?>">
              <a href="#">
                <i class="fa fa-truck text-teal"></i>
                <span>Suppliers</span>
                <i class="fa fa-angle-left pull-right text-teal"></i>
              </a>
              <ul class="treeview-menu">                
                <li class="<?= ((App::getRouter()->getController()=='supplier')&&(App::getRouter()->getAction()=='index'))?'active':'';?>"><a href="<?=HTTP_PATH?>supplier"><i class="fa fa-circle-o text-teal"></i> All Suppliers</a></li>
                <li class="<?= ((App::getRouter()->getController()=='measure')&&(App::getRouter()->getAction()=='newsupplier'))?'active':'';?>"><a href="<?=HTTP_PATH?>supplier/newsupplier"><i class="fa fa-circle-o text-teal"></i> New Supplier</a></li>
              </ul>
            </li>
              <?php } if(in_array($this->data['admin']->section,array(2,4))){  ?>
            <li class="treeview <?= (App::getRouter()->getController()=='station')?'active':'';?>">
              <a href="#">
                <i class="fa fa-home text-olive"></i>
                <span>Stations</span>
                <i class="fa fa-angle-left pull-right text-olive"></i>
              </a>
              <ul class="treeview-menu">                
                <li class="<?= ((App::getRouter()->getController()=='station')&&(App::getRouter()->getAction()=='index'))?'active':'';?>"><a href="<?=HTTP_PATH?>station"><i class="fa fa-circle-o text-olive"></i> All Stations</a></li>
                <li class="<?= ((App::getRouter()->getController()=='station')&&(App::getRouter()->getAction()=='newstation'))?'active':'';?>"><a href="<?=HTTP_PATH?>station/newstation"><i class="fa fa-circle-o text-olive"></i> New Station</a></li>
              </ul>
            </li>
          <?php } } ?>

           <?php if($this->data['admin']->privilage_id >1){ 
               if(in_array($this->data['admin']->section,array(4))){   ?>
            <li class="treeview  <?=(App::getRouter()->getController()=='manageuser')?'active':''?>" >
                <a href="#">
                    <i class="fa fa-users text-maroon"></i>
                    <span>Users</span>
                    <i class="fa fa-angle-left pull-right text-maroon"></i>
                </a>
                <ul class="treeview-menu"> 
                    <li class="<?=((App::getRouter()->getController()=='manageuser')&&(App::getRouter()->getAction()=='index'))?'active':''?>" > <a href="<?=HTTP_PATH.'manageuser'?>" ><i class="fa fa-circle-o text-maroon"></i>All Users</a></li>
                    <?php if($this->data['admin']->privilage_id >1){ ?>
                       <li class="<?=((App::getRouter()->getController()=='manageuser')&&(App::getRouter()->getAction()=='inactive'))?'active':''?>" > <a href="<?=HTTP_PATH.'manageuser/inactive'?>" ><i class="fa fa-circle-o text-maroon"></i>Inactive Users</a></li>
                    <?php } ?>
                 <li class="<?=((App::getRouter()->getController()=='manageuser')&&(App::getRouter()->getAction()=='newuser'))?'active':''?>" > <a href="<?=HTTP_PATH.'manageuser/newuser'?>" ><i class="fa fa-circle-o text-maroon"></i>New User</a></li>
                </ul>
            </li>
           <?php } } ?>

            <?php //change master stock when user level is higher than 3//
            if($this->data['admin']->station_id > 1 && $this->data['admin']->privilage_id>2){ ?>
              
              <li class="treeview  <?=(App::getRouter()->getController()=='settings')?'active':''?>" >
                <a href="#">
                    <i class="fa fa-cogs text-red"></i>
                    <span>Settings</span>
                    <i class="fa fa-angle-left pull-right text-red"></i>
                </a>
                <ul class="treeview-menu"> 
                    <?php if($data['admin']->station_id==2){ /*bmd stock*/  ?>
                    <li class="<?=((App::getRouter()->getController()=='settings')&&(App::getRouter()->getAction()=='changestock'))?'active':''?>" > <a href="<?=HTTP_PATH.'settings/changestock/'?>" ><i class="fa fa-circle-o text-red"></i>Change Masster stok</a></li>
                  <?php }else{ /*stations stock*/ ?>
                     <li class="<?=((App::getRouter()->getController()=='settings')&&(App::getRouter()->getAction()=='changestock_station'))?'active':''?>" > <a href="<?=HTTP_PATH.'settings/changestock_station/'?>" ><i class="fa fa-circle-o text-red"></i>Change Masster stok</a></li>
                   <?php } ?>

                </ul>
            </li>
            <?php } ?>
            
            <li class="treeview  <?=(App::getRouter()->getController()=='cusers')?'active':''?>" >
                <a href="#">
                    <i class="fa fa-user text-orange"></i>
                    <span>Profile</span>
                    <i class="fa fa-angle-left pull-right text-orange"></i>
                </a>
                <ul class="treeview-menu"> 
                    <li class="<?=((App::getRouter()->getController()=='cusers')&&(App::getRouter()->getAction()=='profile'))?'active':''?>" > <a href="<?=HTTP_PATH.'cusers/profile/'?>" ><i class="fa fa-circle-o text-orange"></i>Change password</a></li>
                </ul>
            </li>

            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>