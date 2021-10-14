	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo $langData['admin-page-msg-2']['KO'];?></h1>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						자산 현황
					</div>
					<div class="panel-body">
						<table class="table table-striped table-bordered table-hover" id="siteBalanceTable">
							<thead>
								<tr>
									<th style="width:16%;">자산 분류</th>
									<th style="width:28%;">코인스카이 지갑</th>
									<th style="width:28%;">회원 보유자산</th>
									<th style="width:28%;">수익</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="text-align:center;">총 보유자산</td>	
									<td><?php echo number_format($siteTotalBase);?> KRW</td>
									<td><?php echo number_format($userTotalBase); ?> KRW</td>
									<td><?php echo number_format($siteTotalProfitBase); ?> KRW</td>
								</tr>
								<?php
									foreach($coinList as $row){
								?>
								<tr>
									<td style="text-align:left;"><img src="<?php echo base_url(). $row['f_img'];?>" style="width:32px;height:32px;"> <?php echo $row['f_title']; ?> (<?php echo $row['f_unit'];?>)</td>
									<td><?php if($row['f_unit'] == 'KRW'){echo number_format($siteWalletBalance[$row['f_unit']]);}else{echo number_format($siteWalletBalance[$row['f_unit']], 8, '.', ',');};?> <?php echo $row['f_unit']; ?></td>
									<td><?php if ($row['f_unit'] == 'KRW') {echo number_format($userTotalBalance[$row['f_unit']]);} else {echo number_format($userTotalBalance[$row['f_unit']], 8, '.', ',');}; ?> <?php echo $row['f_unit']; ?></td>
									<td><?php if ($row['f_unit'] == 'KRW') {echo number_format($siteProfit[$row['f_unit']]);} else {echo number_format($siteProfit[$row['f_unit']], 8, '.', ',');}; ?> <?php echo $row['f_unit']; ?></td>
								</tr>
								<?php
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						코인스카이 자산 내역
					</div>
					<div class="panel-body" style="border-bottom:solid 1px #c7c7c7;">
						<div class="panel-body low-padding">
							<?php
								$toDate = date('Y-m-d');
							?>
							<span class="float-left" style="line-height: 30px;"><?php echo $langData['admin-page-msg-33']['KO'];?></span>
							<input type="text" class="form-control my-datepicker" data-toggle="datepicker" id="searchByFromDate" value="<?php echo $fromDate;?>"><span class="float-left" style="line-height: 30px;"> ~ </span>
							<input type="text" class="form-control my-datepicker" data-toggle="datepicker" id="searchByToDate" value="<?php echo $toDate;?>">
							<button class="btn btn-default btn-dark-grey" id="filterSearch"><?php echo $langData['admin-page-msg-34']['KO'];?></button>
							<!-- <div class="filterElementContent"> -->
								<select id="searchByType" class="form-control float-right" style="margin-right:10px;">
									<option value=""><?php echo $langData['admin-page-msg-120']['KO'];?></option>
									<option value="in"><?php echo $langData['admin-page-msg-121']['KO'];?></option>
									<option value="out"><?php echo $langData['admin-page-msg-122']['KO'];?></option>
								</select>
								<span class="float-right" style="margin-right:10px;line-height:34px;"><?php echo $langData['admin-page-msg-128']['KO'];?></span>
							<!-- </div>
							<div class="filterElementContent"> -->
								<select id="searchByUnit" class="form-control float-right" style="margin-right:10px;">
									<option value=""><?php echo $langData['admin-page-msg-120']['KO'];?></option>    
									<option value="KRW"><?php echo $langData['admin-page-msg-115']['KO'];?> <span class="span-grey span-tiny">(KRW)</span></option>
									<option value="BTC"><?php echo $langData['admin-page-msg-116']['KO'];?> <span class="span-grey span-tiny">(BTC)</span></option>
									<option value="ETH"><?php echo $langData['admin-page-msg-118']['KO'];?> <span class="span-grey span-tiny">(ETH)</span></option>
									<option value="SKY"><?php echo $langData['admin-page-msg-119']['KO'];?> <span class="span-grey span-tiny">(SKY)</span></option>
								</select>
								<span class="float-right" style="margin-right:10px;line-height:34px;"><?php echo $langData['admin-page-msg-129']['KO'];?></span>
							<!-- </div> -->
						</div>
					</div>
					<div class="panel-body">
						<table width="100%" class="table table-striped table-bordered table-hover" id="siteProfitHistory">
							<thead>
								<tr>
									<th><?php echo $langData['admin-page-msg-35']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-124']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-123']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-125']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-126']['KO'];?></th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
