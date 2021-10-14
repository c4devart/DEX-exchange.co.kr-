	<div class="coinsky-page">
		<div class="coinsky-page-header">
			<div class="width-350 height-80 display-inline">
				<span class="coinsky-page-header-title"><?php echo $langData['acnt-prof-msg-28'][$lang];?></span>
			</div>
			<div class="width-1050 height-80 display-inline">
				<div class="height-40 border-bottom-dotted-1" style="line-height:48px;">
					<div class="width-250 display-inline"><span class="span-normal-bold span-16px span-black"><?php echo $langData['acnt-prof-msg-32'][$lang];?></span></div>
					<div class="width-250 display-inline"><span class="span-normal-bold span-16px span-black"><?php echo $langData['acnt-prof-msg-33'][$lang];?></span></div>
					<div class="width-250 display-inline"><span class="span-normal-bold span-16px span-black"><?php echo $langData['acnt-prof-msg-34'][$lang];?></span></div>
					<div class="width-250 display-inline"><span class="span-normal-bold span-16px span-black"><?php echo $langData['acnt-prof-msg-35'][$lang];?></span></div>
				</div>
				<div class="height-40" style="line-height:6px;">
					<div class="width-250 display-inline"><span class="span-normal span-14px span-grey"><?php echo $user_data['f_username'];?></span></div>
					<div class="width-250 display-inline"><span class="span-normal span-14px span-grey"><?php echo $user_data['f_email'];?></span></div>
					<div class="width-250 display-inline"><span class="span-normal span-14px span-grey"><?php echo $user_data['f_phone'];?></span></div>
					<div class="width-250 display-inline"><span class="span-normal span-14px span-grey"><?php echo $user_data['f_address'];?></span></div>
				</div>
			</div>
		</div>
		<div class="coinsky-fixed-width-1400 display-inline panelShadowed">
			<ul class="nav nav-tabs panelShadowed basicBackground">
				<li class="tab-basic <?php if($tab=='password'){echo 'active';};?>" style="border-left:0px !important;"><a href="#change-password" data-toggle="tab"><?php echo $langData['acnt-prof-msg-29'][$lang];?></a></li>
				<li class="tab-basic <?php if($tab=='mobile'){echo 'active';};?>"><a href="#change-mobile-number" data-toggle="tab"><?php echo $langData['acnt-prof-msg-30'][$lang];?></a></li>
				<li class="tab-basic <?php if($tab=='close'){echo 'active';};?>"><a href="#close-account" data-toggle="tab"><?php echo $langData['acnt-prof-msg-31'][$lang];?></a></li>
			</ul>
			<div class="tab-content basicBackground">
				<div class="tab-pane fade <?php if($tab=='password'){echo 'in active';};?>" id="change-password">
					<div class="height-626 border-bottom-solid-1">
						<div class="width-480 margin-0-auto" style="padding-top:59px;height:502px;">
							<div class="inputbox-content">
								<span class="span-normal-bold span-15px span-black float-left"><?php echo $langData['acnt-prof-msg-36'][$lang];?></span>
								<input type="password" class="form-control width-348 float-right" placeholder="<?php echo $langData['acnt-prof-msg-25'][$lang];?>" id="currentPassword">
							</div>
							<div class="inputbox-content">
								<span class="span-normal-bold span-15px span-black float-left"><?php echo $langData['acnt-prof-msg-37'][$lang];?></span>
								<input type="password" class="form-control width-348 float-right" placeholder="<?php echo $langData['acnt-prof-msg-26'][$lang];?>" id="newPassword">
							</div>
							<div class="inputbox-content">
								<span class="span-normal-bold span-15px span-black float-left"><?php echo $langData['acnt-prof-msg-38'][$lang];?></span>
								<input type="password" class="form-control width-348 float-right" placeholder="<?php echo $langData['acnt-prof-msg-27'][$lang];?>" id="confirmPassword">
							</div>
							<div class="width-350 float-right" style="padding:13px 20px;border:1px solid #c7c7c7;">
								<span class="span-normal-bold span-14px span-grey" style="line-height:40px;"><?php echo $langData['acnt-prof-msg-1'][$lang];?></span><br>
								<div class="change-password-form passive" id="conditionSame"><?php echo $langData['acnt-prof-msg-2'][$lang];?></div>
								<div class="change-password-form passive" id="conditionAlpha"><?php echo $langData['acnt-prof-msg-3'][$lang];?></div>
								<div class="change-password-form passive" id="conditionNumeric"><?php echo $langData['acnt-prof-msg-4'][$lang];?></div>
								<div class="change-password-form passive" id="conditionSpec"><?php echo $langData['acnt-prof-msg-5'][$lang];?></div>
								<div class="change-password-form passive" id="conditionLength"><?php echo $langData['acnt-prof-msg-6'][$lang];?></div>
								<div class="change-password-form passive" id="conditionConfirm"><?php echo $langData['acnt-prof-msg-7'][$lang];?></div>
								<div class="change-password-form-conditions-footer">※ <?php echo $langData['acnt-prof-msg-8'][$lang];?></div>
							</div>
						</div>
						<div class="width-480 margin-0-auto">
							<div class="inputbox-content">
								<span class="span-normal-bold span-15px span-black float-left"><?php echo $langData['acnt-prof-msg-39'][$lang];?></span>
								<div class="width-350 float-right">
									<span class="span-normal-bold span-13px span-light span-background-blue" id="submitPhoneNumber" style="padding:8px 30px;cursor: pointer;"><?php echo $langData['acnt-prof-msg-9'][$lang];?></span>
								</div>
								<div class="width-350 float-right" style="margin-top:20px;">
									<input type="text" class="form-control width-270 float-left" id="phoneConfirm" placeholder="<?php echo $langData['acnt-prof-msg-10'][$lang];?>">
									<span class="register-form-timer-count" id="phoneNumberConfirmCount"></span>
									<input type="hidden" id="phoneNumber" value="<?php echo $user_data['f_phone'];?>">
									<span class="span-normal-bold span-13px span-light span-background-blue float-right" style="padding:0px 20px;cursor: pointer;" id="phoneNumberConfirmSubmit"><?php echo $langData['acnt-prof-msg-40'][$lang];?></span>
								</div>
							</div>
						</div>
					</div>
					<div class="height-80 back-color-light align-center" style="padding-top:17px;">
						<button class="span-normal-bold span-16px span-light span-background-black" style="padding:12px 94px;border:0px;margin-left:2px;" id="submit_change"><?php echo $langData['acnt-prof-msg-41'][$lang];?></button>
					</div>
				</div>
				<div class="tab-pane fade <?php if($tab=='mobile'){echo 'in active';};?>" id="change-mobile-number">
					<div class="height-319 border-bottom-solid-1">
						<div class="width-480 margin-0-auto" style="padding-top:59px;height:112px;">
							<div class="inputbox-content">
								<span class="span-normal-bold span-15px span-black float-left"><?php echo $langData['acnt-prof-msg-34'][$lang];?></span>
								<input type="text" class="form-control width-348 float-right" id="phoneNumber_change" placeholder="<?php echo $langData['acnt-prof-msg-11'][$lang];?>">
							</div>
						</div>
						<div class="width-480 margin-0-auto" style="height:98px;">
							<div class="inputbox-content">
								<span class="span-normal-bold span-15px span-black float-left"><?php echo $langData['acnt-prof-msg-39'][$lang];?></span>
								<div class="width-350 float-right">
									<span class="span-normal-bold span-13px span-light span-background-blue" id="submitPhoneNumber_change" style="padding:8px 30px;margin-left:3px;cursor: pointer;"><?php echo $langData['acnt-prof-msg-9'][$lang];?></span>
								</div>
								<div class="width-350 float-right" style="margin-top:20px;">
									<input type="text" class="form-control width-270 float-left" id="phoneConfirm_change" placeholder="<?php echo $langData['acnt-prof-msg-10'][$lang];?>" style="margin-left:3px;">
									<span class="register-form-timer-count" id="phoneNumberConfirmCount_change"></span>
									<span class="span-normal-bold span-13px span-light span-background-blue float-right" style="padding:0px 20px;cursor: pointer;" id="phoneNumberConfirmSubmit_change"><?php echo $langData['acnt-prof-msg-40'][$lang];?></span>
								</div>
							</div>
								<span class="span-normal span-12px span-grey" style="position:absolute;margin-top:50px;cursor: pointer;margin-left:132px;"><?php echo $langData['acnt-prof-msg-12'][$lang];?></span>
						</div>
					</div>
					<div class="height-80 back-color-light align-center" style="padding-top:17px;">
						<button class="span-normal-bold span-16px span-light span-background-black" style="padding:12px 94px;border:0px;margin-left:7px;" id="changePhoneNumberSubmit"><?php echo $langData['acnt-prof-msg-41'][$lang];?></button>
					</div>
				</div>
				<div class="tab-pane fade<?php if($tab=='close'){echo 'in active';};?>" id="close-account">
					<div class="height-816 border-bottom-solid-1">
						<p style="text-align: center;padding-top:60px;" class="span-normal span-15px span-black">
							<?php echo $langData['acnt-prof-msg-13'][$lang];?>
						</p>
						<p style="text-align: center;" class="span-normal-bold span-15px span-brown">
							<?php echo $langData['acnt-prof-msg-14'][$lang];?>
						</p>
						<input type="hidden" id="balanceStatus" value="<?php echo $balanceStatus;?>">
						<input type="hidden" id="onOrderStatus" value="<?php echo $onOrderStatus;?>">
						<input type="hidden" id="onWithdrawStatus" value="<?php echo $onWithdrawStatus;?>">
						<input type="hidden" id="blockStatus" value="<?php echo $blockStatus;?>">
						<div class="width-700 height-232 margin-0-auto" style="padding:13px 29px;border:1px solid #c7c7c7;margin-top:20px;">
							<span class="span-normal-bold span-14px span-grey" style="line-height:40px;"><?php echo $langData['acnt-prof-msg-15'][$lang];?></span><br>

							<div class="confirm-close<?php if($balanceStatus == true){echo ' passive';}else{echo ' active';};?>" id=""><?php echo $langData['acnt-prof-msg-16'][$lang];?></div>
							<div class="confirm-close<?php if($onOrderStatus == true){echo ' passive';}else{echo ' active';};?>" id="conditionAlpha"><?php echo $langData['acnt-prof-msg-17'][$lang];?></div>
							<div class="confirm-close<?php if($onWithdrawStatus == true){echo ' passive';}else{echo ' active';};?>" id="conditionNumeric"><?php echo $langData['acnt-prof-msg-18'][$lang];?></div>
							<div class="confirm-close<?php if($blockStatus == true){echo ' passive';}else{echo ' active';};?>" id="conditionSpec"><?php echo $langData['acnt-prof-msg-19'][$lang];?></div>
							<div class="confirm-close-footer">※ <?php echo $langData['acnt-prof-msg-20'][$lang];?></div>
							<div class="confirm-close-footer">※ <?php echo $langData['acnt-prof-msg-21'][$lang];?></div>
						</div>
						<div class="width-400 height-175 margin-0-auto" style="padding:13px 29px;margin-top:20px;">
							<span class="span-normal-bold span-14px span-grey" style="line-height:40px;"><?php echo $langData['acnt-prof-msg-22'][$lang];?></span><br>
							<span class="span-normal span-12px span-grey" style="margin-left:20px;line-height:25px;">- <?php echo $langData['acnt-prof-msg-42'][$lang];?></span><br>
							<span class="span-normal span-12px span-grey" style="margin-left:20px;line-height:25px;">- <?php echo $langData['acnt-prof-msg-43'][$lang];?></span><br>
							<span class="span-normal span-12px span-grey" style="margin-left:20px;line-height:25px;">- <?php echo $langData['acnt-prof-msg-44'][$lang];?></span><br>
							<!-- <input type="checkbox" id="agree_close" style="line-height:40px;">
							<span class="span-normal-bold span-14px span-grey" style="line-height:40px;"><?php echo $langData['acnt-prof-msg-22'][$lang];?>(필수)</span> -->

							<div class="register-form-accept-sub-3">
								<div class="register-form-accept-sub-3-left display-inline img-check-off" id="agreePrivacy"></div>
								<div class="register-form-accept-sub-3-right display-inline" id="imgAgreePrivacy"><?php echo $langData['acnt-prof-msg-24'][$lang];?></div>
							</div>
						</div>
						<div class="width-480 margin-0-auto" style="height:54px;margin-top:20px;">
							<div class="inputbox-content">
								<span class="span-normal-bold span-15px span-black float-left"><?php echo $langData['acnt-prof-msg-45'][$lang];?></span>
								<input type="password" class="form-control width-348 float-right" style="width:368px;" placeholder="<?php echo $langData['acnt-prof-msg-23'][$lang];?>" id="close_password">
							</div>
						</div>
						<div class="width-480 margin-0-auto">
							<div class="inputbox-content">
								<span class="span-normal-bold span-15px span-black float-left"><?php echo $langData['acnt-prof-msg-39'][$lang];?></span>
								<div class="width-370 float-right">
									<span class="span-normal-bold span-13px span-light span-background-blue" id="submitPhoneNumber_close" style="padding:8px 30px;margin-left:3px;cursor: pointer;"><?php echo $langData['acnt-prof-msg-9'][$lang];?></span>
									<input type="hidden" id="phoneNumber_close" value="<?php echo $user_data['f_phone'];?>">
								</div>
								<div class="width-370 float-right" style="margin-top:20px;">
									<input type="text" class="form-control width-270 float-left" id="phoneConfirm_close" placeholder="<?php echo $langData['acnt-prof-msg-10'][$lang];?>" style="margin-left:3px;">
									<span class="register-form-timer-count" id="phoneNumberConfirmCount_close"></span>
									<span class="span-normal-bold span-13px span-light span-background-blue float-right" id="phoneNumberConfirmSubmit_close" style="padding:0px 20px;cursor: pointer;"><?php echo $langData['acnt-prof-msg-40'][$lang];?></span>
								</div>
							</div>
						</div>
					</div>
					<div class="height-80 back-color-light align-center" style="padding-top:17px;">
						<button class="span-normal-bold span-16px span-light span-background-black" style="padding:12px 94px;border:0px;margin-left:2px;" id="submit_close"><?php echo $langData['acnt-prof-msg-46'][$lang];?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
