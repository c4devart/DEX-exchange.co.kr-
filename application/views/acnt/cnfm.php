	<div class="coinsky-page">
		<div class="coinsky-page-header">
			<span class="coinsky-page-header-title"><?php echo $langData['acnt-cnfm-msg-1'][$lang];?></span>
		</div>
		<div class="coinsky-page-content back-color-light">
			<div class="width-1400 panelShadowed">
				<ul class="nav nav-tabs panelShadowed basicBackground">
	                <li class="tab-basic<?php if($tab=='withdraw'){echo ' active';};?>" style="border-left:0px;"><a href="#withdraw" data-toggle="tab">출금계좌 등록요청</a></li>
	                <li class="tab-basic<?php if($tab=='account'){echo ' active';};?>"><a href="#account" data-toggle="tab"><?php echo $langData['acnt-cnfm-msg-3'][$lang];?></a></li>
	                <li class="tab-basic<?php if($tab=='close'){echo ' active';};?>"><a href="#close" data-toggle="tab"><?php echo $langData['acnt-cnfm-msg-4'][$lang];?></a></li>
	                <li class="tab-basic<?php if($tab=='owner'){echo ' active';};?>"><a href="#owner" data-toggle="tab"><?php echo $langData['acnt-cnfm-msg-5'][$lang];?></a></li>
	                <li class="tab-basic<?php if($tab=='ownerDeposit'){echo ' active';};?>"><a href="#ownerDeposit" data-toggle="tab"><?php echo $langData['acnt-cnfm-msg-6'][$lang];?></a></li>
	                <li class="tab-basic<?php if($tab=='otherDeposit'){echo ' active';};?>"><a href="#otherDeposit" data-toggle="tab"><?php echo $langData['acnt-cnfm-msg-7'][$lang];?></a></li>
	                <li class="tab-basic<?php if($tab=='otp'){echo ' active';};?>"><a href="#otp" data-toggle="tab"><?php echo $langData['acnt-cnfm-msg-8'][$lang];?></a></li>
	                <li class="tab-basic<?php if($tab=='unblock'){echo ' active';};?>"><a href="#unblock" data-toggle="tab"><?php echo $langData['acnt-cnfm-msg-9'][$lang];?></a></li>
	            </ul>
	            <div class="tab-content">
	                <div class="background-white tab-pane fade<?php if($tab== 'withdraw'){echo ' in active';};?>" id="withdraw">
	                    <div class="panel panel-default tab-content-body no-margin panelShadowed" style="background:none;">
	                        <div class="panel-body basicBackground tab-content-sub">
	                        	<!-- <div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-44'][$lang];?></div>
	                        		<div class="cnfm-text-content">
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-10'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-11'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-12'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-13'][$lang];?><br>
	                        		</div>
	                        	</div> -->
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-14'][$lang];?></div>
	                        		<div class="cnfm-sub-title">1. <?php echo $langData['acnt-cnfm-msg-15'][$lang];?></div>
	                        		<div class="row">
	                        			<div class="col-md-6">
	                        				<div class="identify-front-content"></div>
	                        			</div>
	                        			<div class="col-md-6">
	                        				<div class="cnfm-text-content">
			                        			- <?php echo $langData['acnt-cnfm-msg-16'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-17'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-18'][$lang];?>
			                        		</div>
	                        			</div>
	                        		</div>
	                        		<div class="cnfm-sub-title">2. <?php echo $langData['acnt-cnfm-msg-19'][$lang];?></div>
	                        		<div class="row">
	                        			<div class="col-md-6">
	                        				<div class="identify-back-content"></div>
	                        			</div>
	                        			<div class="col-md-6">
	                        				<div class="cnfm-text-content">
			                        			- <?php echo $langData['acnt-cnfm-msg-20'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-21'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-22'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-23'][$lang];?>
			                        		</div>
	                        			</div>
	                        		</div>
	                        		<!-- <div class="cnfm-sub-title">3. <?php echo $langData['acnt-cnfm-msg-24'][$lang];?></div>
                    				<div class="cnfm-text-content">
                    					<table class="cnfm-phone-template">
                    						<tr>
                    							<th>SK</th>
                    							<th>KT</th>
                    							<th>LGU</th>
                    						</tr>
                    						<tr>
                    							<td><?php echo $langData['acnt-cnfm-msg-25'][$lang];?></td>
                    							<td><?php echo $langData['acnt-cnfm-msg-26'][$lang];?></td>
                    							<td><?php echo $langData['acnt-cnfm-msg-27'][$lang];?></td>
                    						</tr>
                    					</table>
	                        			- <?php echo $langData['acnt-cnfm-msg-28'][$lang];?>
	                        		</div> -->
	                        	</div>
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-29'][$lang];?></div>
	                        		<div class="cnfm-text-content">
	                        			<table class="cnfm-email-template">
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-30'][$lang];?></th>
	                        					<td>출금계좌 등록요청</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-32'][$lang];?></th>
	                        					<td>cs@coinsky.co.kr</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-33'][$lang];?></th>
	                        					<td>
	                        						<?php echo $langData['acnt-cnfm-msg-34'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-35'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-36'][$lang];?> :
	                        					</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-43'][$lang];?></th>
	                        					<td>
	                        						1. <?php echo $langData['acnt-cnfm-msg-37'][$lang];?><br>
	                        						2. <?php echo $langData['acnt-cnfm-msg-38'][$lang];?><br>
	                        						3. 출금계좌 통장사본 (필수)<br>
	                        						<span class="span-background-blue span-light" style="padding: 5px;"><?php echo $langData['acnt-cnfm-msg-40'][$lang];?></span>
	                        					</td>
	                        				</tr>
	                        			</table>
	                        			※ <?php echo $langData['acnt-cnfm-msg-41'][$lang];?><br>
	                        			※ <?php echo $langData['acnt-cnfm-msg-42'][$lang];?>
	                        		</div>
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	                <div class="background-white tab-pane fade<?php if($tab=='account'){echo ' in active';};?>" id="account">
	                    <div class="panel panel-default tab-content-body no-margin panelShadowed" style="background:none;">
	                        <div class="panel-body basicBackground tab-content-sub">
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-44'][$lang];?></div>
	                        		<div class="cnfm-text-content">
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-45'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-46'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-47'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-13'][$lang];?><br>
	                        		</div>
	                        	</div>
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-14'][$lang];?></div>
	                        		<div class="cnfm-sub-title">1. <?php echo $langData['acnt-cnfm-msg-15'][$lang];?></div>
	                        		<div class="row">
	                        			<div class="col-md-6">
	                        				<div class="identify-front-content"></div>
	                        			</div>
	                        			<div class="col-md-6">
	                        				<div class="cnfm-text-content">
			                        			- <?php echo $langData['acnt-cnfm-msg-16'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-17'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-18'][$lang];?>
			                        		</div>
	                        			</div>
	                        		</div>
	                        		<div class="cnfm-sub-title">2. <?php echo $langData['acnt-cnfm-msg-19'][$lang];?></div>
	                        		<div class="row">
	                        			<div class="col-md-6">
	                        				<div class="identify-back-content"></div>
	                        			</div>
	                        			<div class="col-md-6">
	                        				<div class="cnfm-text-content">
			                        			- <?php echo $langData['acnt-cnfm-msg-16'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-21'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-22'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-23'][$lang];?>
			                        		</div>
	                        			</div>
	                        		</div>
	                        		<div class="cnfm-sub-title">3. <?php echo $langData['acnt-cnfm-msg-48'][$lang];?></div>
                    				<div class="cnfm-text-content">
	                        			- <?php echo $langData['acnt-cnfm-msg-49'][$lang];?><br>
	                        			- <?php echo $langData['acnt-cnfm-msg-50'][$lang];?>
	                        		</div>
	                        	</div>
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-29'][$lang];?></div>
	                        		<div class="cnfm-text-content">
	                        			<table class="cnfm-email-template">
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-30'][$lang];?></th>
	                        					<td><?php echo $langData['acnt-cnfm-msg-31'][$lang];?></td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-32'][$lang];?></th>
	                        					<td>cs@coinsky.co.kr</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-33'][$lang];?></th>
	                        					<td>
	                        						<?php echo $langData['acnt-cnfm-msg-34'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-35'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-36'][$lang];?> :
	                        					</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-43'][$lang];?></th>
	                        					<td>
	                        						1. <?php echo $langData['acnt-cnfm-msg-19'][$lang];?> <?php echo $langData['acnt-cnfm-msg-60'][$lang];?><br>
	                        						2. <?php echo $langData['acnt-cnfm-msg-38'][$lang];?><br>
	                        						3. <?php echo $langData['acnt-cnfm-msg-51'][$lang];?><br>
	                        						<span class="span-background-blue span-light" style="padding: 5px;"><?php echo $langData['acnt-cnfm-msg-40'][$lang];?></span>
	                        					</td>
	                        				</tr>
	                        			</table>
	                        			※ <?php echo $langData['acnt-cnfm-msg-41'][$lang];?><br>
	                        			※ <?php echo $langData['acnt-cnfm-msg-42'][$lang];?>
	                        		</div>
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	                <div class="background-white tab-pane fade<?php if($tab=='close'){echo ' in active';};?>" id="close">
	                    <div class="panel panel-default tab-content-body no-margin panelShadowed" style="background:none;">
	                        <div class="panel-body basicBackground tab-content-sub">
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-52'][$lang];?></div>
	                        		<div class="cnfm-text-content">
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-53'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-54'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-55'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-13'][$lang];?><br>
	                        		</div>
	                        	</div>
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-14'][$lang];?></div>
	                        		<div class="cnfm-sub-title">1. <?php echo $langData['acnt-cnfm-msg-15'][$lang];?></div>
	                        		<div class="row">
	                        			<div class="col-md-6">
	                        				<div class="identify-front-content"></div>
	                        			</div>
	                        			<div class="col-md-6">
	                        				<div class="cnfm-text-content">
			                        			- <?php echo $langData['acnt-cnfm-msg-16'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-17'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-18'][$lang];?>
			                        		</div>
	                        			</div>
	                        		</div>
	                        		<div class="cnfm-sub-title">2. <?php echo $langData['acnt-cnfm-msg-19'][$lang];?></div>
	                        		<div class="row">
	                        			<div class="col-md-6">
	                        				<div class="identify-back-content"></div>
	                        			</div>
	                        			<div class="col-md-6">
	                        				<div class="cnfm-text-content">
			                        			- <?php echo $langData['acnt-cnfm-msg-16'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-21'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-22'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-23'][$lang];?>
			                        		</div>
	                        			</div>
	                        		</div>
	                        	</div>
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-29'][$lang];?></div>
	                        		<div class="cnfm-text-content">
	                        			<table class="cnfm-email-template">
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-30'][$lang];?></th>
	                        					<td><?php echo $langData['acnt-cnfm-msg-56'][$lang];?></td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-32'][$lang];?></th>
	                        					<td>cs@coinsky.co.kr</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-33'][$lang];?></th>
	                        					<td>
	                        						<?php echo $langData['acnt-cnfm-msg-34'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-35'][$lang];?> :<br>
	                        						탈퇴 사유 :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-36'][$lang];?> :
	                        					</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-43'][$lang];?></th>
	                        					<td>
	                        						1. <?php echo $langData['acnt-cnfm-msg-19'][$lang];?> <?php echo $langData['acnt-cnfm-msg-60'][$lang];?><br>
	                        						2. <?php echo $langData['acnt-cnfm-msg-38'][$lang];?><br>
	                        						<span class="span-background-blue span-light" style="padding: 5px;"><?php echo $langData['acnt-cnfm-msg-40'][$lang];?></span>
	                        					</td>
	                        				</tr>
	                        			</table>
	                        			※ <?php echo $langData['acnt-cnfm-msg-41'][$lang];?><br>
	                        			※ <?php echo $langData['acnt-cnfm-msg-42'][$lang];?>
	                        		</div>
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	                <div class="background-white tab-pane fade<?php if($tab=='owner'){echo ' in active';};?>" id="owner">
	                    <div class="panel panel-default tab-content-body no-margin panelShadowed" style="background:none;">
	                        <div class="panel-body basicBackground tab-content-sub">
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-57'][$lang];?></div>
	                        		<div class="cnfm-text-content">
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-58'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-59'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-13'][$lang];?><br>
	                        		</div>
	                        	</div>
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-14'][$lang];?></div>
	                        		<div class="cnfm-sub-title">1. <?php echo $langData['acnt-cnfm-msg-15'][$lang];?></div>
	                        		<div class="row">
	                        			<div class="col-md-6">
	                        				<div class="identify-front-content"></div>
	                        			</div>
	                        			<div class="col-md-6">
	                        				<div class="cnfm-text-content">
			                        			- <?php echo $langData['acnt-cnfm-msg-16'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-17'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-18'][$lang];?>
			                        		</div>
	                        			</div>
	                        		</div>
	                        		<div class="cnfm-sub-title">2. <?php echo $langData['acnt-cnfm-msg-19'][$lang];?></div>
	                        		<div class="row">
	                        			<div class="col-md-6">
	                        				<div class="identify-back-content"></div>
	                        			</div>
	                        			<div class="col-md-6">
	                        				<div class="cnfm-text-content">
			                        			- <?php echo $langData['acnt-cnfm-msg-16'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-21'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-22'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-23'][$lang];?>
			                        		</div>
	                        			</div>
	                        		</div>
	                        		<div class="cnfm-sub-title">3. <?php echo $langData['acnt-cnfm-msg-63'][$lang];?></div>
                    				<div class="cnfm-text-content">
	                        			- <?php echo $langData['acnt-cnfm-msg-61'][$lang];?><br>
	                        			- <?php echo $langData['acnt-cnfm-msg-62'][$lang];?>
	                        		</div>
	                        	</div>
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-29'][$lang];?></div>
	                        		<div class="cnfm-text-content">
	                        			<table class="cnfm-email-template">
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-30'][$lang];?></th>
	                        					<td><?php echo $langData['acnt-cnfm-msg-76'][$lang];?></td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-32'][$lang];?></th>
	                        					<td>cs@coinsky.co.kr</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-33'][$lang];?></th>
	                        					<td>
	                        						<?php echo $langData['acnt-cnfm-msg-34'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-35'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-75'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-69'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-36'][$lang];?> :
	                        					</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-43'][$lang];?></th>
	                        					<td>
	                        						1. <?php echo $langData['acnt-cnfm-msg-19'][$lang];?> <?php echo $langData['acnt-cnfm-msg-60'][$lang];?><br>
	                        						2. <?php echo $langData['acnt-cnfm-msg-38'][$lang];?><br>
	                        						3. <?php echo $langData['acnt-cnfm-msg-63'][$lang];?> <?php echo $langData['acnt-cnfm-msg-60'][$lang];?><br>
	                        						<span class="span-background-blue span-light" style="padding: 5px;"><?php echo $langData['acnt-cnfm-msg-40'][$lang];?></span>
	                        					</td>
	                        				</tr>
	                        			</table>
	                        			※ <?php echo $langData['acnt-cnfm-msg-41'][$lang];?><br>
	                        			※ <?php echo $langData['acnt-cnfm-msg-42'][$lang];?>
	                        		</div>
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	                <div class="background-white tab-pane fade<?php if($tab=='ownerDeposit'){echo ' in active';};?>" id="ownerDeposit">
	                    <div class="panel panel-default tab-content-body no-margin panelShadowed" style="background:none;">
	                        <div class="panel-body basicBackground tab-content-sub">
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-87'][$lang];?></div>
	                        		<div class="cnfm-text-content">
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-89'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-90'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-91'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-92'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-13'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-93'][$lang];?><br>
	                        		</div>
	                        	</div>
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-14'][$lang];?></div>
	                        		<div class="cnfm-sub-title">1. <?php echo $langData['acnt-cnfm-msg-15'][$lang];?></div>
	                        		<div class="row">
	                        			<div class="col-md-6">
	                        				<div class="identify-front-content"></div>
	                        			</div>
	                        			<div class="col-md-6">
	                        				<div class="cnfm-text-content">
			                        			- <?php echo $langData['acnt-cnfm-msg-16'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-17'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-18'][$lang];?>
			                        		</div>
	                        			</div>
	                        		</div>
	                        		<div class="cnfm-sub-title">2. <?php echo $langData['acnt-cnfm-msg-19'][$lang];?></div>
	                        		<div class="row">
	                        			<div class="col-md-6">
	                        				<div class="identify-back-content"></div>
	                        			</div>
	                        			<div class="col-md-6">
	                        				<div class="cnfm-text-content">
			                        			- <?php echo $langData['acnt-cnfm-msg-16'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-21'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-22'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-23'][$lang];?>
			                        		</div>
	                        			</div>
	                        		</div>
	                        		<div class="cnfm-sub-title">3. <?php echo $langData['acnt-cnfm-msg-63'][$lang];?></div>
                    				<div class="cnfm-text-content">
	                        			- <?php echo $langData['acnt-cnfm-msg-61'][$lang];?><br>
	                        			- <?php echo $langData['acnt-cnfm-msg-62'][$lang];?>
	                        		</div>
	                        	</div>
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-29'][$lang];?></div>
	                        		<div class="cnfm-text-content">
	                        			<table class="cnfm-email-template">
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-30'][$lang];?></th>
	                        					<td><?php echo $langData['acnt-cnfm-msg-88'][$lang];?></td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-32'][$lang];?></th>
	                        					<td>cs@coinsky.co.kr</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-33'][$lang];?></th>
	                        					<td>
	                        						<?php echo $langData['acnt-cnfm-msg-34'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-35'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-74'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-77'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-69'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-36'][$lang];?> :
	                        					</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-43'][$lang];?></th>
	                        					<td>
	                        						1. <?php echo $langData['acnt-cnfm-msg-19'][$lang];?> <?php echo $langData['acnt-cnfm-msg-60'][$lang];?><br>
	                        						2. <?php echo $langData['acnt-cnfm-msg-38'][$lang];?><br>
	                        						3. <?php echo $langData['acnt-cnfm-msg-63'][$lang];?> <?php echo $langData['acnt-cnfm-msg-60'][$lang];?><br>
	                        						<span class="span-background-blue span-light" style="padding: 5px;"><?php echo $langData['acnt-cnfm-msg-40'][$lang];?></span>
	                        					</td>
	                        				</tr>
	                        			</table>
	                        			※ <?php echo $langData['acnt-cnfm-msg-41'][$lang];?><br>
	                        			※ <?php echo $langData['acnt-cnfm-msg-42'][$lang];?>
	                        		</div>
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	                <div class="background-white tab-pane fade<?php if($tab=='otherDeposit'){echo ' in active';};?>" id="otherDeposit">
	                    <div class="panel panel-default tab-content-body no-margin panelShadowed" style="background:none;">
	                        <div class="panel-body basicBackground tab-content-sub">
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-87'][$lang];?></div>
	                        		<div class="cnfm-text-content">
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-86'][$lang];?><br>
	                        			&nbsp;&nbsp;&nbsp;* <?php echo $langData['acnt-cnfm-msg-85'][$lang];?><br>
	                        			&nbsp;&nbsp;&nbsp;* <?php echo $langData['acnt-cnfm-msg-84'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-13'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-83'][$lang];?><br>
	                        		</div>
	                        	</div>
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-14'][$lang];?></div>
	                        		<div class="cnfm-sub-title">1. <?php echo $langData['acnt-cnfm-msg-15'][$lang];?></div>
	                        		<div class="row">
	                        			<div class="col-md-6">
	                        				<div class="identify-front-content"></div>
	                        			</div>
	                        			<div class="col-md-6">
	                        				<div class="cnfm-text-content">
			                        			- <?php echo $langData['acnt-cnfm-msg-16'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-17'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-18'][$lang];?>
			                        		</div>
	                        			</div>
	                        		</div>
	                        		<div class="cnfm-sub-title">2. <?php echo $langData['acnt-cnfm-msg-19'][$lang];?></div>
	                        		<div class="row">
	                        			<div class="col-md-6">
	                        				<div class="identify-back-content"></div>
	                        			</div>
	                        			<div class="col-md-6">
	                        				<div class="cnfm-text-content">
			                        			- <?php echo $langData['acnt-cnfm-msg-16'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-21'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-22'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-23'][$lang];?>
			                        		</div>
	                        			</div>
	                        		</div>
	                        		<div class="cnfm-sub-title">3. <?php echo $langData['acnt-cnfm-msg-82'][$lang];?></div>
                    				<div class="cnfm-text-content">
	                        			- <?php echo $langData['acnt-cnfm-msg-81'][$lang];?><br>
	                        			- <?php echo $langData['acnt-cnfm-msg-80'][$lang];?><br>
	                        			- <?php echo $langData['acnt-cnfm-msg-79'][$lang];?>
	                        		</div>
	                        	</div>
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-29'][$lang];?></div>
	                        		<div class="cnfm-text-content">
	                        			<table class="cnfm-email-template">
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-30'][$lang];?></th>
	                        					<td><?php echo $langData['acnt-cnfm-msg-78'][$lang];?></td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-32'][$lang];?></th>
	                        					<td>cs@coinsky.co.kr</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-33'][$lang];?></th>
	                        					<td>
	                        						<?php echo $langData['acnt-cnfm-msg-34'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-35'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-74'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-77'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-69'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-36'][$lang];?> :
	                        					</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-43'][$lang];?></th>
	                        					<td>
	                        						1. <?php echo $langData['acnt-cnfm-msg-19'][$lang];?> <?php echo $langData['acnt-cnfm-msg-60'][$lang];?><br>
	                        						2. <?php echo $langData['acnt-cnfm-msg-38'][$lang];?><br>
	                        						3. <?php echo $langData['acnt-cnfm-msg-73'][$lang];?> : <?php echo $langData['acnt-cnfm-msg-60'][$lang];?><br>
	                        						<span class="span-background-blue span-light" style="padding: 5px;"><?php echo $langData['acnt-cnfm-msg-40'][$lang];?></span>
	                        					</td>
	                        				</tr>
	                        			</table>
	                        			※ <?php echo $langData['acnt-cnfm-msg-41'][$lang];?><br>
	                        			※ <?php echo $langData['acnt-cnfm-msg-42'][$lang];?>
	                        		</div>
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	                <div class="background-white tab-pane fade<?php if($tab=='otp'){echo ' in active';};?>" id="otp">
	                    <div class="panel panel-default tab-content-body no-margin panelShadowed" style="background:none;">
	                        <div class="panel-body basicBackground tab-content-sub">
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-72'][$lang];?> :</div>
	                        		<div class="cnfm-text-content">
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-70'][$lang];?> :<br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-13'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-71'][$lang];?> :<br>
	                        		</div>
	                        	</div>
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-14'][$lang];?></div>
	                        		<div class="cnfm-sub-title">1. <?php echo $langData['acnt-cnfm-msg-15'][$lang];?></div>
	                        		<div class="row">
	                        			<div class="col-md-6">
	                        				<div class="identify-front-content"></div>
	                        			</div>
	                        			<div class="col-md-6">
	                        				<div class="cnfm-text-content">
			                        			- <?php echo $langData['acnt-cnfm-msg-16'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-17'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-18'][$lang];?>
			                        		</div>
	                        			</div>
	                        		</div>
	                        		<div class="cnfm-sub-title">2. <?php echo $langData['acnt-cnfm-msg-19'][$lang];?></div>
	                        		<div class="row">
	                        			<div class="col-md-6">
	                        				<div class="identify-back-content"></div>
	                        			</div>
	                        			<div class="col-md-6">
	                        				<div class="cnfm-text-content">
			                        			- <?php echo $langData['acnt-cnfm-msg-16'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-21'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-22'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-23'][$lang];?>
			                        		</div>
	                        			</div>
	                        		</div>
	                        	</div>
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-29'][$lang];?></div>
	                        		<div class="cnfm-text-content">
	                        			<table class="cnfm-email-template">
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-30'][$lang];?></th>
	                        					<td><?php echo $langData['acnt-cnfm-msg-68'][$lang];?></td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-32'][$lang];?></th>
	                        					<td>cs@coinsky.co.kr</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-33'][$lang];?></th>
	                        					<td>
	                        						<?php echo $langData['acnt-cnfm-msg-34'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-35'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-69'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-36'][$lang];?> :
	                        					</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-43'][$lang];?></th>
	                        					<td>
	                        						1. <?php echo $langData['acnt-cnfm-msg-19'][$lang];?> <?php echo $langData['acnt-cnfm-msg-60'][$lang];?><br>
	                        						2. <?php echo $langData['acnt-cnfm-msg-38'][$lang];?><br>
	                        						<span class="span-background-blue span-light" style="padding: 5px;"><?php echo $langData['acnt-cnfm-msg-40'][$lang];?></span>
	                        					</td>
	                        				</tr>
	                        			</table>
	                        			※ <?php echo $langData['acnt-cnfm-msg-41'][$lang];?><br>
	                        			※ <?php echo $langData['acnt-cnfm-msg-42'][$lang];?>
	                        		</div>
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	                <div class="background-white tab-pane fade<?php if($tab=='unblock'){echo ' in active';};?>" id="unblock">
	                    <div class="panel panel-default tab-content-body no-margin panelShadowed" style="background:none;">
	                        <div class="panel-body basicBackground tab-content-sub">
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-67'][$lang];?></div>
	                        		<div class="cnfm-text-content">
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-66'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-13'][$lang];?><br>
	                        			&#183; <?php echo $langData['acnt-cnfm-msg-65'][$lang];?><br>
	                        		</div>
	                        	</div>
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-14'][$lang];?></div>
	                        		<div class="cnfm-sub-title">1. <?php echo $langData['acnt-cnfm-msg-15'][$lang];?></div>
	                        		<div class="row">
	                        			<div class="col-md-6">
	                        				<div class="identify-front-content"></div>
	                        			</div>
	                        			<div class="col-md-6">
	                        				<div class="cnfm-text-content">
			                        			- <?php echo $langData['acnt-cnfm-msg-16'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-17'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-18'][$lang];?>
			                        		</div>
	                        			</div>
	                        		</div>
	                        		<div class="cnfm-sub-title">2. <?php echo $langData['acnt-cnfm-msg-19'][$lang];?></div>
	                        		<div class="row">
	                        			<div class="col-md-6">
	                        				<div class="identify-back-content"></div>
	                        			</div>
	                        			<div class="col-md-6">
	                        				<div class="cnfm-text-content">
			                        			- <?php echo $langData['acnt-cnfm-msg-16'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-21'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-22'][$lang];?><br>
			                        			- <?php echo $langData['acnt-cnfm-msg-23'][$lang];?>
			                        		</div>
	                        			</div>
	                        		</div>
	                        	</div>
	                        	<div class="cnfm-description">
	                        		<div class="cnfm-title"><?php echo $langData['acnt-cnfm-msg-29'][$lang];?></div>
	                        		<div class="cnfm-text-content">
	                        			<table class="cnfm-email-template">
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-30'][$lang];?></th>
	                        					<td><?php echo $langData['acnt-cnfm-msg-64'][$lang];?></td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-32'][$lang];?></th>
	                        					<td>cs@coinsky.co.kr</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-33'][$lang];?></th>
	                        					<td>
	                        						<?php echo $langData['acnt-cnfm-msg-34'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-35'][$lang];?> :<br>
	                        						<?php echo $langData['acnt-cnfm-msg-36'][$lang];?> :
	                        					</td>
	                        				</tr>
	                        				<tr>
	                        					<th><?php echo $langData['acnt-cnfm-msg-43'][$lang];?></th>
	                        					<td>
	                        						1. <?php echo $langData['acnt-cnfm-msg-19'][$lang];?> <?php echo $langData['acnt-cnfm-msg-60'][$lang];?><br>
	                        						2. <?php echo $langData['acnt-cnfm-msg-38'][$lang];?><br>
	                        						<span class="span-background-blue span-light" style="padding: 5px;"><?php echo $langData['acnt-cnfm-msg-40'][$lang];?></span>
	                        					</td>
	                        				</tr>
	                        			</table>
	                        			※ <?php echo $langData['acnt-cnfm-msg-41'][$lang];?><br>
	                        			※ <?php echo $langData['acnt-cnfm-msg-42'][$lang];?>
	                        		</div>
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	            </div>
			</div>
		</div>
	</div>
</div>
