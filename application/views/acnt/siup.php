<div class="coinsky-page">
	<div class="register-form">
		<div class="register-form-header">
			<div class="register-form-header-content passive" onclick="window.location.href=base_url+'acnt/siin';">
				<?php echo $langData['acnt-siup-msg-21'][$lang];?>
			</div>
			<div class="register-form-header-content active" onclick="window.location.href=base_url+'acnt/siup';">
				<?php echo $langData['acnt-siup-msg-20'][$lang];?>
			</div>
		</div>
		<div class="register-form-accept">
			<div class="register-form-accept-sub-1">※ <?php echo $langData['acnt-siup-msg-1'][$lang];?></div>
			<div class="register-form-accept-sub-2">
				<div class="register-form-accept-sub-2-left display-inline img-check-off" id="agreeAll"></div>
				<div class="register-form-accept-sub-2-right display-inline" id="imgAgreeAll"><?php echo $langData['acnt-siup-msg-22'][$lang];?></div>
			</div>
			<div class="register-form-accept-sub-3">
				<div class="register-form-accept-sub-3-left display-inline img-check-off" id="agreeProvision"></div>
				<div class="register-form-accept-sub-3-right display-inline" id="imgAgreeProvision"><?php echo $langData['acnt-siup-msg-15'][$lang];?> <a class="span-under-line float-right" target="_blank" href="<?php echo base_url();?>cusc/provision"><?php echo $langData['acnt-siup-msg-19'][$lang];?></a></div>
			</div>
			<div class="register-form-accept-sub-3">
				<div class="register-form-accept-sub-3-left display-inline img-check-off" id="agreePrivacy"></div>
				<div class="register-form-accept-sub-3-right display-inline" id="imgAgreePrivacy"><?php echo $langData['acnt-siup-msg-14'][$lang];?> <a class="span-under-line float-right" target="_blank" href="<?php echo base_url();?>cusc/privacy"><?php echo $langData['acnt-siup-msg-19'][$lang];?></a></div>
			</div>
		</div>
		<div class="register-form-content">
			<div class="register-form-content-header"><?php echo $langData['acnt-siup-msg-23'][$lang];?></div>
			<input type="text" class="register-form-content-inputbox margin-20" placeholder="<?php echo $langData['acnt-siup-msg-2'][$lang];?>" id="username">
			<input type="text" class="register-form-content-inputbox margin-20" placeholder="<?php echo $langData['acnt-siup-msg-3'][$lang];?>" id="email">
			<input type="password" class="register-form-content-inputbox margin-10" placeholder="<?php echo $langData['acnt-siup-msg-4'][$lang];?>" id="password">
			<input type="password" class="register-form-content-inputbox margin-10" placeholder="<?php echo $langData['acnt-siup-msg-5'][$lang];?>" id="confirmPassword">
			<div class="register-form-content-pass-conditions">
				<div class="register-form-content-pass-conditions-header"><?php echo $langData['acnt-siup-msg-13'][$lang];?></div>
				<div class="register-form-content-pass-conditions-content">
					<div class="register-form-content-pass-conditions-content-sub passive" id="conditionAlpha"><?php echo $langData['acnt-siup-msg-6'][$lang];?></div>
					<div class="register-form-content-pass-conditions-content-sub passive" id="conditionNumeric"><?php echo $langData['acnt-siup-msg-7'][$lang];?></div>
					<div class="register-form-content-pass-conditions-content-sub passive" id="conditionSpec"><?php echo $langData['acnt-siup-msg-8'][$lang];?></div>
					<div class="register-form-content-pass-conditions-content-sub passive" id="conditionLength"><?php echo $langData['acnt-siup-msg-9'][$lang];?></div>
					<div class="register-form-content-pass-conditions-content-sub passive" id="conditionConfirm"><?php echo $langData['acnt-siup-msg-10'][$lang];?></div>
					<div class="register-form-content-pass-conditions-footer">※ <?php echo $langData['acnt-siup-msg-11'][$lang];?></div>
				</div>
			</div>
			<div class="register-form-content-confirm-content margin-20">
				<input type="text" class="register-form-content-confirm-content-inputbox" id="phoneNumber" placeholder="<?php echo $langData['acnt-siup-msg-17'][$lang];?>">
				<button class="register-form-content-confirm-btn" id="submitPhoneNumber"><?php echo $langData['acnt-siup-msg-18'][$lang];?></button>
			</div>
			<div class="register-form-content-confirm-content margin-10">
				<input type="text" class="register-form-content-confirm-content-inputbox" id="phoneConfirm" placeholder="<?php echo $langData['acnt-siup-msg-16'][$lang];?>">
				<span class="register-form-timer-count" id="phoneNumberConfirmCount"></span>
				<button class="register-form-content-confirm-btn" id="phoneNumberConfirmSubmit"><?php echo $langData['acnt-siup-msg-24'][$lang];?></button>
			</div>
			<div class="register-form-content-footer">※ <?php echo $langData['acnt-siup-msg-12'][$lang];?></div>
		</div>
		<div class="register-form-btn">
			<button class="register-form-submit" id="submit_signup"><?php echo $langData['acnt-siup-msg-20'][$lang];?></button>
		</div>
	</div>
</div>
