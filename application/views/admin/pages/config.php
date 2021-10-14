<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/css/config.css">        
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $langData['admin-page-msg-19']['KO'];?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php foreach ($config as $key => $value) {
                            ?>
                            <div class="form-group input-group">
								<?php
									if ($key == 'ETH_drop') {
								?>
                                <span class="input-group-addon">이더리움 배당</span>
								<?php
									}else if($key == 'SKY_pool'){
								?>
                                <span class="input-group-addon">스카이코인 채굴</span>
								<?php
									}else{
								?>
                                <span class="input-group-addon"><?php echo $key ?></span>
								<?php
									}
								?>
								<?php
									if($key== 'ETH_drop' || $key == 'SKY_pool'){
								?>
								<select  class="form-control" id="<?php echo $key; ?>">
									<option value="1" <?php if($value==1){echo "selected";};?>>활성</option>
									<option value="0" <?php if ($value == 0) {echo "selected";}; ?>>비활성</option>
								</select>
								<?php
									}else{
								?>
                                <input type="text" class="form-control" id="<?php echo $key; ?>" value="<?php echo $value; ?>">
								<?php
								
									}
								?>
                            </div>
                            <?php
                            } ?>
                            <button class="btn btn-primary" id="submit_change">저장</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/config.js"></script>
