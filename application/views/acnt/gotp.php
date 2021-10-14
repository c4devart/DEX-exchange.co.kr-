	<div class="coinsky-page">
		<div class="coinsky-page-header">
			<span class="coinsky-page-header-title"><?php echo $langData['acnt-gotp-msg-1'][$lang];?></span>
		</div>
		<div class="coinsky-page-content back-color-light">
			<div class="width-1400 height-<?php if($googleOtpStatus == 1){echo '610';}else{echo '1060';};?> panelShadowed">
				<div class="otp-content-header"><?php echo $langData['acnt-gotp-msg-2'][$lang];?></div>
				<div class="otp-content-app-container">
					<div class="otp-container-header">
						<div class="otp-container-header-left"><?php echo $langData['acnt-gotp-msg-3'][$lang];?></div>
						<div class="otp-container-header-right"><?php echo $langData['acnt-gotp-msg-4'][$lang];?></div>
					</div>
					<div class="otp-container-description"><?php echo $langData['acnt-gotp-msg-5'][$lang];?></div>
					<div class="otp-app-download-content">
						<div class="otp-app-icon-content ios"></div>
						<div class="otp-app-url-content">
							<div class="otp-app-url-content-header">
								iOS
							</div>
							<div class="otp-app-url-content-center">
								Google Authenticator
							</div>
							<div class="otp-app-url-content-footer" onclick="window.open('https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8')">
								<?php echo $langData['acnt-gotp-msg-6'][$lang];?> >
							</div>
						</div>
						<div class="otp-app-icon-content android" style="margin-left: 100px;"></div>
						<div class="otp-app-url-content">
							<div class="otp-app-url-content-header">
								Android
							</div>
							<div class="otp-app-url-content-center">
								Google OTP
							</div>
							<div class="otp-app-url-content-footer" onclick="window.open('https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2')">
								<?php echo $langData['acnt-gotp-msg-7'][$lang];?> >
							</div>
						</div>
					</div>
				</div>
				<?php
					if($googleOtpStatus == 0){
				?>				
				<div class="otp-content-certify-container">
					<div class="otp-container-header">
						<div class="otp-container-header-left"><?php echo $langData['acnt-gotp-msg-8'][$lang]; ?></div>
						<div class="otp-container-header-right"><?php echo $langData['acnt-gotp-msg-9'][$lang]; ?></div>
					</div>
					<div class="otp-container-description"><?php echo $langData['acnt-gotp-msg-10'][$lang]; ?></div>
					<div class="otp-app-certify-content">
						<div class="otp-app-certify-qrcode-content">
							<img src="<?php echo $qrcode_url; ?>" class="img_otp_qrcode">
						</div>
						<div class="otp-app-certify-key-content">
							<div class="otp-app-certify-key-description-content-1">
								<?php echo $langData['acnt-gotp-msg-11'][$lang]; ?>
							</div>
							<div class="otp-app-certify-key-description-content-2">
								<?php echo $langData['acnt-gotp-msg-12'][$lang]; ?>
							</div>
							<div class="otp-app-certify-key-description-content-2">
							</div>
							<div class="otp-app-certify-key-description-content-3">
								<div class="otp-key-content" id="otpKey"><?php echo $user_data['f_google2fa_key']; ?></div>
								<div class="otp-key-copy-btn" id="copyOtpkey"><?php echo $langData['acnt-gotp-msg-13'][$lang]; ?></div>
							</div>
						</div>
					</div>
				</div>
				<?php
					}else{
				?>
				<input type="hidden" id="otpKey" value="<?php echo $user_data['f_google2fa_key']; ?>">
				<?php
					}
				?>
				<div class="otp-content-confirm-container">
					<div class="otp-content-confirm-container-header"><?php echo $langData['acnt-gotp-msg-14'][$lang];?></div>
					<div class="otp-content-confirm-container-center">
						<div class="otp-confirm-description"><?php echo $langData['acnt-gotp-msg-15'][$lang];?></div>
						<input type="text" class="otp-confirm-inputbox" id="otpConfirmKey" placeholder="<?php echo $langData['acnt-gotp-msg-8'][$lang];?><?php echo $langData['acnt-gotp-msg-16'][$lang];?>">
						<?php
							if($user_data['f_google2fa_status'] == 1){
								echo '<div class="otp-key-copy-btn" id="turnOffOtp">'.$langData['acnt-gotp-msg-17'][$lang].'</div>';
							}else{
								echo '<div class="otp-key-passive-btn" id="turnOnOtp">'.$langData['acnt-gotp-msg-18'][$lang].'</div>';
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
