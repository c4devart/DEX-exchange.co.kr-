    <div id="page-wrapper" class="background-white">
        <div class="row" style="padding-top:10px;">
			<div class="div-width-300px">
				<span class="span-32px span-grey"><?php echo $langData['my_balance'][$lang];?></span>
			</div>
			<div class="div-width-1100px">
				<div class="div-height-39px-border-bottom-1px">
					<span class="span-24px span-grey span-normal-bold"><?php echo $userdata['f_username'];?></span>
					<span class="span-14px span-under-line span-blue" style="margin-left:33px;"><a href="<?php echo base_url();?>acnt/prof" class="span-blue"><?php echo $langData['profile'][$lang];?> ></a></span>
					<span class="span-24px span-grey span-normal-bold" style="margin-left:50px;"><?php echo $langData['withdraw_account'][$lang];?></span>
					<span class="span-14px span-under-line span-blue" style="margin-left:25px;" onclick="changeTabToMyOrderHistory();"><a href="#history" class="span-blue" data-toggle="tab"><?php echo $langData['my_buy_sell_history'][$lang];?> ></a></span>
					<span class="span-light span-background-blue span-btn-long span-14px" style="margin-left:50px;cursor:pointer;" onclick="window.location.href = base_url + 'excn/adnc';"><?php echo $langData['trading'][$lang];?>(<?php echo $langData['buy_sell'][$lang];?>)</span>
					<span class="span-light span-background-blue span-btn-short span-14px" style="margin-left:10px;cursor:pointer;" onclick="window.location.href = base_url + 'walt/dept';"><?php echo $langData['deposit'][$lang];?></span>
					<span class="span-light span-background-blue span-btn-short span-14px" style="margin-left:10px;cursor:pointer;" onclick="window.location.href = base_url + 'walt/dept';"><?php echo $langData['withdraw'][$lang];?></span>
				</div>
				<div class="div-height-40px">
					<span class="span-normal span-14px span-black"><?php echo $userdata['f_email'];?></span>
                    <span class="span-normal span-14px span-grey" style="margin-left:78px;">
                        <?php
                            if($userdata['f_withdraw_KRW_account_verified'] == 1){
                                echo $userdata['f_withdraw_bank'].' '.$userdata['f_withdraw_bank_no'].' ('.$userdata['f_withdraw_account_name'].')';
                            }else{
                                echo $langData['msg_withdraw_account_not_registered'][$lang];
                            }
                        ?>
                    </span>
				</div>
			</div>
        </div>
        <div class="row" style="background:none;margin-left:0px;margin-right:0px;">
            <ul class="nav nav-tabs panelShadowed basicBackground">
                <li class="tab-basic active" style="border-left:0px;"><a href="#balance" data-toggle="tab"><?php echo $langData['my_wallet_status'][$lang];?></a></li>
                <li class="tab-basic"><a href="#analyze" data-toggle="tab"><?php echo $langData['my_wallet_calc'][$lang];?></a></li>
                <li class="tab-basic"><a href="#openorders" data-toggle="tab"><?php echo $langData['my_open_orders_space'][$lang];?></a></li>
                <li class="tab-basic"><a href="#depositwithdrawal" data-toggle="tab"><?php echo $langData['dep_with_history_space'][$lang];?></a></li>
                <li class="tab-basic" id="specTabMyOrderHistory"><a href="#history" data-toggle="tab"><?php echo $langData['my_order_history_space'][$lang];?></a></li>
                <li class="tab-basic"><a href="#SKYPoolHistoryTab" data-toggle="tab"><?php echo $langData['sky_pool'][$lang];?></a></li>
                <li class="tab-basic"><a href="#ETHairdropHistoryTab" data-toggle="tab"><?php echo $langData['eth_airdrop'][$lang];?></a></li>
            </ul>
            <div class="tab-content">
                <div class="background-white tab-pane fade in active" id="balance">
                    <div class="panel panel-default tab-content-body no-margin panelShadowed" style="background:none;">
                        <div class="panel-body basicBackground">
                            <div class="col-lg-6 no-left-right-padding">
                                <div class="panel panel-default low-margin">
                                    <div class="panel-body high-line-height">
                                        <span class="span-normal-bold span-16px span-grey" style="padding:7px 0px;"><?php echo $langData['KRW_balance'][$lang];?></span><br>
										<span class="float-right" style="padding:7px 0px;"><span class="span-30px span-normal-bold span-black"><?php echo number_format($KRW_balance);?></span> <span class="span-20px span-20px span-grey">KRW</span></span><br>
										<div class="dottedDivider"></div>
                                        <span class="span-normal-bold span-16px span-grey" style="padding:7px 0px;"><?php echo $langData['total_by_base'][$lang];?></span><br>
										<span class="float-right" style="padding:7px 0px;"><span class="span-30px span-normal-bold span-black"><?php echo number_format($totalTargetBalanceByKRW);?></span> <span class="span-20px span-20px span-grey">KRW</span></span><br>
										<div class="dottedDivider"></div>
                                        <span class="span-normal-bold span-16px span-grey" style="padding:7px 0px;"><?php echo $langData['total_balance'][$lang];?></span><br>
                                        <span class="float-right" style="padding:7px 0px;"><span class="span-30px span-normal-bold span-black"><?php echo number_format($totalBalanceByKRW);?></span> <span class="span-20px span-20px span-grey">KRW</span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 no-left-right-padding" style="display:none;">
                                <div class="panel panel-default low-margin">
									<div class="panel-body high-line-height">
										<span class="span-normal-bold span-16px span-grey" style="padding:7px 0px;"><?php echo $langData['total_buy_base'][$lang];?></span><br>
										<span class="float-right" style="padding:7px 0px;"><span class="span-30px span-normal-bold span-black"><?php echo number_format($totalTargetBuyBalance);?></span> <span class="span-20px span-20px span-grey">KRW</span></span><br>
										<div class="dottedDivider"></div>
										<span class="span-normal-bold span-16px span-grey" style="padding:7px 0px;"><?php echo $langData['total_diff_base'][$lang];?></span><br>
										<span class="float-right" style="padding:7px 0px;"><span class="span-30px span-normal-bold span-black"><?php echo number_format($totalDiff);?></span> <span class="span-20px span-20px span-grey">KRW</span></span><br>
										<div class="dottedDivider"></div>
										<span class="span-normal-bold span-16px span-grey" style="padding:7px 0px;"><?php echo $langData['total_diff_percent'][$lang];?></span><br>
										<span class="float-right" style="padding:7px 0px;"><span class="span-30px span-normal-bold span-black"><?php echo number_format($totalDiffPercent,2,'.','');?></span> <span class="span-20px span-20px span-grey">%</span></span>
									</div>
								</div>
                            </div>
                            <div class="col-lg-6 no-left-right-padding">
                                <div class="panel panel-default low-margin">
                                    <div class="panel-body high-line-height" style="height: 296px;">
                                        <div id="chartdiv"></div>
                                        <script src="https://www.amcharts.com/lib/4/core.js"></script>
                                        <script src="https://www.amcharts.com/lib/4/charts.js"></script>
                                        <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="basicBackground" style="text-align: right;border-top:solid 1px #c7c7c7;">
							<div class="row no-margin" style="padding: 3px 3px 3px 19px;">
								<div class="span-normal span-14px span-grey float-left">※ <?php echo $langData['msg_about_analyzing_rule'][$lang];?></div>
								<div class="quiz" id="balance_quiz">
									<div class="answer" id="balance_answer">
										<div class="close-answer"></div>
										<span class="span-bold" style="line-height: 25px;"><?php echo $langData['algorithm'][$lang];?></span><br>
										<span class="span-grey">- <?php echo $langData['total_by_base'][$lang];?> = <?php echo $langData['each_coin_balance'][$lang];?> * <?php echo $langData['current_rate'][$lang];?> <?php echo $langData['total_sum'][$lang];?></span><br>
										<span class="span-grey">- <?php echo $langData['total_buy_base'][$lang];?> = <?php echo $langData['balance'][$lang];?> * <?php echo $langData['avg_buy_rate'][$lang];?></span><br>
										<span class="span-grey">- <?php echo $langData['total_diff_base'][$lang];?> = <?php echo $langData['total_by_base'][$lang];?> ? <?php echo $langData['total_buy_base'][$lang];?></span><br>
										<span class="span-grey">- <?php echo $langData['total_diff_percent'][$lang];?> = <?php echo $langData['total_diff_base'][$lang];?> / <?php echo $langData['total_buy_base'][$lang];?> * 100</span><br>
									</div>
								</div>
							</div>
						</div>
                    </div>
                    <div class="panel panel-default no-padding basicBackground panelShadowed" style="border-color: #fff;margin-top:20px;">
                        <div class="panel-body no-padding" style="line-height: 45px;padding-left:8px;padding-right:8px;">
                            <span class="span-24px span-black" style="padding-left:50px;padding-right:50px;border-bottom:solid 2px #182129"><?php echo $langData['balc_my_balance_status'][$lang];?></span>
                        </div>
                        <div class="panel-body" style="padding:0px 8px 8px 8px;">
                            <table class="table-current-balance table-striped">
                                <thead>
                                    <tr>
										<th style="width:100px;text-align:right;"></th>
                                        <th style="width:145px;text-align:left;"><?php echo $langData['balc_balance_type'][$lang];?></th>
                                        <th style="width:240px;"><?php echo $langData['balc_total_balance'][$lang];?></th>
                                        <th style="width:240px;"><?php echo $langData['buy_price'][$lang];?></th>
                                        <!-- <th style="width:180px;"><?php echo $langData['total_diff'][$lang];?></th>
                                        <th style="width:180px;"><?php echo $langData['earning_percent'][$lang];?></th> -->
                                        <th style="width:240px;"><?php echo $langData['balc_my_open_orders'][$lang];?></th>
                                        <th style="width:240px;"><?php echo $langData['balc_on_withdraw'][$lang];?></th>
                                        <th style="width:239px;"><?php echo $langData['balc_available'][$lang];?></th>
                                    </tr>
                                </thead>
                                <tbody style="border-top:solid 1px #c7c7c7;border-bottom:solid 1px #c7c7c7;">
                                    <?php
                                        foreach ($balanceData as $key => $value) {
                                    ?>
                                    <tr>
                                        <td class="text-align-center">
                                             <img src="<?php if($value['f_unit'] != 'KRW'){echo base_url().$coinData[$value['f_unit']]['f_img'];}else{echo base_url().'assets/image/coin/KRW.png';};?>" style="width:32px;height:32px;">
										</td>
										<td class="text-align-left">
                                            <span class="span-13px span-black">
                                            <?php
                                                if($value['f_unit'] == 'KRW'){
                                                    echo ' '.$langData['KRW'][$lang];
                                                }else{
                                                    echo $langData[strtolower($value['f_unit'])][$lang];
                                                }
                                            ?>
                                            </span><br>
                                            <span class="span-12px span-grey"><?php echo $value['f_unit'];?></span>
                                        </td>
                                        <td class="text-align-right">
                                            <span class="span-13px span-black">
                                            <?php 
                                                if($value['f_unit']=='KRW'){
                                                    echo number_format($value['f_total']);
                                                }else{
                                                    echo number_format($value['f_total'], 8, '.', '');
                                                };
                                            ?>
                                            </span><br>
                                            <span class="span-12px span-grey"><?php echo $value['f_unit'];?></span>
                                        </td>
                                        <td class="text-align-right">
                                            <span class="span-13px span-black">
                                            <?php 
                                                echo number_format($value['f_buy_base_volume']);
                                            ?>
                                            </span><br>
                                            <span class="span-12px span-grey">KRW</span>
                                        </td>
                                        <!-- <td class="text-align-right">
                                            <span class="span-13px span-black">
                                                <?php
                                                    $coinBalanceByBase = $value['f_total'] * $currentRate[$value['f_unit']];
                                                    $coinBalanceDiff = $coinBalanceByBase - $value['f_buy_base_volume'];
                                                    echo number_format($coinBalanceDiff);
                                                ?>
                                            </span><br>
                                            <span class="span-12px span-grey">KRW</span>
                                        </td>
                                        <td class="text-align-right">
                                            <span class="span-13px span-black">
                                            <?php 
                                                if($value['f_buy_base_volume'] > 0){
                                                    $percent = $coinBalanceDiff/$value['f_buy_base_volume']*100;    
                                                }else{
                                                    $percent = 0;
                                                }                                                
                                                echo number_format($percent, 2, '.', ',');
                                            ?>
                                            </span><br>
                                            <span class="span-12px span-grey">%</span>
                                        </td> -->
                                        <td class="text-align-right">
                                            <span class="span-13px span-black">
                                            <?php
												$onOrderBalance = $value['f_blocked'] - $onWithdraw[$value['f_unit']];
                                                if($value['f_unit']=='KRW'){
                                                    echo number_format($onOrderBalance);
                                                }else{
                                                    echo number_format($onOrderBalance, 8, '.', '');
                                                };
                                            ?>
                                            </span><br>
                                            <span class="span-12px span-grey"><?php echo $value['f_unit']?></span>
                                        </td>
                                        <td class="text-align-right">
                                            <span class="span-13px span-black">
                                            <?php 
                                                echo $onWithdraw[$value['f_unit']];
                                            ?>
                                            </span><br>
                                            <span class="span-12px span-grey">
                                            <?php 
                                                echo $value['f_unit'];
                                            ?>
                                            </span>
                                        </td>
                                        <td class="text-align-right">
                                            <span class="span-13px span-black">
                                            <?php 
                                                if($value['f_unit']=='KRW'){
                                                    echo number_format($value['f_available']);
                                                }else{
                                                    echo number_format($value['f_available'], 8, '.', '');
                                                };
                                            ?>
                                            </span><br>
                                            <span class="span-12px span-grey">
                                            <?php 
                                                echo $value['f_unit'];
                                            ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="analyze">
					<div class="panel panel-default no-padding basicBackground panelShadowed" style="border-color: #fff;padding-top:20px;">
                        <div class="panel-body no-padding" style="line-height: 45px;padding-left:8px;padding-right:8px;">
                            <span class="span-24px span-black" style="padding-left:50px;padding-right:50px;border-bottom:solid 2px #182129"><?php echo $langData['balc_balance_anaylze'][$lang];?> <span class="span-14px span-normal span-black" style="margin-left:30px;"><?php echo $langData['total_count'][$lang];?> : <?php echo count($balanceData);?> <?php echo $langData['row'][$lang];?></span></span>
                        </div>
                        <div class="panel-body" style="padding:0px 8px 0px 8px;">
                            <table class="table-current-balance table-striped">
                                <thead>
                                    <tr>
										<th style="width:50px;"></th>
                                        <th style="width:250px;"><?php echo $langData['balc_balance_type'][$lang];?></th>
                                        <th style="width:300px;"><?php echo $langData['balc_total_balance'][$lang];?></th>
                                        <th style="width:300px;"><?php echo $langData['total_price'][$lang];?></th>
                                        <th style="width:300px;"><?php echo $langData['balc_earn_avg_rate'][$lang];?></th>
                                        <th style="width:300px;"><?php echo $langData['current_rate'][$lang];?></th>
                                        <th style="width:300px;"><?php echo $langData['earning_percent'][$lang];?></th>
                                    </tr>
                                </thead>
								<tbody style="border-top:solid 1px #c7c7c7;border-bottom:solid 1px #c7c7c7;">
									<?php
                                        foreach ($balanceData as $key => $value) {
                                    ?>
                                    <tr>
                                        <td class="text-align-center">
                                            <img src="<?php echo base_url();?>assets/image/coin/<?php echo $value['f_unit'];?>.png" style="width:32px;height:32px;"> 
										</td>
										<td class="text-align-left">
                                            <span class="span-13px span-black">
                                                <?php 
                                                    if($value['f_unit'] == 'KRW'){
                                                        echo $langData['KRW'][$lang];
                                                    }else{
                                                        echo $langData[strtolower($value['f_unit'])][$lang];
                                                    }
                                                ;?>
                                            </span><br>
                                            <span class="span-12px span-grey"><?php echo $value['f_unit'];?></span>
                                        </td>
                                        <td class="text-align-right">
                                            <span class="span-13px span-black">
                                            <?php 
                                                if($value['f_unit']=='KRW'){
                                                    echo number_format($value['f_total']);
                                                }else{
                                                    echo number_format($value['f_total'], 8, '.', '');
                                                };
                                            ?>
                                            </span><br>
                                            <span class="span-12px span-grey"><?php echo $value['f_unit'];?></span>
                                        </td>
                                        <td class="text-align-right">
                                            <span class="span-13px span-black">
                                                <?php echo number_format($value['f_total']*$currentRate[$value['f_unit']]);?>
                                            </span><br>
                                            <span class="span-12px span-grey">KRW</span>
                                        </td>
                                        <td class="text-align-right">
                                            <span class="span-13px span-black">
                                                <?php echo number_format($buySellAvgRate[$value['f_unit']]);?>
                                            </span><br>
                                            <span class="span-12px span-grey">KRW</span>
                                        </td>
                                        <td class="text-align-right">
                                            <span class="span-13px span-black">
                                                <?php echo number_format($currentRate[$value['f_unit']]);?>
                                            </span><br>
                                            <span class="span-12px span-grey">KRW</span>
                                        </td>
                                        <td class="text-align-right">
                                            <span class="span-13px span-black">
                                                <?php 
                                                    if($buySellAvgRate[$value['f_unit']] > 0){
                                                        $tempPercent = ($currentRate[$value['f_unit']] - $buySellAvgRate[$value['f_unit']])/$buySellAvgRate[$value['f_unit']]*100;
                                                    }else{
                                                        $tempPercent = 0;
                                                    }
                                                    echo number_format($tempPercent, 2, '.', ',');
                                                ?>
                                            </span><br>
                                            <span class="span-12px span-grey">%</span>
                                        </td>
                                    </tr>                                    
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
						<div class="text-align-right">
							<div class="row no-margin" style="padding: 3px 3px 3px 19px;">
								<div class="span-normal span-14px span-grey float-left">※ <?php echo $langData['msg_about_analyzing_rule'][$lang];?></div>
								<div class="quiz" id="analyze_quiz">
									<div class="answer" id="analyze_answer">
										<div class="close-answer"></div>
										<span class="span-bold" style="line-height: 25px;"><?php echo $langData['algorithm'][$lang];?></span><br>
										<span class="span-grey">- <?php echo $langData['total_price'][$lang];?> = <?php echo $langData['balance'][$lang];?> * <?php echo $langData['current_rate'][$lang];?></span><br>
										<span class="span-grey">- <?php echo $langData['earning_percent'][$lang];?> = (<?php echo $langData['current_rate'][$lang];?>-<?php echo $langData['balc_earn_avg_rate'][$lang];?>)/<?php echo $langData['balc_earn_avg_rate'][$lang];?> * 100</span><br>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- analyze by datetime interval -->
					<!-- ! analyze by datetime interval ! -->
                </div>
                <div class="tab-pane fade" id="openorders">
                    <div class="panel panel-default no-padding panelShadowed basicBackground" style="border-color: #fff;padding-top: 20px;padding-bottom:30px;">
                        <div class="panel panel-default tab-content-body-grey basicBackground" style="margin-bottom:0px;">
                            <div class="panel-body" style="padding:2px 8px;border-bottom:solid 1px #c7c7c7;">
								<span class="span-normal span-14px span-black float-left" style="line-height: 28px;margin-left:20px;"><?php echo $langData['total_count'][$lang];?> : <span id="total_count_my_open_orders"></span> <?php echo $langData['row'][$lang];?> </span>
								<button class="btn btn-default float-right span-normal span-14px span-light span-background-grey btnFixedPadding" onclick="exportTableToCSV('myOpenOrders.csv', 'datatableMyOpenOrders');"><?php echo $langData['download'][$lang];?></button>
								<button class="btn btn-default btn-dark-grey float-right span-normal span-14px span-background-black span-light btnFixedPadding" style="margin-right:10px;" id="datatableMyOpenOrdersFilterSearch"><?php echo $langData['search'][$lang];?></button>
                                <div class="datatableMyOpenOrdersFilterElementContent">
									<span class="span-normal span-14px span-black float-left" style="line-height: 28px;margin-right:10px;margin-left:10px;"><?php echo $langData['type'][$lang];?></span>
                                    <select id="datatableMyOpenOrdersFilterSearchByOrderType" class="form-control float-right searchByOrderType">
                                        <option value=""><?php echo $langData['all'][$lang];?></option>
                                        <option value="buy"><?php echo $langData['buy'][$lang];?></option>
                                        <option value="sell"><?php echo $langData['sell'][$lang];?></option>
                                    </select>
								</div>
                                <div class="datatableMyOpenOrdersFilterElementContent">
									<span class="span-normal span-14px span-black float-left" style="line-height: 28px;margin-right:10px;margin-left:20px;"><?php echo $langData['coin'][$lang];?></span>
                                    <select id="datatableMyOpenOrdersFilterSearchByCoin" class="form-control float-right searchByCoin">
                                        <option value=""><?php echo $langData['all'][$lang];?></option>    
                                        <option value="BTC"><?php echo $langData['btc'][$lang];?>(BTC)</option>
                                        <option value="ETH"><?php echo $langData['eth'][$lang];?>(ETH)</option>
                                        <option value="SKY"><?php echo $langData['sky'][$lang];?>(SKY)</option>
                                        <option value="BDR"><?php echo $langData['bdr'][$lang];?>(BDR)</option>
                                    </select>
                                </div>
                                <?php
                                    $from_date = date('Y-m-d', $userdata['f_regdate']);
                                    $to_date = date('Y-m-d');
                                ?>
								<input type="text" class="form-control my-datepicker" data-toggle="datepicker" id="datatableMyOpenOrdersFilterSearchByToDate" value="<?php echo $to_date;?>">
								<span class="float-right" style="line-height: 30px;"> ~ </span>
                                <input type="text" class="form-control my-datepicker" data-toggle="datepicker" id="datatableMyOpenOrdersFilterSearchByFromDate" value="<?php echo $from_date;?>">
                                <span class="float-right" style="line-height: 30px;"><?php echo $langData['datetime_interval'][$lang];?></span>
                            </div>
                        </div>
                        <div class="panel-body no-padding datatable-myopenorders-content">
                            <table class="table-current-balance" id="datatableMyOpenOrders">
                                <thead>
                                    <tr>
                                        <th style="width:215px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['order_datetime'][$lang];?></span></th>
                                        <th style="width:100px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['market'][$lang];?></span></th>
                                        <th style="width:100px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['type'][$lang];?></span></th>
                                        <th style="width:200px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['order_volume'][$lang];?></span></th>
                                        <th style="width:200px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['balc_order_rate_space'][$lang];?></span></th>
                                        <th style="width:200px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['balc_order_price_space'][$lang];?></span></th>
                                        <th style="width:200px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['balc_my_open_orders'][$lang];?></span></th>
                                        <th style="width:80px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['edit'][$lang];?></span></th>
                                        <th style="width:80px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['cancel'][$lang];?></span></th>
                                    </tr>
								</thead>
								<tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="depositwithdrawal">
					<div class="panel panel-default no-padding panelShadowed basicBackground" style="border-color: #fff;padding-top: 20px;padding-bottom:30px;">
                        <div class="panel panel-default tab-content-body-grey basicBackground" style="margin-bottom:0px;">
                            <div class="panel-body" style="padding:2px 8px;border-bottom:solid 1px #c7c7c7;">
								<span class="span-normal span-14px span-black float-left" style="line-height: 28px;margin-left:20px;"><?php echo $langData['total_count'][$lang];?> : <span id="total_count_depWith"></span> <?php echo $langData['row'][$lang];?> </span>
								<button class="btn btn-default float-right span-normal span-14px span-light span-background-grey btnFixedPadding" onclick="exportTableToCSV('depositWithdraw.csv', 'datatableDepWith');"><?php echo $langData['download'][$lang];?></button>
								<button class="btn btn-default btn-dark-grey float-right span-normal span-14px span-background-black span-light btnFixedPadding" style="margin-right:10px;" id="datatableDepWithFilterSearch"><?php echo $langData['search'][$lang];?></button>
                                <div class="datatableDepWithFilterElementContent">
                                    <select id="datatableDepWithFilterSearchByOrderType" class="form-control float-right searchByOrderType" style="margin-right:20px;">
                                        <option value=""><?php echo $langData['all'][$lang];?></option>
                                        <option value="deposit"><?php echo $langData['deposit'][$lang];?></option>
                                        <option value="withdraw"><?php echo $langData['withdraw'][$lang];?></option>
                                    </select>
									<span class="span-normal span-14px span-black float-right" style="line-height: 28px;margin-right:10px;margin-left:10px;"><?php echo $langData['type'][$lang];?></span>
								</div>
                                <div class="datatableDepWithFilterElementContent">
                                    <select id="datatableDepWithFilterSearchByCoin" class="form-control float-right searchByCoin" style="margin-right:20px;">
                                        <option value=""><?php echo $langData['all'][$lang];?></option>
                                        <option value="KRW"><?php echo $langData['KRW'][$lang];?>(KRW)</option>
                                        <option value="BTC"><?php echo $langData['btc'][$lang];?>(BTC)</option>
                                        <option value="ETH"><?php echo $langData['eth'][$lang];?>(ETH)</option>
                                        <option value="SKY"><?php echo $langData['sky'][$lang];?>(SKY)</option>
                                        <option value="BDR"><?php echo $langData['bdr'][$lang];?>(BDR)</option>
                                    </select>
									<span class="span-normal span-14px span-black float-right" style="line-height: 28px;margin-right:10px;margin-left:10px;"><?php echo $langData['coin'][$lang];?></span>
                                </div>
                                <?php
                                    $from_date = date('Y-m-d', $userdata['f_regdate']);
                                    $to_date = date('Y-m-d');
                                ?>
								<input type="text" class="form-control my-datepicker" data-toggle="datepicker" id="datatableDepWithFilterSearchByToDate" value="<?php echo $to_date;?>" style="margin-right:20px;">
								<span class="float-right" style="line-height: 30px;"> ~ </span>
                                <input type="text" class="form-control my-datepicker" data-toggle="datepicker" id="datatableDepWithFilterSearchByFromDate" value="<?php echo $from_date;?>">
                                <span class="float-right" style="line-height: 30px;"><?php echo $langData['datetime_interval'][$lang];?></span>
                            </div>
                        </div>
                        <div class="panel-body no-padding datatable-myopenorders-content">
                            <table class="table-current-balance datatable-basic" id="datatableDepWith">
                                <thead>
                                    <tr>
                                        <th style="width:215px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['order_datetime'][$lang];?></span></th>
                                        <th style="width:250px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['coin_title'][$lang];?></span></th>
                                        <th style="width:100px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['type'][$lang];?></span></th>
                                        <th style="width:220px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['volume_short'][$lang];?>/<?php echo $langData['balc_price'][$lang];?></span></th>
                                        <th style="width:180px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['fee'][$lang];?></span></th>
                                        <th style="width:313px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['user_address'][$lang];?></span></th>
                                        <th style="width:100px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['status'][$lang];?></span></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="history">
					<div class="panel panel-default no-padding panelShadowed basicBackground" style="border-color: #fff;padding-top: 20px;padding-bottom:30px;">
                        <div class="panel panel-default tab-content-body-grey basicBackground" style="margin-bottom:0px;">
                            <div class="panel-body" style="padding:2px 8px;border-bottom:solid 1px #c7c7c7;">
								<span class="span-normal span-14px span-black float-left" style="line-height: 28px;margin-left:20px;"><?php echo $langData['total_count'][$lang];?> : <span id="total_count_my_markethistory"></span> <?php echo $langData['row'][$lang];?> </span>
								<button class="btn btn-default float-right span-normal span-14px span-light span-background-grey btnFixedPadding" onclick="exportTableToCSV('myMarketHistory.csv', 'datatableMyMarketHistory');"><?php echo $langData['download'][$lang];?></button>
								<button class="btn btn-default btn-dark-grey float-right span-normal span-14px span-background-black span-light btnFixedPadding" style="margin-right:10px;" id="datatableMyMarketHistoryFilterSearch"><?php echo $langData['search'][$lang];?></button>
                                <div class="datatableDepWithFilterElementContent">
                                    <select id="datatableMyMarketHistoryFilterSearchByOrderType" class="form-control float-right searchByOrderType" style="margin-right:20px;">
										<option value=""><?php echo $langData['all'][$lang];?></option>
                                        <option value="buy"><?php echo $langData['buy'][$lang];?></option>
                                        <option value="sell"><?php echo $langData['sell'][$lang];?></option>
                                    </select>
									<span class="span-normal span-14px span-black float-right" style="line-height: 28px;margin-right:10px;margin-left:10px;"><?php echo $langData['type'][$lang];?></span>
								</div>
                                <div class="datatableDepWithFilterElementContent">
                                    <select id="datatableMyMarketHistoryFilterSearchByCoin" class="form-control float-right searchByCoin" style="margin-right:20px;">
                                        <option value=""><?php echo $langData['all'][$lang];?></option>
                                        <option value="KRW"><?php echo $langData['KRW'][$lang];?>(KRW)</option>
                                        <option value="BTC"><?php echo $langData['btc'][$lang];?>(BTC)</option>
                                        <option value="ETH"><?php echo $langData['eth'][$lang];?>(ETH)</option>
                                        <option value="SKY"><?php echo $langData['sky'][$lang];?>(SKY)</option>
                                        <option value="BDR"><?php echo $langData['bdr'][$lang];?>(BDR)</option>
                                    </select>
									<span class="span-normal span-14px span-black float-right" style="line-height: 28px;margin-right:10px;margin-left:10px;"><?php echo $langData['coin'][$lang];?></span>
                                </div>
                                <?php
                                    $from_date = date('Y-m-d', $userdata['f_regdate']);
                                    $to_date = date('Y-m-d');
                                ?>
								<input type="text" class="form-control my-datepicker" data-toggle="datepicker" id="datatableMyMarketHistoryFilterSearchByToDate" value="<?php echo $to_date;?>" style="margin-right:20px;">
								<span class="float-right" style="line-height: 30px;"> ~ </span>
                                <input type="text" class="form-control my-datepicker" data-toggle="datepicker" id="datatableMyMarketHistoryFilterSearchByFromDate" value="<?php echo $from_date;?>">
                                <span class="float-right" style="line-height: 30px;"><?php echo $langData['datetime_interval'][$lang];?></span>
                            </div>
                        </div>
                        <div class="panel-body no-padding datatable-myopenorders-content">
                            <table class="table-current-balance datatable-basic table-striped" id="datatableMyMarketHistory">
                                <thead>
                                    <tr>
                                        <th style="width:200px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['order_datetime'][$lang];?></span></th>
										<th style="width:125px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['coin_title'][$lang];?></span></th>
										<th style="width:145px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['market'][$lang];?></span></th>
                                        <th style="width:105px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['type'][$lang];?></span></th>
                                        <th style="width:205px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['target_volume'][$lang];?></span></th>
                                        <th style="width:205px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['exchange_rate'][$lang];?></span></th>
                                        <th style="width:205px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['exchange_price'][$lang];?></span></th>
                                        <th style="width:165px;"><span class="span-normal-bold span-14px span-grey"><?php echo $langData['fee'][$lang];?></span></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
					</div>
                </div>
                <div class="tab-pane fade" id="SKYPoolHistoryTab">
					<div class="panel panel-default no-padding panelShadowed basicBackground" style="border-color: #fff;padding-top: 20px;padding-bottom:30px;">
                        <div class="panel panel-default tab-content-body-grey basicBackground" style="margin-bottom:0px;">
                            <div class="panel-body" style="padding:2px 8px;border-bottom:solid 1px #c7c7c7;">
								<span class="span-normal span-14px span-black float-left" style="line-height: 28px;margin-left:20px;"><?php echo $langData['total_count'][$lang];?> : <span id="total_count_SKYPoolHistory"></span> <?php echo $langData['row'][$lang];?> </span>
								<button class="btn btn-default float-right span-normal span-14px span-light span-background-grey btnFixedPadding" onclick="exportTableToCSV('mySKYPoolHistory.csv', 'datatableSKYPoolHistory');"><?php echo $langData['download'][$lang];?></button>
								<button class="btn btn-default btn-dark-grey float-right span-normal span-14px span-background-black span-light btnFixedPadding" style="margin-right:10px;" id="datatableSKYPoolHistoryFilterSearch"><?php echo $langData['search'][$lang];?></button>
                                <?php
                                    $from_date = date('Y-m-d', $userdata['f_regdate']);
                                    $to_date = date('Y-m-d');
                                ?>
								<input type="text" class="form-control my-datepicker" data-toggle="datepicker" id="datatableSKYPoolHistoryFilterSearchByToDate" value="<?php echo $to_date;?>" style="margin-right:20px;">
								<span class="float-right" style="line-height: 30px;"> ~ </span>
                                <input type="text" class="form-control my-datepicker" data-toggle="datepicker" id="datatableSKYPoolHistoryFilterSearchByFromDate" value="<?php echo $from_date;?>">
                                <span class="float-right" style="line-height: 30px;"><?php echo $langData['datetime_interval'][$lang];?></span>
                            </div>
                        </div>
                        <div class="panel-body no-padding datatable-myopenorders-content">
                            <table class="table-current-balance datatable-basic table-striped" id="datatableSKYPoolHistory">
                                <thead>
                                    <tr>
                                        <th><span class="span-normal-bold span-14px span-grey"><?php echo $langData['pool_datetime'][$lang];?></span></th>
										<th><span class="span-normal-bold span-14px span-grey"><?php echo $langData['pool_my_exchange_price'][$lang];?></span></th>
										<th><span class="span-normal-bold span-14px span-grey"><?php echo $langData['pool_all_exchange_price'][$lang];?></span></th>
                                        <th><span class="span-normal-bold span-14px span-grey"><?php echo $langData['pool_effect_percent'][$lang];?></span></th>
                                        <th><span class="span-normal-bold span-14px span-grey"><?php echo $langData['pool_daily_volume'][$lang];?></span></th>
                                        <th><span class="span-normal-bold span-14px span-grey"><?php echo $langData['pool_my_volume'][$lang];?></span></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
					</div>
                </div>
                <div class="tab-pane fade" id="ETHairdropHistoryTab">
					<div class="panel panel-default no-padding panelShadowed basicBackground" style="border-color: #fff;padding-top: 20px;padding-bottom:30px;">
                        <div class="panel panel-default tab-content-body-grey basicBackground" style="margin-bottom:0px;">
                            <div class="panel-body" style="padding:2px 8px;border-bottom:solid 1px #c7c7c7;">
								<span class="span-normal span-14px span-black float-left" style="line-height: 28px;margin-left:20px;"><?php echo $langData['total_count'][$lang];?> : <span id="total_count_ETHairdropHistory"></span> <?php echo $langData['row'][$lang];?> </span>
								<button class="btn btn-default float-right span-normal span-14px span-light span-background-grey btnFixedPadding" onclick="exportTableToCSV('myETHAirdropHistory.csv', 'datatableETHairdropHistory');"><?php echo $langData['download'][$lang];?></button>
								<button class="btn btn-default btn-dark-grey float-right span-normal span-14px span-background-black span-light btnFixedPadding" style="margin-right:10px;" id="datatableETHairdropHistoryFilterSearch"><?php echo $langData['search'][$lang];?></button>
                                <?php
                                    $from_date = date('Y-m-d', $userdata['f_regdate']);
                                    $to_date = date('Y-m-d');
                                ?>
								<input type="text" class="form-control my-datepicker" data-toggle="datepicker" id="datatableETHairdropHistoryFilterSearchByToDate" value="<?php echo $to_date;?>" style="margin-right:20px;">
								<span class="float-right" style="line-height: 30px;"> ~ </span>
                                <input type="text" class="form-control my-datepicker" data-toggle="datepicker" id="datatableETHairdropHistoryFilterSearchByFromDate" value="<?php echo $from_date;?>">
                                <span class="float-right" style="line-height: 30px;"><?php echo $langData['datetime_interval'][$lang];?></span>
                            </div>
                        </div>
                        <div class="panel-body no-padding datatable-myopenorders-content">
                            <table class="table-current-balance datatable-basic table-striped" id="datatableETHairdropHistory">
                                <thead>
                                    <tr>
										<th><span class="span-normal-bold span-14px span-grey"><?php echo $langData['airdrop_date'][$lang];?></span></th>
                                        <!-- <th><span class="span-normal-bold span-14px span-grey"><?php echo $langData['airdrop_exchange_price'][$lang];?></span></th>
                                        <th><span class="span-normal-bold span-14px span-grey"><?php echo $langData['fee'][$lang];?></span></th>
                                        <th><span class="span-normal-bold span-14px span-grey"><?php echo $langData['airdrop_total_airdrop_volume'][$lang];?></span></th>
                                        <th><span class="span-normal-bold span-14px span-grey"><?php echo $langData['airdrop_eth_close_rate'][$lang];?></span></th>
                                        <th><span class="span-normal-bold span-14px span-grey"><?php echo $langData['airdrop_eth_airdrop_volume'][$lang];?></span></th> -->
                                        <th><span class="span-normal-bold span-14px span-grey"><?php echo $langData['airdrop_my_sky_balance'][$lang];?></span></th>
										<th><span class="span-normal-bold span-14px span-grey"><?php echo $langData['airdrop_my_eth_airdrop_volume'][$lang];?></span></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <iframe id="txtArea1" style="display:none"></iframe>
