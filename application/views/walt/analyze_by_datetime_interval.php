<div class="panel panel-default no-padding basicBackground panelShadowed" style="border-color: #fff;margin-top:20px;padding-top:20px;">
	<div class="panel-body no-padding" style="line-height: 45px;padding-left:8px;padding-right:8px;">
		<span class="span-24px span-black" style="padding-left:50px;padding-right:50px;border-bottom:solid 2px #182129"><?php echo $langData['balc_balance_analyze_by_date'][$lang];?><span class="span-14px span-normal span-black" style="margin-left:30px;"><?php echo $langData['total_count'][$lang];?> : <?php echo count($balanceData);?> <?php echo $langData['row'][$lang];?></span></span>
		<button class="btn btn-default float-right span-normal span-14px span-light span-background-grey btnFixedPadding"><?php echo $langData['download'][$lang];?></button>
		<button class="btn btn-default btn-dark-grey float-right span-normal span-14px span-background-black span-light btnFixedPadding" style="margin-right:10px;"><?php echo $langData['search'][$lang];?></button>
		<input type="text" class="form-control my-datepicker float-right" data-toggle="datepicker"><span class="float-right" style="line-height: 30px;"> ~ </span>
		<input type="text" class="form-control my-datepicker float-right" data-toggle="datepicker">
	</div>
	<div class="panel-body" style="padding:0px 8px 0px 8px;">
		<table class="table-current-balance table-striped">
			<thead>
				<tr>
					<th style="width:50px;"></th>
					<th style="width:123px;"><?php echo $langData['balc_balance'][$lang];?></th>
					<th style="width:173px;"><?php echo $langData['balc_basic_balance'][$lang];?></th>
					<th style="width:173px;"><?php echo $langData['balc_basic_balance_price'][$lang];?></th>
					<th style="width:173px;"><?php echo $langData['balc_deposit_amount'][$lang];?></th>
					<th style="width:173px;"><?php echo $langData['balc_withdraw_amount'][$lang];?></th>
					<th style="width:173px;"><?php echo $langData['balc_final_interval_balance'][$lang];?></th>
					<th style="width:173px;"><?php echo $langData['balc_final_Interval_price'][$lang];?></th>
					<th style="width:173px;"><?php echo $langData['lost_profit'][$lang];?></th>
				</tr>
			</thead>
			<tbody style="border-top:solid 1px #c7c7c7;border-bottom:solid 1px #c7c7c7;">
				<tr>
					<td class="text-align-center"><img src="<?php echo base_url();?>assets/image/coin/KRW.png"></td>
					<td class="text-align-left"><span class="span-13px span-black"><?php echo $langData['KRW'][$lang];?></span><br><span class="span-12px span-grey">KRW</span></td>
					<td class="text-align-right"><span class="span-13px span-black">0</span><br><span class="span-12px span-grey">KRW</span></td>
					<td class="text-align-right"><span class="span-13px span-black">0</span><br><span class="span-12px span-grey">KRW</span></td>
					<td class="text-align-right"><span class="span-13px span-black">0</span><br><span class="span-12px span-grey">KRW</span></td>
					<td class="text-align-right"><span class="span-13px span-black">0</span><br><span class="span-12px span-grey">KRW</span></td>
					<td class="text-align-right"><span class="span-13px span-black">0</span><br><span class="span-12px span-grey">KRW</span></td>
					<td class="text-align-right"><span class="span-13px span-black">0</span><br><span class="span-12px span-grey">KRW</span></td>
					<td class="text-align-right"><span class="span-13px span-black">0</span><br><span class="span-12px span-grey">KRW</span></td>
				</tr>
				<?php
					foreach ($coinData as $key => $value) {
				?>
				<tr>
					<td class="text-align-center"><img src="<?php echo base_url().$value['f_img'];?>"></td>
					<td class="text-align-left"><span class="span-13px span-black"><?php echo $langData[strtolower($value['f_unit'])][$lang];?></span><br><span class="span-12px span-grey"><?php echo $value['f_unit'];?></span></td>
					<td class="text-align-right"><span class="span-13px span-black">0</span><br><span class="span-12px span-grey"><?php echo $value['f_unit'];?></span></td>
					<td class="text-align-right"><span class="span-13px span-black">0</span><br><span class="span-12px span-grey"><?php echo $value['f_unit'];?></span></td>
					<td class="text-align-right"><span class="span-13px span-black">0</span><br><span class="span-12px span-grey"><?php echo $value['f_unit'];?></span></td>
					<td class="text-align-right"><span class="span-13px span-black">0</span><br><span class="span-12px span-grey"><?php echo $value['f_unit'];?></span></td>
					<td class="text-align-right"><span class="span-13px span-black">0</span><br><span class="span-12px span-grey"><?php echo $value['f_unit'];?></span></td>
					<td class="text-align-right"><span class="span-13px span-black">0</span><br><span class="span-12px span-grey"><?php echo $value['f_unit'];?></span></td>
					<td class="text-align-right"><span class="span-13px span-black">0</span><br><span class="span-12px span-grey"><?php echo $value['f_unit'];?></span></td>
				</tr>
				<?php
					}
				?>
			</tbody>
		</table>
	</div>
	<div style="text-align: right;">
		<div class="row no-margin" style="padding: 3px 3px 3px 19px;">
			<div class="span-normal span-14px span-grey float-left">※ <?php echo $langData['msg_about_analyzing_rule'][$lang];?></div>
			<div class="quiz" id="intval_analyze_quiz">
				<div class="answer" id="intval_analyze_answer">
					<div class="close-answer"></div>
					<span class="span-bold" style="line-height: 25px;"><?php echo $langData['algorithm'][$lang];?></span><br>
					<span class="span-grey">- <?php echo $langData['balc_basic_balance'][$lang];?> = <?php echo $langData['search'][$lang];?><?php echo $langData['balc_balance_of_start_date'][$lang];?></span><br>
					<span class="span-grey">- <?php echo $langData['balc_basic_balance_price'][$lang];?> = <?php echo $langData['search'][$lang];?><?php echo $langData['balc_balance_of_start_date'][$lang];?> * <?php echo $langData['search'][$lang];?><?php echo $langData['balc_start_date_close_rate'][$lang];?></span><br>
					<span class="span-grey">- <?php echo $langData['balc_final_interval_balance'][$lang];?> = <?php echo $langData['search'][$lang];?><?php echo $langData['balc_balance_of_last_date'][$lang];?></span><br>
					<span class="span-grey">- <?php echo $langData['balc_final_Interval_price'][$lang];?> = <?php echo $langData['search'][$lang];?><?php echo $langData['balc_balance_of_last_date'][$lang];?> * <?php echo $langData['search'][$lang];?><?php echo $langData['balc_close_rate_of_last_date'][$lang];?>(<?php echo $langData['balc_calc_by_search_date_rate'][$lang];?>)</span><br>
					<span class="span-grey">- <?php echo $langData['lost_profit'][$lang];?> = <?php echo $langData['balc_final_Interval_price'][$lang];?> - <?php echo $langData['balc_basic_balance_price'][$lang];?></span><br>
				</div>
			</div>
		</div>
	</div>
</div>
