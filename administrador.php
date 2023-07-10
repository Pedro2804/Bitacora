<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Isna Nur Azis">
    <meta name="keyword" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administradores</title>
    <!-- start: validacion -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.js"></script>
    <!-- end: validacion -->
    <!-- start: Css -->
    <link rel="stylesheet" type="text/css" href="asset/css/bootstrap.min.css">
    
    <!-- plugins -->
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/datatables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/animate.min.css"/>
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/simple-line-icons.css"/>
    <link href="asset/css/style.css" rel="stylesheet">
    
    <!-- end: Css -->
    
    <!-- Favicon -->
    <link rel="shortcut&#x20;icon" href="http://magnogra-cp514.wordpresstemporal.com/wp-content/uploads/2016/01/favicon-1.png" type="image/png" />
    <link rel="icon" href="http://magnogra-cp514.wordpresstemporal.com/wp-content/uploads/2016/01/favicon-1.png" type="image/png" />
    <!-- Apple Touch Icons -->    
    <link rel="apple-touch-icon" href="http://magnogra-cp514.wordpresstemporal.com/wp-content/uploads/2016/01/lmg_logo_iPh-1.png">
    <link rel="apple-touch-icon" sizes="72x72" href="http://magnogra-cp514.wordpresstemporal.com/wp-content/uploads/2016/01/lmg_logo_iPa-1.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="http://magnogra-cp514.wordpresstemporal.com/wp-content/uploads/2016/01/lmg_logo_riPh-1.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="http://magnogra-cp514.wordpresstemporal.com/wp-content/uploads/2016/01/lmg_logo_riPa-1.png" />  
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]--> 

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- start: Javascript -->
    <script src="asset/js/jquery.min.js"></script>
    <script src="asset/js/jquery.ui.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
    
    <!-- plugins -->
    
    <script src="asset/js/plugins/moment.min.js"></script>
    <script src="asset/js/plugins/jquery.datatables.min.js"></script>
    <script src="asset/js/plugins/datatables.bootstrap.min.js"></script>
    <script src="asset/js/plugins/jquery.nicescroll.js"></script>

    <!-- start: main script -->
    
    <!-- custom -->
    <script src="scriprts/administradores.js"></script>
    <!-- end: Javascript -->
</head>
<body id="mimin" class="dashboard">

    <!-- start: Header -->
    <nav class="navbar navbar-default header navbar-fixed-top">
        <div class="col-md-12 nav-wrapper">
        <div class="navbar-header" style="width:100%;">
        <div class="opener-left-menu is-open">
        <span class="top"></span>
        <span class="middle"></span>
        <span class="bottom"></span>
        </div>
        <a href="index.html" class="navbar-brand"> 
        <img src="asset/img/logo_magno.png" width="130px"/>
        </a>

        <ul class="nav navbar-nav navbar-right user-nav">
        <li class="user-name"><span>Admin</span></li>
        <li class="dropdown avatar-dropdown">
        <img src="asset/img/avatar.jpg" class="img-circle avatar" alt="user name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"/>
        <ul class="dropdown-menu user-dropdown">
        <li><a href="#"><span class="fa fa-user"></span> My Profile</a></li>
        <li><a href="#"><span class="fa fa-calendar"></span> My Calendar</a></li>
        <li role="separator" class="divider"></li>
        <li class="more">
        <ul>
        <li><a href=""><span class="fa fa-cogs"></span></a></li>
        <li><a href=""><span class="fa fa-lock"></span></a></li>
        <li><a href=""><span class="fa fa-power-off "></span></a></li>
        </ul>
        </li>
        </ul>
        </li>
        </ul>
        </div>
        </div>
    </nav>
    <!-- end: Header -->
    <div class="container-fluid mimin-wrapper">
    <!-- start:Left Menu -->
    <?php include'menu.php'; ?>
    <!-- end: Left Menu -->
    <!-- start: Content -->
    <div id="content">
        <div class="panel box-shadow-none content-header">
            <div class="panel-body">
            <div class="col-md-12">
            <h3 class="animated fadeInLeft">Administradores</h3>
            <p class="animated fadeInDown">
            Table <span class="fa-angle-right fa"></span> Administradores
            </p>
            </div>
            </div>
        </div>
        <div class="col-md-12 top-20 padding-0">
            <div class="col-md-12">
            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#nuevo_modal">Nuevo Admin</button>
            </div>  
        </div>
        <div class="col-md-12 top-20 padding-0">
            <div class="col-md-12">
            <div class="panel">
            <div class="panel-heading"><h3>Administradores</h3></div>
            <div class="panel-body">
            <div class="responsive-table">
            <table id="datatables-example" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
            <tr>
            <th>Id</th>
            <th>Nombres</th>
            <th>Correo</th>
            <th>Acciones</th>
            </tr>
            </thead>
            </table>
            </div>
            </div>
            </div>
            </div>  
        </div>
        <!-- Modal -->
        <div id="nuevo_modal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Nuevo Admin</h4>
              </div>
              <div class="modal-body">
                <form id="form_nuevo" class="form" data-toggle="validator" role="form">
                    <div class="form-group form-animate-text" style="margin-top:40px !important;">
                        <input id="txt_nombre" type="text" class="form-text" data-error="Campo requerido" required>
                        <span class="bar"></span>
                        <label>Nombres</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group form-animate-text" style="margin-top:40px !important;">
                        <input id="txt_apellido" type="text" class="form-text" data-error="Campo requerido" required>
                        <span class="bar"></span>
                        <label>Apellidos</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group form-animate-text" style="margin-top:40px !important;">
                        <input id="txt_mail" type="email" class="form-text" data-error="Mail no valido" required>
                        <span class="bar"></span>
                        <label>Correo</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group form-animate-text" style="margin-top:40px !important;">
                        <input id="txt_pass" type="password" class="form-text" data-error="Contraseña no valida" required>
                        <span class="bar"></span>
                        <label>Password</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group form-animate-text" style="margin-top:40px !important;">
                        <select id="select_nivel" class="form-text" data-error="Campo requerido" required>
                             <option>Elije uno--</option>
                             <option value="1">Admin</option>
                        </select>
                        <span class="bar"></span>
                        <label>Nivel</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-default" value="Guardar"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                   </form>
                </div>
            </div>
          </div>
        </div>
        <!-- end Modal -->
    </div>
    <!-- end: content -->  
    </div>
    <!-- start: Mobile -->
    <?php include'mobile.php'; ?>
    <!-- end: Mobile -->
    <script src="asset/js/main.js"></script>
</body>
</html>