<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
</script>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?php echo $langData['admin-page-msg-1']['KO'];?></title>
        <link href="<?php echo base_url();?>assets/theme/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/theme/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/theme/dist/css/sb-admin-2.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/theme/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="<?php echo base_url();?>assets/css/main.css" rel="stylesheet" type="text/css">
		<link href="<?php echo base_url();?>assets/admin/css/signin.css" rel="stylesheet" type="text/css">
        <link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" /> 
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading" style="background-color:#006ac8">
                            <p style="text-align: center;margin: 20px;">
                                <a href="<?php echo base_url();?>">
                                    <img src="<?php echo base_url();?>assets/image/main-logo.png" style="width:100%;">
                                </a>
                            </p>
                        </div>
                        <div class="panel-heading" style="text-align: center;">
                            <h3 class="panel-title"><?php echo $langData['admin-page-msg-104']['KO'];?></h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group" id="submit_error_alert_content">
                                <div class="alert alert-danger" id="submit_error_alert"></div>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="<?php echo $langData['admin-page-msg-105']['KO'];?>" id="username" type="text" autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="<?php echo $langData['admin-page-msg-106']['KO'];?>" id="password" type="password" value="">
                            </div>
                            <button class="btn btn-lg btn-primary btn-block" id="submit_signin"><?php echo $langData['admin-page-msg-107']['KO'];?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?php echo base_url();?>assets/theme/vendor/jquery/jquery.min.js"></script>
        <script src="<?php echo base_url();?>assets/theme/vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>assets/theme/vendor/metisMenu/metisMenu.min.js"></script>
        <script src="<?php echo base_url();?>assets/admin/js/signin.js"></script>
    </body>
</html>
