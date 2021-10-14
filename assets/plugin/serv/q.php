<?php

	$db_server_name = "localhost";
	$db_username = "root";
	$db_password = "mirai2018";
	$dbname = "trade_coinsky_c";
	$conn = new mysqli($db_server_name, $db_username, $db_password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	function get_my_balance($conn, $token)
	{
		$query = "SELECT * FROM tb_user_wallet WHERE f_token = '" . $token . "'";
		$count = 0;
		$result = $conn->query($query);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$return_data[$count]['unit'] = $row['f_unit'];
				$return_data[$count]['total'] = $row['f_total'];
				$return_data[$count]['available'] = $row['f_available'];
				$return_data[$count]['blocked'] = $row['f_blocked'];
				$return_data[$count]['buyBVolume'] = $row['f_buy_base_volume'];
				$count++;
			}
		} else {
			$return_data = [];
		}
		return $return_data;
	}

	function get_my_open_orders($conn, $token, $target, $base)
	{
		$query = "SELECT * FROM tb_market_order WHERE `f_token`='" . $token . "' && `f_base`='" . $base . "' && `f_target`='" . $target . "' ORDER BY f_regdate DESC, f_id DESC LIMIT 0, 50";
		$result = $conn->query($query);
		$count = 0;
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$return_data[$count]['id'] = $row['f_id'];
				$return_data[$count]['target'] = $target;
				$return_data[$count]['base'] = $base;
				$return_data[$count]['type'] = $row['f_type'];
				$return_data[$count]['rate'] = $row['f_rate'];
				$return_data[$count]['originalTargetVolume'] = number_format($row['f_original_target_volume'], 3, '.', ',');
				$return_data[$count]['targetVolume'] = number_format($row['f_target_volume'], 3, '.', ',');
				$return_data[$count]['regdate'] = date('Y-m-d H:i:s', $row['f_regdate']);
				$count++;
			}
		} else {
			$return_data[$count]['id'] = 'NAN';
			$return_data[$count]['target'] = $target;
			$return_data[$count]['base'] = $base;
			$return_data[$count]['type'] = '';
			$return_data[$count]['rate'] = '';
			$return_data[$count]['originalTargetVolume'] = '';
			$return_data[$count]['targetVolume'] = '';
			$return_data[$count]['regdate'] = '';
		}
		return $return_data;
	}

	function get_order_book($conn, $target, $base){

		$token_list = [];

		$sql = "SELECT f_open FROM tb_market WHERE f_target='" . $target . "' && f_base='" . $base . "'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$result_data = $row;
			}
		}
		if (isset($result_data['f_rate'])) {
			$open = $result_data['f_open'];
		} else {
			$open = 0;
		}
		$sql = "SELECT f_rate, SUM(f_target_volume) AS target_volume, GROUP_CONCAT(f_token SEPARATOR ' ') AS token_string FROM tb_market_order WHERE f_type='sell' && f_target='" . $target . "' && f_base='" . $base . "' GROUP BY f_rate ORDER BY f_rate ASC limit 0, 13";
		$result = $conn->query($sql);
		$order_data_count = 0;
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$order_data[$order_data_count] = $row;
				$order_data_count++;

				$sell_token_string = $row['token_string'];
				$exploded_token_string = explode(' ', $sell_token_string);
				foreach ($exploded_token_string as $token) {
					if (!in_array($token, $token_list) && $token != '') {
						array_push($token_list, $token);
					}
				}
			}
		}
		if ($order_data_count > 0) {
			if ($order_data_count < 13) {
				for ($i = 1; $i <= 13 - $order_data_count; $i++) {
					$sell_order[$i]['rate'] = '';
					$sell_order[$i]['targetVolume'] = '';
				}
			}
			for ($i = 13 - $order_data_count + 1; $i <= 13; $i++) {
				$sell_order[$i]['rate'] = $order_data[13 - $i]['f_rate'];
				$sell_order[$i]['targetVolume'] = $order_data[13 - $i]['target_volume'];
			}
		} else {
			for ($i = 1; $i <= 13; $i++) {
				$sell_order[$i]['rate'] = '';
				$sell_order[$i]['targetVolume'] = '';
			}
		}
		$order_data_count = 0;
		$sql = "SELECT f_rate, SUM(f_target_volume) AS target_volume, GROUP_CONCAT(f_token SEPARATOR ' ') AS token_string FROM tb_market_order WHERE f_type='buy' && f_target='" . $target . "' && f_base='" . $base . "' GROUP BY f_rate ORDER BY f_rate DESC limit 0, 13";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$order_data[$order_data_count] = $row;
				$order_data_count++;

				$buy_token_string = $row['token_string'];
				$exploded_token_string = explode(' ', $buy_token_string);
				foreach ($exploded_token_string as $token) {
					if (!in_array($token, $token_list) && $token != '') {
						array_push($token_list, $token);
					}
				}
			}
		}
		if ($order_data_count > 0) {
			for ($i = 1; $i <= $order_data_count; $i++) {
				$buy_order[$i]['rate'] = $order_data[$i - 1]['f_rate'];
				$buy_order[$i]['targetVolume'] = $order_data[$i - 1]['target_volume'];
			}
			if ($order_data_count < 13) {
				for ($i = $order_data_count + 1; $i <= 13; $i++) {
					$buy_order[$i]['rate'] = '';
					$buy_order[$i]['targetVolume'] = '';
				}
			}
		} else {
			for ($i = 1; $i <= 13; $i++) {
				$buy_order[$i]['rate'] = '';
				$buy_order[$i]['targetVolume'] = '';
			}
		}
		$token_volume = [];
		$token_volume_status = false;
		foreach($token_list as $token){

			$token_volume_count = 0;

			foreach ($buy_order as $key => $order) {
				if ($order['rate'] == '') {
					$token_volume[$token][$token_volume_count]['type'] = 'buy';
					$token_volume[$token][$token_volume_count]['target'] = $target;
					$token_volume[$token][$token_volume_count]['base'] = $base;
					$token_volume[$token][$token_volume_count]['no'] = $key;
					$token_volume[$token][$token_volume_count]['myTargetVolume'] = '';
				} else {
					$query = "SELECT SUM(f_target_volume) AS my_target_volume FROM tb_market_order WHERE f_token='".$token."' && f_type='buy' && f_target='".$target."' && f_base='".$base."' && f_rate=".$order['rate'];
					$result = $conn->query($query);
					if ($result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
							$my_target_volume = $row['my_target_volume'];
							if ($my_target_volume == null) {
								$my_target_volume = '';
							}
						}
					}
					$token_volume[$token][$token_volume_count]['type'] = 'buy';
					$token_volume[$token][$token_volume_count]['target'] = $target;
					$token_volume[$token][$token_volume_count]['base'] = $base;
					$token_volume[$token][$token_volume_count]['no'] = $key;
					$token_volume[$token][$token_volume_count]['myTargetVolume'] = $my_target_volume;
				}
				$token_volume_count++;
			}
			
			foreach ($sell_order as $key => $order) {
				if ($order['rate'] == '') {
					$token_volume[$token][$token_volume_count]['type'] = 'sell';
					$token_volume[$token][$token_volume_count]['target'] = $target;
					$token_volume[$token][$token_volume_count]['base'] = $base;
					$token_volume[$token][$token_volume_count]['no'] = $key;
					$token_volume[$token][$token_volume_count]['myTargetVolume'] = '';
				} else {
					$query = "SELECT SUM(f_target_volume) AS my_target_volume FROM tb_market_order WHERE f_token='" . $token . "' && f_type='sell' && f_target='" . $target . "' && f_base='" . $base . "' && f_rate=" . $order['rate'];
					$result = $conn->query($query);
					if ($result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
							$my_target_volume = $row['my_target_volume'];
							if ($my_target_volume == null) {
								$my_target_volume = '';
							}
						}
					}
					$token_volume[$token][$token_volume_count]['type'] = 'sell';
					$token_volume[$token][$token_volume_count]['target'] = $target;
					$token_volume[$token][$token_volume_count]['base'] = $base;
					$token_volume[$token][$token_volume_count]['no'] = $key;
					$token_volume[$token][$token_volume_count]['myTargetVolume'] = $my_target_volume;
				}
				$token_volume_count++;
			}
			$token_volume_status = true;
		}
		$order_book['buy_order'] = $buy_order;
		$order_book['sell_order'] = $sell_order;
		$order_book['token_volume'] = $token_volume;
		$order_book['token_volume_status'] = $token_volume_status;
		return $order_book;
	}

	function send_curl_request($url, $post = 0, $post_data = false)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, $post);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		if ($post == 1) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = json_decode(curl_exec($ch));
		curl_close($ch);
		return $result;
	}

	function get_current_date_timestamp()
	{
		$current_date = time();
		$current_date = $current_date - $current_date % 86400;
		return $current_date;
	}

	function get_market_summaries($conn)
	{
		$count = 0;
		$query = "SELECT * FROM tb_market WHERE f_enabled=1";
		$result = $conn->query($query);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$return_data[$count]['target'] = $row['f_target'];
				$return_data[$count]['base'] = $row['f_base'];
				$return_data[$count]['unitDecimal'] = $row['f_decimal'];
				$return_data[$count]['currentRate'] = $row['f_close'];
				$return_data[$count]['pastRate'] = $row['f_open'];
				$return_data[$count]['diff'] = $row['f_diff'];
				$return_data[$count]['percent'] = $row['f_percent'];
				$return_data[$count]['highRate'] = $row['f_high'];
				$return_data[$count]['lowRate'] = $row['f_low'];
				$return_data[$count]['dayTVolume'] = $row['f_day_target_volume'];
				$return_data[$count]['dayBVolume'] = $row['f_day_base_volume'];
				$return_data[$count]['lastTVolume'] = $row['f_last_day_target_volume'];
				$return_data[$count]['lastBVolume'] = $row['f_last_day_base_volume'];
				$return_data[$count]['tVolume'] = $row['f_target_volume'];
				$return_data[$count]['bVolume'] = $row['f_base_volume'];
				$count++;
			}
		}else{
			$return_data = [];
		}
		return $return_data;
	}

	function get_daily_market_history_after_update($conn, $target, $base, $rate, $target_volume, $base_volume, $reg_date)
	{
		$query = "SELECT * FROM `tb_market` WHERE `f_target`='".$target."' && `f_base`='".$base."'";
		$result = $conn->query($query);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$market_info = $row;
			}
		}

		$close = $rate;
		$open = $market_info['f_open'];
		$current_date = get_current_date_timestamp();
		if ($market_info['f_regdate'] < $current_date) {
			$open = $market_info['f_close'];
		}
		$diff = $close - $open;
		$percent = $market_info['f_percent'];
		if ($open > 0) {
			$percent = $diff / $open * 100;
		} else {
			$percent = 0;
		}
		$high = $market_info['f_high'];
		if ($market_info['f_regdate'] < $current_date) {
			$high = $rate;
		}
		if ($rate > $high) {
			$high = $rate;
		}
		$low = $market_info['f_low'];
		if ($market_info['f_regdate'] < $current_date) {
			$low = $rate;
		}
		if ($low == 0) $low = $rate;
		if ($rate < $low) {
			$low = $rate;
		}
		$day_target_volume = $market_info['f_day_target_volume'];
		if ($market_info['f_regdate'] < $current_date) {
			$day_target_volume = $target_volume;
		} else {
			$day_target_volume += $target_volume;
		}
		$day_base_volume = $market_info['f_day_base_volume'];
		if ($market_info['f_regdate'] < $current_date) {
			$day_base_volume = $base_volume;
		} else {
			$day_base_volume += $base_volume;
		}
		$last_day_target_volume = $market_info['f_last_day_target_volume'];
		if ($market_info['f_regdate'] < $current_date) {
			$last_day_target_volume = $market_info['f_day_target_volume'];
		}
		$last_day_base_volume = $market_info['f_last_day_base_volume'];
		if ($market_info['f_regdate'] < $current_date) {
			$last_day_base_volume = $market_info['f_day_base_volume'];
		}
		$total_target_volume = $market_info['f_target_volume'] + $target_volume;
		$total_base_volume = $market_info['f_base_volume'] + $base_volume;
		$update_query = "UPDATE tb_market SET f_close='".$close."', f_open='".$open."', f_diff='".$diff."', f_percent='".$percent."', f_high='".$high."', f_low='".$low."', f_day_target_volume='".$day_target_volume."', f_day_base_volume='".$day_base_volume."', f_last_day_target_volume='".$last_day_target_volume."', f_last_day_base_volume='".$last_day_base_volume."', f_target_volume='".$total_target_volume."', f_base_volume='".$total_base_volume."', f_regdate='".$reg_date."' WHERE f_target='".$target."' && f_base='".$base."'";
		$conn->query($update_query);

		$current_date = date('Y-m-d');
		$return_data = [];
		$return_data['target'] = $target;
		$return_data['base'] = $base;
		$return_data['open'] = $open;
		$return_data['close'] = $close;
		$return_data['high'] = $high;
		$return_data['low'] = $low;
		$return_data['tVolume'] = $day_target_volume;
		$return_data['bVolume'] = $day_base_volume;
		$return_data['diff'] = $diff;
		$return_data['percent'] = $percent;
		$return_data['regDate'] = $current_date;
		return $return_data;
		
	}

	function update_site_profit($conn, $unit, $volume, $reg_date)
	{
		$query = "SELECT * FROM tb_site_profit WHERE f_unit='".$unit."'";
		$result = $conn->query($query);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$site_profit = $row;
			}
		}

		$new_profit = $volume * 0.15 / 100;
		$site_profit['f_amount'] += $new_profit;

		$update_query = "UPDATE tb_site_profit SET f_amount='". $site_profit['f_amount']."' WHERE f_unit='".$unit."'";
		$conn->query($update_query);

		$insert_query = "INSERT INTO tb_site_profit_history (`f_type`, `f_unit`, `f_volume`, `f_fee`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('in', '".$unit."', '".$volume."', 0.15, '".$new_profit."', 'order process', '".$reg_date."')";
		$conn->query($insert_query);

		return true;
	}

	function update_wallet($conn, $thisToken, $target, $base, $update_data)
	{
		$reg_date = time();
		$update_data[$target]['f_total'] = $update_data[$target]['f_available'] + $update_data[$target]['f_blocked'];
		$update_data[$base]['f_total'] = $update_data[$base]['f_available'] + $update_data[$base]['f_blocked'];

		$query = "UPDATE tb_user_wallet SET f_total='".$update_data[$target]['f_total']."', f_available='".$update_data[$target]['f_available']."', f_blocked='".$update_data[$target]['f_blocked']."', f_buy_volume='".$update_data[$target]['f_buy_volume']."', f_buy_base_volume='".$update_data[$target]['f_buy_base_volume']."', f_sell_volume='".$update_data[$target]['f_sell_volume']."', f_sell_base_volume='".$update_data[$target]['f_sell_base_volume']."', f_buy_sell_base_volume='".$update_data[$target]['f_buy_sell_base_volume']."' WHERE f_token='".$thisToken."' && f_unit='".$target."'";
		$result = $conn->query($query);
		
		$query = "UPDATE tb_user_wallet SET f_total='".$update_data[$base]['f_total']."', f_available='".$update_data[$base]['f_available']."', f_blocked='".$update_data[$base]['f_blocked']."', f_buy_volume='".$update_data[$base]['f_buy_volume']."', f_buy_base_volume='".$update_data[$base]['f_buy_base_volume']."', f_sell_volume='".$update_data[$base]['f_sell_volume']."', f_sell_base_volume='".$update_data[$base]['f_sell_base_volume']."', f_buy_sell_base_volume='".$update_data[$base]['f_buy_sell_base_volume']."' WHERE f_token='".$thisToken."' && f_unit='".$base."'";
		$result = $conn->query($query);	
		
		if ($update_data['new_available_target_volume'] > 0) {
			$query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('".$thisToken."', 'enabled', '".$target."', '".$update_data['new_available_target_volume'] ."', 'create order', '".$reg_date."')";
			$result = $conn->query($query);
		}
		if ($update_data['new_blocked_target_volume'] > 0) {
			$query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('".$thisToken."', 'blocked', '".$target."', '". $update_data['new_blocked_target_volume'] ."', 'create order', '".$reg_date."')";
			$result = $conn->query($query);
		}
		if ($update_data['new_available_base_volume'] > 0) {
			$query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('".$thisToken."', 'enabled', '".$base."', '".$update_data['new_available_base_volume']."', 'create order', '".$reg_date."')";
			$result = $conn->query($query);
		}
		if ($update_data['new_blocked_base_volume'] > 0) {
			$query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('".$thisToken."', 'blocked', '".$base."', '".$update_data['new_blocked_base_volume']."', 'create order', '".$reg_date."')";
			$result = $conn->query($query);
		}
		if ($update_data['new_target_volume'] > 0) {
			$query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('".$thisToken."', 'in', '".$target."', '".$update_data['new_target_volume']."', 'create order', '".$reg_date."')";
			$result = $conn->query($query);
		} else if ($update_data['new_target_volume'] < 0) {
			$new_target_volume = $update_data['new_target_volume'] * (-1);
			$query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('".$thisToken."', 'out', '".$target."', '".$new_target_volume."', 'create order', '".$reg_date."')";
			$result = $conn->query($query);
		}
		if ($update_data['new_base_volume'] > 0) {
			$query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('".$thisToken."', 'in', '".$base."', '".$update_data['new_base_volume']."', 'create order', '".$reg_date."')";
			$result = $conn->query($query);
		} else if ($update_data['new_base_volume'] < 0) {
			$new_base_volume = $update_data['new_base_volume'] * (-1);
			$query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('".$thisToken."', 'out', '".$base."', '".$new_base_volume."', 'create order', '".$reg_date."')";
			$result = $conn->query($query);
		}
		return true;
	}

	function get_chart_data($target, $base, $close, $volume)
	{
		$time = time();
		$return_data = [];
		$return_data['target'] = $target;
		$return_data['base'] = $base;
		$return_data['time'] = $time;
		$return_data['close'] = $close;
		$return_data['volume'] = $volume;
		return $return_data;
	}

	function get_order_history($conn, $token, $type, $target, $base, $target_volume, $rate, $base_volume, $reg_date, $other_token)
	{
		$insert_query = "INSERT INTO tb_market_history (`f_token`, `f_type`, `f_target`, `f_base`, `f_target_volume`, `f_rate`, `f_base_volume`, `f_regdate`, `f_otoken`) VALUES ('".$token."', '".$type."', '".$target."', '".$base."', '".$target_volume."', '".$rate."', '".$base_volume."', '".$reg_date."', '".$other_token."')";
		$conn->query($insert_query);

		$temp_reg_date = date("Y-m-d H:i:s");

		$push_data = [];
		$push_data['target'] = $target;
		$push_data['base'] = $base;
		$push_data['regdate'] = $temp_reg_date;
		$push_data['type'] = $type;
		$push_data['targetVolume'] = $target_volume;
		$push_data['rate'] = $rate;
		$push_data['baseVolume'] = $base_volume;
		$return_data['token'] = $token;
		$return_data['token_data'] = $push_data;
		if ($type == 'buy') {
			$tempType = 'sell';
		} else {
			$tempType = 'buy';
		}
		$push_data['type'] = $tempType;
		$return_data['other_token'] = $other_token;
		$return_data['other_token_data'] = $push_data;
		return $return_data;
	}

	function process_create_order($conn, $token, $order_data){

		$socket_data = [];

		$type = $order_data->order_type;
		$target = $order_data->target;
		$base = $order_data->base;
		$target_volume = (double)number_format($order_data->order_amount , 5 , "." , "");
		$original_target_volume = $target_volume;
		$rate = $order_data->order_rate;
		$base_volume = (double)number_format($order_data->order_price , 5 , "." , "");
		$temp_value = $target_volume * $rate;
		if($base_volume < $temp_value){
			$base_Volume = $temp_value;
		}
		// $fee = $order_data->exchange_fee;
		$fee = 0.15;

		$socket_data['res'] = true;
		$socket_data['msg'] = 'order_succeed';

		$status = true;
		$diff = 0.001;
		$reg_date = time();
		
		$query = "SELECT * FROM tb_user_wallet WHERE f_token='".$token."'";
		$result = $conn->query($query);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				if($row['f_unit'] == $target){
					$balance_data[$token][$target] = $row;
				}else if($row['f_unit'] == $base){
					$balance_data[$token][$base] = $row;
				}
				$balance_data[$token]['new_available_base_volume'] = 0;
				$balance_data[$token]['new_blocked_base_volume'] = 0;
				$balance_data[$token]['new_available_target_volume'] = 0;
				$balance_data[$token]['new_blocked_target_volume'] = 0;
				$balance_data[$token]['new_target_volume'] = 0;
				$balance_data[$token]['new_target_volume'] = 0;
				$balance_data[$token]['new_base_volume'] = 0;
				$balance_data[$token]['new_base_volume'] = 0;
			}
		}

		if ($type == 'buy') {
			if ($balance_data[$token][$base]['f_available'] < $base_volume) {
				$socket_data['res'] = false;
				$socket_data['msg'] = 'less_base_balance';
				$status = false;
			}
		} else {
			if ($balance_data[$token][$target]['f_available'] < $target_volume) {
				$socket_data['res'] = false;
				$socket_data['msg'] = 'less_target_balance';
				$status = false;
			}
		}
		if ($target_volume < $diff) {
			$socket_data['res'] = false;
			$socket_data['msg'] = 'less_order_volume';
			$status = false;
		}

		if ($status == true) {

			$socket_market_history_data = [];
			
			if ($type == 'buy') {
				$query = "SELECT * FROM `tb_market_order` WHERE `f_type`='sell' && `f_target`='".$target."' && `f_base`='".$base."' && `f_rate`<='".$rate."' ORDER BY f_rate ASC, f_regdate ASC";
				$tempType = 'sell';
			} else {
				$query = "SELECT * FROM `tb_market_order` WHERE `f_type`='buy' && `f_target`='".$target."' && `f_base`='".$base."' && `f_rate`>='".$rate."' ORDER BY f_rate DESC, f_regdate ASC";
				$tempType = 'buy';
			}

			$order_count = 0;
			$result = $conn->query($query);
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$orders[$order_count] = $row;
					$order_count++;
				}
			}
			
			if ($order_count > 0) {
				$select_query = "SELECT * FROM tb_user_wallet WHERE f_token='".$orders[0]['f_token']."'";
				for ($i = 1;$i < $order_count;$i++){
					$select_query .= " or f_token='".$orders[$i]['f_token']."'";
				}
				
				$result = $conn->query($select_query);
				if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()) {
						if($row['f_token'] != $token){
							if ($row['f_unit'] == $target) {
								$balance_data[$row['f_token']][$target] = $row;
							} else if ($row['f_unit'] == $base) {
								$balance_data[$row['f_token']][$base] = $row;
							}
							$balance_data[$row['f_token']]['new_available_base_volume'] = 0;
							$balance_data[$row['f_token']]['new_blocked_base_volume'] = 0;
							$balance_data[$row['f_token']]['new_available_target_volume'] = 0;
							$balance_data[$row['f_token']]['new_blocked_target_volume'] = 0;
							$balance_data[$row['f_token']]['new_target_volume'] = 0;
							$balance_data[$row['f_token']]['new_target_volume'] = 0;
							$balance_data[$row['f_token']]['new_base_volume'] = 0;
							$balance_data[$row['f_token']]['new_base_volume'] = 0;
						}
					}
				}
				
				foreach($orders as $order){
					
					// change target currency value
					if ($target_volume - $order['f_target_volume'] > $diff) {

						$process_volume = $order['f_target_volume'];
						$new_target_volume = $target_volume - $order['f_target_volume'];
						$temp_base_volume = $order['f_rate'] * $process_volume;

						//delete this order
						$delete_query = "DELETE FROM tb_market_order WHERE f_id='".$order['f_id']."'";
						$conn->query($delete_query);

					} else if ($order['f_target_volume'] - $target_volume > $diff) {

						$process_volume = $target_volume;
						$new_target_volume = 0;
						$temp_base_volume = $order['f_rate'] * $process_volume;

						//update order
						$update_data = [];
						$update_data['f_target_volume'] = $order['f_target_volume'] - $target_volume;
						$update_data['f_base_volume'] = $order['f_base_volume'] - $temp_base_volume;
						$update_query = "UPDATE tb_market_order SET f_target_volume='".$update_data['f_target_volume']."', f_base_volume='".$update_data['f_base_volume']."' WHERE f_id='".$order['f_id']."'";
						$conn->query($update_query);

					} else{
						
						$process_volume = $target_volume;
						$new_target_volume = 0;
						$temp_base_volume = $order['f_rate'] * $process_volume;
						
						//delete this order
						$delete_query = "DELETE FROM tb_market_order WHERE f_id='".$order['f_id']."'";
						$conn->query($delete_query);

					}

					// broadcast order book
					$order_book = get_order_book($conn, $target, $base);
					$socket_data['order_book'] = $order_book;

					// emit my open orders;
					$my_open_orders = get_my_open_orders($conn, $order['f_token'], $target, $base);
					$socket_data['my_open_orders'][$order['f_token']] = $my_open_orders;
								
					//record to market history
					$order_history = get_order_history($conn, $token, $type, $target, $base, $process_volume, $order['f_rate'], $temp_base_volume, $reg_date, $order['f_token']);
					$chart_data = get_chart_data($target, $base, $rate, $target_volume);
					$daily_market_history = get_daily_market_history_after_update($conn, $target, $base, $rate, $target_volume, $base_volume, $reg_date);
					$market_summaries = get_market_summaries($conn);

					$socket_data['order_history'] = $order_history;
					$socket_data['chart_data'] = $chart_data;
					$socket_data['daily_market_history'] = $daily_market_history;
					$socket_data['market_summaries'] = $market_summaries;
					
					$push_data = [];
					$push_data['rate'] = $order['f_rate'];
					$push_data['targetVolume'] = $process_volume;
					$push_data['baseVolume'] = $temp_base_volume;
					$push_data['regdate'] = date('Y-m-d H:i:s', $reg_date);
					$push_data['type'] = $type;
					
					array_push($socket_market_history_data, $push_data);

					//change user balance data
					if ($type == 'buy') {
						$earn_target_volume = $process_volume * (1 - $fee / 100);
						$pay_base_volume = $temp_base_volume;

						$balance_data[$token][$target]['f_available'] += $earn_target_volume;
						$balance_data[$token][$target]['f_buy_base_volume'] += $pay_base_volume;
						$balance_data[$token][$target]['f_buy_volume'] += $earn_target_volume;
						$balance_data[$token][$base]['f_available'] -= $pay_base_volume;
						$balance_data[$token][$base]['f_buy_sell_base_volume'] += $pay_base_volume;

						$balance_data[$token]['new_available_target_volume'] += $earn_target_volume;
						$balance_data[$token]['new_available_base_volume'] -= $pay_base_volume;
						$balance_data[$token]['new_target_volume'] += $earn_target_volume;
						$balance_data[$token]['new_base_volume'] -= $pay_base_volume;

						update_site_profit($conn, $target, $process_volume, $reg_date);

					} else {
						$pay_target_volume = $process_volume;
						$earn_base_volume = $temp_base_volume * (1 - $fee / 100);

						$balance_data[$token][$target]['f_available'] -= $pay_target_volume;
						$balance_data[$token][$target]['f_sell_base_volume'] += $temp_base_volume;
						$balance_data[$token][$target]['f_sell_volume'] += $pay_target_volume;
						$balance_data[$token][$base]['f_available'] += $earn_base_volume;
						$balance_data[$token][$base]['f_buy_sell_base_volume'] += $earn_base_volume;

						$balance_data[$token]['new_available_target_volume'] -= $pay_target_volume;
						$balance_data[$token]['new_available_base_volume'] += $earn_base_volume;
						$balance_data[$token]['new_target_volume'] -= $pay_target_volume;
						$balance_data[$token]['new_base_volume'] += $earn_base_volume;

						update_site_profit($conn, $base, $temp_base_volume, $reg_date);

					}

					//update otoken wallet
					if ($type == 'buy') {

						$pay_target_volume = $process_volume;
						$earn_base_volume = $temp_base_volume * (1 - $fee / 100);

						$balance_data[$order['f_token']][$target]['f_blocked'] -= $process_volume;
						$balance_data[$order['f_token']][$target]['f_sell_base_volume'] += $temp_base_volume;
						$balance_data[$order['f_token']][$target]['f_sell_volume'] += $process_volume;
						$balance_data[$order['f_token']][$base]['f_available'] += $earn_base_volume;
						$balance_data[$order['f_token']][$base]['f_buy_sell_base_volume'] += $earn_base_volume;

						$balance_data[$order['f_token']]['new_blocked_target_volume'] -= $process_volume;
						$balance_data[$order['f_token']]['new_available_base_volume'] += $earn_base_volume;
						$balance_data[$order['f_token']]['new_target_volume'] -= $pay_target_volume;
						$balance_data[$order['f_token']]['new_base_volume'] += $earn_base_volume;

						update_site_profit($conn, $base, $temp_base_volume, $reg_date);

					} else {
						$earn_target_volume = $process_volume * (1 - $fee / 100);

						$balance_data[$order['f_token']][$target]['f_available'] += $earn_target_volume;
						$balance_data[$order['f_token']][$target]['f_buy_base_volume'] += $earn_target_volume * $order['f_rate'];
						$balance_data[$order['f_token']][$target]['f_buy_volume'] += $earn_target_volume;
						$balance_data[$order['f_token']][$base]['f_blocked'] -= $process_volume * $order['f_rate'];
						$balance_data[$order['f_token']][$base]['f_buy_sell_base_volume'] += $earn_target_volume * $order['f_rate'];

						$balance_data[$order['f_token']]['new_available_target_volume'] += $earn_target_volume;
						$balance_data[$order['f_token']]['new_blocked_base_volume'] -= $process_volume * $order['f_rate'];
						$balance_data[$order['f_token']]['new_target_volume'] += $earn_target_volume;
						$balance_data[$order['f_token']]['new_base_volume'] -= $process_volume * $order['f_rate'];

						update_site_profit($conn, $target, $process_volume, $reg_date);

					}
					if ($order['f_token'] != $token) {
						update_wallet($conn, $order['f_token'], $target, $base, $balance_data[$order['f_token']]);
						//------------------emit-------------------
						$my_balance = get_my_balance($conn, $order['f_token']);
						$socket_data['my_balance'][$order['f_token']] = $my_balance;
					}

					$target_volume = $new_target_volume;
					if ($target_volume == 0) break;

				}
				
				//if target volume is still larger than 0.001
				if ($target_volume > $diff) {
					
					// create new order
					$temp_base_volume = $target_volume * $rate;
					$insert_query = "INSERT INTO tb_market_order (`f_token`, `f_type`, `f_target`, `f_base`, `f_target_volume`, `f_original_target_volume`, `f_rate`, `f_base_volume`, `f_regdate`) VALUES ('".$token."', '".$type."', '".$target."', '".$base."', '".$target_volume."', '".$original_target_volume."', '".$rate."', '".$temp_base_volume."', '".$reg_date."')";
					$conn->query($insert_query);

					// broadcast order book;
					$order_book = get_order_book($conn, $target, $base);
					$socket_data['order_book'] = $order_book;
					
					// emit my open orders;
					$my_open_orders = get_my_open_orders($conn, $token, $target, $base);
					$socket_data['my_open_orders'][$token] = $my_open_orders;

					
					
					//update wallet base volume after create order
					if ($type == 'buy') {
						
						insertLogs($conn , 'buy' , $balance_data[$token][$base]['f_blocked'] , $temp_base_volume);
						
						$balance_data[$token][$base]['f_available'] -= $temp_base_volume;
						$balance_data[$token][$base]['f_blocked'] += $temp_base_volume;

						$balance_data[$token]['new_available_base_volume'] -= $temp_base_volume;
						$balance_data[$token]['new_blocked_base_volume'] += $temp_base_volume;
					} else {
						
						insertLogs($conn , 'sell' , $balance_data[$token][$target]['f_blocked'] , $target_volume);
						
						$balance_data[$token][$target]['f_available'] -= $target_volume;
						$balance_data[$token][$target]['f_blocked'] += $target_volume;

						$balance_data[$token]['new_available_target_volume'] -= $target_volume;
						$balance_data[$token]['new_blocked_target_volume'] += $target_volume;
					}
				}
			} else {

				//if matching orders don't exist
				$insert_query = "INSERT INTO tb_market_order (`f_token`, `f_type`, `f_target`, `f_base`, `f_target_volume`, `f_original_target_volume`, `f_rate`, `f_base_volume`, `f_regdate`) VALUES ('".$token."', '".$type."', '".$target."', '".$base."', '".$target_volume."', '".$original_target_volume."', '".$rate."', '".$base_volume."', '".$reg_date."')";
				$conn->query($insert_query);

				// broadcast order book;
				$order_book = get_order_book($conn, $target, $base);
				$socket_data['order_book'] = $order_book;

				// emit my open orders;
				$my_open_orders = get_my_open_orders($conn, $token, $target, $base);
				$socket_data['my_open_orders'][$token] = $my_open_orders;
				
				//update wallet volume after create order
				if ($type == 'buy') {
					
					insertLogs($conn , 'buy' , $balance_data[$token][$base]['f_blocked'] , $base_volume);
					
					$balance_data[$token][$base]['f_available'] -= $base_volume;
					$balance_data[$token][$base]['f_blocked'] += $base_volume;

					$balance_data[$token]['new_available_base_volume'] -= $base_volume;
					$balance_data[$token]['new_blocked_base_volume'] += $base_volume;
				} else {
					
					insertLogs($conn , 'sell' , $balance_data[$token][$target]['f_blocked'] , $target_volume);
					
					$balance_data[$token][$target]['f_available'] -= $target_volume;
					$balance_data[$token][$target]['f_blocked'] += $target_volume;

					$balance_data[$token]['new_available_target_volume'] -= $target_volume;
					$balance_data[$token]['new_blocked_target_volume'] += $target_volume;
				}
			}

			if (count($socket_market_history_data) > 0) {
				$socket_data['market_history'] = $socket_market_history_data;
			}

			update_wallet($conn, $token, $target, $base, $balance_data[$token]);
			$my_balance = get_my_balance($conn, $token);
			$socket_data['my_balance'][$token] = $my_balance;

		}

		if(!isset($socket_data['my_open_orders'])){
			$socket_data['my_open_orders'] = '';
		}

		if (!isset($socket_data['order_book'])) {
			$socket_data['order_book'] = '';
		}

		if (!isset($socket_data['my_balance'])) {
			$socket_data['my_balance'] = '';
		}

		if (!isset($socket_data['market_history'])) {
			$socket_data['market_history'] = '';
		}

		if (!isset($socket_data['order_history'])) {
			$socket_data['order_history'] = '';
		}


		if (!isset($socket_data['chart_data'])) {
			$socket_data['chart_data'] = '';
		}


		if (!isset($socket_data['daily_market_history'])) {
			$socket_data['daily_market_history'] = '';
		}


		if (!isset($socket_data['market_summaries'])) {
			$socket_data['market_summaries'] = '';
		}

		$fields = array(
			'res' => $socket_data['res'],
			'msg' => $socket_data['msg'],
			'token' => $token,
			'target' => $target,
			'base' => $base,
			'my_open_orders' => $socket_data['my_open_orders'],
			'order_book' => $socket_data['order_book'],
			'my_balance' => $socket_data['my_balance'],
			'market_history' => $socket_data['market_history'],
			'order_history' => $socket_data['order_history'],
			'chart_data' => $socket_data['chart_data'],
			'daily_market_history' => $socket_data['daily_market_history'],
			'market_summaries' => $socket_data['market_summaries']
		);
		$fields_string = http_build_query($fields);

		$url = "localhost:8082/create_order";
		$curl_result = send_curl_request($url, 1, $fields_string);
		
		return $curl_result;
	}
	
	function process_cancel_order($conn, $token, $data){

		$socket_data = array();
		
		$socket_data['blocked'] = 0;
		$socket_data['res'] = '1';
		$socket_data['token'] = $token;
		$f_id = $data->f_id; 
		$cancelIdArray = array();
		
		if ( $f_id == '-1' ) {
			$target = $data->target; $base = $data->base;
			$socket_data['target'] = $target = $data->target;
			$socket_data['base'] = $base = $data->base;	
			$query = "select *
					from tb_market_order
					where f_token = '".$token."'
					and   f_target = '".$target."'
					and   f_base = '".$base."'
					order by f_id ";
			$result = $conn->query($query);
			if ( $result->num_rows > 0 ) {
				while ($row = $result->fetch_assoc()) {
					$cancelIdArray[] = $row['f_id'];
				}
			}
		} else {
			$cancelIdArray[] = $f_id;
		}
		
		//calculate wallet before
		if( count($cancelIdArray) > 0 && $f_id == '-1') {
			$query = "SELECT * FROM tb_user_wallet WHERE f_token='" . $token . "' && f_unit='" . $base . "'";
			
			$result = $conn->query($query);
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$baseWallet = $row;
				}
			}
			
			$query = "SELECT * FROM tb_user_wallet WHERE f_token='" . $token . "' && f_unit='" . $target . "'";
			$result = $conn->query($query);
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$targetWallet = $row;
				}
			}
		}
		
		foreach ( $cancelIdArray as $key=>$id) {
			
			$reg_date = time();
			$query = "SELECT * FROM tb_market_order WHERE f_id=".$id;
			$result = $conn->query($query);
			$order_count = 0;
			
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$order = $row;
					$order_count++;
				}
			} else {
				$socket_data['target'] = '';
				$socket_data['base'] = '';
				$socket_data['res'] = '2';
			}	
			
			$sum1 = 0;
			$sum2 = 0;
			
			if ($order_count > 0) {
				
				if($f_id != '-1') {
					$socket_data['target'] = $target = $order['f_target'];
					$socket_data['base'] = $base = $order['f_base'];	
					$query = "SELECT * FROM tb_user_wallet WHERE f_token='" . $token . "' && f_unit='" . $order['f_base'] . "'";
			
					$result = $conn->query($query);
					if ($result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
							$baseWallet = $row;
						}
					}
					
					$query = "SELECT * FROM tb_user_wallet WHERE f_token='" . $token . "' && f_unit='" . $order['f_target'] . "'";
					$result = $conn->query($query);
					if ($result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
							$targetWallet = $row;
						}
					}	
				}
				// buy => baseWallet
				// sell => targetWallet
				if ($order['f_type'] == 'buy') {
					$available = $baseWallet['f_available'] + $order['f_base_volume'];
					$blocked = $baseWallet['f_blocked'] - $order['f_base_volume'];
					
					$sum1 += $order['f_base_volume'];
					
					if ($blocked > -0.00001) {
						$total = $available + $blocked;
						$baseWallet['f_total'] = $total; $baseWallet['f_available'] = $available; $baseWallet['f_blocked'] = $blocked;
						$insert_query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" . $token . "', 'enabled', '" . $order['f_base'] . "', '" . $order['f_base_volume'] . "', 'cancel order', '" . $reg_date . "')";
					} else {
						$socket_data['token'] = $token;
						$socket_data['blocked'] = $blocked;
						$socket_data['res'] = '3';
						break;
					}
				} else {
					$available = $targetWallet['f_available'] + $order['f_target_volume'];
					$blocked = $targetWallet['f_blocked'] - $order['f_target_volume'];
					
					$sum2 += $order['f_target_volume'];
					
					if ($blocked > -0.00001) {
						$total = $available + $blocked;
						$targetWallet['f_total'] = $total; $targetWallet['f_available'] = $available; $targetWallet['f_blocked'] = $blocked;
						$insert_query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" . $token . "', 'enabled', '" . $order['f_target'] . "', '" . $order['f_target_volume'] . "', 'cancel order', '" . $reg_date . "')";
					} else {
						$socket_data['token'] = $token;
						$socket_data['blocked'] = $blocked;
						$socket_data['res'] =  '4';
						break;
					}
					
				}
				
				if ($blocked > -0.00001) {
					$conn->query($insert_query);
					$delete_query = "DELETE FROM tb_market_order WHERE f_id=" . $id;
					$conn->query($delete_query);
				}
				
			}
		}		
		
		if( count($cancelIdArray) > 0 ) {
			
			if(isset($baseWallet['f_total'])) {
				
				insertLogs($conn , 'buy' , $baseWallet['f_blocked'] , -$sum1);
				
				$update_query = "UPDATE tb_user_wallet SET f_total=" . $baseWallet['f_total'] . ",
								f_available=" . $baseWallet['f_available'] . ",
								f_blocked=" . $baseWallet['f_blocked'] . "
								WHERE f_token='" . $token . "' && f_unit='" . $base . "'";
				$conn->query($update_query);
			}
			
			if(isset($targetWallet['f_total'])) {
				
				insertLogs($conn , 'sell' , $targetWallet['f_blocked'] , -$sum2);
				
				$update_query = "UPDATE tb_user_wallet SET f_total=" . $targetWallet['f_total'] . ",
								f_available=" . $targetWallet['f_available'] . ",
								f_blocked=" . $targetWallet['f_blocked'] . "
								WHERE f_token='" . $token . "' && f_unit='" . $target . "'";
				$conn->query($update_query);
			}
			
		}
		
		if ( $socket_data['res'] == '1') {
			// emitMyBalanceData(token);
			$my_balance = get_my_balance($conn, $token);
			$socket_data['my_balance'] = $my_balance;
			
			$socket_data['token'] = $token;
			$socket_data['res'] = true;
			
			// broadcast order book;
			$order_book = get_order_book($conn, $target, $base);
			$socket_data['order_book'] = $order_book;
			
			// emit my open orders;
			$my_open_orders = get_my_open_orders($conn, $token, $target, $base);
			$socket_data['my_open_orders'] = $my_open_orders;
		}
		else {
			//$socket_data['res'] = false;
		}
		
		if(!isset($socket_data['order_book'])){
			$socket_data['order_book'] = '';
		}
		if (!isset($socket_data['my_open_orders'])) {
			$socket_data['my_open_orders'] = '';
		}
		if (!isset($socket_data['my_balance'])) {
			$socket_data['my_balance'] = '';
		}
		
		$fields = array(
			'blocked' => $socket_data['blocked'],
			'res' => $socket_data['res'],
			'token' => $socket_data['token'],
			'target' => $socket_data['target'],
			'base' => $socket_data['base'],
			'order_book' => $socket_data['order_book'],
			'my_open_orders' => $socket_data['my_open_orders'],
			'my_balance' => $socket_data['my_balance']
		);
		
		$fields_string = http_build_query($fields);
		$url = "localhost:8082/cancel_order";
		$curl_result = send_curl_request($url, 1, $fields_string);
		return $curl_result;
	
	}

	function process_edit_order($conn, $token, $data)
	{
		$socket_data = [];

		$f_id = $data->f_id;
		$new_target_volume = $data->targetVolume;
		$reg_date = time();

		$socket_data['res'] = true;
		$socket_data['msg'] = 'changeOrderSucceed';

		if ($new_target_volume < 0.001) {

			$socket_data['res'] = false;
			$socket_data['msg'] = 'less_order_volume';

		} else {

			$order_count = 0;
			$query = "SELECT * FROM tb_market_order WHERE f_id=" . $f_id;
			$result = $conn->query($query);
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$order = $row;
					$order_count++;
				}
				$socket_data['target'] = $order['f_target'];
				$socket_data['base'] = $order['f_base'];
			}else{
				$socket_data['target'] = '';
				$socket_data['base'] = '';
			}

			if($order_count > 0){
				$new_base_volume = $order['f_rate'] * $new_target_volume;
				if ($order['f_type'] == 'buy') {
					$query = "SELECT * FROM tb_user_wallet WHERE f_token='" . $token . "' && f_unit='" . $order['f_base'] . "'";
				} else {
					$query = "SELECT * FROM tb_user_wallet WHERE f_token='" . $token . "' && f_unit='" . $order['f_target'] . "'";
				}
				$result = $conn->query($query);
				if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()) {
						$wallet = $row;
					}
				}

				if ($order['f_type'] == 'buy') {
					$change_volume = $order['f_base_volume'] - $new_base_volume;
					$available = $wallet['f_available'] + $change_volume;
					$blocked = $wallet['f_blocked'] - $change_volume;
					$total = $available + $blocked;
				} else {
					$change_volume = $order['f_target_volume'] - $new_target_volume;
					$available = $wallet['f_available'] + $change_volume;
					$blocked = $wallet['f_blocked'] - $change_volume;
					$total = $available + $blocked;
				}
				if ($available < 0) {

					if ($order['f_type'] == 'buy') {
						$socket_data['res'] = false;
						$socket_data['msg'] = 'less_base_balance';						
					} else {
						$socket_data['res'] = false;
						$socket_data['msg'] = 'less_target_balance';
					}

				} else if ($blocked < 0) {

					if ($order['f_type'] == 'buy') {
						$socket_data['res'] = false;
						$socket_data['msg'] = 'less_base_balance';
					} else {
						$socket_data['res'] = false;
						$socket_data['msg'] = 'less_target_balance';
					}

				} else {
					if ($new_target_volume != $order['f_target_volume']) {
						if ($order['f_type'] == 'buy') {
							$update_query = "UPDATE tb_user_wallet SET f_total=" . $total . ", f_available=" . $available . ", f_blocked=" . $blocked . " WHERE f_token='" . $token . "' && f_unit='" . $order['f_base'] . "'";
							if ($change_volume > 0) {
								$insert_query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" . $token . "', 'enabled', '" . $order['f_base'] . "', '" . $change_volume . "', 'changed order amount', '" . $reg_date . "')";
							} else if ($change_volume < 0) {
								$change_volume = $change_volume * (-1);
								$insert_query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" . $token . "', 'blocked', '" . $order['f_base'] . "', '" . $change_volume . "', 'changed order amount', '" . $reg_date . "')";
							}
						} else {
							$update_query = "UPDATE tb_user_wallet SET f_total=" . $total . ", f_available=" . $available . ", f_blocked=" . $blocked . " WHERE f_token='" . $token . "' && f_unit='" . $order['f_target'] . "'";
							if ($change_volume > 0) {
								$insert_query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" . $token . "', 'enabled', '" . $order['f_target'] . "', '" . $change_volume . "', 'changed order amount', '" . $reg_date . "')";
							} else if ($change_volume < 0) {
								$change_volume = $change_volume * (-1);
								$insert_query = "INSERT INTO tb_user_wallet_history (`f_token`, `f_type`, `f_unit`, `f_amount`, `f_detail`, `f_regdate`) VALUES ('" . $token . "', 'blocked', '" . $order['f_target'] . "', '" . $change_volume . "', 'changed order amount', '" . $reg_date . "')";
							}
						}
						$conn->query($update_query);
						$conn->query($insert_query);

						$my_balance = get_my_balance($conn, $token);
						$socket_data['my_balance'] = $my_balance;

						$update_query = "UPDATE tb_market_order SET f_target_volume=" . $new_target_volume . ", f_base_volume=" . $new_base_volume . " WHERE f_id=" . $f_id;
						$conn->query($update_query);

						$order_book = get_order_book($conn, $order['f_target'], $order['f_base']);
						$socket_data['order_book'] = $order_book;

					} else {
						$socket_data['res'] = false;
						$socket_data['msg'] = 'the_same_volume';
					}
				}
			}
		}

		if(!isset($socket_data['order_book'])){
			$socket_data['order_book'] = '';
		}

		if (!isset($socket_data['my_balance'])) {
			$socket_data['my_balance'] = '';
		}

		$fields = array(
			'res' => $socket_data['res'],
			'token' => $token,
			'target' => $socket_data['target'],
			'base' => $socket_data['base'],
			'order_book' => $socket_data['order_book'],
			'my_balance' => $socket_data['my_balance'],
			'msg' => $socket_data['msg']
		);
		$fields_string = http_build_query($fields);

		$url = "localhost:8082/edit_order";
		$curl_result = send_curl_request($url, 1, $fields_string);

		return $curl_result;
	}
	
	function insertLogs($conn , $type , $block , $val) {
		$query = "insert into tb_logs
		set f_type = '".$type."' ,
			f_block = ".$block." ,
			f_val = ".$val;
		$conn->query($query);
	}
	
	function testError($conn) {
		$query = "select sum(f_base_volume) as sum
from tb_market_order
where f_type = 'buy'
and f_token = 'WybFmfmfyLik4iZJlUORpGRjcCKwQW9b'";
		$ret = $conn->query($query);
		
		$sum1 = 0;
		
		if ($ret->num_rows == 0) {
			$sum1 = 0;
		}
		else {
			$row = $ret->fetch_assoc();
			if($row['sum'] == NULL)
				$sum1 = 0;
			else
				$sum1 = $row['sum'];
		}
			
		$query = "select sum(f_target_volume) as sum
from tb_market_order
where f_type = 'sell'
and f_token = 'WybFmfmfyLik4iZJlUORpGRjcCKwQW9b'";
		$ret = $conn->query($query);
		
		$sum2 = 0;
		
		if ($ret->num_rows == 0) {
			$sum2 = 0;
		}
		else {
			$row = $ret->fetch_assoc();
			if($row['sum'] == NULL)
				$sum2 = 0;
			else
				$sum2 = $row['sum'];
		}
			
		$query = "select f_blocked
from tb_user_wallet
where f_token = 'WybFmfmfyLik4iZJlUORpGRjcCKwQW9b'
and f_unit = 'KRW'";
		
		$ret = $conn->query($query);
		$row = $ret->fetch_assoc();
		$wallet1 = $row['f_blocked'];
		
		$query = "select f_blocked
from tb_user_wallet
where f_token = 'WybFmfmfyLik4iZJlUORpGRjcCKwQW9b'
and f_unit = 'BTC'";
		
		$ret = $conn->query($query);
		$row = $ret->fetch_assoc();
		
		$wallet2 = $row['f_blocked'];
		
		
		if($wallet1 != $sum1)
			return 2;
		if($wallet2 != $sum2)
			return 3;
		return 1;
	
	}

	$flag = true;
	while(1){
		$count = 0;
		//$query = "SELECT * FROM `tb_query_copy` where f_id = 68049 ORDER BY f_timestamp ASC";
		$query = "SELECT * FROM `tb_query` ORDER BY f_timestamp ASC";
		$result = $conn->query($query);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$token = $row['f_token'];
				$action = $row['f_action'];
				$data = json_decode($row['f_data']);
				
				$delete_query = "DELETE FROM tb_query WHERE f_id='" . $row['f_id'] . "'";
				$conn->query($delete_query);
				
				if($action == 'create_order'){
					process_create_order($conn, $token, $data);
				}else if($action == 'cancel_order'){
					process_cancel_order($conn, $token, $data);
				}else if($action == 'edit_order'){
					process_edit_order($conn, $token, $data);
				}

				print_r($row['f_id'] . PHP_EOL);

			}
		}else{
			sleep(1);
			print_r('---------------empty----------------' . PHP_EOL);
		}
	}

?>
