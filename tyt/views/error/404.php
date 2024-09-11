<link href="<?=HTTP_PATH?>/public/css/main.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
<?php include VIEWS_PATH.'/include/navi.php'; ?>
        <div class="container" style=" padding-top:61px; "  >
            <div id="search-box" class="text-center">
                <h1 class="h1" style="font-size: 100px; color: red"> <i class="fa fa-warning fa-2x"> </i> <br/>404! <br/>Error Not Found..  </h1>
                
                <p class="p-lg">Page that you <strong><?=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']?></strong> Not Available.. </p>
               <p class="p-lg"> <a href="<?=HTTP_PATH?>">Home</a> | <a href="<?=HTTP_PATH.'index/shops'?>">Page List</a> | <a href="<?=HTTP_PATH.'index/contact'?>">Contact</a></p>
                
            </div>
        </div>
        <?php include VIEWS_PATH.'/include/footer.php'; ?>
    </body>
    