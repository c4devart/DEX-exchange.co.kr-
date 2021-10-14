<?php

	use Twilio\Rest\Client;

    class Twilio_Library
    {
        public function __construct()
        {
            log_message('Debug', 'Twilio class is loaded.');
        }

		public function sendVerifyCode($sid, $token, $to, $from, $msg)
		{
			require_once(APPPATH . '../assets/plugin/twilio/Twilio/autoload.php');
			$client = new Client($sid, $token);
			try {
				$result = $client->messages->create(
					$to,
					array(
						'from' => $from,
						'body' => $msg
					)
				);
				return $result;
			} catch (Exception $e) {
				return $e;
			}
        }
    }
?>
