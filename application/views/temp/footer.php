		<script src="<?php echo base_url();?>assets/theme/vendor/jquery/jquery.min.js"></script>
		<script src="<?php echo base_url();?>assets/theme/vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>assets/theme/vendor/metisMenu/metisMenu.min.js"></script>
		<script src="<?php echo base_url();?>assets/theme/dist/js/sb-admin-2.js"></script>
		<script src="<?php echo base_url();?>assets/theme/vendor/datatables/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>assets/theme/vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>assets/theme/vendor/datatables-responsive/dataTables.responsive.js"></script>
		<script src="<?php echo base_url();?>assets/js/jquery.sweet-modal.min.js"></script>
		<script src="<?php echo base_url();?>assets/js/clipboard.js" type="text/javascript"></script>
		<script src="<?php echo base_url();?>assets/js/jquery.number.js" type="text/javascript"></script>
		<script src="<?php echo base_url();?>assets/js/lang.js" type="text/javascript"></script>
		<script src="<?php echo base_url(); ?>assets/js/main.js" type="text/javascript"></script>
		<script src="<?php echo base_url();?>assets/js/table2csv.js" type="text/javascript"></script>
		<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
		<?php
			if($page == 'prof'){
		?>
				<script type="text/javascript" src="<?php echo base_url();;?>assets/js/datepicker.js"></script>
				<script type="text/javascript" src="<?php echo base_url();;?>assets/js/jquery.countryselector.js"></script>
				<script type="text/javascript" src="<?php echo base_url();;?>assets/js/intlInputPhone.min.js"></script>
		<?php
			}else if ($page == 'balc') {
		?>	
				<script charset="UTF-8"></script>
				<script type="text/javascript" src="<?php echo base_url();?>assets/js/datepicker.js"></script>
				<script src="<?php echo base_url();?>socket/node_modules/socket.io-client/dist/socket.io.js"></script>
		<?php
			}else if ($page == 'adnc') {
		?>
			<script src="<?php echo base_url();?>assets/js/jquery.tablesort.js" type="text/javascript"></script>
			<script src="<?php echo base_url();?>socket/node_modules/socket.io-client/dist/socket.io.js"></script>
		<?php
			}
		?>
		<script src="<?php echo base_url();?>assets/js/jquery.scrollbar.js" type="text/javascript"></script>
		<script type="text/javascript">
			function setLangSession(value){
				$.ajax({
					url : base_url + "api/setLangSession",
					type : 'POST',
					data : {
						lang : value
					},
					dataType : 'json',
					success:function(response){
						window.location.reload();
					}
				});
			}
		</script>
		<script src="<?php echo base_url();?>assets/js/<?php echo $page;?>.js" type="text/javascript"></script>
		<?php
			if ($page == 'adnc') {
		?>
			<script type="text/javascript" src="<?php echo base_url();?>assets/js/chart.js"></script>
		<?php
			}
		?>
		<style type="text/css">
			footer{width:100%;background-color:#065ec2;font-family:'malgunbd';margin-top:10px;}
			#ft_wr {background-color:#065ec2;width:1400px;margin:0 auto;height:200px}	
			.bottom_area {width:1400px;margin:0 auto;height:200px;position:relative;background-image:url('../../../assets/image/plane.png');background-repeat:no-repeat;background-position:850px 35px;}
			.bottom_logo{position:absolute;top:38px;left:40px}
			.bottom_menu{position:absolute;left:180px;top:0px;width:1180px;height:50px}
			.bottom_menu a{color:#ffffff;font-size:14px;text-decoration:none !important;}
			.bottom_menu a:hover{text-decoration:none;font-weight:bold;}
			.bottom_menu li{float:left;padding-bottom:5px;border-bottom:solid 1px #ffffff;padding-right:80px;}
			.about {position:absolute;left:180px;top:55px;width:300px;height:100px;color:#ffffff;line-height:20px;font-size:12px;}
			.contact {position:absolute;left:480px;top:55px;width:300px;height:100px;color:#ffffff;line-height:20px;font-size:12px;}
			.copyright {position:absolute;left:1060px;top:130px;width:300px;height:30px;color:#ffffff;line-height:20px;font-size:13px;}
		</style>
		<footer>
			<div id="ft_wr">
				<div class="bottom_area">
					<div class="bottom_logo">
						<img src="<?php echo base_url();?>assets/image/bottom_logo.png">
					<div>
					<div class="bottom_menu">
						<ul style="margin: 0;padding: 0;list-style: none;">
							<li><a href="<?php echo base_url();?>cusc/provision" target="_blank"><?php echo $langData['provision'][$lang];?></a></li>
							<li><a href="<?php echo base_url();?>cusc/privacy" target="_blank"><?php echo $langData['personal_privacy'][$lang];?></a></li>
							<li><a href="https://coinsky.co.kr/bbs/board.php?bo_table=notice" target="_blank"><?php echo $langData['notification'][$lang];?></a></li>
							<li><a href="https://coinsky.co.kr/bbs/board.php?bo_table=news" target="_blank"><?php echo $langData['news_media'][$lang];?></a></li>
							<li><a href="https://open.kakao.com/o/ghnbFw6" target="_blank"><?php echo $langData['kakao_talk'][$lang];?></a></li>
						</ul>
					</div>
					<div class="about">
						<?php echo $langData['company_name'][$lang];?> : <?php echo $langData['company_name_content'][$lang];?><br>
						<?php echo $langData['owner'][$lang];?> : <?php echo $langData['owner_name'][$lang];?><br>
						<?php echo $langData['address'][$lang];?> : <?php echo $langData['address_content'][$lang];?><br>
						<?php echo $langData['owner_number'][$lang];?> : ***-**-*****
					</div>
					<div class="contact">
						<?php echo $langData['email'][$lang];?> : cs@coinsky.co.kr<br>
						<?php echo $langData['contact'][$lang];?> : contact@coinsky.co.kr
					</div>
					<div class="copyright">
						© Copyright by Coinsky. All right reserved
					</div>
				</div>
			</div>
		</footer>
		<div class="ajaxLoadingModal"></div>
	</body>
</html>
