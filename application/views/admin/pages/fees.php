<div id="page-wrapper"> 
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $langData['admin-page-msg-16']['KO'];?></h1>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading" style="height:51px;line-height:34px;">
                <?php echo $langData['admin-page-msg-22']['KO'];?>
            </div>
            <div class="panel-body">
                <table class="tableFees">
                    <thead>
                        <tr>
                            <th colspan="2"><?php echo $langData['admin-page-msg-53']['KO'];?></th>
                            <th>1<?php echo $langData['admin-ctrl-msg-20']['KO'];?><br><?php echo $langData['admin-page-msg-54']['KO'];?></th>
                            <th>2<?php echo $langData['admin-ctrl-msg-20']['KO'];?><br><?php echo $langData['admin-page-msg-55']['KO'];?>,<br><?php echo $langData['admin-page-msg-56']['KO'];?></th>
                            <th>3<?php echo $langData['admin-ctrl-msg-20']['KO'];?><br><?php echo $langData['admin-page-msg-57']['KO'];?></th>
                            <th><?php echo $langData['admin-page-msg-58']['KO'];?></th>
                            <th><?php echo $langData['admin-page-msg-59']['KO'];?></th>
                            <th><?php echo $langData['admin-page-msg-60']['KO'];?></th>
                            <th><?php echo $langData['admin-page-msg-61']['KO'];?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach ($coinData as $key => $value) {
                        ?>
                        <tr>
                            <td rowspan="2"><?php echo $value['f_title'];?>(<?php echo $value['f_unit'];?>)</td>
                            <td>1회</td>
                            <td><input class="feeInputBox" type="text" id="<?php echo $value['f_unit'];?>1f_daily_available_withdraw_amount_first" value="<?php echo $value['f_daily_available_withdraw_amount_first'];?>"> <?php echo $value['f_unit'];?></td>
                            <td><input class="feeInputBox" type="text" id="<?php echo $value['f_unit'];?>1f_daily_available_withdraw_amount_second" value="<?php echo $value['f_daily_available_withdraw_amount_second'];?>"> <?php echo $value['f_unit'];?></td>
                            <td><input class="feeInputBox" type="text" id="<?php echo $value['f_unit'];?>1f_daily_available_withdraw_amount_third" value="<?php echo $value['f_daily_available_withdraw_amount_third'];?>"> <?php echo $value['f_unit'];?></td>
                            <td rowspan="2"><input class="feeInputBox" type="text" id="<?php echo $value['f_unit'];?>1f_available_min_deposit_amount" value="<?php echo $value['f_available_min_deposit_amount'];?>"> <?php echo $value['f_unit'];?></td>
                            <td rowspan="2"><input class="feeInputBox" type="text" id="<?php echo $value['f_unit'];?>1f_withdraw_fee_amount" value="<?php echo $value['f_withdraw_fee_amount'];?>"> <?php echo $value['f_unit'];?></td>
                            <td rowspan="2"><input class="feeInputBox" type="text" id="<?php echo $value['f_unit'];?>1f_exchange_fee_percent" value="<?php echo $value['f_exchange_fee_percent'];?>"> %</td>
                            <td rowspan="2"><input class="feeInputBox" type="text" id="<?php echo $value['f_unit'];?>1f_min_withdraw_amount" value="<?php echo $value['f_min_withdraw_amount'];?>"> <?php echo $value['f_unit'];?></td>
                        </tr>
                        <tr>
                            <td>1일</td>
                            <td><input class="feeInputBox" type="text" id="<?php echo $value['f_unit'];?>1f_once_available_withdraw_amount_first" value="<?php echo $value['f_once_available_withdraw_amount_first'];?>"> <?php echo $value['f_unit'];?></td>
                            <td><input class="feeInputBox" type="text" id="<?php echo $value['f_unit'];?>1f_once_available_withdraw_amount_second" value="<?php echo $value['f_once_available_withdraw_amount_second'];?>"> <?php echo $value['f_unit'];?></td>
                            <td><input class="feeInputBox" type="text" id="<?php echo $value['f_unit'];?>1f_once_available_withdraw_amount_third" value="<?php echo $value['f_once_available_withdraw_amount_third'];?>"> <?php echo $value['f_unit'];?></td>
                        </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
