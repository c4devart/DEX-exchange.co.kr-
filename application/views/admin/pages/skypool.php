	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo $langData['admin-page-msg-6']['KO'];?></h1>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php echo $langData['admin-page-msg-108']['KO'];?>
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
						<table width="100%" class="table table-striped table-bordered table-hover" id="datatableSkyPoolHistory">
							<thead>
								<tr>
									<th><?php echo $langData['admin-page-msg-35']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-36']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-37']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-109']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-110']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-111']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-112']['KO'];?></th>
									<th><?php echo $langData['admin-page-msg-113']['KO'];?></th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
