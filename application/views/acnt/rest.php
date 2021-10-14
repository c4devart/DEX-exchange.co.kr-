<div class="coinsky-page">
    <div class="register-form">
        <div class="register-form-header">
            <div class="register-form-header-content active" style="margin-left: 131px;">
                <?php echo $langData['anct-rest-msg-1'][$lang];?>
            </div>
        </div>
        <div class="register-form-accept">
            <div class="register-form-accept-sub-1">
                <?php echo $langData['anct-rest-msg-2'][$lang];?><br>
                <?php echo $langData['anct-rest-msg-3'][$lang];?>
            </div>
        </div>
        <div class="register-form-content">
            <input type="password" class="register-form-content-inputbox margin-top-20" id="password" placeholder="<?php echo $langData['anct-rest-msg-13'][$lang];?>">
            <input type="password" class="register-form-content-inputbox margin-top-20" id="confirmPassword" placeholder="<?php echo $langData['anct-rest-msg-12'][$lang];?>">
            <div class="register-form-content-pass-conditions margin-top-20">
                <div class="register-form-content-pass-conditions-header"><?php echo $langData['anct-rest-msg-4'][$lang];?></div>
                <div class="register-form-content-pass-conditions-content">
                    <div class="register-form-content-pass-conditions-content-sub passive" id="conditionAlpha"><?php echo $langData['anct-rest-msg-5'][$lang];?></div>
                    <div class="register-form-content-pass-conditions-content-sub passive" id="conditionNumeric"><?php echo $langData['anct-rest-msg-6'][$lang];?></div>
                    <div class="register-form-content-pass-conditions-content-sub passive" id="conditionSpec"><?php echo $langData['anct-rest-msg-7'][$lang];?></div>
                    <div class="register-form-content-pass-conditions-content-sub passive" id="conditionLength"><?php echo $langData['anct-rest-msg-8'][$lang];?></div>
                    <div class="register-form-content-pass-conditions-content-sub passive" id="conditionConfirm"><?php echo $langData['anct-rest-msg-9'][$lang];?></div>
                    <div class="register-form-content-pass-conditions-footer">※ <?php echo $langData['anct-rest-msg-10'][$lang];?></div>
                </div>
            </div>
        </div>
        <div class="register-form-btn">
            <button class="register-form-submit" id="submit_reset"><?php echo $langData['anct-rest-msg-11'][$lang];?></button>
        </div>
    </div>
</div>
