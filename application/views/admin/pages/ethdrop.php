<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo $langData['admin-page-msg-7']['KO'];?></h1>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php echo $langData['admin-page-msg-42']['KO'];?>
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
						<table width="100%" class="table table-striped table-bordered table-hover" id="datatableEthDropHistory">
							<thead>
								<tr>
									<th><?php echo $langData['admin-page-msg-35']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-43']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-44']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-45']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-46']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-47']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-48']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-49']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-50']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-51']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-52']['KO'];?></th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
