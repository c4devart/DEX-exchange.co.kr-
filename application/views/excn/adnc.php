    <script type="text/javascript">
        var target = '<?php echo $target; ?>';
		var base = '<?php echo $base; ?>';
    </script>
    <input type="hidden" id="is_login" value="<?php if($is_login == true){echo "true";}else{echo "false";};?>">
    <input type="hidden" id="exchange_fee" value="<?php echo $config['default_exchange_fee'];?>">
	<input type="hidden" id="hoga_unit" value="<?php echo (float)($marketData[$target][$base]['f_limit']); ?>">
	<input type="hidden" id="unitDecimal" value="<?php echo $unitDecimal; ?>">
	<input type="hidden" id="currentRate" value="<?php echo number_format($marketData[$target][$base]['f_close'], $marketData[$target][$base]['f_decimal'], '.', ','); ?>">
    <span id="tempNumberFormatter" style="display:none"></span>
    <div id="page-wrapper">
        <div class="row" style="margin-top: 10px;margin-right:0px;margin-left:0px;background-color:#eaedee;">
            <div class="no-padding fixed-width-left">
                <div class="panel panel-default no-border" id="coinMarketList">
                    <div class="favMarketSearch">
                        <div class="favMarketSearchImg passive" id="searchByFavImg"></div>
                        <?php echo $langData['fav_coin'][$lang];?>
                    </div>
                    <div class="marketListSearchBoxDivContent">
                        <input type="text" id="marketListSearchBox" placeholder="<?php echo $langData['coin_title'][$lang];?>/<?php echo $langData['search_symbol'][$lang];?>">
                    </div>
                </div>
            	<ul class="nav nav-tabs">
                    <li <?php if($base=='KRW') echo 'class="active"';?>><a href="#KRW" data-toggle="tab" <?php if($base=='KRW') echo 'aria-expanded="true"';?> style="margin-left:8px;">KRW</a></li>
                    <li><a href="#" data-toggle="tab" <?php if($base=='BTC') echo 'aria-expanded="true"';?> style="margin-left:3px;">BTC</a></li>
                    <li><a href="#" data-toggle="tab" <?php if($base=='ETH') echo 'aria-expanded="true"';?> style="margin-left:3px;margin-right:8px;">ETH</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade<?php if($base=='KRW') echo ' in active';?>" id="KRW">
                    	<div class="panel panel-default coin-list marketlist-content">
	                        <table id="marketList_KRW">
                                <thead>
                                    <tr style="border-top:0px !important;">
                                        <th class="span-grey" style="width:170px;text-align:center;"><?php echo $langData['coin_title'][$lang];?></th>
                                        <th class="span-grey sortable marketListHeaderSorted" style="width:60px;"><?php echo $langData['current_rate'][$lang];?></th>
                                        <th class="span-grey sortable marketListHeaderSorted" style="width:80px;"><?php echo $langData['diff_percent'][$lang];?></th>
                                        <th class="span-grey sortable marketListHeaderSorted" style="width:90px;"><?php echo $langData['base_volume'][$lang];?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    foreach($marketData as $marketTarget => $value){
                                        foreach ($value as $marketBase => $vvalue) {
                                            if($marketBase == $base){
                                                if($marketTarget == $target){
                                                    $trClassName = 'marketlist_active';
                                                }else{
                                                    $trClassName = 'marketlist_passive';
                                                }
                                                if($is_login == true){
                                                    if($favMarket[$marketTarget][$base] == 1){
                                                        $html_image = '<div style="width:100%;"><div class="isFav active" id="marketList_favMarket_'.$marketTarget.'" onclick="changeFavMarket('."'".$marketTarget."'".', '."'".$base."'".')"></div><div style="display:inline-block;float:left;margin-top:4px;"><img src="'.base_url().$coinData[$marketTarget]['f_img'].'" style="width:28px;height:28px;margin-left:4px;"></div>';
                                                    }else{
                                                        $html_image = '<div style="width:100%;"><div class="isFav passive" id="marketList_favMarket_'.$marketTarget.'" onclick="changeFavMarket('."'".$marketTarget."'".', '."'".$base."'".')"></div><div style="display:inline-block;float:left;margin-top:4px;"><img src="'.base_url().$coinData[$marketTarget]['f_img'].'" style="width:28px;height:28px;margin-left:4px;"></div>';
                                                    }
                                                }else{
                                                    $html_image = '<div style="width:100%;"><div class="isFav passive no-hover"></div><div style="display:inline-block;float:left;margin-top:4px;"><img src="'.base_url().$coinData[$marketTarget]['f_img'].'" style="width:28px;height:28px;margin-left:4px;"></div>';
                                                }
                                                $html_title = $html_image.'<div onclick="window.location.href = '."'".base_url()."excn/adnc/".$marketTarget."/".$base."'".'" style="display:inline-block;float:left;padding-left:10px;line-height:15px !important;margin-top:3px;"><span class="span-13px span-black span-normal-bold">'.$langData[strtolower($marketTarget)][$lang].'</span><br><span class="span-11px span-grey span-normal">'.$marketTarget.' ('.$base.')</span></div></div>';
                                                if($vvalue['f_percent']>0){
                                                    $percent = '+'.$vvalue['f_percent'].'%';
                                                    $tdClassName = 'span-red';
                                                }else if($vvalue['f_percent']==0){
                                                    $percent = '0.00%';
                                                    $tdClassName = 'span-grey';
                                                }else{
                                                    $percent = $vvalue['f_percent'].'%';
                                                    $tdClassName = 'span-blue';
                                                }
                                                if($vvalue['f_base_volume']>=1000000){
                                                    $bVolume = number_format(round($vvalue['f_base_volume']/1000000)).'<br><span class="span-grey span-11px span-normal">백만</span>';
                                                }else{
                                                    $bVolume = number_format($vvalue['f_base_volume']);
                                                }
                                                echo '<tr id="marketListTr_'.$marketTarget.'" class="'.$trClassName.'">';
                                                echo '<td style="padding-left:10px;width:170px;">'.$html_title.'</td>';
                                                echo '<td style="width:60px;" class="align-right" onclick="window.location.href = '."'".base_url()."excn/adnc/".$marketTarget."/".$base."'".'"><span class="span-13px span-normal-bold '.$tdClassName.'" id="marketList_rate_'.$marketTarget.'">'.number_format($vvalue['f_close'], $vvalue['f_decimal'], '.', ',').'</span></td>';
                                                echo '<td style="width:80px;" class="align-right" onclick="window.location.href = '."'".base_url()."excn/adnc/".$marketTarget."/".$base."'".'"><span class="span-12px span-normal-bold '.$tdClassName.'" id="marketList_percent_'.$marketTarget.'">'.$percent.'</span></td>';
                                                echo '<td style="padding-right:10px;width:90px;" style="padding-right:10px;" class="align-right" onclick="window.location.href = '."'".base_url()."excn/adnc/".$marketTarget."/".$base."'".'"><span class="span-13px span-black span-normal-bold" id="marketList_volume_'.$marketTarget.'">'.$bVolume.'</span></td>';
                                                echo '</tr>';
                                            }
                                        }
                                    }
                                ?>
                                </tbody>
                            </table>
                            <table id="marketList_KRW_Temp">
                                <tbody>
                                <?php
                                    for($i=0;$i<20;$i++){
										echo '<tr class="marketlist_passive">';
										echo '<td style="padding-left:10px;"></td>';
										echo '<td style="text-align:right;"> --- </td>';
										echo '<td style="text-align:right;"> --- </td>';
										echo '<td style="text-align:right;padding-right:10px;"> '.$langData['coming_soon'][$lang].' </td>';
										echo '</tr>';
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>       
            </div>
            <div class="low-padding fixed-width-right">
                <div class="row no-margin no-padding" style="background: none;">
                	<div class="panel panel-default no-margin">
                        <div class="panel-heading market-title" style="padding-top:0px;">
                        	<div class="row" style="border-bottom: dotted 1px #c6c7c7;">
                        		<div class="fixed-width-370" style="margin-left: 20px;">
                        			<img src="<?php echo base_url().$coinData[$target]['f_img'];?>" class="market-title-img"> 
                        			<span class="market-title-target"><span class="span-normal-bold span-30pxm span-black"><?php echo $langData[strtolower($target)][$lang];?></span> <span class="span-normal span-18px span-grey"><?php echo $target;?>/<?php echo $base;?></span>
                        		</div>
                                <?php
                                    if($is_login == true){
                                ?>
                                <div class="fixed-width-600">
                                    <div class="fixed-width-520">
                                        <table class="table-analyze">
                                            <tbody>
												<tr>
                                                    <td><span class="span-normal span-14px span grey"><?php echo $langData['balance'][$lang];?></span></td>
                                                    <td><span class="span-normal span-14px span grey"><?php echo $langData['buy_price'][$lang];?></span></td>
                                                    <td><span class="span-normal span-14px span grey"><?php echo $langData['total_price'][$lang];?></span></td>
                                                    <td><span class="span-normal span-14px span grey"><?php echo $langData['total_diff'][$lang];?></span></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="span-normal-bold span-16px span-black" id="myBalance_total"><?php echo number_format($myBalanceData[$target]['f_total'], 8, '.', ',');?></span> <span class="span-normal-bold span-12px span-grey"><?php echo $target;?></span></td>
                                                    <td><span class="span-normal-bold span-16px span-black" id="myBalance_buy">
														<?php 
															if($myBalanceData[$target]['f_buy_base_volume'] >= 1000000){
																$tempValue = $myBalanceData[$target]['f_buy_base_volume'] / 1000000;
																$tempValue = round($tempValue);
																$tempValue = number_format($tempValue).'백만';
															}else{
																$tempValue = number_format($myBalanceData[$target]['f_buy_base_volume']);
															}
															echo $tempValue; 
														?>
														</span>
													</td>
                                                    <td>
														<span class="span-normal-bold span-16px span-black" id="myBalance_bVolume">
														<?php 
															$tVolumeByBase = $myBalanceData[$target]['f_total'] * $marketData[$target]['KRW']['f_close'];
															if ($tVolumeByBase >= 1000000) {
																$tempValue = $tVolumeByBase / 1000000;
																$tempValue = round($tempValue);
																$tempValue = number_format($tempValue) . '백만';
															} else {
																$tempValue = number_format($tVolumeByBase);
															}
															echo $tempValue;
														?>
														</span>
													</td>
                                                    <td>
														<span class="span-normal-bold span-16px span-black" id="myBalance_diff">
														<?php 
															$tempDiff = $myBalanceData[$target]['f_buy_base_volume'] - $tVolumeByBase;
															if ($tempDiff >= 1000000) {
																$tempValue = $tempDiff / 1000000;
																$tempValue = round($tempValue);
																$tempValue = number_format($tempValue) . '백만';
															} else if($tempDiff < -1000000) {
																$tempValue = $tempDiff / 1000000;
																$tempValue = round($tempValue);
																$tempValue = number_format($tempValue) . '백만';
															} else {
																$tempValue = number_format($tempDiff);
															}
															echo $tempValue;
														?>
														</span>
													<br>
														<!-- <span class="span-normal-bold span-12px span-grey" id="myBalance_diff">0</span> 
														<span class="span-normal-bold span-12px span-grey" id="myBalance_percent">0</span> -->
													</td>
												</tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="fixed-width-80 coin-balance-status-div">
                                        <label class="coin-balance-status"><?php echo $langData['my_balance_status'][$lang];?></label>
                                    </div>
                                </div>
                                <?php
                                    }
                                ?>                    		
                        	</div>
                    		<div class="row" style="margin-top: 15px;">
                        		<div class="fixed-width-245">
                        			<table class="market-info-table">
                        				<tbody style="width: 100%;">
                        					<tr>
												<?php
													if ($marketData[$target][$base]['f_diff'] > 0) {
														$className = 'span-red';
													} else if ($marketData[$target][$base]['f_diff'] < 0) {
														$className = 'span-blue';
													} else {
														$className = 'span-grey';
													}
												?>
                                                <td class="align-right">
													<span class="<?php echo $className; ?>" id="marketSummary_rateContent">
														<span class="span-normal-bold span-30px" id="marketSummary_rate">
															<?php
																echo number_format($marketData[$target][$base]['f_close'], $marketData[$target][$base]['f_decimal'], '.', ','); 
															?>
														</span> 
														<span class="span-HYWUML span-18px"><?php echo $base;?></span>
													</span>
												</td>
                        					</tr>
                        					<tr>
                                                <td class="span-HYWUML span-18px align-right">
													<span class="<?php echo $className; ?>" id="marketSummary_diff">
														<?php echo number_format($marketData[$target][$base]['f_diff'], $marketData[$target][$base]['f_decimal'], '.', ','); ?>
													</span>&nbsp;&nbsp;
													<span class="<?php echo $className; ?>" id="marketSummary_percent">
														<?php 
															if($marketData[$target][$base]['f_percent'] > 0){
																echo '+';
															}
															echo number_format($marketData[$target][$base]['f_percent'], 2, '.', ','); 
														?>%
													</span>
												</td>
                        					</tr>
                        				</tbody>
                        			</table>  
                        		</div>
                        		<div class="fixed-width-245">
                        			<table class="market-info-table">
                        				<tbody style="width: 100%;">
                        					<tr>
                        						<td class="float-left"><span class="span-normal span-14px span-grey">24H <?php echo $langData['high_rate'][$lang];?><span class="target-title">(<?php echo $base;?>)</span></span></td>
												<?php
													if($marketData[$target][$base]['f_high'] > $marketData[$target][$base]['f_open']){
														$className = 'span-red';
													}else if($marketData[$target][$base]['f_high'] == $marketData[$target][$base]['f_open']){
														$className = 'span-grey';
													}else{
														$className = 'span-blue';
													}
												?>
												<td class="float-right span-HYWUML span-16px"><span id="marketSummary_high" class="<?php echo $className;?>"><?php echo number_format($marketData[$target][$base]['f_high'], $marketData[$target][$base]['f_decimal'], '.', ','); ?></span></td>
                        					</tr>
                        					<tr>
                        						<td class="float-left"><span class="span-normal span-14px span-grey">24H <?php echo $langData['low_rate'][$lang];?><span class="target-title">(<?php echo $base;?>)</span></span></td>
												<?php
													if ($marketData[$target][$base]['f_low'] > $marketData[$target][$base]['f_open']) {
														$className = 'span-red';
													} else if ($marketData[$target][$base]['f_low'] == $marketData[$target][$base]['f_open']) {
														$className = 'span-grey';
													} else {
														$className = 'span-blue';
													}
												?>
												<td class="float-right span-HYWUML span-16px"><span id="marketSummary_low" class="<?php echo $className; ?>"><?php echo number_format($marketData[$target][$base]['f_low'], $marketData[$target][$base]['f_decimal'], '.', ','); ?></span></td>
                        					</tr>
                        				</tbody>
                        			</table>  
                        		</div>
                        		<div class="fixed-width-245">
                        			<table class="market-info-table">
                        				<tbody style="width: 100%;">
                        					<tr>
                        						<td class="float-left"><span class="span-normal span-14px span-grey"><?php echo $langData['buy_rate'][$lang];?> <span class="target-title">(<?php echo $base;?>)</span></span></td>
                        						<td class="float-right"><span id="marketSummary_buy" class=" span-HYWUML span-16px"><?php echo number_format($lastBuyRate, $marketData[$target][$base]['f_decimal'], '.', ','); ?></span></td>
                        					</tr>
                        					<tr>
                        						<td class="float-left"><span class="span-normal span-14px span-grey"><?php echo $langData['sell_rate'][$lang];?> <span class="target-title">(<?php echo $base;?>)</span></span></td>
                        						<td class="float-right"><span id="marketSummary_sell" class=" span-HYWUML span-16px"><?php echo number_format($lastSellRate, $marketData[$target][$base]['f_decimal'], '.', ','); ?></span></td>
                        					</tr>
                        				</tbody>
                        			</table>  
                        		</div>
                        		<div class="fixed-width-245" style="width: 260px;">
                        			<table class="market-info-table">
                        				<tbody style="width: 100%;">
                        					<tr>
                        						<td class="float-left" style="width: 60%;"><span class="span-normal span-14px span-grey">24H <?php echo $langData['target_volume'][$lang];?>(<?php echo $target;?>)</span></td>
                        						<td class="float-left" style="width: 40%;"><span id="marketSummary_tVolume" class=" span-HYWUML span-16px"><?php echo number_format($marketData[$target][$base]['f_day_target_volume'], 3, '.', ','); ?></span></td>
                        					</tr>
                        					<tr>
                        						<td class="float-left" style="width: 60%;"><span class="span-normal span-14px span-grey">24H <?php echo $langData['base_volume'][$lang];?>(<?php echo $base;?>)</span></span></td>
												<td class="float-left" style="width: 40%;">
													<span id="marketSummary_bVolume" class=" span-HYWUML span-16px">
													<?php 
														$tempValue = $marketData[$target][$base]['f_day_base_volume'];
														if ($tempValue >= 1000000) {
															$tempValue = $tempValue / 1000000;
															$tempValue = round($tempValue);
															$tempValue = number_format($tempValue) . '백만';
														} else {
															$tempValue = number_format($tempValue);
														}
														echo $tempValue;
													?>
													</span>
												</td>
                        					</tr>
                        				</tbody>
                        			</table>  
                        		</div>
                        	</div>
                        </div>
                        <div class="panel-body chartContentDiv">
                            <div id="chartdiv" class="svg-container"></div>
                            <script type="text/javascript" src="<?php echo base_url();?>assets/chart/charting_library/charting_library.min.js"></script>
                        </div>
                    </div>
                </div>
                <div class="row no-margin no-padding">
                    <div class="fixed-width-490 right-padding low-margin-top">
                    	<div class="panel panel-default" style="margin-bottom: 0px;background-color:#f4f7f9 !important;">
                        	<table class="table-full-width">
                        		<tbody>
    	                            <tr class="span-normal-bold span-12px span-grey" style="background-color:#f4f7f9;border-bottom:solid 1px #c6c7c7;line-height:28px;">
    	                                <td class="table-order-list-td left" style="width:197px;"><span class="float-left"><?php echo $langData['rate_short'][$lang];?>(<?php echo $base;?>)</span> <span class="float-right"><?php echo $langData['volume_short'][$lang];?>(<?php echo $target;?>)</span></td>
    	                                <td class="table-order-list-td center" style="width:102px;"><?php echo $langData['my_order_volume'][$lang];?></td>
    	                                <td class="table-order-list-td right" style="width:188px;"><?php echo $langData['market_summary'][$lang];?></td>
    	                            </tr>
                        		</tbody>
                            </table>
							<table class="table-sell-order-list" id="datatableSellOrders" style="background-color:#ffffff;">
                                <tbody>
                                    <?php
										$maxSellTVolume = 0;
										foreach ($orderBookData['sellOrders'] as $row) {
											if($row['tVolume'] == ''){
												$maxSellTVolume += 0;
											}else{
												$maxSellTVolume += $row['tVolume'];
											}
										}
                                        for($i=1;$i<=13;$i++){
											if($orderBookData['sellOrders'][$i]['tVolume'] == ''){
												$width = 0;
											}else{
												if($maxSellTVolume == 0){
													$width = 0;
												}else{
													$width = $orderBookData['sellOrders'][$i]['tVolume'] / $maxSellTVolume * 120;
												}
											}
                                    ?>
                                        <tr>
											<td style="width:197px;" class="orderBookSellOrders">
												<span id="orderBookSellOrder_<?php echo $i;?>_rate" style="display:inline;">
													<?php
														if($orderBookData['sellOrders'][$i]['rate'] != ''){
															echo number_format($orderBookData['sellOrders'][$i]['rate'], $marketData[$target][$base]['f_decimal'], '.', ',');
														}else{
															echo '';
														}
													?>
												</span>
												<span class="orderBookSellOrderShape" id="orderBookSellOrder_<?php echo $i;?>_tVolume" style="display:inline;width:<?php echo $width;?>px;">
													<?php
														if ($orderBookData['sellOrders'][$i]['tVolume'] != '') {
															echo number_format($orderBookData['sellOrders'][$i]['tVolume'], 3, '.', ',');
														} else {
															echo '';
														}
													?>
												</span>
											</td>
											<td style="width:102px;padding-right:12px;background-color:rgba(204,0,0,0.05);">
												<span id="orderBookSellOrder_<?php echo $i;?>_myTVolume" class="span-red span-12px span-normal-bold float-right" style="line-height:20px;">
													<?php
														if ($orderBookData['sellOrders'][$i]['myTVolume'] != '') {
															echo number_format($orderBookData['sellOrders'][$i]['myTVolume'], 8, '.', ',');
														} else {
															echo '';
														}
													?>
												</span>
											</td>
                                        </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
							<table class="table-buy-order-list-right" style="width:188px;background-color:#f4f7f9;">
								<tbody>
									<tr>
										<td style="border-top:0px;"><span class="span-normal span-12px span-black"><?php echo $langData['current_rate'][$lang];?></span></td>
										<td style="border-top:0px;" align="right" class="span-16px">
											<?php
												if ($marketData[$target][$base]['f_diff'] > 0) {
													$className = 'span-red';
												} else if ($marketData[$target][$base]['f_diff'] < 0) {
													$className = 'span-blue';
												} else {
													$className = 'span-grey';
												}
											?>	
											<span id="orderBook_rate" class="<?php echo $className;?>">
											<?php echo number_format($marketData[$target][$base]['f_close'], $marketData[$target][$base]['f_decimal'], '.', ','); ?>
											</span>
										</td>
									</tr>
									<tr>
										<td><span class="span-normal span-12px span-black"><?php echo $langData['last_day_diff'][$lang];?></span></td>
										<td align="right" style="line-height:20px;"><span id="orderBook_diff" class="<?php echo $className; ?>" style="font-size:16px;"><?php echo number_format($marketData[$target][$base]['f_diff'], $marketData[$target][$base]['f_decimal'], '.', ','); ?></span><br><span id="orderBook_percent" class="<?php echo $className; ?>" style="font-size:12px;"><?php if($marketData[$target][$base]['f_percent']>0){echo '+';} echo number_format($marketData[$target][$base]['f_percent'], $marketData[$target][$base]['f_decimal'], '.', ','); ?>%</span></td>
									</tr>
									<tr>
										<td><span class="span-normal span-12px span-black"><?php echo $langData['target_volume'][$lang];?></span></td>
										<td align="right"><span id="orderBook_tVolume" class="span-black span-16px"><?php echo number_format($marketData[$target][$base]['f_day_target_volume'], 3, '.', ','); ?></span><br>
										<span id="orderBook_bVolume" class="span-12px span-grey">
										<?php 
											$tempValue = $marketData[$target][$base]['f_day_base_volume'];
											if ($tempValue >= 1000000) {
												$tempValue = $tempValue / 1000000;
												$tempValue = round($tempValue);
												$tempValue = number_format($tempValue) . '백만';
											} else {
												$tempValue = number_format($tempValue);
											}
											echo $tempValue;
										?>
										</span> 
										<span class="span-12px span-grey"><?php echo $base;?></span></td>
									</tr>
									<tr>
										<?php
											if ($marketData[$target][$base]['f_high'] > $marketData[$target][$base]['f_open']) {
												$className = 'span-red';
											} else if ($marketData[$target][$base]['f_high'] == $marketData[$target][$base]['f_open']) {
												$className = 'span-grey';
											} else {
												$className = 'span-blue';
											}
										?>
										<td><span class="span-normal span-12px span-black"><?php echo $langData['high_rate'][$lang];?></span></td>
										<td align="right" class="span-16px"><span id="orderBook_high" class="<?php echo $className; ?>"><?php echo number_format($marketData[$target][$base]['f_high'], $marketData[$target][$base]['f_decimal'], '.', ','); ?></span></td>
									</tr>
									<tr>
										<?php
											if ($marketData[$target][$base]['f_low'] > $marketData[$target][$base]['f_open']) {
												$className = 'span-red';
											} else if ($marketData[$target][$base]['f_low'] == $marketData[$target][$base]['f_open']) {
												$className = 'span-grey';
											} else {
												$className = 'span-blue';
											}
										?>
										<td><span class="span-normal span-12px span-black"><?php echo $langData['low_rate'][$lang];?></span></td>
										<td align="right" class="span-16px"><span id="orderBook_low" class="<?php echo $className; ?>"><?php echo number_format($marketData[$target][$base]['f_low'], $marketData[$target][$base]['f_decimal'], '.', ','); ?></span></td>
									</tr>
								</tbody>
							</table>
							<table class="table-full-width">
                        		<tbody>
    	                            <tr class="span-normal-bold span-12px span-grey" style="background-color:#f4f7f9;border-bottom:solid 1px #c6c7c7;border-top:solid 1px #c6c7c7;">
										<td style="width:299px;padding:0px 5px 0px 12px;line-height:38px;" class="span-19px span-bold">
										<?php
										if ($marketData[$target][$base]['f_diff'] > 0) {
											$className = 'span-red';
										} else if ($marketData[$target][$base]['f_diff'] < 0) {
											$className = 'span-blue';
										} else {
											$className = 'span-grey';
										}
										?>
										<span id="orderBookCenterCurrentRate" class="<?php echo $className;?>">
											<?php echo number_format($marketData[$target][$base]['f_close'], $marketData[$target][$base]['f_decimal'], '.', ','); ?></span>
										</span> 
										<span class="span-14px span-grey" id="orderBookCenterCurrentRateByBase"></span> <span class="span-14px span-grey">KRW</span></td>
										<td style="width:188px;padding:0px 12px;border-left:solid 1px #c6c7c7;"><span class="float-left"><?php echo $langData['ordered_rate_short'][$lang];?></span> <span class="float-right"><?php echo $langData['ordered_volume_short'][$lang];?></span></td>
    	                            </tr>
                        		</tbody>
                            </table>
                            <div class="row no-margin no-padding">  	
                                <table class="table-buy-order-list" id="datatableBuyOrderList" style="background-color:#ffffff;border-right:solid 1px #c6c7c7;">
                            		<thead>
                                        <tr>
                                            <th>매도잔량</th>
                                            <th>주문가</th>
                                            <th>전일대비</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
											$maxBuyTVolume = 0;
											foreach ($orderBookData['buyOrders'] as $row) {
												if ($row['tVolume'] == '') {
													$maxBuyTVolume += 0;
												} else {
													$maxBuyTVolume += $row['tVolume'];
												}
											}
											for ($i = 1; $i <= 13; $i++) {
												if ($orderBookData['buyOrders'][$i]['tVolume'] == '') {
													$width = 0;
												} else {
													if ($maxBuyTVolume == 0) {
														$width = 0;
													} else {
														$width = $orderBookData['buyOrders'][$i]['tVolume'] / $maxBuyTVolume * 120;
													}
												}
                                        ?>
                                            <tr>
												<td style="width:197px;line-height:20px;" class="orderBookBuyOrders">
													<span id="orderBookBuyOrder_<?php echo $i;?>_rate" style="display:inline;">
														<?php
															if ($orderBookData['buyOrders'][$i]['rate'] != '') {
																echo number_format($orderBookData['buyOrders'][$i]['rate'], $marketData[$target][$base]['f_decimal'], '.', ',');
															} else {
																echo '';
															}
														?>
													</span>
													<span class="orderBookBuyOrderShape" id="orderBookBuyOrder_<?php echo $i;?>_tVolume" style="display:inline;width:<?php echo $width;?>px;">
													<?php
														if ($orderBookData['buyOrders'][$i]['tVolume'] != '') {
															echo number_format($orderBookData['buyOrders'][$i]['tVolume'], 3, '.', ',');
														} else {
															echo '';
														}
													?>
													</span>
												</td>
												<td style="width:102px;padding-right:12px;background-color:rgba(6,94,194,0.05);">
													<span id="orderBookBuyOrder_<?php echo $i;?>_myTVolume" class="span-blue span-12px span-normal-bold float-right" style="line-height:20px;">
													<?php
														if ($orderBookData['buyOrders'][$i]['myTVolume'] != '') {
															echo number_format($orderBookData['buyOrders'][$i]['myTVolume'], 8, '.', ',');
														} else {
															echo '';
														}
													?>
													</span>
												</td>
                                            </tr>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>	
                                <table class="span-normal span-12px" id="datatableMinMarketHistory">
                                    <tbody>
                                        <?php
                                            $tempCount = 0;
                                            foreach($marketHistoryData as $key => $row){
                                                $tempCount++;
                                                if($tempCount > 13) break;
                                                if($row['type'] == 'buy'){
                                                    $className = 'span-red';
                                                }else{
                                                    $className = 'span-blue';
                                                }
                                        ?>
                                                <tr>
                                                    <td><span class="float-left <?php echo $className;?>"><?php if($row['rate'] != ''){echo number_format($row['rate'], $unitDecimal, '.', ',');}else{echo '';};?></span></td>
                                                    <td><span class="<?php echo $className;?>"><?php if($row['volume'] != ''){echo number_format($row['volume'],3,'.',',');}else{echo '';};?></span></td>
                                                </tr>
                                        <?php
                                            }
                                            if($tempCount<13){
                                                for($i=$tempCount+1;$i<=13;$i++){
                                        ?>
                                                    <tr>
                                                        <td><span></span></td>
                                                        <td><span></span></td>
                                                    </tr>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
							</div>
							<table class="table-full-width">
                        		<tbody>
    	                            <tr class="span-normal-bold span-12px span-grey" style="background-color:#f4f7f9;border-top:solid 1px #c6c7c7;">
    	                                <td class="table-order-list-td left" style="width:197px;height:15px;"></td>
    	                                <td class="table-order-list-td center" style="width:102px;height:15px;"></td>
    	                                <td class="table-order-list-td right" style="width:188px;height:15px;"></td>
    	                            </tr>
                        		</tbody>
                            </table>
    	                </div>
    	            </div>
    	            <div class="fixed-width-505 left-padding low-margin-top">
    	            	<div class="no-padding no-margin orderCreationContent">
    	                	<ul class="nav nav-tabs tab-create-orders">
    		                    <li class="tab-buy-order active"><a href="#tabOrderBuy" data-toggle="tab" aria-expanded="true"><?php echo $langData['buy'][$lang];?></a></li>
    		                    <li class="tab-sell-order "><a href="#tabOrderSell" data-toggle="tab"><?php echo $langData['sell'][$lang];?></a></li>
    		                </ul>
    		                <div class="tab-content" style="background-color:#f4f7f9 !important;">
    		                    <div class="tab-pane fade in active" id="tabOrderBuy">
    		                    	<div class="panel panel-default create-orders">
                                        <div class="row">
                        				    <div class="fixed-width-120">
												<span class="span-normal span-14px span-grey"><?php echo $langData['available_buy_price'][$lang];?></span>
											</div>
                                            <div class="fixed-width-252">
												<div style="width:230px;border-bottom: solid 1px #dddddd;float:right;text-align: right;height:32px;line-height:32px;">
													<span class="span-16px span-normal-bold span-bold" id="baseAvailableBalance"><?php if($is_login == true){echo number_format($myBalanceData[$base]['f_available']);}else{echo 0;};?></span> 
													<span class="span-normal span-13px span-grey"><?php echo $base;?></span>
												</div>
											</div>
										</div>
										<div class="row">
                        				    <div class="fixed-width-120">
												<span class="span-normal span-14px span-grey"><?php echo $langData['order_type'][$lang];?></span>
											</div>
                                            <div class="fixed-width-252">
												<div style="width:252px;height:32px;line-height:32px;">
												</div>
											</div>
                                        </div>
                                        <div class="row">
                                            <div class="fixed-width-120"><span class="span-normal span-14px span-grey"><?php echo $langData['buy_rate'][$lang];?></span> <span class="span-normal span-13px span-grey">(<?php echo $base;?>)</span></div>
                                            <div class="fixed-width-252" style="padding-top:2px; padding-bottom:2px;">
                                                <a href="#" class="quantitychange minus buy"  title="-">-</a>
                                                <a href="#" class="quantitychange plus buy" title="+">+</a>
                                                <input type="text" id="order_buy_rate" class="form-control" style="width: 170px;float:right;height:30px;" value="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="fixed-width-120"><span class="span-normal span-14px span-grey"><?php echo $langData['buy_volume'][$lang];?></span> <span class="span-normal span-13px span-grey">(<?php echo $target;?>)</div>
                                            <div class="fixed-width-252" style="padding-top:2px;padding-bottom:2px;">
                                                <input type="text" id="order_buy_amount" class="form-control" style="width: 230px;float:right;height:30px;">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="fixed-width-120"></div>
                                            <div class="fixed-width-252" style="text-align:right;padding-top:2px;padding-bottom:2px;">
                                                        
                                                <a href="#" class="qtBt buy" quantity="10">10%</a>          
                                                <a href="#" class="qtBt buy" quantity="25">25%</a>
                                                <a href="#" class="qtBt buy" quantity="50">50%</a>
                                                <a href="#" class="qtBt buy" quantity="100">100%</a>                   
                                            
                                            </div>
                                        </div>
										<div class="row">
                                            <div class="fixed-width-120"><span class="span-normal span-14px span-grey"><?php echo $langData['buy_price'][$lang];?></span> <span class="span-normal span-13px span-grey">(<?php echo $base;?>)</div>
                                            <div class="fixed-width-252" style="padding-top:2px;padding-bottom:2px;">
                                                <span id="order_buy_price" class="form-control" style="width: 230px;float:right;height:30px;"></span>
                                            </div>
										</div>
										<div class="row" style="padding:0px 22px;line-height:39px;">
											<span class="span-normal span-12px span-grey float-left"><?php echo $langData['min_order_price'][$lang];?> : </span> 
											<span class="span-normal span-12px span-grey float-left">1000 <?php echo $base;?></span>
											<span class="span-normal span-12px span-grey float-right"><?php echo $langData['fee'][$lang];?>(<?php echo $langData['supertax'][$lang];?>) : 0.15%</span>
                                        </div>
                                        <button class="btn half-content format" id="format_buy_order_details"><?php echo $langData['format'][$lang];?></button>
                                        <button class="btn half-content create-buy-order" id="create_order_buy"><?php if($is_login==true){echo $langData['buy'][$lang];}else{echo $langData['sign_in'][$lang];}; ?></button>
    		                    	</div>
    		                    </div>
    		                    <div class="tab-pane fade" id="tabOrderSell">
    		                    	<div class="panel panel-default create-orders">
										<div class="row">
                        				    <div class="fixed-width-120">
												<span class="span-normal span-14px span-grey"><?php echo $langData['available_sell_volume'][$lang];?></span>
											</div>
                                            <div class="fixed-width-252">
												<div style="width:230px;border-bottom: solid 1px #dddddd;float:right;text-align: right;height:32px;line-height:32px;">
													<span class="span-16px span-normal-bold span-bold" id="targetAvailableBalance"><?php if ($is_login == true) {echo number_format($myBalanceData[$target]['f_available'], 8, '.', ',');} else {echo 0;}; ?></span>
													<span class="span-normal span-13px span-grey"><?php echo $target;?></span>
												</div>
											</div>
										</div>
										<div class="row">
                        				    <div class="fixed-width-120">
												<span class="span-normal span-14px span-grey"><?php echo $langData['order_type'][$lang];?></span>
											</div>
                                            <div class="fixed-width-252">
												<div style="width:252px;height:32px;line-height:32px;">
												</div>
											</div>
                                        </div>
                                        <div class="row">
                                            <div class="fixed-width-120"><span class="span-normal span-14px span-grey"><?php echo $langData['sell_rate'][$lang];?></span> <span class="span-normal span-13px span-grey">(<?php echo $base;?>)</span></div>
                                            <div class="fixed-width-252" style="padding-top:2px; padding-bottom:2px;">
                                                <a href="#" class="quantitychange minus sell"  title="-">-</a>
                                                <a href="#" class="quantitychange plus sell" title="+">+</a>
                                                <input type="text" id="order_sell_rate" class="form-control" style="width: 170px;float:right;height:30px;" value="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="fixed-width-120"><span class="span-normal span-14px span-grey"><?php echo $langData['sell_volume'][$lang];?></span> <span class="span-normal span-13px span-grey">(<?php echo $target;?>)</div>
                                            <div class="fixed-width-252" style="padding-top:2px;padding-bottom:2px;">
                                                <input type="text" id="order_sell_amount" class="form-control" style="width: 230px;float:right;height:30px;">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="fixed-width-120"></div>
                                            <div class="fixed-width-252" style="text-align:right;padding-top:2px;padding-bottom:2px;">
                                                        
                                                <a href="#" class="qtBt sell" quantity="10">10%</a>          
                                                <a href="#" class="qtBt sell" quantity="25">25%</a>
                                                <a href="#" class="qtBt sell" quantity="50">50%</a>
                                                <a href="#" class="qtBt sell" quantity="100">100%</a>                   
                                            
                                            </div>
                                        </div>
										<div class="row">
                                            <div class="fixed-width-120"><span class="span-normal span-14px span-grey"><?php echo $langData['sell_price'][$lang];?></span> <span class="span-normal span-13px span-grey">(<?php echo $base;?>)</div>
                                            <div class="fixed-width-252" style="padding-top:2px;padding-bottom:2px;">
                                                <span id="order_sell_price" class="form-control" style="width: 230px;float:right;height:30px;"></span>
                                            </div>
										</div>
										<div class="row" style="padding:0px 22px;line-height:39px;">
											<span class="span-normal span-12px span-grey float-left"><?php echo $langData['min_order_price'][$lang];?> : </span> 
											<span class="span-normal span-12px span-grey float-left">1000 <?php echo $base;?></span>
											<span class="span-normal span-12px span-grey float-right"><?php echo $langData['fee'][$lang];?>(<?php echo $langData['supertax'][$lang];?>) : 0.15%</span>
                                        </div>
                                        <button class="btn half-content format" id="format_sell_order_details"><?php echo $langData['format'][$lang];?></button>
										<button class="btn half-content create-sell-order" id="create_order_sell"><?php if($is_login==true){echo $langData['sell'][$lang];}else{echo $langData['sign_in'][$lang];}; ?></button>
                                    </div>
    		                    </div>
    		                </div>
    		            </div>
    		            <div class="row no-padding no-margin myOrderHistoryContent" style="margin-top: -5px;padding-bottom:15px;background-color:#f4f7f9;">
                            <ul class="nav nav-tabs tab-create-orders">
                                <li class="tab-my-open-orders active"><a href="#tab-my-open-orders" data-toggle="tab" aria-expanded="true"><?php echo $langData['my_open_orders'][$lang];?></a></li>
                                <li class="tab-my-order-history "><a href="#tab-my-order-history" data-toggle="tab"><?php echo $langData['my_order_history'][$lang];?></a></li>
                            </ul>
                            <div class="tab-content my-order-history">
                                <div class="tab-pane fade in active" id="tab-my-open-orders">
                                    <div class="panel panel-default coin-list" style="margin-bottom: 0px;">
                                        <table class="table-my-open-orders" id="datatableMyOpenOrders">
                                            <thead style="border-bottom:solid 1px #c7c7c7;background-color:#f4f7f9;">
                                                <tr>
                                                    <th style="width:135px;"><?php echo $langData['order_datetime'][$lang];?></th>
                                                    <th style="width:40px;"><?php echo $langData['type'][$lang];?></th>
                                                    <th style="width:80px;"><?php echo $langData['order_rate'][$lang];?></th>
                                                    <th style="width:80px;"><?php echo $langData['order_volume'][$lang];?></th>
                                                    <th style="width:80px;"><?php echo $langData['remained_order_volume'][$lang];?></th>
                                                    <th style="width:75px;"><?php echo $langData['edit'][$lang];?>/<?php echo $langData['cancel'][$lang];?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
												<?php
													$tempCount = 0;
													if ($is_login == true) {
														foreach ($myOpenOrdersData as $row) {
															if ($row['type'] == '매수') {
																$className = 'span-red';
																$tempTrStyle = 'background-color:#f4f7f9;';
															} else {
																$className = 'span-blue';
																$tempTrStyle = '';
															}
															$tempCount++;
												?>
                                                    <tr style="<?php echo $tempTrStyle; ?>">
														<td style="width:125px;text-align:center;">
															<?php echo $row['date'] ?>
														</td>
														<td style="width:40px;text-align:center;">
															<span class="<?php echo $className; ?>">
																<?php echo $row['type'] ?>
															</span>
														</td>
														<td style="width:80px;text-align:right;">
															<span class="<?php echo $className; ?>">
																<?php 
																	if ($row['rate'] != '') {
																		echo number_format($row['rate'], $unitDecimal, '.', ',');
																	} else {
																		echo '';
																	}; 
																?>
															</span>
														</td>
														<td style="width:80px;text-align:right;">
															<span class="<?php echo $className; ?>">
																<?php 
																	if ($row['originalTVolume'] != '') {
																		echo number_format($row['originalTVolume'], 3, '.', ',');
																	} else {
																		echo '';
																	}; 
																?>
															</span>
														</td>
														<td style="width:80px;text-align:right;">
															<span class="<?php echo $className; ?>">
																<?php 
																	if ($row['tVolume'] != '') {
																		echo number_format($row['tVolume'], 3, '.', ',');
																	} else {
																		echo '';
																	};
																?>
															</span>
														</td>
														<?php
															if ($row['type'] == '매수') {
																$tempStyle = "background-color:#cc0000;";
															} else {
																$tempStyle = "background-color:#065ec2;";
															}
														?>
														<td style="width:75px;text-align:center;"><span class="date badge badge-my-order-delete" style="<?php echo $tempStyle;?>" onclick="cancel_my_open_order('<?php echo $row['id'];?>')">취소</span></td>
                                                    </tr>
                                                <?php
														}
													}
													if ($tempCount == 0) {
														echo '<tr id="datatableMyOpenOrdersEmptyDiv"><td colspan="6"><div class="empty-panel" style="width:500px;height:250px;"><p class="empty-panel-title" style="padding-top: 35%;">' . $langData['excn-adnc-msg-1'][$lang] . '</p></div></td></tr>';
													}
												?>
											</tbody>
                                        </table>    
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab-my-order-history">
                                    <div class="panel panel-default coin-list" style="margin-bottom: 0px;">
                                        <table class="table-my-order-history" id="datatableMyOrderHistory">
                                            <thead style="border-bottom:solid 1px #c7c7c7;background-color:#f4f7f9;">
                                                <tr>
                                                    <th><?php echo $langData['ordered_date'][$lang];?></th>
                                                    <th><?php echo $langData['type'][$lang];?></th>
                                                    <th><?php echo $langData['ordered_rate'][$lang];?></th>
                                                    <th><?php echo $langData['ordered_volume'][$lang];?></th>
                                                    <th><?php echo $langData['fee'][$lang];?></th>
                                                    <th><?php echo $langData['ordered_price'][$lang];?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $tempCount = 0;
                                                    if($is_login==true){
                                                        foreach($myOrderHistoryData as $row){
                                                            if($row['type'] == '매수'){
																$className = 'span-red';
																$tempTrStyle = 'background-color:#f4f7f9;';
                                                            }else{
																$className = 'span-blue';
																$tempTrStyle = '';
                                                            }
                                                            $tempCount++;
                                                ?>
                                                    <tr style="<?php echo $tempTrStyle;?>">
                                                        <td style="width:125px;text-align:center;"><?php echo $row['date']?></td>
                                                        <td style="width:40px;text-align:center;"><span class="<?php echo $className;?>"><?php echo $row['type']?></s></td>
                                                        <td style="width:80px;text-align:right;"><span class="<?php echo $className;?>"><?php if($row['rate']!=''){echo number_format($row['rate'], $unitDecimal, '.', ',');}else{echo '';};?></span></td>
                                                        <td style="width:80px;text-align:right;"><span class="<?php echo $className;?>"><?php if($row['tVolume']!=''){echo number_format($row['tVolume'],3,'.',',');}else{echo '';};?></span></td>
                                                        <td style="width:80px;text-align:right;"><?php echo $row['fee']?></td>
                                                        <td style="width:85px;text-align:right;padding-right:10px;"><span class="<?php echo $className;?>"><?php if($row['bVolume']!=''){echo number_format($row['bVolume'], $unitDecimal, '.', ',');}else{echo '';};?></span></td>
                                                    </tr>
                                                <?php
                                                        }
                                                    }
                                                    if($tempCount == 0){
                                                        echo '<tr id="datatableMyOrderHistoryEmptyDiv"><td colspan="6"><div class="empty-panel" style="width:500px;height:250px;"><p class="empty-panel-title" style="padding-top: 35%;">'.$langData['excn-adnc-msg-1'][$lang].'</p></div></td></tr>';
                                                    }
                                                ?>
                                            </tbody>
                                        </table>  
                                    </div>
                                </div>
                            </div>
    		            </div>
    	            </div>
                </div>
                <div class="row no-margin no-padding marketHistoryContent" style="margin-top: 5px !important;">
                    <ul class="nav nav-tabs tab-create-orders">
                        <li class="tab-market-history active"><a href="#tab-market-history" data-toggle="tab" aria-expanded="true"><?php echo $langData['market_history'][$lang];?></a></li>
                        <li class="tab-market-daily-history"><a href="#tab-market-daily-history" data-toggle="tab"><?php echo $langData['daily_market_history'][$lang];?></a></li>
                    </ul>
                    <div class="tab-content market-history">
                        <div class="tab-pane fade in active" id="tab-market-history">
                            <div class="panel panel-default coin-list datatableMarketHistoryScroll">
                                <table width="100%" id="datatableMarketHistory">
                                    <thead>
                                        <tr>
                                            <th><?php echo $langData['ordered_date'][$lang];?></th>
                                            <th><?php echo $langData['ordered_rate'][$lang];?></th>
                                            <th><?php echo $langData['ordered_volume_short'][$lang];?></th>
                                            <th><?php echo $langData['ordered_price'][$lang];?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $tempCount = 0;
                                            foreach($marketHistoryData as $key => $row){
                                                if($row['type'] == 'buy'){
													$className = 'span-red';
													$tempTrStyle = '';
                                                }else{
                                                    $className = 'span-blue';
													$tempTrStyle = 'background-color:#f4f7f9;';
                                                }
                                                $tempCount++;
                                        ?>
                                                <tr style="<?php echo $tempTrStyle;?>">
                                                    <td style="width:245px;text-align:center;"><?php echo $row['regdate'];?></td>
                                                    <td style="width:245px;text-align:center;"><span class="<?php echo $className;?>"><?php if($row['rate'] != ''){echo number_format($row['rate'], $unitDecimal, '.', ',');};?></span></td>
                                                    <td style="width:245px;text-align:center;"><span class="<?php echo $className;?>"><?php if($row['volume'] != ''){echo number_format($row['volume'],8,'.',',');};?></span></td>
                                                    <td style="width:245px;text-align:right;padding-right:100px;"><span class="<?php echo $className;?>"><?php if($row['bVolume'] != ''){echo number_format($row['bVolume'], $unitDecimal, '.', ',');};?></span></td>
                                                </tr>
                                        <?php
                                            }
                                            if($tempCount == 0){
                                                echo '<tr id="datatableMarketHistoryEmptyDiv"><td colspan="4"><div class="empty-panel" style="width:995px;height:355px;"><p class="empty-panel-title" style="padding-top: 23%;">'.$langData['excn-adnc-msg-1'][$lang].'</p></div></td></tr>';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-market-daily-history">
                            <div class="panel panel-default coin-list datatableMarketHistoryScroll">
                                <table width="100%" class="table-striped" id="datatableDailyMarketHistory">
                                    <thead>
                                        <tr>
                                            <th style="width:164px;"><?php echo $langData['ordered_date'][$lang];?></th>
                                            <th style="width:164px;"><?php echo $langData['close_rate'][$lang];?>(<?php echo $base;?>)</th>
                                            <th style="width:164px;"><?php echo $langData['last_day_diff'][$lang];?>(<?php echo $base;?>)</th>
                                            <th style="width:164px;"><?php echo $langData['in_de_percent'][$lang];?></th>
                                            <th style="width:164px;"><?php echo $langData['target_volume'][$lang];?>(<?php echo $target;?>)</th>
                                            <th style="width:164px;"><?php echo $langData['base_volume'][$lang];?>(<?php echo $base;?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $tempCount = 0;
                                            foreach($dailyMarketHistoryData as $key => $row){
                                                if($row['diffPercent'] > 0){
													$className = 'span-red';
													$percent = '+'.number_format($row['diffPercent'],2,'.',',').'%';
                                                }else if($row['diffPercent'] == 0){
													$className = 'span-grey';
													$percent = number_format($row['diffPercent'],2,'.',',').'%';
                                                }else{
                                                    $className = 'span-blue';
													$percent = number_format($row['diffPercent'],2,'.',',').'%';
                                                }
                                                $tempCount++;
                                        ?>
                                                <tr>
                                                    <td style="width:164px;text-align:center;"><span class="dailyMarketHistoryDate"><?php echo $row['date'];?></span></td>
                                                    <td style="width:164px;text-align:center;"><span class="<?php echo $className;?>"><?php echo number_format($row['close'], $unitDecimal, '.', ',');?></span></td>
                                                    <td style="width:164px;text-align:right;padding-right:50px;"><span class="<?php echo $className;?>"><?php echo number_format($row['diff'], $unitDecimal, '.', ',');?></span></td>
                                                    <td style="width:164px;text-align:right;padding-right:50px;"><span class="<?php echo $className;?>"><?php echo $percent;?></span></td>
                                                    <td style="width:164px;text-align:right;padding-right:50px;"><span><?php echo number_format($row['tVolume'],8,'.',',');?></span></td>
                                                    <td style="width:164px;text-align:right;padding-right:50px;"><span><?php echo number_format($row['bVolume'], $unitDecimal, '.', ',');?></span></td>
                                                </tr>
                                        <?php
                                            }
                                            if($tempCount == 0){
                                                echo '<tr id="datatableDailyMarketHistoryEmptyDiv"><td colspan="6"><div class="empty-panel" style="width:995px;height:355px;"><p class="empty-panel-title" style="padding-top: 23%;">'.$langData['excn-adnc-msg-1'][$lang].'</p></div></td></tr>';
                                            }
                                        ?>
                                    </tbody>
                                </table>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var walletObject = [];
		walletObject['BTC'] = walletObject['ETH'] = walletObject['SKY'] = walletObject['BDR'] = 0;
	</script>
	<?php
		if($is_login == true){
			echo '	<script>
						walletObject[target] = '.$myBalanceData[$target]['f_available'].';
						walletObject[base] = '.$myBalanceData[$base]['f_available'].';
					</script>';
		}else{
			echo '	<script>
						walletObject[target] = 0;
						walletObject[base] = 0;
					</script>';
		}
	?>
