            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"><?php echo $langData['admin-page-msg-97']['KO'];?></h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <?php echo $langData['admin-page-msg-99']['KO'];?>
                            </div>
                            <div class="panel-body">
                                <div class="form-group" id="submit_error_alert_content">
                                    <div class="alert alert-danger" id="submit_error_alert"></div>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><?php echo $langData['admin-page-msg-100']['KO'];?></span>
                                    <input type="text" class="form-control" id="username" value="<?php echo $admin['f_username'];?>" readonly>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><?php echo $langData['admin-page-msg-101']['KO'];?></span>
                                    <input type="password" class="form-control" id="password">
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><?php echo $langData['admin-page-msg-102']['KO'];?></span>
                                    <input type="password" class="form-control" id="new_password">
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><?php echo $langData['admin-page-msg-103']['KO'];?></span>
                                    <input type="password" class="form-control" id="confirm_password">
                                </div>
                                <button class="btn btn-primary" id="submit_change"><?php echo $langData['admin-page-msg-98']['KO'];?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
