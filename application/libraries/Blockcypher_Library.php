<?php
	
	use BlockCypher\Api\TX;
	use BlockCypher\Auth\SimpleTokenCredential;
	use BlockCypher\Rest\ApiContext;
	use BlockCypher\Client\TXClient;
	use BlockCypher\Client\AddressClient;
	use BlockCypher\Client\paymentForwardClient;
	
    class Blockcypher_Library
    {
        public function __construct()
        {
            log_message('Debug', 'Blockcypher class is loaded.');
        }

		public function getNewAddress($coin, $blockcypherToken)
        {
			require APPPATH . '../assets/plugin/blockcypher/php-client/autoload.php';
			$apiContext = ApiContext::create(
				'main', $coin, 'v1',
				new SimpleTokenCredential($blockcypherToken),
				array('log.LogEnabled' => true, 'log.FileName' => 'BlockCypher.log', 'log.LogLevel' => 'DEBUG')
			);
			$addressClient = new AddressClient($apiContext);
			$addressKeyChain = $addressClient->generateAddress();
			return $addressKeyChain;
		}

		public function signer($tosign, $privateKey)
        {
			require APPPATH . '../assets/plugin/blockcypher/php-client/autoload.php';
            try {
                $signature = BlockCypher\Crypto\Signer::sign($tosign, $privateKey);
            } catch (Exception $ex) {
                ResultPrinter::printError("Sign", "toSign", null, json_encode($tosign), $ex);
                exit(1);
            }
            return $signature;
		}

		public function deletePaymentForwardAddress($addressId)
		{
			require APPPATH . '../assets/plugin/blockcypher/php-client/autoload.php';
			$apiContext = ApiContext::create(
				'main',
				'btc',
				'v1',
				new SimpleTokenCredential('7c34777a04354e7ea5d02ddee36a9a91'),
				array('log.LogEnabled' => true, 'log.FileName' => 'BlockCypher.log', 'log.LogLevel' => 'DEBUG')
			);
			$ForwardClient = new paymentForwardClient($apiContext);
			$result = $ForwardClient->deleteForwardingAddress($addressId);
			return $result;
		}
		
        public function NewTransactionEndpoint($coin, $blockcypherToken, $from_address, $to_address, $amount, $privatekey)
        {
			require APPPATH . '../assets/plugin/blockcypher/php-client/autoload.php';
			if($coin == 'btc'){
				$apiContext = ApiContext::create(
					'main',
					$coin,
					'v1',
					new SimpleTokenCredential($blockcypherToken),
					array('log.LogEnabled' => true, 'log.FileName' => 'BlockCypher.log', 'log.LogLevel' => 'DEBUG')
				);
				$tx = new TX();
				$input = new \BlockCypher\Api\TXInput();
				$input->addAddress($from_address);
				$tx->addInput($input);
				$output = new \BlockCypher\Api\TXOutput();
				$output->addAddress($to_address);
				$tx->addOutput($output);
				$output->setValue($amount);

				$txClient = new TXClient($apiContext);
				$txSkeleton = $txClient->create($tx);
				$txSkeleton = $txClient->sign($txSkeleton, $privatekey);
				$txSkeleton = $txClient->send($txSkeleton);
				return $txSkeleton;
			}else if($coin == 'eth'){
				$url = "https://api.blockcypher.com/v1/eth/main/txs/new?token=" . $blockcypherToken;
				$ch = curl_init();
				$postData = '{"inputs":[{"addresses": ["' . $from_address . '"]}],"outputs":[{"addresses": ["' . $to_address . '"], "value": ' . $amount . '}]}';
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				try {
					$result = curl_exec($ch);
				} catch (Exception $ex) {
				}
				curl_close($ch);
				$post_data = json_decode($result);
				$tosign = $post_data->tosign[0];
				try {
					$signature = BlockCypher\Crypto\Signer::sign($tosign, $privatekey);
				} catch (Exception $ex) {
				}
				$post_data->signatures[0] = $signature;
				$post_data = json_encode($post_data);
				$ch = curl_init();
				$url = "https://api.blockcypher.com/v1/eth/main/txs/send?token=" . $blockcypherToken;
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				try {
					$result = curl_exec($ch);
				} catch (Exception $ex) {
				}
				curl_close($ch);
				$api_result = json_decode($result);
				return $api_result;
			}
        }
    }
?>
