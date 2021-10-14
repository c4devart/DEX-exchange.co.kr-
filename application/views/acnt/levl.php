	<div class="coinsky-page">
		<div class="coinsky-page-header">
			<span class="coinsky-page-header-title"><?php echo $langData['acnt-levl-msg-31'][$lang];?></span>
		</div>
		<div class="coinsky-page-content">
			<div class="width-1400 height-600 panelShadowed">
				<div class="height-120<?php if($userdata['f_kyc_level'] >= 1){echo ' back-color-white';}else{echo ' back-color-light';};?>">
					<div class="width-170 height-120 display-inline">
						<span class="span-blue span-normal-bold float-right" style="margin-right:20px;">
							<span class="span-48px">1</span> <span class="span-24px"><?php echo $langData['acnt-levl-msg-32'][$lang];?></span>
						</span>
					</div>
					<div class="width-250 height-120 display-inline" style="padding-top:25px;">
						<span class="span-normal-bold span-18px span-grey" style="line-height:40px;"><?php echo $langData['acnt-levl-msg-33'][$lang];?></span><br>
						<?php
							if($userdata['f_kyc_level'] >= 1){
						?>
						<span class="span-normal-bold span-14px span-light span-background-black" style="padding:5px;margin-top:10px;"><?php echo $langData['acnt-levl-msg-22'][$lang];?></span>
						<?php
							}
						?>
					</div>
					<div class="width-980 height-120 display-inline">
						<div class="width-980 height-35" style="line-height:54px;">
							<span class="span-normal span-13px span-grey"><?php echo $langData['acnt-levl-msg-30'][$lang];?></span><br>
						</div>
						<div class="width-980 height-25" style="line-height:34px;">
							<div class="width-70 height-25 display-inline">
								<span class="span-normal-bold span-14px span-black"><?php echo $langData['acnt-levl-msg-34'][$lang];?></span>
							</div>
							<div class="width-240 height-25 display-inline">
								<span class="span-normal span-14px span-grey"><?php echo $userdata['f_username'];?></span>
							</div>
							<div class="width-70 height-25 display-inline">
								<span class="span-normal-bold span-14px span-black"><?php echo $langData['acnt-levl-msg-35'][$lang];?></span>
							</div>
							<div class="width-600 height-25 display-inline">
							<?php
								if($userdata['f_phone_verified'] == 1){
							?>
								<span class="span-normal span-14px span-grey"><?php echo $userdata['f_phone'];?></span>
							<?php
								}else{
							?>
								<button class="levl-up-btn" style="line-height: 28px;" onclick="window.open(base_url+'acnt/prof/mobile');"><?php echo $langData['acnt-levl-msg-36'][$lang];?> ></button>								
							<?php
								}
							?>
							</div>
						</div>
						<div class="width-980 height-25" style="line-height:34px;padding-top: 10px;">
							<div class="width-70 height-25 display-inline">
								<span class="span-normal-bold span-14px span-black" style="width:70px;"><?php echo $langData['acnt-levl-msg-37'][$lang];?></span>
							</div>
							<div class="width-240 height-25 display-inline">
								<span class="span-normal span-14px span-grey"><?php echo $userdata['f_email'];?></span>
							</div>
							<div class="width-70 height-25 display-inline">
								<span class="span-normal-bold span-14px span-black"><?php echo $langData['acnt-levl-msg-38'][$lang];?></span>
							</div>
							<div class="height-25 display-inline">
								<span class="span-normal span-14px span-grey" id="levlAddress"><?php $address=$userdata['f_address']; echo $userdata['f_address'];?></span>
								<span class="span-normal-bold span-14px span-light span-background-black" style="padding:5px;margin-left:20px;cursor:pointer;" onclick="levlChangeAddress('<?php echo $address;?>');"><?php echo $langData['acnt-levl-msg-39'][$lang];?></span>
							</div>
						</div>
					</div>
				</div>
				<div class="width-1400 height-360 border-top-solid-1<?php if($userdata['f_kyc_level'] >= 2){echo ' back-color-white';}else{echo ' back-color-light';};?>">
					<div class="width-170 height-120 display-inline">
						<span class="span-grey span-normal-bold float-right" style="margin-right:20px;">
							<span class="span-48px">2</span> <span class="span-24px"><?php echo $langData['acnt-levl-msg-32'][$lang];?></span>
						</span>
					</div>
					<div class="width-1060 height-120 display-inline border-bottom-dotted-1">
						<div class="width-250 height-120 display-inline" style="padding-top:25px;">
							<span class="span-normal-bold span-18px span-grey" style="line-height:40px;"><?php echo $langData['acnt-levl-msg-29'][$lang];?></span><br>
							<?php
								if($userdata['f_phone_owner_verified'] == 1){
							?>
							<span class="span-normal-bold span-14px span-light span-background-black" style="padding:5px;margin-top:10px;"><?php echo $langData['acnt-levl-msg-22'][$lang];?></span>
							<?php
								}
							?>
						</div>
						<div class="width-810 height-120 display-inline">
							<div class="width-810 height-60" style="line-height:80px;">
								<span class="span-normal span-13px span-grey"><?php echo $langData['acnt-levl-msg-28'][$lang];?></span>
							</div>
							<?php
								if($userdata['f_phone_owner_verified'] == 1){
							?>
							<div class="width-810 height-60" style="line-height:40px;">
								<span class="span-normal-bold span-14px span-black"><?php echo $langData['acnt-levl-msg-27'][$lang];?></span>
							</div>
							<?php
								}else{
							?>
							<button class="levl-up-btn" style="padding-top:5px;padding-bottom:5px;" onclick="openKCPcertWindow('<?php echo base64_encode($token);?>', '<?php echo base64_encode($userdata['f_username']);?>', '<?php echo base64_encode($userdata['f_phone']); ?>')"><?php echo $langData['acnt-levl-msg-26'][$lang];?> ></button>
							<?php
								}
							?>
						</div>
					</div>
					<div class="width-1060 height-120 display-inline border-bottom-dotted-1 margin-left-170">
						<div class="width-250 height-120 display-inline" style="padding-top:25px;">
							<span class="span-normal-bold span-18px span-grey" style="line-height:40px;"><?php echo $langData['acnt-levl-msg-11'][$lang];?></span><br>
							<?php
								if($userdata['f_otp_verified'] == 1){
							?>
							<span class="span-normal-bold span-14px span-light span-background-black" style="padding:5px;margin-top:10px;"><?php echo $langData['acnt-levl-msg-22'][$lang];?></span>
							<?php
								}
							?>
						</div>
						<div class="width-810 height-120 display-inline">
							<div class="width-810 height-60" style="line-height:80px;">
								<span class="span-normal span-13px span-grey"><?php echo $langData['acnt-levl-msg-25'][$lang];?></span>
							</div>
							<div class="width-810 height-60">
								<?php
									if($userdata['f_otp_verified'] == 1){
								?>
								<div class="width-810 height-60" style="line-height:40px;">
									<span class="span-normal-bold span-14px span-black"><?php echo $langData['acnt-levl-msg-23'][$lang];?></span>
								</div>
								<?php
									}else{
								?>
								<button class="levl-up-btn" style="padding-top:5px;padding-bottom:5px;" onclick="window.open(base_url+'acnt/gotp');"><?php echo $langData['acnt-levl-msg-24'][$lang];?> ></button>
								<?php
									}
								?>
							</div>
						</div>
					</div>
					<div class="width-1060 height-120 display-inline margin-left-170">
						<div class="width-250 height-120 display-inline" style="padding-top:25px;">
							<span class="span-normal-bold span-18px span-grey" style="line-height:40px;"><?php echo $langData['acnt-levl-msg-12'][$lang];?></span><br>
							<?php
								if($userdata['f_withdraw_KRW_account_verified'] == 1){
							?>
							<span class="span-normal-bold span-14px span-light span-background-black" style="padding:5px;margin-top:10px;"><?php echo $langData['acnt-levl-msg-22'][$lang];?></span>
							<?php
								}
							?>
						</div>
						<div class="width-810 height-120 display-inline">
							<div class="width-810 height-60" style="line-height:80px;">
								<span class="span-normal span-13px span-grey"><?php echo $langData['acnt-levl-msg-20'][$lang];?></span>
							</div>
							<div class="width-810 height-60">
								<?php
									if($userdata['f_withdraw_KRW_account_verified'] == 1){
								?>
								<div class="width-810 height-60" style="line-height:40px;">
									<span class="span-normal-bold span-14px span-black"><?php echo $langData['acnt-levl-msg-21'][$lang];?></span>
								</div>
								<?php
									}else{
								?>
								<form id="reqAccountForm" name="reqAccountForm" method="post" action="" style="margin:0px;">
									<input type="hidden" id="req_info" name="req_info" value="<?php echo $reqInfo;?>"/>
									<input type="hidden" id="call_back" name="call_back" value="<?php echo $callback;?>"/>
								</form>
								<button class="levl-up-btn" style="padding-top:5px;padding-bottom:5px;" onclick="window.open('<?php echo base_url(); ?>acnt/cnfm/withdraw')//openAccountWindow()"><?php echo $langData['acnt-levl-msg-42'][$lang];?> ></button>
								<?php
									}
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="width-1400 height-120 border-top-solid-1<?php if($userdata['f_kyc_level'] == 3){echo ' back-color-white';}else{echo ' back-color-light';};?>">
					<div class="width-170 height-120 display-inline">
						<span class="span-grey span-normal-bold float-right" style="margin-right:20px;">
							<span class="span-48px">3</span> <span class="span-24px"><?php echo $langData['acnt-levl-msg-32'][$lang];?></span>
						</span>
					</div>
					<div class="width-1060 height-120 display-inline">
						<div class="width-250 height-120 display-inline" style="padding-top:25px;">
							<span class="span-normal-bold span-18px span-grey" style="line-height:40px;">서류 제출</span><br>
							<?php
								if($userdata['f_kyc_level'] >= 3){
							?>
							<span class="span-normal-bold span-14px span-light span-background-black" style="padding:5px;margin-top:10px;"><?php echo $langData['acnt-levl-msg-22'][$lang];?></span>
							<?php
								}
							?>
						</div>
						<div class="width-810 height-120 display-inline">
							<div class="width-810 height-60" style="line-height:80px;">
								<span class="span-normal span-13px span-grey"><?php echo $langData['acnt-levl-msg-17'][$lang];?></span>
							</div>
							<div class="width-810 height-60">
								<?php
									if($userdata['f_kyc_level'] == 3){
								?>
								<div class="width-810 height-60" style="line-height:40px;">
									<span class="span-normal-bold span-14px span-black"><?php echo $langData['acnt-levl-msg-18'][$lang];?></span>
								</div>
								<?php
									}else{
								?>
								<button class="levl-up-btn" style="padding-top:5px;padding-bottom:5px;" onclick="window.open(base_url+'acnt/cnfm/owner');"><?php echo $langData['acnt-levl-msg-19'][$lang];?> ></button>
								<?php
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="width-1400 panelShadowed back-color-light" style="margin-top:60px;">
				<div class="width-1400 height-70" style="line-height:96px;padding-left:8px;padding-right:8px;">
					<span class="span-panel-header-title">
						<?php echo $langData['acnt-levl-msg-15'][$lang];?>
					</span>
				</div>
				<div class="width-1400">
					<div class="width-1400 height-35 border-bottom-solid-1" style="padding-left:8px;padding-right:8px;">
						<div class="width-380 height-35 display-inline text-align-center" style="line-height:40px;"><span class="span-HYWUML span-14px span-black"><?php echo $langData['acnt-levl-msg-40'][$lang];?></span></div>
						<div class="width-250 height-35 display-inline text-align-center" style="line-height:40px;"><span class="span-HYWUML span-14px span-black"></span></div>
						<div class="width-250 height-35 display-inline text-align-center" style="line-height:40px;"><span class="span-HYWUML span-14px span-black"><?php echo $langData['acnt-levl-msg-41'][$lang];?></span></div>
						<div class="width-250 height-35 display-inline text-align-center" style="line-height:40px;"><span class="span-HYWUML span-14px span-black"></span></div>
						<div class="width-250 height-35 display-inline text-align-center" style="line-height:40px;"><span class="span-HYWUML span-14px span-black"><?php echo $langData['acnt-levl-msg-16'][$lang];?></span></div>
					</div>
					<table class="datatable-dept-with-info-list">
						<thead>
							<tr class="width-1384" style="padding-left:8px;padding-right:8px;">
								<th class="width-380 height-70 no-border-top"><?php echo $langData['acnt-levl-msg-6'][$lang];?></th>
								<th class="width-250 height-70 no-border-top back-color-white"><span class="span-normal-bold span-14px span-blue"><?php echo $langData['acnt-levl-msg-7'][$lang];?></span><br><span class="span-normal span-14px span-blue"><?php echo $langData['acnt-levl-msg-8'][$lang];?></span></th>
								<th class="width-250 height-70 no-border-top"><span class="span-normal-bold span-14px span-black"><?php echo $langData['acnt-levl-msg-9'][$lang];?></span><br><span class="span-normal span-14px span-black"><?php echo $langData['acnt-levl-msg-10'][$lang];?>, <?php echo $langData['acnt-levl-msg-11'][$lang];?>, <br><?php echo $langData['acnt-levl-msg-12'][$lang];?></span></th>
								<th class="width-250 height-70 no-border-top"><span class="span-normal-bold span-14px span-black"><?php echo $langData['acnt-levl-msg-13'][$lang];?></span><br><span class="span-normal span-14px span-black"><?php echo $langData['acnt-levl-msg-14'][$lang];?></span></th>
								<th class="width-250 height-70 no-border-top">-</th>
							</tr>
						</thead>
					</table>
					<table class="datatable-dept-with-info-list">
						<tbody>
							<?php
								foreach ($coinData as $key => $value) {
							?>
							<tr class="height-60">
								<td class="width-250 text-align-left" style="padding-left:30px;"><?php echo $langData[strtolower($value['f_unit'])][$lang];?> (<?php echo $value['f_unit'];?>)</td>
								<td class="width-129"><div class="td-content-black-centered width-109 border-bottom-dot-1"><?php echo $langData['acnt-levl-msg-4'][$lang];?></div><div class="td-content-black-centered width-109"><?php echo $langData['acnt-levl-msg-5'][$lang];?></div></td>
								<td class="width-250 back-color-white"><div class="td-content-blue-right width-230" style="border-bottom:dotted 1px #065ec2;">
									<?php
										if($value['f_once_available_withdraw_amount_first'] == 0){
											echo '불가';
										}else{
											echo number_format($value['f_once_available_withdraw_amount_first']);
										}
									?></div><div class="td-content-blue-right width-230">
									<?php
										if ($value['f_daily_available_withdraw_amount_first'] == 0) {
											echo '불가';
										} else {
											echo number_format($value['f_daily_available_withdraw_amount_first']);
										}									
									?></div>
								</td>
								<td class="width-250"><div class="td-content-black-right width-230 border-bottom-dot-1">
									<?php
										if($value['f_once_available_withdraw_amount_second'] >= 1000000000000){
											echo '무제한';
										}else{
											echo number_format($value['f_once_available_withdraw_amount_second']);
										}
									?>
								</div>
								<div class="td-content-black-right width-230">
									<?php
										if ($value['f_daily_available_withdraw_amount_second'] >= 1000000000000) {
											echo '무제한';
										} else {
											echo number_format($value['f_daily_available_withdraw_amount_second']);
										}
									?></div>
								</td>
								<td class="width-250">
									<div class="td-content-black-right width-230 border-bottom-dot-1">
										<?php
											if ($value['f_once_available_withdraw_amount_third'] >= 1000000000000) {
												echo '무제한';
											} else {
												echo number_format($value['f_once_available_withdraw_amount_third']);
											}
										?>										
									</div>
									<div class="td-content-black-right width-230">
										<?php
											if ($value['f_daily_available_withdraw_amount_third'] >= 1000000000000) {
												echo '무제한';
											} else {
												echo number_format($value['f_daily_available_withdraw_amount_third']);
											}
										?>
									</div>
								</td>
								<td class="width-250 text-align-right"><span class="span-normal span-13px span-black">
									<?php
										if($value['f_unit'] == 'KRW'){
											echo number_format($value['f_available_min_deposit_amount']);
										}else{
											echo number_format($value['f_available_min_deposit_amount'], 8, '.', ',');
										}
									?></span>
								</td>
							</tr>
							<?php
								}
							?>
						</tbody>
					</table>
				</div>
				<div class="width-1400 height-115 border-top-solid-1" style="padding: 20px 30px;margin-top: -1px;">
					<span class="span-normal span-13px span-grey">※ <?php echo $langData['acnt-levl-msg-3'][$lang];?></span><br>
					<span class="span-normal span-13px span-grey">※ <?php echo $langData['acnt-levl-msg-2'][$lang];?></span><br>
					<span class="span-normal span-13px span-grey">※ <?php echo $langData['acnt-levl-msg-1'][$lang];?></span>
				</div>
			</div>
		</div>
	</div>
</div>
