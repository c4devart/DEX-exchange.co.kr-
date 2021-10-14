    <input type="hidden" id="coin" value="<?php echo $coin;?>">
	<input type="hidden" id="phoneNumber" value="<?php echo $userdata['f_phone']; ?>">
	<div class="coinsky-page">
		<div class="coinsky-page-header">
			<span class="coinsky-page-header-title"><?php echo $langData['dep_with'][$lang];?></span>
		</div>
		<div class="coinsky-page-content">
			<div class="width-200 display-inline">
				<div class="coinsky-sidebar-content panelShadowed basicBackground">
					<input type="checkbox" id="checkboxViewBalancedOnly"> <span class="malgun span-13px span-grey"><?php echo $langData['dept_view_balanced_coin'][$lang];?></span>
				</div>
				<div class="coinsky-sidebar panelShadowed background-white margin-top-4">
					<?php
						foreach ($coinList as $row) {
					?>
					<div class="coinsky-sidebar-content <?php if($coin==$row['f_unit']) echo 'active';?>" id="sidebarContent<?php echo $row['f_unit'];?>" onclick="window.location.href = base_url + 'walt/dept/<?php echo $row['f_unit'];?>'">
						<img src="<?php echo base_url().$row['f_img'];?>" class="coinsky-sidebar-content-img">
						<span class="coinsky-sidebar-content-main-title"><?php echo $langData[strtolower($row['f_unit'])][$lang];?></span><span class="coinsky-sidebar-content-sub-title"><?php echo $row['f_unit'];?></span></span>
					</div>
					<?php
						}
					?>
				</div>
			</div>
			<div class="coinsky-fixed-width-1180 display-inline margin-left-20">
				<div class="coinsky-fixed-width-1180 display-inline">
					<div class="coinsky-sub-content-height-44 border-bottom-dot-1">
						<img src="<?php echo base_url().$coinData['f_img'];?>" class="coinsky-sidebar-content-img float-left" style="margin:10px 13px;">
						<div style="line-height:48px;"><span class="span-HYWUML span-24px span-black float-left"><?php echo $langData[strtolower($coin)][$lang];?></span></div>
						<div style="line-height:48px;"><span class="span-HYWUML span-18px span-grey float-left" style="margin-left:10px;"><?php echo $coin;?></span></div>
						<span class="span-background-black span-20px span-HYWUML float-right" style="padding:8px 12px;color:#f4f7f9;"><?php echo $langData['balance'][$lang];?></span>
						<div style="line-height:48px;"><span class="span-HYWUML span-18px span-grey float-right" style="margin-right:10px;"><?php echo $coin;?></span></div>
						<div style="line-height:48px;"><span class="span-HYWUML span-24px span-blue float-right" style="margin-right:10px;"><?php if($coin=='KRW'){echo number_format($balance['f_total']);}else{echo number_format($balance['f_total'], 8, '.', ',');};?></span></div>
					</div>
				</div>
				<div class="coinsky-fixed-width-1180 display-inline">
					<div class="coinsky-sub-content-height-40">
						<?php
							if($coin!='KRW'){
						?>
						<div style="line-height:44px;float:right;"><span class="span-normal span-13px span-black" style="padding:8px;color:#f4f7f9;"><?php echo $langData['total_price'][$lang];?>(KRW)</span></div>
						<div style="line-height:44px;float:right;"><span class="span-normal span-18px span-grey" style="margin-right:10px;"><?php echo number_format($totalByBase);?></span></div>
						<?php
							}
						?>
					</div>
				</div>
				<div class="coinsky-fixed-width-1180 display-inline panelShadowed">
					<div class="fixed-width-590 display-inline height-270">
						<div class="height-240 border-bottom-solid-1">
							<div class="width-580 float-right">
								<div class="height-40 back-color-light border-bottom-solid-1 border-left-solid-1" style="padding:0px 20px;line-height:40px;">
									<div style="text-align:left;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-normal span-14px span-black"><?php echo $langData['daily_withdrawal_limit'][$lang];?></span>
									</div>
									<div style="text-align:right;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-HYWUML span-20px span-black" style="<?php if ($day_withdraw_limit != '불가' && $day_withdraw_limit != '무제한') echo 'margin-right:10px;'; ?>">
											<?php echo $day_withdraw_limit;?>
										</span>	
										<span class="span-HYWUML span-14px span-grey" style="line-height:44px;"><?php if($day_withdraw_limit!='불가' && $day_withdraw_limit != '무제한') echo $coin;?></span>
									</div>
								</div>
								<div class="height-40 back-color-white border-bottom-dotted-1 border-left-solid-1" style="padding:0px 20px;line-height:40px;">
									<div style="text-align:left;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-normal span-13px span-grey">- <?php echo $langData['withdrawal_amount_today'][$lang];?></span>
									</div>
									<div style="text-align:right;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-HYWUML span-16px span-black" style="margin-right:10px;">
											<?php echo $dayWithdrawalAmount;?>
										</span>
										<span class="span-HYWUML span-14px span-grey" style="line-height:44px;"><?php echo $coin;?></span>
									</div>
								</div>
								<div class="height-40 back-color-white border-bottom-dotted-1 border-left-solid-1" style="padding:0px 20px;line-height:40px;">
									<div style="text-align:left;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-normal span-13px span-grey">- <?php echo $langData['min_withdrawal_amount'][$lang];?></span>
									</div>
									<div style="text-align:right;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-HYWUML span-16px span-black" style="margin-right:10px;"><?php echo $minWithdrawAmount;?></span>
										<span class="span-HYWUML span-14px span-grey" style="line-height:44px;"><?php echo $coin;?></span>
									</div>
								</div>
								<div class="height-40 back-color-white border-bottom-dotted-1 border-left-solid-1" style="padding:0px 20px;line-height:40px;">
									
								</div>
								<div class="height-40 back-color-light border-bottom-solid-1 border-left-solid-1" style="padding:0px 20px;line-height:40px;">
									<div style="text-align:left;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-normal span-14px span-black"><?php echo $langData['withdrawal_limit_at_once'][$lang];?></span>
									</div>
									<div style="text-align:right;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-HYWUML span-20px span-black" style="<?php if ($day_withdraw_limit != '불가' && $day_withdraw_limit != '무제한') echo 'margin-right:10px;'; ?>">
											<?php echo $once_withdraw_limit; ?>
										</span>
										<span class="span-HYWUML span-14px span-grey" style="line-height:44px;"><?php if ($once_withdraw_limit != '불가' && $once_withdraw_limit != '무제한') echo $coin; ?></span>
									</div>
								</div>
								<div class="height-40 back-color-light border-bottom-solid-1 border-left-solid-1" style="padding:0px 20px;line-height:40px;">
									<div style="text-align:left;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-normal span-14px span-black"><?php echo $langData['todays_available_withdrawl_balance'][$lang];?></span>
									</div>
									<div style="text-align:right;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-HYWUML span-20px span-black" style="<?php if ($day_withdraw_limit != '불가' && $day_withdraw_limit != '무제한') echo 'margin-right:10px;'; ?>">
											<?php echo $possibleWithdrawAmountToday;?>
										</span>
										<span class="span-HYWUML span-14px span-grey" style="line-height:44px;"><?php if ($possibleWithdrawAmountToday != '불가' && $possibleWithdrawAmountToday != '무제한') echo $coin; ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="fixed-width-590 display-inline height-277" style="border-left:solid 1px #c7c7c7;">
						<div class="height-240 border-bottom-solid-1">
							<div class="width-580 border-right-solid-1 float-left">
							<div class="height-40 back-color-light border-bottom-solid-1" style="padding:0px 20px;line-height:40px;">
									<div style="text-align:left;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-normal span-14px span-black"><?php echo $langData['on_exchange'][$lang];?> (<?php echo $langData['open_order'][$lang];?>) <?php echo $langData['total_sum'][$lang];?></span>
									</div>
									<div style="text-align:right;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-HYWUML span-20px span-black" style="margin-right:10px;"><?php echo $onOrderVolume;?></span>	
										<span class="span-HYWUML span-14px span-grey" style="line-height:44px;"><?php echo $coin;?></span>
									</div>
								</div>
								<div class="height-40 back-color-white border-bottom-dotted-1" style="padding:0px 20px;line-height:40px;">
									<div style="text-align:left;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-normal span-13px span-grey">- <?php echo $langData['on_buy'][$lang];?></span>
									</div>
									<div style="text-align:right;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-HYWUML span-16px span-black" style="margin-right:10px;"><?php echo $onBuyOrderVolume;?></span>
										<span class="span-HYWUML span-14px span-grey" style="line-height:44px;"><?php echo $coin;?></span>
									</div>
								</div>
								<div class="height-40 back-color-white border-bottom-dotted-1" style="padding:0px 20px;line-height:40px;">
									<div style="text-align:left;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-normal span-13px span-grey">- <?php echo $langData['on_sell'][$lang];?></span>
									</div>
									<div style="text-align:right;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-HYWUML span-16px span-black" style="margin-right:10px;"><?php echo $onSellOrderVolume;?></span>
										<span class="span-HYWUML span-14px span-grey" style="line-height:44px;"><?php echo $coin;?></span>
									</div>
								</div>
								<div class="height-40 back-color-white border-bottom-dotted-1" style="padding:0px 20px;line-height:40px;">
									<div style="text-align:left;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-normal span-13px span-grey">- <?php echo $langData['on_withdrawal'][$lang];?></span>
									</div>
									<div style="text-align:right;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-HYWUML span-16px span-black" style="margin-right:10px;"><?php echo $onWithdrawalAmount;?></span>
										<span class="span-HYWUML span-14px span-grey" style="line-height:44px;"><?php echo $coin;?></span>
									</div>
								</div>
								<div class="height-40 back-color-light border-bottom-solid-1" style="padding:0px 20px;line-height:40px;">
									<div style="text-align:left;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-normal span-14px span-black"><?php echo $langData['withdrawal_fee'][$lang];?></span>
									</div>
									<div style="text-align:right;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-HYWUML span-20px span-black" style="margin-right:10px;"><?php echo $withdrawFee;?></span>
										<span class="span-HYWUML span-14px span-grey" style="line-height:44px;"><?php echo $coin;?></span>
									</div>
								</div>
								<div class="height-40 back-color-light border-bottom-solid-1" style="padding:0px 20px;line-height:40px;">
									<div style="text-align:left;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-normal span-14px span-brown"><?php echo $langData['available_balance'][$lang];?></span>
									</div>
									<div style="text-align:right;width:50%;display:inline-block;float:left;height:20px;">
										<span class="span-HYWUML span-20px span-brown" style="<?php if ($day_withdraw_limit != '불가' && $day_withdraw_limit != '무제한') echo 'margin-right:10px;'; ?>"><?php echo $possibleWithdrawAmountToday;?></span>
										<span class="span-HYWUML span-14px span-brown" style="line-height:44px;"><?php if ($possibleWithdrawAmountToday != '불가' && $possibleWithdrawAmountToday != '무제한') echo $coin; ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="coinsky-fixed-width-1180 display-inline margin-top-20 panelShadowed">
					<ul class="nav nav-tabs panelShadowed basicBackground">
						<?php
							if($coin=='KRW'){
						?>
						<li class="tab-basic tab-dept-KRW active" style="border-left:0px !important;"><a class="border-right-solid-1" href="#short-deposit" data-toggle="tab"><?php echo $langData['no_invoice'][$lang];?> <?php echo $langData['deposit'][$lang];?></a></li>
						<li class="tab-basic tab-dept-KRW" style="border-left:0px;"><a href="#deposit" data-toggle="tab"><?php echo $langData['deposit'][$lang];?></a></li>
						<li class="tab-basic tab-dept-KRW"><a href="#withdraw" data-toggle="tab"><?php echo $langData['withdraw'][$lang];?></a></li>
						<?php		
							}else{
						?>
						<li class="tab-basic active" style="border-left:0px !important;"><a href="#deposit" data-toggle="tab"><?php echo $langData['deposit'][$lang];?></a></li>
						<li class="tab-basic"><a href="#withdraw" data-toggle="tab"><?php echo $langData['withdraw'][$lang];?></a></li>
						<?php
							}
						?>
					</ul>
					<div class="tab-content">
						<?php
							if($coin=='KRW'){
						?>
						<div class="back-color-white tab-pane fade in active" id="short-deposit">
							<div class="height-350 border-bottom-solid-1"></div>
							<div class="height-80 back-color-light text-align-center" style="padding:10px;"></div>
						</div>
						<?php		
							};
						?>
						<?php
							if($coin=='KRW'){
						?>
						<div class="back-color-white tab-pane fade" id="deposit">
						<?php		
							}else{
						?>				
						<div class="back-color-white tab-pane fade in active" id="deposit">
						<?php
							}
						?>
							<div class="height-350 border-bottom-solid-1" >
								<?php
									if($coin == 'BTC' || $coin =='ETH' || $coin == 'SKY' || $coin == 'BDR'){
								?>
								<?php 
									if($depositAddress==''){
								?>		
									<div class="depositAddress-app-certify-key-content" style="text-align: center;margin-left: 300px;">
										<div class="depositAddress-app-certify-key-description-content-1">
											<?php echo $langData['walt-dept-msg-1'][$lang];?>
										</div>
										<div class="depositAddress-app-certify-key-description-content-2">
											<?php echo $langData['walt-dept-msg-2'][$lang];?>
										</div>
										<div class="depositAddress-app-certify-key-description-content-2">
										</div>
										<div class="depositAddress-app-certify-key-description-content-3">
											<div class="depositAddress-key-copy-btn" id="generate_coin_address" style="margin-left: 175px;"><?php echo $langData['walt-dept-msg-3'][$lang];?></div>
											<input type="hidden" id="coin_deposit_address" value="empty">
										</div>
									</div>
								<?php
									}else{
								?>
									<div class="depositAddress-app-certify-content">
										<div class="depositAddress-app-certify-qrcode-content">
											<img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo $depositAddress;?>" class="img_depositAddress_qrcode">
										</div>
										<div class="depositAddress-app-certify-key-content">
											<div class="depositAddress-app-certify-key-description-content-1">
												<?php echo $langData['walt-dept-msg-4'][$lang];?>
											</div>
											<div class="depositAddress-app-certify-key-description-content-2">
												<?php echo $langData['walt-dept-msg-5'][$lang];?>
											</div>
											<div class="depositAddress-app-certify-key-description-content-2">
											</div>
											<div class="depositAddress-app-certify-key-description-content-3">
												<div class="depositAddress-key-content" id="depositAddressKey"><?php echo $depositAddress;?></div>
												<div class="depositAddress-key-copy-btn" id="copydepositAddresskey"><?php echo $langData['walt-dept-msg-6'][$lang];?></div>
											</div>
										</div>
									</div>
								<?php
									}
								?>								
								<?php
									}else if($coin == 'KRW'){
								?>
									<div class="width-480" style="padding-top:59px;height:223px;float:left;margin-left:70px;">
										<div class="inputbox-content">
											<span class="span-normal-bold span-15px span-black float-left">입금자명</span>
											<input type="text" class="form-control width-348 float-right" placeholder="입금자명을 입력하세요." id="username_Deposit" value="<?php echo $userdata['f_username'].str_pad($userdata['f_id'], 4, '0', STR_PAD_LEFT);?>">
										</div>
										<span class="span-12px span-grey" style="position:absolute;margin-top:-20px;margin-left:132px;">(입금시, 입금자명을 반드시 [<span class="span-red"><?php echo $userdata['f_username'] . str_pad($userdata['f_id'], 4, '0', STR_PAD_LEFT); ?></span>]으로 해주세요)</span>
										<div class="inputbox-content">
											<span class="span-normal-bold span-15px span-black float-left">입금하실금액</span>
											<input type="number" class="form-control width-348 float-right" placeholder="입금하실금액을 입력하세요." id="amount_Deposit" min="1000" value="1000">
										</div>
										<div class="inputbox-content">
											<span class="span-normal-bold span-15px span-black float-left">은행명</span>
											<input type="text" class="form-control width-348 float-right" placeholder="은행명을 입력하세요." id="bankName_Deposit">
										</div>
										<div class="inputbox-content">
											<span class="span-normal-bold span-15px span-black float-left">계좌번호</span>
											<input type="text" class="form-control width-348 float-right" placeholder="계좌번호를 입력하세요." id="bankId_Deposit">
										</div>
									</div>
									<div class="width-480" style="padding-top:59px;height:223px;float:right;margin-right:70px;">
										<div class="inputbox-content">
											<div class="inputbox-content">
												<span class="span-normal-bold span-15px span-black float-left">비밀번호</span>
												<input type="password" class="form-control width-348 float-right" placeholder="<?php echo $langData['walt-dept-msg-12'][$lang]; ?>" id="password_Deposit">
											</div>
											<span class="span-normal-bold span-15px span-black float-left"><?php echo $langData['walt-dept-msg-16'][$lang]; ?></span>
											<div class="width-350 float-right">
												<span class="span-normal-bold span-13px span-light span-background-blue" id="submitPhoneNumber_Deposit" style="padding:8px 30px;cursor: pointer;"><?php echo $langData['walt-dept-msg-15'][$lang]; ?></span>
											</div>
											<div class="width-350 float-right" style="margin-top:20px;">
												<input type="text" class="form-control width-270 float-left" id="phoneConfirm_Deposit" placeholder="<?php echo $langData['walt-dept-msg-17'][$lang]; ?>">
												<span class="dept-form-timer-count" id="phoneNumberConfirmCount_Deposit"></span>
												<span class="span-normal-bold span-13px span-light span-background-blue float-right" style="padding:0px 20px;cursor: pointer;" id="phoneNumberConfirmSubmit_Deposit"><?php echo $langData['walt-dept-msg-18'][$lang]; ?></span>
											</div>
											<button class="span-normal-bold span-16px span-light span-background-black" style="padding:6px 94px;border:0px;margin-left:130px;margin-top: 20px;" id="submit_Deposit">원화 입금요청</button>
										</div>
									</div>
								<?php
									}
								?>
							</div>
							<div class="height-80 back-color-light text-align-center" style="padding:10px;">
								<?php
									if ($coin == 'KRW') {
								?>
									<span style="line-height:32px;" class="span-HYWUML span-blue span-18px">입금할 계좌번호 : 하나은행 (주)스카이제이엔티 863-910012-30504</span><br><span class="pan-malgun span-grey span-12">※ 주의 원화(KRW)를 처음 입금하시는 경우, 72시간 동안 출금이 제한됩니다.</span>
								<?php
									}
								?>
							</div>
						</div>
						<div class="back-color-white tab-pane fade" id="withdraw">
							<?php
								if($userdata['f_kyc_level'] < 2){
							?>
								<div class="height-350 border-bottom-solid-1 empty-panel">
									<p style="text-align: center;padding-top: 230px;"><span class="span-16px span-black">[<?php echo $langData['walt-dept-msg-7'][$lang];?>]</span><span class="span-16px span-grey"><?php echo $langData['walt-dept-msg-8'][$lang];?></span></p>
								<?php
									}else{
								?>			
								<div class="height-350 border-bottom-solid-1">					
									<?php	
										if($coin == 'BTC' || $coin =='ETH' || $coin == 'SKY' || $coin == 'BDR'){
									?>
										<div class="width-480 margin-0-auto" style="padding-top:59px;height:223px;">
											<div class="inputbox-content">
												<span class="span-normal-bold span-15px span-black float-left"><?php echo $langData['walt-dept-msg-9'][$lang];?></span>
												<input type="text" class="form-control width-348 float-right" placeholder="<?php echo $langData['walt-dept-msg-10'][$lang];?>" id="to_address">
											</div>
											<div class="inputbox-content">
												<span class="span-normal-bold span-15px span-black float-left"><?php echo $langData['walt-dept-msg-13'][$lang];?></span>
												<input type="text" class="form-control width-348 float-right" placeholder="<?php echo $langData['walt-dept-msg-11'][$lang];?>" id="amount">
											</div>
											<div class="inputbox-content">
												<span class="span-normal-bold span-15px span-black float-left"><?php echo $langData['walt-dept-msg-14'][$lang];?></span>
												<input type="password" class="form-control width-348 float-right" placeholder="<?php echo $langData['walt-dept-msg-12'][$lang];?>" id="password">
											</div>
										</div>
										<div class="width-480 margin-0-auto">
											<div class="inputbox-content">
												<span class="span-normal-bold span-15px span-black float-left"><?php echo $langData['walt-dept-msg-16'][$lang];?></span>
												<div class="width-350 float-right">
													<span class="span-normal-bold span-13px span-light span-background-blue" id="submitPhoneNumber" style="padding:8px 30px;cursor: pointer;"><?php echo $langData['walt-dept-msg-15'][$lang];?></span>
												</div>
												<div class="width-350 float-right" style="margin-top:20px;">
													<input type="text" class="form-control width-270 float-left" id="phoneConfirm" placeholder="<?php echo $langData['walt-dept-msg-17'][$lang];?>">
													<span class="dept-form-timer-count" id="phoneNumberConfirmCount"></span>
													<span class="span-normal-bold span-13px span-light span-background-blue float-right" style="padding:0px 20px;cursor: pointer;" id="phoneNumberConfirmSubmit"><?php echo $langData['walt-dept-msg-18'][$lang];?></span>
												</div>
											</div>
										</div>
									<?php
										}else if($coin == 'KRW'){
									?>
										<div class="width-480" style="padding-top:59px;height:223px;float:left;margin-left:70px;">
										<div class="inputbox-content">
											<span class="span-normal-bold span-15px span-black float-left">출금신청금액</span>
											<input type="number" class="form-control width-348 float-right" placeholder="출금신청금액을 입력하세요." id="request_amount_Withdraw" min="1000" value="1000">
										</div>
										<div class="inputbox-content">
											<span class="span-normal-bold span-15px span-black float-left">은행명</span>
											<input type="text" class="form-control width-348 float-right" placeholder="은행명을 입력하세요." id="bankName_Withdraw" value="<?php echo $userdata['f_withdraw_bank'];?>">
										</div>
										<div class="inputbox-content">
											<span class="span-normal-bold span-15px span-black float-left">계좌번호</span>
											<input type="text" class="form-control width-348 float-right" placeholder="계좌번호를 입력하세요." id="bankId_Withdraw" value="<?php echo $userdata['f_withdraw_bank_no'];?>">
										</div>
										<div class="inputbox-content">
											<span class="span-normal-bold span-15px span-black float-left">예금주</span>
											<input type="text" class="form-control width-348 float-right" placeholder="예금주명을 입력하세요." id="accountName_Withdraw" value="<?php echo $userdata['f_withdraw_account_name'];?>">
										</div>
									</div>
									<div class="width-480" style="padding-top:59px;height:223px;float:right;margin-right:70px;">
										<div class="inputbox-content">
											<div class="inputbox-content">
												<span class="span-normal-bold span-15px span-black float-left">수수료</span>
												<input type="text" class="form-control width-348 float-right" id="fee_Withdraw" value="<?php echo $withdrawFee;?>" readonly>
											</div>
											<div class="inputbox-content">
												<span class="span-normal-bold span-15px span-black float-left">출금액(수수료제외)</span>
												<input type="text" class="form-control width-348 float-right" id="amount_Withdraw" value="0" readonly>
											</div>
											<div class="inputbox-content">
												<span class="span-normal-bold span-15px span-black float-left">비밀번호</span>
												<input type="password" class="form-control width-348 float-right" placeholder="<?php echo $langData['walt-dept-msg-12'][$lang]; ?>" id="password_Withdraw">
											</div>
											<span class="span-normal-bold span-15px span-black float-left"><?php echo $langData['walt-dept-msg-16'][$lang]; ?></span>
											<div class="width-350 float-right">
												<span class="span-normal-bold span-13px span-light span-background-blue" id="submitPhoneNumber" style="padding:8px 30px;cursor: pointer;"><?php echo $langData['walt-dept-msg-15'][$lang]; ?></span>
											</div>
											<div class="width-350 float-right" style="margin-top:20px;">
												<input type="text" class="form-control width-270 float-left" id="phoneConfirm" placeholder="<?php echo $langData['walt-dept-msg-17'][$lang]; ?>">
												<span class="dept-form-timer-count" id="phoneNumberConfirmCount"></span>
												<input type="hidden" id="phoneNumber" value="<?php echo $userdata['f_phone']; ?>">
												<span class="span-normal-bold span-13px span-light span-background-blue float-right" style="padding:0px 20px;cursor: pointer;" id="phoneNumberConfirmSubmit"><?php echo $langData['walt-dept-msg-18'][$lang]; ?></span>
											</div>
										</div>
									</div>
									<?php
										}
									?>
								<?php
									}

								?>
							</div>
							<div class="height-80 back-color-light text-align-center" style="padding:10px;">
								<?php
									if($coin == 'KRW' || $coin == 'BTC' || $coin =='ETH' || $coin == 'SKY' || $coin == 'BDR'){
								?>
								<?php
									if($userdata['f_kyc_level'] < 2){
								?>
									<span style="line-height: 50px;cursor: pointer;" class="span-HYWUML span-under-line span-blue span-18px" onclick="window.location.href=base_url+'acnt/levl';">&nbsp;&nbsp;&nbsp;<?php echo $langData['walt-dept-msg-19'][$lang];?> > > >&nbsp;&nbsp;&nbsp;</span>
								<?php
									}else{
								?>
									<button class="span-normal-bold span-16px span-light span-background-black" style="padding:12px 94px;border:0px;margin-left:2px;margin-top: 7px;" id="submit_withdraw"><?php echo $langData['walt-dept-msg-20'][$lang];?></button>
								<?php
									}
								?>
								<?php
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
