	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo $langData['admin-page-msg-21']['KO'];?></h1>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-heading" style="height:51px;line-height:34px;">
					<?php echo $langData['admin-page-msg-22']['KO'];?>
				</div>
				<div class="panel-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="tableCoinList">
						<thead>
							<tr>
								<th><?php echo $langData['admin-page-msg-29']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-30']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-24']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-31']['KO'];?></th>
								<th><?php echo $langData['admin-ctrl-msg-22']['KO'];?>/<?php echo $langData['admin-ctrl-msg-21']['KO'];?></th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($coinData as $key => $value){
							?>
							<tr>
								<td style="text-align:center;"><img src="<?php echo base_url().$value['f_img'];?>"></td>
								<td><?php echo $value['f_unit'];?></td>
								<td><?php echo $value['f_title'];?></td>
								<td><?php echo $value['f_img'];?></td>
								<td style="text-align:center;">
									<select class="form-control" style="width:100px;" onchange="changeCoinStatus('<?php echo $value['f_id'];?>',this)">
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
