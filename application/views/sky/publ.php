	<input type="hidden" id="remainTime" value="<?php echo $remainTime;?>">
	<div class="coinsky-page">
		<div class="coinsky-page-header">
			<span class="coinsky-page-header-title"></span>
		</div>
		<div class="coinsky-page-content back-color-light">
			<div class="publ-main-board panelShadowed">
				<div class="publ-main-board-content">
					<div class="publ-header-content">
						<div class="publ-header-content-left">
							<div class="publ-header-content-logo"><?php echo $langData['sky-publ-msg-1'][$lang];?></div>
							<div class="publ-header-content-descript span-black"><?php echo $langData['sky-publ-msg-2'][$lang];?></div>
						</div>
						<div class="publ-header-content-right">
							<span class="snapShotTimer Hour">00</span>&nbsp;:&nbsp;<span class="snapShotTimer Min">00</span>&nbsp;:&nbsp;<span class="snapShotTimer Sec">00</span>
						</div>
					</div>
					<div class="publ-body-content">
						<div class="publ-body-content-half">
							<div class="publ-board-header span-blue"><?php echo $langData['sky-publ-msg-3'][$lang];?></div>
							<div class="publ-board-title span-black"><?php echo $langData['sky-publ-msg-4'][$lang];?></div>
							<div class="publ-board-text span-black"><?php echo $langData['sky-publ-msg-5'][$lang];?> <span class="float-right"><?php echo number_format(6314422, 8, '.', ',');?> SKY</span></div>
							<div class="publ-board-text span-black"><?php echo $langData['sky-publ-msg-11'][$lang];?> <span class="float-right"><?php echo $lastDayTotalETHVolume;?> ETH</span></div>
							<div class="publ-board-total span-blue">1 ETH= <span class="float-right"><?php echo $lastDayETHSKYRate;?> SKY</span></div>
							<div class="publ-board-sub span-grey span-normal">* <?php echo $langData['sky-publ-msg-6'][$lang];?></div>
							<div class="publ-board-title span-black"><?php echo $langData['sky-publ-msg-9'][$lang];?></div>
							<div class="publ-board-text span-black"><?php echo $langData['sky-publ-msg-5'][$lang];?> <span class="float-right"><?php echo number_format(6314422, 8, '.', ','); ?> SKY</span></div>
							<div class="publ-board-text span-black"><?php echo $langData['sky-publ-msg-11'][$lang];?> <span class="float-right"><?php echo $todayTotalETHVolume;?> ETH</span></div>
							<div class="publ-board-total span-blue">1 ETH= <span class="float-right"><?php echo $todayETHSKYRate;?> SKY</span></div>
							<div class="publ-board-sub span-grey span-normal">* <?php echo $langData['sky-publ-msg-7'][$lang];?></div>
						</div>
						<div class="publ-body-content-half">
							<div class="publ-board-header span-brown"><?php echo $langData['sky-publ-msg-10'][$lang];?></div>
							<div class="publ-board-title span-black"><?php echo $langData['sky-publ-msg-4'][$lang];?></div>
							<div class="publ-board-text span-black"><?php echo $langData['sky-publ-msg-12'][$lang];?> <span class="float-right"><?php echo $lastDaySKYPoolVolume;?> SKY</span></div>
							<div class="publ-board-text span-black"><?php echo $langData['sky-publ-msg-13'][$lang];?> <span class="float-right"><?php echo $lastDayEarningETH;?> ETH</span></div>
							<div class="publ-board-total span-brown">1 SKY= <span class="float-right"><?php echo $lastDaySKYETHRate;?> ETH</span></div>
							<div class="publ-board-sub span-grey span-normal">* <?php echo $langData['sky-publ-msg-8'][$lang];?></div>
							<div class="publ-board-title span-black"><?php echo $langData['sky-publ-msg-9'][$lang];?></div>
							<div class="publ-board-text span-black"><?php echo $langData['sky-publ-msg-12'][$lang];?> <span class="float-right"><?php echo $todaySKYPoolVolume;?> SKY</span></div>
							<div class="publ-board-text span-black"><?php echo $langData['sky-publ-msg-13'][$lang];?> <span class="float-right"><?php echo $todayEarningETH;?> ETH</span></div>
							<div class="publ-board-total span-brown">1 SKY= <span class="float-right"><?php echo $todaySKYETHRate;?> ETH</span></div>
							<div class="publ-board-sub span-grey span-normal">* <?php echo $langData['sky-publ-msg-8'][$lang];?></div>
						</div>
					</div>
					<div class="publ-footer-content">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
