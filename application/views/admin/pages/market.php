<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo $langData['admin-page-msg-13']['KO'];?></h1>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-heading" style="height:51px;line-height:34px;">
					<?php echo $langData['admin-page-msg-85']['KO'];?>
				</div>
				<div class="panel-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="tableCoinList">
						<thead>
							<tr>
								<th><?php echo $langData['admin-page-msg-87']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-88']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-89']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-90']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-91']['KO'];?>(%)</th>
								<th><?php echo $langData['admin-page-msg-92']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-93']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-94']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-95']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-96']['KO'];?></th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($marketData as $key => $value){
									$percent = $value['f_percent'];
									if($percent > 0){
										$percent = '+'.number_format($percent,2,'.',',').'%';
									}else{
										$percent = number_format($percent,2,'.',',').'%';
									}
							?>
							<tr>
								<td style="text-align:center;"><?php echo $value['f_target'].'-'.$value['f_base'];?></td>
								<td style="text-align:right;"><?php echo number_format($value['f_close']);?></td>
								<td style="text-align:right;"><?php echo number_format($value['f_open']);?></td>
								<td style="text-align:right;"><?php echo number_format($value['f_diff']);?></td>
								<td style="text-align:right;"><?php echo $percent;?></td>
								<td style="text-align:right;"><?php echo number_format($value['f_high']);?></td>
								<td style="text-align:right;"><?php echo number_format($value['f_low']);?></td>
								<td style="text-align:right;"><?php echo number_format($value['f_target_volume']);?></td>
								<td style="text-align:right;"><?php echo number_format($value['f_base_volume']);?></td>
								<td>
									<select class="btn btn-default btn-outline" style="padding:0px;width:45%;" onchange="changeMarketStatus('<?php echo $value['f_id'];?>',this)">
										<option value="1" <?php if($value['f_enabled'] == 1){echo 'selected';};?>><?php echo $langData['admin-ctrl-msg-22']['KO'];?></option>
										<option value="0" <?php if($value['f_enabled'] == 0){echo 'selected';};?>><?php echo $langData['admin-ctrl-msg-21']['KO'];?></option>
									</select>
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
	</div>
</div>
