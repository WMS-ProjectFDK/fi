<!DOCTYPE html>
<html>
  <head>
      <title>MONITORING</title>
      <link rel="icon" type="image/png" href="../../favicon.png">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../../plugins/bootstrap/css/bootstrap.min.css">
        <script src="../../plugins/bootstrap/js/jquery.min.js"></script>
        <script src="../../plugins/bootstrap/js/bootstrap.min.js"></script>

        <link rel="stylesheet" href="../../plugins/bootstrap/css/style.css" type="text/css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="../../plugins/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
        <link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
        <link rel="stylesheet" type="text/css" href="../../css/style.css">
        <link rel="stylesheet" type="text/css" href="../../themes/color.css" />
        <script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
        <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="../../js/datagrid-filter.js"></script>
        <script type="text/javascript" src="../../js/datagrid-detailview.js"></script>
        <script type="text/javascript" src="../../js/jquery.edatagrid.js"></script>
  </head>
  <body style="background-color: #F4F4F4">
    <div class='container carousel'>
      <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="10000">
        <div class="carousel-inner text-center" role="listbox">
          <!-- START LR03#1 -->
          <div class="item active" style="margin-top: 20px;">
            <div class="col-lg-2">
                <div id='demo3_1'></div><hr>
                <img class="img-fluid rounded-circle" src="../../images/user.png">
                <hr>
                <div class="alert alert-info" role="alert" style="width: 200px;">
                  <h4 id="usr_lr03_1"></h4>
                  <hr>
                  <h4 id="nm_lr03_1"></h4>
                </div>
            </div>
            
            <div class="col-lg-2"></div>
            <div class="col-lg-8 pull-right" style="padding: 10px;">
              <div class="alert alert-info"><h2>LR03#1</h2></div>
              
              <div align="left">
                
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/calender.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TANGGAL PRODUKSI</b></h4><h4 id="tgl_prod_lr03_1"></h4>
                  </div>

                  <div class="media-left">
                    <img src="../../images/stack.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>CELL TYPE</b></h4><h4 id="cell_type_lr03_1"></h4>
                  </div>

                  <div class="media-left">
                    <img src="../../images/pallet.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>PALLET NO.</b></h4><h4 id="pallet_no_lr03_1"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/start.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>MULAI</b></h4><h4 id="mulai_lr03_1"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/finish.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>AKHIR</b></h4><h4 id="akhir_lr03_1"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/box.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>QTY / BOX</b></h4><h4 id="qty_box_lr03_1"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/pallet.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>QTY / PALLET</b></h4><h4 id="qty_pallet_lr03_1"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/hitung.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TOTAL QTY (TODAY)</b></h4><h4 id="qty_total_today_lr03_1"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/hitung.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TOTAL QTY (MONTH)</b></h4><h4 id="qty_total_month_lr03_1"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/warning.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TROUBLE</b></h4><h4 id="trouble_lr03_1"></h4>
                  </div>
                </div>

              </div>

            </div>
          </div>
          <!-- END LR03#1 -->

          <!-- START LR03#2 -->
          <div class="item" style="margin-top: 20px;">
            <div class="col-lg-2">
                <div id='demo3_2'></div><hr>
                <img class="img-fluid rounded-circle" src="../../images/user.png">
                <hr>
                <div class="alert alert-success" role="alert" style="width: 200px;">
                  <h4 id="usr_lr03_2"></h4>
                  <hr>
                  <h4 id="nm_lr03_2"></h4>
                </div>
            </div>
            
            <div class="col-lg-2"></div>
            <div class="col-lg-8 pull-right" style="padding: 10px;">
              <div class="alert alert-success"><h2>LR03#2</h2></div>
              
              <div align="left">
                
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/calender.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TANGGAL PRODUKSI</b></h4><h4 id="tgl_prod_lr03_2"></h4>
                  </div>

                  <div class="media-left">
                    <img src="../../images/stack.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>CELL TYPE</b></h4><h4 id="cell_type_lr03_2"></h4>
                  </div>

                  <div class="media-left">
                    <img src="../../images/pallet.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>PALLET NO.</b></h4><h4 id="pallet_no_lr03_2"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/start.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>MULAI</b></h4><h4 id="mulai_lr03_2"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/finish.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>AKHIR</b></h4><h4 id="akhir_lr03_2"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/box.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>QTY / BOX</b></h4><h4 id="qty_box_lr03_2"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/pallet.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>QTY / PALLET</b></h4><h4 id="qty_pallet_lr03_2"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/hitung.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TOTAL QTY (TODAY)</b></h4><h4 id="qty_total_today_lr03_2"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/hitung.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TOTAL QTY (MONTH)</b></h4><h4 id="qty_total_month_lr03_2"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/warning.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TROUBLE</b></h4><h4 id="trouble_lr03_2"></h4>
                  </div>
                </div>

              </div>

            </div>
          </div>
          <!-- END LR03#1 -->

          <!-- START LR06#1 -->
          <div class="item" style="margin-top: 20px;">
            <div class="col-lg-2">
                <div id='demo6_1'></div><hr>
                <img class="img-fluid rounded-circle" src="../../images/user.png">
                <hr>
                <div class="alert alert-warning" role="alert" style="width: 200px;">
                  <h4 id="usr_lr06_1"></h4>
                  <hr>
                  <h4 id="nm_lr06_1"></h4>
                </div>
            </div>
            
            <div class="col-lg-2"></div>
            <div class="col-lg-8 pull-right" style="padding: 10px;">
              <div class="alert alert-warning"><h2>LR06#1</h2></div>
              
              <div align="left">
                
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/calender.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TANGGAL PRODUKSI</b></h4><h4 id="tgl_prod_lr06_1"></h4>
                  </div>

                  <div class="media-left">
                    <img src="../../images/stack.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>CELL TYPE</b></h4><h4 id="cell_type_lr06_1"></h4>
                  </div>

                  <div class="media-left">
                    <img src="../../images/pallet.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>PALLET NO.</b></h4><h4 id="pallet_no_lr06_1"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/start.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>MULAI</b></h4><h4 id="mulai_lr06_1"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/finish.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>AKHIR</b></h4><h4 id="akhir_lr06_1"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/box.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>QTY / BOX</b></h4><h4 id="qty_box_lr06_1"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/pallet.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>QTY / PALLET</b></h4><h4 id="qty_pallet_lr06_1"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/hitung.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TOTAL QTY (TODAY)</b></h4><h4 id="qty_total_today_lr06_1"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/hitung.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TOTAL QTY (MONTH)</b></h4><h4 id="qty_total_month_lr06_1"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/warning.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TROUBLE</b></h4><h4 id="trouble_lr06_1"></h4>
                  </div>
                </div>

              </div>

            </div>
          </div>
          <!-- END LR06#1 -->

          <!-- START LR06#2 -->
          <div class="item" style="margin-top: 20px;">
            <div class="col-lg-2">
                <div id='demo6_2'></div><hr>
                <img class="img-fluid rounded-circle" src="../../images/user.png">
                <hr>
                <div class="alert alert-default" role="alert" style="width: 200px;">
                  <h4 id="usr_lr06_2"></h4>
                  <hr>
                  <h4 id="nm_lr06_2"></h4>
                </div>
            </div>
            
            <div class="col-lg-2"></div>
            <div class="col-lg-8 pull-right" style="padding: 10px;">
              <div class="alert alert-default"><h2>LR06#2</h2></div>
              
              <div align="left">
                
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/calender.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TANGGAL PRODUKSI</b></h4><h4 id="tgl_prod_lr06_2"></h4>
                  </div>

                  <div class="media-left">
                    <img src="../../images/stack.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>CELL TYPE</b></h4><h4 id="cell_type_lr06_2"></h4>
                  </div>

                  <div class="media-left">
                    <img src="../../images/pallet.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>PALLET NO.</b></h4><h4 id="pallet_no_lr06_2"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/start.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>MULAI</b></h4><h4 id="mulai_lr06_2"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/finish.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>AKHIR</b></h4><h4 id="akhir_lr06_2"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/box.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>QTY / BOX</b></h4><h4 id="qty_box_lr06_2"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/pallet.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>QTY / PALLET</b></h4><h4 id="qty_pallet_lr06_2"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/hitung.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TOTAL QTY (TODAY)</b></h4><h4 id="qty_total_today_lr06_2"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/hitung.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TOTAL QTY (MONTH)</b></h4><h4 id="qty_total_month_lr06_2"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/warning.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TROUBLE</b></h4><h4 id="trouble_lr06_2"></h4>
                  </div>
                </div>

              </div>

            </div>
          </div>
          <!-- END LR06#2 -->

          <!-- START LR06#3 -->
          <div class="item" style="margin-top: 20px;">
            <div class="col-lg-2">
                <div id='demo6_3'></div><hr>
                <img class="img-fluid rounded-circle" src="../../images/user.png">
                <hr>
                <div class="alert alert-warning" role="alert" style="width: 200px;">
                  <h4 id="usr_lr06_3"></h4>
                  <hr>
                  <h4 id="nm_lr06_3"></h4>
                </div>
            </div>
            
            <div class="col-lg-2"></div>
            <div class="col-lg-8 pull-right" style="padding: 10px;">
              <div class="alert alert-warning"><h2>LR06#3</h2></div>
              
              <div align="left">
                
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/calender.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TANGGAL PRODUKSI</b></h4><h4 id="tgl_prod_lr06_3"></h4>
                  </div>

                  <div class="media-left">
                    <img src="../../images/stack.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>CELL TYPE</b></h4><h4 id="cell_type_lr06_3"></h4>
                  </div>

                  <div class="media-left">
                    <img src="../../images/pallet.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>PALLET NO.</b></h4><h4 id="pallet_no_lr06_3"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/start.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>MULAI</b></h4><h4 id="mulai_lr06_3"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/finish.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>AKHIR</b></h4><h4 id="akhir_lr06_3"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/box.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>QTY / BOX</b></h4><h4 id="qty_box_lr06_3"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/pallet.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>QTY / PALLET</b></h4><h4 id="qty_pallet_lr06_3"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/hitung.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TOTAL QTY (TODAY)</b></h4><h4 id="qty_total_today_lr06_3"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/hitung.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TOTAL QTY (MONTH)</b></h4><h4 id="qty_total_month_lr06_3"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/warning.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TROUBLE</b></h4><h4 id="trouble_lr06_3"></h4>
                  </div>
                </div>

              </div>

            </div>
          </div>
          <!-- END LR06#2 -->

          <!-- START LR06#4 -->
          <div class="item" style="margin-top: 20px;">
            <div class="col-lg-2">
                <div id='demo6_4'></div><hr>
                <img class="img-fluid rounded-circle" src="../../images/user.png">
                <hr>
                <div class="alert alert-default" role="alert" style="width: 200px;">
                  <h4 id="usr_lr06_4"></h4>
                  <hr>
                  <h4 id="nm_lr06_4"></h4>
                </div>
            </div>
            
            <div class="col-lg-2"></div>
            <div class="col-lg-8 pull-right" style="padding: 10px;">
              <div class="alert alert-succes"><h2>LR06#4(T)</h2></div>
              
              <div align="left">
                
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/calender.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TANGGAL PRODUKSI</b></h4><h4 id="tgl_prod_lr06_4"></h4>
                  </div>

                  <div class="media-left">
                    <img src="../../images/stack.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>CELL TYPE</b></h4><h4 id="cell_type_lr06_4"></h4>
                  </div>

                  <div class="media-left">
                    <img src="../../images/pallet.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>PALLET NO.</b></h4><h4 id="pallet_no_lr06_4"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/start.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>MULAI</b></h4><h4 id="mulai_lr06_4"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/finish.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>AKHIR</b></h4><h4 id="akhir_lr06_4"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/box.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>QTY / BOX</b></h4><h4 id="qty_box_lr06_4"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/pallet.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>QTY / PALLET</b></h4><h4 id="qty_pallet_lr06_4"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/hitung.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TOTAL QTY (TODAY)</b></h4><h4 id="qty_total_today_lr06_4"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/hitung.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TOTAL QTY (MONTH)</b></h4><h4 id="qty_total_month_lr06_4"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/warning.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TROUBLE</b></h4><h4 id="trouble_lr06_4"></h4>
                  </div>
                </div>

              </div>

            </div>
          </div>
          <!-- END LR06#4 -->

          <!-- START LR06#5 -->
          <div class="item" style="margin-top: 20px;">
            <div class="col-lg-2">
                <div id='demo6_5'></div><hr>
                <img class="img-fluid rounded-circle" src="../../images/user.png">
                <hr>
                <div class="alert alert-danger" role="alert" style="width: 200px;">
                  <h4 id="usr_lr06_5"></h4>
                  <hr>
                  <h4 id="nm_lr06_5"></h4>
                </div>
            </div>
            
            <div class="col-lg-2"></div>
            <div class="col-lg-8 pull-right" style="padding: 10px;">
              <div class="alert alert-danger"><h2>LR06#5</h2></div>
              
              <div align="left">
                
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/calender.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TANGGAL PRODUKSI</b></h4><h4 id="tgl_prod_lr06_5"></h4>
                  </div>

                  <div class="media-left">
                    <img src="../../images/stack.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>CELL TYPE</b></h4><h4 id="cell_type_lr06_5"></h4>
                  </div>

                  <div class="media-left">
                    <img src="../../images/pallet.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>PALLET NO.</b></h4><h4 id="pallet_no_lr06_5"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/start.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>MULAI</b></h4><h4 id="mulai_lr06_5"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/finish.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>AKHIR</b></h4><h4 id="akhir_lr06_5"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/box.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>QTY / BOX</b></h4><h4 id="qty_box_lr06_5"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/pallet.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>QTY / PALLET</b></h4><h4 id="qty_pallet_lr06_5"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/hitung.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TOTAL QTY (TODAY)</b></h4><h4 id="qty_total_today_lr06_5"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/hitung.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TOTAL QTY (MONTH)</b></h4><h4 id="qty_total_month_lr06_5"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/warning.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TROUBLE</b></h4><h4 id="trouble_lr06_5"></h4>
                  </div>
                </div>

              </div>

            </div>
          </div>
          <!-- END LR06#5 -->

          <!-- START LR06#6 -->
          <div class="item" style="margin-top: 20px;">
            <div class="col-lg-2">
                <div id='demo6_6'></div><hr>
                <img class="img-fluid rounded-circle" src="../../images/user.png">
                <hr>
                <div class="alert alert-info" role="alert" style="width: 200px;">
                  <h4 id="usr_lr06_6"></h4>
                  <hr>
                  <h4 id="nm_lr06_6"></h4>
                </div>
            </div>
            
            <div class="col-lg-2"></div>
            <div class="col-lg-8 pull-right" style="padding: 10px;">
              <div class="alert alert-info"><h2>LR06#6</h2></div>
              
              <div align="left">
                
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/calender.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TANGGAL PRODUKSI</b></h4><h4 id="tgl_prod_lr06_6"></h4>
                  </div>

                  <div class="media-left">
                    <img src="../../images/stack.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>CELL TYPE</b></h4><h4 id="cell_type_lr06_6"></h4>
                  </div>

                  <div class="media-left">
                    <img src="../../images/pallet.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>PALLET NO.</b></h4><h4 id="pallet_no_lr06_6"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/start.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>MULAI</b></h4><h4 id="mulai_lr06_6"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/finish.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>AKHIR</b></h4><h4 id="akhir_lr06_6"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/box.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>QTY / BOX</b></h4><h4 id="qty_box_lr06_6"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/pallet.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>QTY / PALLET</b></h4><h4 id="qty_pallet_lr06_6"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/hitung.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TOTAL QTY (TODAY)</b></h4><h4 id="qty_total_today_lr06_6"></h4>
                  </div>
                  <div class="media-left">
                    <img src="../../images/hitung.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TOTAL QTY (MONTH)</b></h4><h4 id="qty_total_month_lr06_6"></h4>
                  </div>
                </div>
                <hr>
                <div class="media">
                  <div class="media-left">
                    <img src="../../images/warning.png" class="media-object" style="width:40px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading"><b>TROUBLE</b></h4><h4 id="trouble_lr06_6"></h4>
                  </div>
                </div>

              </div>

            </div>
          </div>
          <!-- END LR06#6 -->

        </div>
      </div>
    </div>
  </body>
  <script type="text/javascript">
    $(function(){
      //var lr1_1 = setInterval(function(){ my_lr1_1() }, 1000);
      var lr3_1 = setInterval(function(){ my_lr3_1() }, 1000);
      var lr3_2 = setInterval(function(){ my_lr3_2() }, 1000);
      var lr6_1 = setInterval(function(){ my_lr6_1() }, 1000);
      var lr6_2 = setInterval(function(){ my_lr6_2() }, 1000);
      var lr6_3 = setInterval(function(){ my_lr6_3() }, 1000);
      var lr6_4 = setInterval(function(){ my_lr6_4() }, 1000);
      var lr6_5 = setInterval(function(){ my_lr6_5() }, 1000);
      var lr6_6 = setInterval(function(){ my_lr6_6() }, 1000);
    });

    var myVar = setInterval(function(){myTimer()},1000);
    function myTimer() {
        $.ajax({
            type: 'GET',
            url: '../../forms/json/json_time_now.php',
            data: { kode:'kode'},
            success: function(data){
                document.getElementById("demo3_1").innerHTML = data[0].kode;
                document.getElementById("demo3_2").innerHTML = data[0].kode;
                document.getElementById("demo6_1").innerHTML = data[0].kode;
                document.getElementById("demo6_2").innerHTML = data[0].kode;
                document.getElementById("demo6_3").innerHTML = data[0].kode;
                document.getElementById("demo6_4").innerHTML = data[0].kode;
                document.getElementById("demo6_5").innerHTML = data[0].kode;
                document.getElementById("demo6_6").innerHTML = data[0].kode;

            }
        });
        
    }

    function my_lr3_1(){
        $.ajax({
            type: 'GET',
            url: 'view_lr3_1.php',
            dataType: 'json',
            success: function(data){
                document.getElementById("usr_lr03_1").innerHTML = data[0].worker_id;
                document.getElementById("nm_lr03_1").innerHTML = data[0].nama;

                document.getElementById("tgl_prod_lr03_1").innerHTML = data[0].tanggal_produksi;
                document.getElementById("cell_type_lr03_1").innerHTML = data[0].cell_type;
                document.getElementById("mulai_lr03_1").innerHTML = data[0].start_date;

                if(data[0].end_date == 'BELUM SELESAI'){
                  document.getElementById("akhir_lr03_1").style.color = "red";
                  document.getElementById("akhir_lr03_1").innerHTML = data[0].end_date;
                }else{
                  document.getElementById("akhir_lr03_1").innerHTML = data[0].end_date;
                }

                document.getElementById("qty_box_lr03_1").innerHTML = data[0].qty_perbox;
                document.getElementById("qty_pallet_lr03_1").innerHTML = data[0].qty_perpallet;
                document.getElementById("pallet_no_lr03_1").innerHTML = data[0].pallet;
                if (data[0].trouble == '') {
                    document.getElementById("trouble_lr03_1").innerHTML = "-";
                }else{
                    document.getElementById("trouble_lr03_1").innerHTML = data[0].trouble;
                }
                document.getElementById("qty_total_today_lr03_1").innerHTML = data[0].qty_harian;
                document.getElementById("qty_total_month_lr03_1").innerHTML = data[0].qty_bulanan;
            }
        });
    }

    function my_lr3_2(){
        $.ajax({
            type: 'GET',
            url: 'view_lr3_2.php',
            dataType: 'json',
            success: function(data){
                document.getElementById("usr_lr03_2").innerHTML = data[0].worker_id;
                document.getElementById("nm_lr03_2").innerHTML = data[0].nama;

                document.getElementById("tgl_prod_lr03_2").innerHTML = data[0].tanggal_produksi;
                document.getElementById("cell_type_lr03_2").innerHTML = data[0].cell_type;
                document.getElementById("mulai_lr03_2").innerHTML = data[0].start_date;
                if(data[0].end_date == 'BELUM SELESAI'){
                  document.getElementById("akhir_lr03_2").style.color = "red";
                  document.getElementById("akhir_lr03_2").innerHTML = data[0].end_date;
                }else{
                  document.getElementById("akhir_lr03_2").innerHTML = data[0].end_date;
                }
                document.getElementById("qty_box_lr03_2").innerHTML = data[0].qty_perbox;
                document.getElementById("qty_pallet_lr03_2").innerHTML = data[0].qty_perpallet;
                document.getElementById("pallet_no_lr03_2").innerHTML = data[0].pallet;
                if (data[0].trouble == '') {
                    document.getElementById("trouble_lr03_2").innerHTML = "-";
                }else{
                    document.getElementById("trouble_lr03_2").innerHTML = data[0].trouble;
                }
                document.getElementById("qty_total_today_lr03_2").innerHTML = data[0].qty_harian;
                document.getElementById("qty_total_month_lr03_2").innerHTML = data[0].qty_bulanan;
            }
        });
    }

    function my_lr6_1(){
        $.ajax({
            type: 'GET',
            url: 'view_lr6_1.php',
            dataType: 'json',
            success: function(data){
                document.getElementById("usr_lr06_1").innerHTML = data[0].worker_id;
                document.getElementById("nm_lr06_1").innerHTML = data[0].nama;

                document.getElementById("tgl_prod_lr06_1").innerHTML = data[0].tanggal_produksi;
                document.getElementById("cell_type_lr06_1").innerHTML = data[0].cell_type;
                document.getElementById("mulai_lr06_1").innerHTML = data[0].start_date;
                if(data[0].end_date == 'BELUM SELESAI'){
                  document.getElementById("akhir_lr06_1").style.color = "red";
                  document.getElementById("akhir_lr06_1").innerHTML = data[0].end_date;
                }else{
                  document.getElementById("akhir_lr06_1").innerHTML = data[0].end_date;
                }
                document.getElementById("qty_box_lr06_1").innerHTML = data[0].qty_perbox;
                document.getElementById("qty_pallet_lr06_1").innerHTML = data[0].qty_perpallet;
                document.getElementById("pallet_no_lr06_1").innerHTML = data[0].pallet;
                if (data[0].trouble == '') {
                    document.getElementById("trouble_lr06_1").innerHTML = "-";
                }else{
                    document.getElementById("trouble_lr06_1").innerHTML = data[0].trouble;
                }
                document.getElementById("qty_total_today_lr06_1").innerHTML = data[0].qty_harian;
                document.getElementById("qty_total_month_lr06_1").innerHTML = data[0].qty_bulanan;
            }
        });
    }

    function my_lr6_2(){
        $.ajax({
            type: 'GET',
            url: 'view_lr6_2.php',
            dataType: 'json',
            success: function(data){
                document.getElementById("usr_lr06_2").innerHTML = data[0].worker_id;
                document.getElementById("nm_lr06_2").innerHTML = data[0].nama;

                document.getElementById("tgl_prod_lr06_2").innerHTML = data[0].tanggal_produksi;
                document.getElementById("cell_type_lr06_2").innerHTML = data[0].cell_type;
                document.getElementById("mulai_lr06_2").innerHTML = data[0].start_date;
                if(data[0].end_date == 'BELUM SELESAI'){
                  document.getElementById("akhir_lr06_2").style.color = "red";
                  document.getElementById("akhir_lr06_2").innerHTML = data[0].end_date;
                }else{
                  document.getElementById("akhir_lr06_2").innerHTML = data[0].end_date;
                }
                document.getElementById("qty_box_lr06_2").innerHTML = data[0].qty_perbox;
                document.getElementById("qty_pallet_lr06_2").innerHTML = data[0].qty_perpallet;
                document.getElementById("pallet_no_lr06_2").innerHTML = data[0].pallet;
                if (data[0].trouble == '') {
                    document.getElementById("trouble_lr06_2").innerHTML = "-";
                }else{
                    document.getElementById("trouble_lr06_2").innerHTML = data[0].trouble;
                }
                document.getElementById("qty_total_today_lr06_2").innerHTML = data[0].qty_harian;
                document.getElementById("qty_total_month_lr06_2").innerHTML = data[0].qty_bulanan;
            }
        });
    }

    function my_lr6_3(){
        $.ajax({
            type: 'GET',
            url: 'view_lr6_3.php',
            dataType: 'json',
            success: function(data){
                document.getElementById("usr_lr06_3").innerHTML = data[0].worker_id;
                document.getElementById("nm_lr06_3").innerHTML = data[0].nama;

                document.getElementById("tgl_prod_lr06_3").innerHTML = data[0].tanggal_produksi;
                document.getElementById("cell_type_lr06_3").innerHTML = data[0].cell_type;
                document.getElementById("mulai_lr06_3").innerHTML = data[0].start_date;
                if(data[0].end_date == 'BELUM SELESAI'){
                  document.getElementById("akhir_lr06_3").style.color = "red";
                  document.getElementById("akhir_lr06_3").innerHTML = data[0].end_date;
                }else{
                  document.getElementById("akhir_lr06_3").innerHTML = data[0].end_date;
                }
                document.getElementById("qty_box_lr06_3").innerHTML = data[0].qty_perbox;
                document.getElementById("qty_pallet_lr06_3").innerHTML = data[0].qty_perpallet;
                document.getElementById("pallet_no_lr06_3").innerHTML = data[0].pallet;
                if (data[0].trouble == '') {
                    document.getElementById("trouble_lr06_3").innerHTML = "-";
                }else{
                    document.getElementById("trouble_lr06_3").innerHTML = data[0].trouble;
                }
                document.getElementById("qty_total_today_lr06_3").innerHTML = data[0].qty_harian;
                document.getElementById("qty_total_month_lr06_3").innerHTML = data[0].qty_bulanan;
            }
        });
    }

    function my_lr6_4(){
        $.ajax({
            type: 'GET',
            url: 'view_lr6_4.php',
            dataType: 'json',
            success: function(data){
                document.getElementById("usr_lr06_4").innerHTML = data[0].worker_id;
                document.getElementById("nm_lr06_4").innerHTML = data[0].nama;

                document.getElementById("tgl_prod_lr06_4").innerHTML = data[0].tanggal_produksi;
                document.getElementById("cell_type_lr06_4").innerHTML = data[0].cell_type;
                document.getElementById("mulai_lr06_4").innerHTML = data[0].start_date;
                if(data[0].end_date == 'BELUM SELESAI'){
                  document.getElementById("akhir_lr06_4").style.color = "red";
                  document.getElementById("akhir_lr06_4").innerHTML = data[0].end_date;
                }else{
                  document.getElementById("akhir_lr06_4").innerHTML = data[0].end_date;
                }
                document.getElementById("qty_box_lr06_4").innerHTML = data[0].qty_perbox;
                document.getElementById("qty_pallet_lr06_4").innerHTML = data[0].qty_perpallet;
                document.getElementById("pallet_no_lr06_4").innerHTML = data[0].pallet;
                if (data[0].trouble == '') {
                    document.getElementById("trouble_lr06_4").innerHTML = "-";
                }else{
                    document.getElementById("trouble_lr06_4").innerHTML = data[0].trouble;
                }
                document.getElementById("qty_total_today_lr06_4").innerHTML = data[0].qty_harian;
                document.getElementById("qty_total_month_lr06_4").innerHTML = data[0].qty_bulanan;
            }
        });
    }

    function my_lr6_5(){
        $.ajax({
            type: 'GET',
            url: 'view_lr6_5.php',
            dataType: 'json',
            success: function(data){
                document.getElementById("usr_lr06_5").innerHTML = data[0].worker_id;
                document.getElementById("nm_lr06_5").innerHTML = data[0].nama;

                document.getElementById("tgl_prod_lr06_5").innerHTML = data[0].tanggal_produksi;
                document.getElementById("cell_type_lr06_5").innerHTML = data[0].cell_type;
                document.getElementById("mulai_lr06_5").innerHTML = data[0].start_date;
                if(data[0].end_date == 'BELUM SELESAI'){
                  document.getElementById("akhir_lr06_5").style.color = "red";
                  document.getElementById("akhir_lr06_5").innerHTML = data[0].end_date;
                }else{
                  document.getElementById("akhir_lr06_5").innerHTML = data[0].end_date;
                }
                document.getElementById("qty_box_lr06_5").innerHTML = data[0].qty_perbox;
                document.getElementById("qty_pallet_lr06_5").innerHTML = data[0].qty_perpallet;
                document.getElementById("pallet_no_lr06_5").innerHTML = data[0].pallet;
                if (data[0].trouble == '') {
                    document.getElementById("trouble_lr06_5").innerHTML = "-";
                }else{
                    document.getElementById("trouble_lr06_5").innerHTML = data[0].trouble;
                }
                document.getElementById("qty_total_today_lr06_5").innerHTML = data[0].qty_harian;
                document.getElementById("qty_total_month_lr06_5").innerHTML = data[0].qty_bulanan;
            }
        });
    }

    function my_lr6_6(){
        $.ajax({
            type: 'GET',
            url: 'view_lr6_6.php',
            dataType: 'json',
            success: function(data){
                document.getElementById("usr_lr06_6").innerHTML = data[0].worker_id;
                document.getElementById("nm_lr06_6").innerHTML = data[0].nama;

                document.getElementById("tgl_prod_lr06_6").innerHTML = data[0].tanggal_produksi;
                document.getElementById("cell_type_lr06_6").innerHTML = data[0].cell_type;
                document.getElementById("mulai_lr06_6").innerHTML = data[0].start_date;
                if(data[0].end_date == 'BELUM SELESAI'){
                  document.getElementById("akhir_lr06_6").style.color = "red";
                  document.getElementById("akhir_lr06_6").innerHTML = data[0].end_date;
                }else{
                  document.getElementById("akhir_lr06_6").innerHTML = data[0].end_date;
                }
                document.getElementById("qty_box_lr06_6").innerHTML = data[0].qty_perbox;
                document.getElementById("qty_pallet_lr06_6").innerHTML = data[0].qty_perpallet;
                document.getElementById("pallet_no_lr06_6").innerHTML = data[0].pallet;
                if (data[0].trouble == '') {
                    document.getElementById("trouble_lr06_6").innerHTML = "-";
                }else{
                    document.getElementById("trouble_lr06_6").innerHTML = data[0].trouble;
                }
                document.getElementById("qty_total_today_lr06_6").innerHTML = data[0].qty_harian;
                document.getElementById("qty_total_month_lr06_6").innerHTML = data[0].qty_bulanan;
            }
        });
    }
  </script>
</html>