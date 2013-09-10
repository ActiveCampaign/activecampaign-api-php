<?php

class AC_Connector {

	public $url;
	public $api_key;
	public $output = "json";

	function __construct($url, $api_key, $api_user = "", $api_pass = "") {
		// $api_pass should be md5() already
		$base = "";
		if (!preg_match("/https:\/\/www.activecampaign.com/", $url)) {
			// not a reseller
			$base = "/admin";
		}
		if (preg_match("/\/$/", $url)) {
			// remove trailing slash
			$url = substr($url, 0, strlen($url) - 1);
		}
		if ($api_key) {
			$this->url = "{$url}{$base}/api.php?api_key={$api_key}";
		}
		elseif ($api_user && $api_pass) {
			$this->url = "{$url}{$base}/api.php?api_user={$api_user}&api_pass={$api_pass}";
		}
		$this->api_key = $api_key;
	}

	public function credentials_test() {
		$test_url = "{$this->url}&api_action=user_me&api_output={$this->output}";
		$r = $this->curl($test_url);
		if (is_object($r) && (int)$r->result_code) {
			// successful
			$r = true;
		}
		else {
			// failed
			$r = false;
		}
		return $r;
	}

	// debug function (nicely outputs variables)
	public function dbg($var, $continue = 0, $element = "pre") {
	  echo "<" . $element . ">";
	  echo "Vartype: " . gettype($var) . "\n";
	  if ( is_array($var) ) echo "Elements: " . count($var) . "\n\n";
	  elseif ( is_string($var) ) echo "Length: " . strlen($var) . "\n\n";
	  print_r($var);
	  echo "</" . $element . ">";
		if (!$continue) exit();
	}

	public function curl($url, $post_data = array()) {
		// find the method from the URL
		$method = preg_match("/api_action=[^&]*/i", $url, $matches);
		$method = preg_match("/[^=]*$/i", $matches[0], $matches2);
		$method = $matches2[0];
		$request = curl_init();
		curl_setopt($request, CURLOPT_URL, $url);
		curl_setopt($request, CURLOPT_HEADER, 0);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		if ($post_data) {
			curl_setopt($request, CURLOPT_POST, 1);
			$data = "";

			if (is_array($post_data)) {
				foreach($post_data as $key => $value) {
					if (is_array($value)) {

						if (is_int($key)) {
							// array two levels deep
							foreach ($value as $key_ => $value_) {
								if (is_array($value_)) {
									foreach ($value_ as $k => $v) {
										$k = urlencode($k);
										$data .= "{$key_}[{$key}][{$k}]=" . urlencode($v) . "&";
									}
								}
								else {
									$data .= "{$key_}[{$key}]=" . urlencode($value_) . "&";
								}
							}
						}
						else {
							// IE: [group] => array(2 => 2, 3 => 3)
							// normally we just want the key to be a string, IE: ["group[2]"] => 2
							// but we want to allow passing both formats
							foreach ($value as $k => $v) {
								if (!is_array($v)) {
									$k = urlencode($k);
									$data .= "{$key}[{$k}]=" . urlencode($v) . "&";
								}
							}
						}

					}
					else {
						$data .= "{$key}=" . urlencode($value) . "&";
					}
				}
			}
			else {
				// not an array - perhaps serialized or JSON string?
				// just pass it as data
				$data = "data={$post_data}";
			}

			$data = rtrim($data, "& ");
			curl_setopt($request, CURLOPT_HTTPHEADER, array("Expect:"));
			curl_setopt($request, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
		$response = curl_exec($request);
//$this->dbg($response);
		$http_code = curl_getinfo($request, CURLINFO_HTTP_CODE);
		curl_close($request);
		$object = json_decode($response);
		if ( !is_object($object) || (!isset($object->result_code) && !isset($object->succeeded)) ) {
		// add methods that only return a string
			$string_responses = array("form_html");
			if (in_array($method, $string_responses)) {
				return $response;
			}
			// something went wrong
			return "An unexpected problem occurred with the API request. Some causes include: invalid JSON or XML returned. Here is the actual response from the server: ---- " . $response;
		}

		header("HTTP/1.1 " . $http_code);

		if (isset($object->result_code)) {
			$object->success = $object->result_code;
			if (!(int)$object->result_code) {
				$object->error = $object->result_message;
			}
		}
		elseif (isset($object->succeeded)) {
			// some calls return "succeeded" only
			$object->success = $object->succeeded;
			if (!(int)$object->succeeded) {
				$object->error = $object->message;
			}
		}
		return $object;
	}

}

?>