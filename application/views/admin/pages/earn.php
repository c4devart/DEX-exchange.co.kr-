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
						수익 현황
					</div>
					<div class="panel-body">
						<div class="col-lg-2 col-md-2 col-sm-3">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <img src="<?php echo base_url() . 'assets/image/coin/KRW.png'; ?>" style="width:32px;height:32px;">
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div style="font-size:20px !important;line-height:26px;"><span class="span-bold"><?php echo $langData['admin-page-msg-114']['KO']; ?></span><br><span class="span-tiny">KRW</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <span class="pull-right"><?php echo number_format($siteTotalProfitBase); ?></span>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
						</div>
						<?php
							foreach ($coinList as $row) {
						?>
						<div class="col-lg-2 col-md-2 col-sm-3">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <img src="<?php echo base_url() . 'assets/image/coin/'.$row['f_unit'].'.png'; ?>" style="width:32px;height:32px;">
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div style="font-size:20px !important;line-height:26px;"><span class="span-bold"><?php echo $row['f_title']; ?></span><br><span class="span-tiny"><?php echo $row['f_unit'];?></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <span class="pull-right">
									<?php 
										if ($row['f_unit'] == 'KRW') {
											echo number_format($siteProfit[$row['f_unit']]);
										} else {
											echo number_format($siteProfit[$row['f_unit']], 8, '.', ',');
										}; 
									?>
									</span>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
						</div>
						<?php

							}
						?>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						수익 내역
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
						</div>
					</div>
					<div class="panel-body">
						<table width="100%" class="table table-striped table-bordered table-hover" id="siteProfitHistory">
							<thead>
								<tr>
									<th>일자별</th>
									<th>자산분류</th>
									<th>거래량</th>
									<th>수수료(수익)</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
