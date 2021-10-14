<?php
    class Phpmailer_Library
    {
        public function __construct()
        {
            log_message('Debug', 'PHPMailer class is loaded.');
        }

        public function load()
        {
            require_once(APPPATH.'../assets/plugin/PHPMailer/class.phpmailer.php');
            $objMail = new PHPMailer;
            return $objMail;
        }
    }
?>
