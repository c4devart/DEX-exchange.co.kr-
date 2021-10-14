<div class="coinsky-page">
    <div class="login-form">
        <div class="login-form-header">
            <div class="login-form-header-content active" onclick="window.location.href=base_url+'acnt/siin';">
                <?php echo $langData['acnt-siin-msg-5'][$lang];?>
            </div>
            <div class="login-form-header-content passive" onclick="window.location.href=base_url+'acnt/siup';">
                <?php echo $langData['acnt-siin-msg-6'][$lang];?>
            </div>
        </div>
        <div class="login-form-accept">
            <div class="login-form-accept-sub-1"><?php echo $langData['acnt-siin-msg-1'][$lang];?></div>
            <div class="login-form-content-url-confirm">http://45.76.180.140:7801/acnt/siin</div>
        </div>
        <div class="login-form-content">
            <input type="text" class="login-form-content-inputbox" id="email" placeholder="<?php echo $langData['acnt-siin-msg-2'][$lang];?>">
            <input type="password" class="login-form-content-inputbox margin-top-20" id="password" placeholder="<?php echo $langData['acnt-siin-msg-3'][$lang];?>">
			<input type="password" class="login-form-content-inputbox margin-top-20" id="google2fa_key" placeholder="<?php echo $langData['acnt-siin-msg-7'][$lang]; ?>">
            <div class="login-form-forget" onclick="window.location.href=base_url+'acnt/forg';" style="cursor: pointer;"><?php echo $langData['acnt-siin-msg-4'][$lang];?></div>
        </div>
        <div class="login-form-btn">
            <button class="login-form-submit" id="submit_signin"><?php echo $langData['acnt-siin-msg-5'][$lang];?></button>
        </div>
    </div>
</div>
