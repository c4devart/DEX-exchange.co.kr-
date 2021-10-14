	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo $langData['admin-page-msg-9']['KO'];?></h1>
			</div>
		</div>
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo $langData['admin-page-msg-135']['KO'];?>
				</div>
				<div class="panel-body" style="border-bottom:solid 1px #c7c7c7;">
					<span class="float-left" style="margin-right:10px;line-height:34px;"><?php echo $langData['admin-page-msg-63']['KO'];?></span>
					<select class="form-control float-left" style="width:100px;" id="userListSelectAll">
						<option></option>	
						<option value="1"><?php echo $langData['admin-page-msg-65']['KO'];?></option>
						<option value="0"><?php echo $langData['admin-page-msg-66']['KO'];?></option>
					</select>
					<select class="form-control float-right" style="width:150px;" id="changeCheckedUserStatus">
						<option value="0"></option>
						<option value="1"><?php echo $langData['admin-page-msg-136']['KO'];?></option>
						<option value="2"><?php echo $langData['admin-page-msg-137']['KO'];?></option>
						<option value="3"><?php echo $langData['admin-page-msg-138']['KO'];?></option>
						<option value="4"><?php echo $langData['admin-page-msg-140']['KO'];?></option>
						<option value="5"><?php echo $langData['admin-page-msg-139']['KO'];?></option>
						<option value="6"><?php echo $langData['admin-page-msg-139']['KO'];?><?php echo $langData['admin-page-msg-66']['KO'];?></option>
						<option value="7"><?php echo $langData['admin-page-msg-141']['KO'];?></option>
					</select>
					<span class="float-right" style="margin-right:10px;line-height:34px;"><?php echo $langData['admin-page-msg-64']['KO'];?></span>
				</div>
				<div class="panel-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="user_list">
						<thead>
							<tr>
								<th><?php echo $langData['admin-page-msg-65']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-35']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-142']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-44']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-143']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-144']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-145']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-139']['KO'];?></th>
								<th><?php echo $langData['admin-page-msg-146']['KO'];?></th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
