<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
</script>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?php echo $langData['admin-page-msg-1']['KO'];?></title>
        <link href="<?php echo base_url();?>assets/theme/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/theme/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/theme/vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/theme/vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/theme/dist/css/sb-admin-2.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/theme/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="<?php echo base_url();?>assets/css/jquery.sweet-modal.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/css/datepicker.css" rel="stylesheet" type="text/css">
		<link href="<?php echo base_url();?>assets/admin/css/main.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url();?>assets/admin/css/<?php echo $page;?>.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/css/alertify.core.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/css/alertify.default.css" id="toggleCSS" />
        <link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" />
    </head>
    <body>
        <div id="wrapper">
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
                            <li class="sidebar-search">
                                <a href="<?php echo base_url();?>admin">
                                    <img src="<?php echo base_url();?>assets/image/ko_logo_blue.png" style="width:100%;">
                                </a>
                            </li>
							<li>
                                <a href="#"><i class="fa fa-credit-card fa-fw"></i> <?php echo $langData['admin-page-msg-2']['KO'];?><span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level
                                <?php
                                    if($page == 'swallet' || $page == 'earn' || $page == 'deposit' || $page == 'withdraw' || $page == 'skypool' || $page == 'ethdrop'){
                                        echo ' collapse in';
                                    }
                                ?>">
                                    <li>
                                        <a href="<?php echo base_url();?>admin/swallet"<?php if($page=='swallet') echo ' class="active"'?>> <?php echo $langData['admin-page-msg-3']['KO'];?></a>
									</li>
									<li>
                                        <a href="<?php echo base_url(); ?>admin/earn"<?php if ($page == 'earn') echo ' class="active"' ?>> <?php echo $langData['admin-page-msg-155']['KO']; ?></a>
                                    </li>
									<li>
                                        <a href="<?php echo base_url();?>admin/deposit"<?php if($page=='deposit') echo ' class="active"'?>> <?php echo $langData['admin-page-msg-4']['KO'];?></a>
                                    </li>
									<li>
                                        <a href="<?php echo base_url();?>admin/withdraw"<?php if($page=='withdraw') echo ' class="active"'?>> <?php echo $langData['admin-page-msg-5']['KO'];?></a>
                                    </li>
									<li>
                                        <a href="<?php echo base_url();?>admin/skypool"<?php if($page=='skypool') echo ' class="active"'?>> <?php echo $langData['admin-page-msg-6']['KO'];?></a>
                                    </li>
									<li>
                                        <a href="<?php echo base_url();?>admin/ethdrop"<?php if($page=='ethdrop') echo ' class="active"'?>> <?php echo $langData['admin-page-msg-7']['KO'];?></a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-users fa-fw"></i> <?php echo $langData['admin-page-msg-8']['KO'];?><span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level
                                <?php
                                    if($page == 'ulist' || $page == 'kyc' || $page == 'ubalance' || $page == 'uwallet' || $page == 'wacnt'){
                                        echo ' collapse in';
                                    }
                                ?>">
                                    <li>
                                        <a href="<?php echo base_url();?>admin/ulist"<?php if($page=='ulist') echo ' class="active"'?>> <?php echo $langData['admin-page-msg-9']['KO'];?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url();?>admin/kyc"<?php if($page=='kyc') echo ' class="active"'?>> <?php echo $langData['admin-page-msg-10']['KO'];?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url();?>admin/ubalance"<?php if($page=='ubalance') echo ' class="active"'?>><?php echo $langData['admin-page-msg-11']['KO'];?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url();?>admin/uwallet"<?php if($page=='uwallet') echo ' class="active"'?>><?php echo $langData['admin-page-msg-12']['KO'];?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url(); ?>admin/wacnt"<?php if ($page == 'wacnt') echo ' class="active"' ?>>Withdraw Account Management</a>
                                    </li>
                                </ul>
                            </li>
							<li>
                                <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> <?php echo $langData['admin-page-msg-20']['KO'];?><span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level
                                <?php
                                    if($page == 'coin' || $page == 'market' || $page == 'chart' || $page == 'order' || $page == 'mhistory' || $page == 'dmhistory'){
                                        echo ' collapse in';
                                    }
                                ?>">
                                    <li>
                                        <a href="<?php echo base_url();?>admin/coin"<?php if($page=='coin') echo ' class="active"'?>> <?php echo $langData['admin-page-msg-13']['KO'];?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url();?>admin/market"<?php if($page=='market') echo ' class="active"'?>> <?php echo $langData['admin-page-msg-14']['KO'];?></a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-cogs fa-fw"></i> <?php echo $langData['admin-page-msg-19']['KO'];?><span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level
                                <?php
                                    if($page == 'config' || $page == 'fees'){
                                        echo ' collapse in';
                                    }
                                ?>">
                                    <li>
                                        <a href="<?php echo base_url();?>admin/config"<?php if($page=='config') echo ' class="active"'?>> <?php echo $langData['admin-page-msg-15']['KO'];?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url();?>admin/fees"<?php if($page=='fees') echo ' class="active"'?>> <?php echo $langData['admin-page-msg-16']['KO'];?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url();?>admin/lang"<?php if($page=='lang') echo ' class="active"'?>> <?php echo $langData['admin-page-msg-154']['KO'];?></a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="<?php echo base_url();?>admin/settings"<?php if($page=='settings') echo ' class="active"'?>>
                                    <i class="fa fa-key fa-fw"></i> <?php echo $langData['admin-page-msg-17']['KO'];?>
                                </a>
							</li>
							<?php
								if($this->session->userdata('coinsky_admin_state')=='super'){
							?>
                            <li>
                                <a href="<?php echo base_url(); ?>admin/account"<?php if ($page == 'account') echo ' class="active"' ?>>
                                    <i class="fa fa-user fa-fw"></i> Admin management
                                </a>
							</li>
                            <li>
                                <a href="<?php echo base_url(); ?>admin/hot"<?php if ($page == 'hot') echo ' class="active"' ?>>
                                    <i class="fa fa-bitcoin fa-fw"></i> Hotwallet management
                                </a>
							</li>
							<?php
								}
							?>
                            <li>
                                <a href="<?php echo base_url();?>admin/signout"<?php if($page=='signout') echo ' class="active"'?>>
                                    <i class="fa fa-sign-out fa-fw"></i> <?php echo $langData['admin-page-msg-18']['KO'];?>
                                </a>
							</li>
                        </ul>
                    </div>
                </div>
            </nav>
