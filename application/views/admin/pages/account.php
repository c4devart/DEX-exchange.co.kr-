<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Admin management</h1>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-heading" style="height:51px;line-height:34px;">
					Admin List					<button type="button" class="btn btn-default btn-outline float-right" data-toggle="modal" data-target="#exampleModal"> + Admin registration</button>
					<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document" style="width:400px;">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel" style="text-align:center;font-size:20px;font-weight:bold;">새 관리자 등록</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									Admin name
									<input type="text" class="form-control" id="username">
									Password
									<input type="password" class="form-control" id="password">
									Password confirm
									<input type="password" class="form-control" id="confirmPassword">
									Admin level
									<select class="form-control" style="padding:0px;" id="state">
										<option value="super">Super user</option>
										<option value="admin">Admin</option>
									</select>
									상태
									<select class="form-control" style="padding:0px;" id="status">
										<option value="super">active</option>
										<option value="admin">block</option>
									</select>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-primary" id="addNewAdmin">Registration</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $langData['admin-page-msg-28']['KO']; ?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<table width="100%" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th style="text-align:center;">Admin name</th>
								<th style="text-align:center;">Level</th>
								<th style="text-align:center;">Status</th>
								<th style="text-align:center;">Registration Date</th>
								<th style="text-align:center;"></th>
								<th style="text-align:center;"></th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($adminData as $key => $value){
							?>
								<tr>
									<td style="text-align:center;"><?php echo $value['f_username'];?></td>
									<td style="text-align:center;">
										<select class="btn btn-default btn-outline" style="padding:0px;width:45%;" onchange="changeStatus('<?php echo $value['f_id']; ?>', 'f_state', this)">
											<option value="super" <?php if ($value['f_state'] == 'super') {echo 'selected';}; ?>>Super user</option>
											<option value="admin" <?php if ($value['f_state'] == 'admin') {echo 'selected';}; ?>>Admin</option>
										</select>
									</td>
									<td style="text-align:center;">
										<select class="btn btn-default btn-outline" style="padding:0px;width:45%;" onchange="changeStatus('<?php echo $value['f_id']; ?>', 'f_blocked', this)">
											<option value="1" <?php if ($value['f_blocked'] == 1) {echo 'selected';}; ?>>Block</option>
											<option value="0" <?php if ($value['f_blocked'] == 0) {echo 'selected';}; ?>>Active</option>
										</select>
									</td>
									<td style="text-align:center;"><?php echo date('Y-m-d', $value['f_regdate']); ?></td>
									<td style="text-align:center;"><input type="button" value="reset password" onclick="resetPassword('<?php echo $value['f_id']; ?>', this)"></td>
									<td style="text-align:center;"><input type="button" value="remove" onclick="deleteAccount('<?php echo $value['f_id']; ?>',this)"></td>
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
