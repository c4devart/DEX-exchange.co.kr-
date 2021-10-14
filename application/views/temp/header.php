<?php
    $date = new DateTime();
    $tz = date_timezone_get($date);
    $timezone = timezone_name_get($tz);
    header('Content-Type: text/html; charset=utf-8');
?>
<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
	var timezone = '<?php echo $timezone; ?>';
	var lang = '<?php echo $lang; ?>';
	var page = '<?php echo $page; ?>';
</script>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
        <meta name="description" content="">
		<meta name="author" content="">
		
		<meta name="title" content="코인스카이(Coinsky) - 국내 비트코인 이더리움 스카이코인 등 암호화폐(가상화폐) 거래소">
		<meta name="description" content="코인스카이는 초보자부터 전문가까지 누구나 쉽고 빠르게 비트코인, 이더리움, 스카이코인 등 암호화폐(가상화폐)를 거래할 수 있는 국내 플랫폼입니다. 가장 진보된 UI와 강력한 보안을 경험해보세요.">
		<meta name="keywords" content="코인스카이, coinsky, 비트코인, 빗코인, bitcoin, 비트코인캐시, 이더리움, ethereum, 이더, 이더리움클래식, 이클, 스카이, sky, 스카이코인, erc20, 리플, 큐텀, 퀀텀, ripple, 채굴, 트레이딩, BCH, ETH, ETC, XRP, QTUM, 비트코인거래, 이더리움거래, 리플거래, 리플코인거래, 이더클래식거래, 이더리움클래식거래, 큐텀거래, 비트코인시세, 비트코인캐시시세, 이더리움시세, 이더시세, 이더클래식시세, 이더리움클래식시세, 리플시세, 큐텀시세, 가상화폐, 전자화폐, 비트코인채굴, 이더리움채굴, 아이오타, IOTA, 비트코인골드, BTG">

		<meta property="og:title" content="코인스카이(Coinsky) - 국내 비트코인 이더리움 스카이코인 등 암호화폐(가상화폐) 거래소">
		<meta property="og:description" content="코인스카이는 초보자부터 전문가까지 누구나 쉽고 빠르게 비트코인, 이더리움, 스카이코인 등 암호화폐(가상화폐)를 거래할 수 있는 국내 플랫폼입니다. 가장 진보된 UI와 강력한 보안을 경험해보세요.">


        <title><?php echo $langData['coinsky'][$lang];?></title>
        <link href="<?php echo base_url();?>assets/theme/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/theme/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/theme/vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/theme/vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/theme/dist/css/sb-admin-2.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/theme/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>assets/css/jquery.sweet-modal.min.css" rel="stylesheet" type="text/css" />
        <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
        <?php
            if($page == 'prof'){
        ?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datepicker.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/jquery-countryselector.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/intlInputPhone.min.css">
        <?php
            }else if($page == 'balc'){
        ?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datepicker.css">
        <?php
            }
        ?>
        <link href="<?php echo base_url();?>assets/css/jquery.scrollbar.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/css/main.css" rel="stylesheet" type="text/css" />
        <?php
            if($page != 'adnc'){
        ?>
        <link href="<?php echo base_url();?>assets/css/coinsky.css" rel="stylesheet" type="text/css" />
        <?php
            }
        ?>
        <link href="<?php echo base_url();?>assets/css/<?php echo $page;?>.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" />
    </head>
    <body>
        <input type="hidden" id="token" value="<?php if(isset($token)){echo $token;}else{echo '';};?>">
        <div id="wrapper">
            <div class="header-navbar-content">
                <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0;border:none;">
                    <a href="<?php echo base_url();?>excn/adnc" class="header-logo-img">
                        <img src="<?php echo base_url();?>assets/image/main-logo-<?php echo $lang;?>.png" class="logo-image">
                    </a>
                    <ul class="nav navbar-top-links navbar-right">
                        <li class="dropdown" style="margin-left:-5px;">
                            <a class="dropdown-toggle a-main-menu" data-toggle="dropdown" href="#">
                                <?php echo $langData['exchange'][$lang];?>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts header-sub-menu margin-top-half" style="margin-top:-1px;">
                                <li><a href="<?php echo base_url();?>excn/adnc" class="a-sub-menu"><?php echo $langData['advance'][$lang];?></a></li>
                            </ul>
                        </li>
                        <li class="dropdown" style="margin-left:-5px;">
                            <a class="dropdown-toggle a-main-menu" data-toggle="dropdown" href="#">
							<?php echo $langData['my_balance'][$lang];?>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts header-sub-menu margin-top-half" style="margin-top:-1px;">
                                <li><a href="<?php echo base_url();?>walt/balc" class="a-sub-menu"><?php echo $langData['my_balance'][$lang];?></a></li>
                            </ul>
                        </li>
                        <li class="dropdown" style="margin-left:-5px;">
                            <a class="dropdown-toggle a-main-menu" data-toggle="dropdown" href="#" style="<?php if($lang =='EN'){echo 'width:auto !important;';};?>">
								<?php echo $langData['dep_with'][$lang];?>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts header-sub-menu margin-top-half" style="margin-top:-1px;<?php if($lang =='EN'){echo 'width:auto !important;';};?>">
                                <li><a href="<?php echo base_url();?>walt/dept" class="a-sub-menu"><?php echo $langData['dep_with'][$lang];?></i></a></li>
                            </ul>
                        </li>
                        <li class="dropdown" style="margin-left:-5px;">
                            <a class="dropdown-toggle a-main-menu" data-toggle="dropdown" href="#">
                                <?php echo $langData['header-msg-3'][$lang];?>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts header-sub-menu margin-top-half" style="margin-top:-1px;<?php if($lang =='EN'){echo 'width:auto !important;';};?>">
                                <li><a href="<?php echo base_url();?>sky/publ" class="a-sub-menu"><?php echo $langData['header-msg-2'][$lang];?></a></li>
                                <!-- <li><a href="<?php echo base_url();?>sky/drop" class="a-sub-menu">SKY지급현황</a></li> -->
                                <li><a href="<?php echo base_url();?>sky/info" class="a-sub-menu"><?php echo $langData['header-msg-3'][$lang];?></a></li>
                            </ul>
                        </li>
                        <!-- <li class="dropdown" style="margin-left:-5px;">
                            <a class="dropdown-toggle a-main-menu" data-toggle="dropdown" href="#">
								<?php echo $langData['support_center'][$lang];?>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts header-sub-menu margin-top-half" style="margin-top:-1px;width:140px;">
                                <li><a href="<?php echo base_url();?>cusc/notc" class="a-sub-menu"><?php echo $langData['notification'][$lang];?></a></li>
                                <li><a href="<?php echo base_url();?>cusc/term" class="a-sub-menu"><?php echo $langData['terms'][$lang];?></a></li>
                                <li><a href="<?php echo base_url();?>cusc/fees" class="a-sub-menu"><?php echo $langData['fees'][$lang];?></a></li>
                                <li><a href="<?php echo base_url();?>cusc/priv" class="a-sub-menu"><?php echo $langData['privacy'][$lang];?></a></li>
                                <li><a href="<?php echo base_url();?>cusc/faq" class="a-sub-menu"><?php echo $langData['faq'][$lang];?></a></li>
                                <li><a href="<?php echo base_url();?>cusc/quiz" class="a-sub-menu"><?php echo $langData['quiz'][$lang];?></a></li>
                                <li><a href="<?php echo base_url();?>cusc/code" class="a-sub-menu"><?php echo $langData['confirm_deposit'][$lang];?></a></li>
                                <li><a href="<?php echo base_url();?>acnt/cnfm" class="a-sub-menu"><?php echo $langData['header-msg-1'][$lang];?></a></li>
                                <li><a href="<?php echo base_url();?>cusc/eror" class="a-sub-menu"><?php echo $langData['error_notification'][$lang];?></a></li>
                                <li><a href="<?php echo base_url();?>cusc/finc" class="a-sub-menu"><?php echo $langData['finacial_scam'][$lang];?></a></li>
                            </ul>
                        </li> -->
                        <?php
                            if($this->session->userdata('exchange_user_login') == true){
                        ?>
                        <li style="margin-left:-5px;"><a href="<?php echo base_url();?>acnt/sout" class="header-small-item a-main-menu"><?php echo $langData['logout'][$lang];?></a></li>
                        <li class="dropdown" style="margin-left:-5px;">
                            <a class="dropdown-toggle header-small-item a-main-menu" data-toggle="dropdown" href="#">
								<?php echo $langData['profile'][$lang];?> <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts header-sub-menu margin-top-1" style="text-align:right;width:140px;">
                                <li><a href="<?php echo base_url();?>acnt/prof" class="a-sub-menu" style="padding:10px 10px !important;"><?php echo $langData['account'][$lang];?></a></li>
                                <li><a href="<?php echo base_url();?>acnt/levl" class="a-sub-menu" style="padding:10px 10px !important;"><?php echo $langData['stage'][$lang];?></a></li>
                                <li><a href="<?php echo base_url(); ?>acnt/gotp" class="a-sub-menu" style="padding:10px 10px !important;"><?php echo $langData['google_otp'][$lang]; ?></a></li>
                                <li><a href="<?php echo base_url(); ?>acnt/cnfm" class="a-sub-menu" style="padding:10px 10px !important;"><?php echo $langData['header-msg-1'][$lang]; ?></a></li>
                                <!-- <li><a href="<?php echo base_url();?>acnt/actn" class="a-sub-menu" style="padding:10px 10px !important;"><?php echo $langData['activity'][$lang];?></a></li>
                                <li><a href="<?php echo base_url();?>acnt/setg" class="a-sub-menu" style="padding:10px 10px !important;"><?php echo $langData['setting'][$lang];?></a></li>
                                <li><a href="<?php echo base_url();?>acnt/coup" class="a-sub-menu" style="padding:10px 10px !important;"><?php echo $langData['coupon'][$lang];?></a></li> -->
                            </ul>
                        </li>
                        <?php
                            }else{
                        ?>
                        <li style="margin-left:-5px;"><a href="<?php echo base_url();?>acnt/siin" class="header-small-item a-main-menu"><?php echo $langData['sign_in'][$lang];?></a></li>
                        <li style="margin-left:-5px;"><a href="<?php echo base_url();?>acnt/siup" class="header-small-item a-main-menu"><?php echo $langData['sign_up'][$lang];?></a></li>
                        <?php
                            }
                        ?>
                        <li class="dropdown" style="margin-left:-5px;">
                            <a class="dropdown-toggle header-small-item a-main-menu" data-toggle="dropdown" href="#">
								<?php 
									if($lang == 'EN'){
										echo 'English';
									}else if($lang == 'CN'){
										echo '中文';
									}else{
										echo '한국어';
									}
								?>
								<i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts header-sub-menu margin-top-1" style="text-align:center;width:100px;">
                                <!-- <li><a href="#" class="a-sub-menu" onclick="setLangSession('KO');">한국어</a></li>
                                <li><a href="#" class="a-sub-menu" onclick="setLangSession('EN');">English</a></li>
                                <li><a href="#" class="a-sub-menu" onclick="setLangSession('CN');">中文</a></li> -->
                                <li><a href="#" class="a-sub-menu">한국어</a></li>
                                <li><a href="#" class="a-sub-menu">English</a></li>
                                <li><a href="#" class="a-sub-menu">中文</a></li>
                            </ul>
                        </li>
                        <li style="margin-right:0px;width:80px;text-align:center;margin-left:0px;">
							<img src="<?php echo base_url();?>assets/image/kakaotalk.png">
							<img src="<?php echo base_url();?>assets/image/google.png">
						</li>
                    </ul>
                </nav>
            </div>
