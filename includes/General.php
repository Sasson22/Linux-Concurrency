<?php

class General
{
	static function get_visitor_ip()
	{
		switch (true)
		{
			case $_SERVER["HTTP_CLIENT_IP"]:
				$ipaddress = $_SERVER["HTTP_CLIENT_IP"];
				break;
			case $_SERVER["HTTP_X_FORWARDED_FOR"]:
				$ipaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
				break;
			case $_SERVER["HTTP_X_FORWARDED"]:
				$ipaddress = $_SERVER["HTTP_X_FORWARDED"];
				break;
			case $_SERVER["HTTP_CLIENT_IP"]:
				$ipaddress = $_SERVER["HTTP_CLIENT_IP"];
				break;
			case $_SERVER["HTTP_FORWARDED_FOR"]:
				$ipaddress = $_SERVER["HTTP_FORWARDED_FOR"];
				break;
			case $_SERVER["REMOTE_ADDR"]:
				$ipaddress = $_SERVER["REMOTE_ADDR"];
				break;
			default:
				$ipaddress = "UNKNOWN";
				break;
		}

		return $ipaddress;
	}

	/* Send email via Mandrill
	 * @param array ("subject", "message", "to_email", "to_name" (optional), "from_email" (optional), "from_name" (optional))
	 *
	 *
	 */
	static function mandrill_email($params)
	{
		$args = array
		(
			"key" => MANDRILL_KEY,
			"message" => array
			(
				"html" => $params["message"],
				"text" => strip_tags ($params["message"]),
				"from_email" => $params["from_email"] == "" ? EMAIL_FROM : $params["from_email"],
				"from_name" => $params["from_name"] == "" ? NAME_FROM : $params["from_name"],
				"subject" => $params["subject"],
				"to" => array
				(
					array
					(
						"email" => $params["to_email"],
						"name" => $params["to_name"] == "" ? $params["to_email"] : $params["to_name"]
					)
				),
				"auto_text" => true
			)
		);

		$curl = curl_init ("https://mandrillapp.com/api/1.0/messages/send.json");
		curl_setopt ($curl, CURLOPT_POST, true);
		curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ($curl, CURLOPT_HEADER, false);
		curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($curl, CURLOPT_HTTPHEADER, array ("Content-Type: application/json"));
		curl_setopt ($curl, CURLOPT_POSTFIELDS, json_encode ($args));

		$cter = 0;
		$response = json_decode (curl_exec ($curl), true);
		while ($response[0]["status"] != "sent" && $cter < 5)
		{
			$response = json_decode (curl_exec ($curl), true);
			sleep (1);
			$cter++;
		}
		curl_close ($curl);

		return $response;
	}
	
	public function fread_url($url, $ref = "")
	{
		if(function_exists("curl_init")){
			$ch = curl_init();
			$user_agent = "Mozilla/5.0 (X11; Linux x86_64; rv:40.0) Gecko/20100101 Firefox/40.0";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
			curl_setopt( $ch, CURLOPT_HTTPGET, 1 );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION , true);
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_REFERER, $ref );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
			$html = curl_exec($ch);
			curl_close($ch);
		}
		else{
			$hfile = fopen($url,"r");
			if($hfile){
				while(!feof($hfile)){
					$html.=fgets($hfile,1024);
				}
			}
		}
		
		return $html;
	}
}
?>
