	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Hotwallet management</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Hotwallet management
					</div>
					<div class="panel-body">
						<?php
							foreach ($coinList as $row) {
								if($row['f_unit'] != 'KRW'){
						?>
							<div class="col-lg-4 col-md-4 col-sm-4">
								<div class="panel panel-default">
									<div class="panel-heading" style="height:130px;">
										<div class="row">
											<div class="col-xs-3">
												<img src="<?php echo base_url() . 'assets/image/coin/' . $row['f_unit'] . '.png'; ?>" style="width:64px;height:64px;margin:8px;">
											</div>
											<div class="col-xs-9 text-right">
												<div style="font-size:20px !important;line-height:40px;"><span class="span-bold" style="font-size: 24px;"><?php echo $row['f_title']; ?></span><br>
												<?php 
													if ($row['f_unit'] == 'KRW') {
														echo number_format($siteWalletBalance[$row['f_unit']]);
													} else {
														echo number_format($siteWalletBalance[$row['f_unit']], 8, '.', ',');
													};
												?>
												<span class="span-tiny"><?php echo $row['f_unit']; ?></span></div>
												<?php echo $siteAddressList[$row['f_unit']]; ?>
											</div>
										</div>
									</div>
									<div class="panel-footer">
										<span class="pull-right">
											<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModal" onclick="setUnit('<?php echo $row['f_unit'];?>')"> Withdraw with cold wallet</button>
										</span>
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
						<?php
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document" style="width:400px;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel" style="text-align:center;font-size:20px;font-weight:bold;">Withdraw with cold wallet</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<input type="hidden" class="form-control" id="coin">
							출금주소
							<input type="text" class="form-control" id="toAddress"><br>
							출금수량
							<input type="text" class="form-control" id="amount">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" id="submitWithdrawal">출금</button>
							<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $langData['admin-page-msg-28']['KO']; ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
