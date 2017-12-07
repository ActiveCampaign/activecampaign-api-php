<?php

require_once(dirname(__FILE__) . "/exceptions/RequestException.php");

/**
 * Class AC_Connector
 */
class AC_Connector {

	/**
	 * Default curl timeout
	 */
	const DEFAULT_TIMEOUT = 30;

	/**
	 * @var string
	 */
	public $url;

	/**
	 * @var
	 */
	public $api_key;

	/**
	 * @var string
	 */
	public $output = "json";

	/**
	 * @var int
	 */
	private $timeout = self::DEFAULT_TIMEOUT;

	/**
	 * AC_Connector constructor.
	 *
	 * @param        $url
	 * @param        $api_key
	 * @param string $api_user
	 * @param string $api_pass
	 */
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

	/**
	 * Test the api credentials
	 *
	 * @return bool|mixed
	 * @throws \RequestException
	 */
	public function credentials_test() {
		$test_url = "{$this->url}&api_action=user_me&api_output={$this->output}";
		$r = $this->curl($test_url);
		if (is_object($r) && (int)$r->result_code) {
			// successful
			$r = true;
		} else {
			// failed - log it
			$this->curl_response_error = $r;
			$r = false;
		}
		return $r;
	}

	/**
	 * Debug helper function
	 *
	 * @param        $var
	 * @param int    $continue
	 * @param string $element
	 * @param string $extra
	 */
	public function dbg($var, $continue = 0, $element = "pre", $extra = "") {
	  echo "<" . $element . ">";
	  echo "Vartype: " . gettype($var) . "\n";
	  if ( is_array($var) ) echo "Elements: " . count($var) . "\n";
	  elseif ( is_string($var) ) echo "Length: " . strlen($var) . "\n";
	  if ($extra) {
	  	echo $extra . "\n";
	  }
	  echo "\n";
	  print_r($var);
	  echo "</" . $element . ">";
		if (!$continue) exit();
	}

	/**
	 * Set curl timeout
	 *
	 * @param $seconds
	 */
	public function set_curl_timeout($seconds) {
		$this->timeout = $seconds;
	}

	/**
	 * Get curl timeout
	 *
	 * @return int
	 */
	public function get_curl_timeout() {
		return $this->timeout;
	}

	/**
	 * Make the curl request
	 *
	 * @param        $url
	 * @param array  $params_data
	 * @param string $verb
	 * @param string $custom_method
	 *
	 * @return mixed
	 * @throws \RequestException
	 */
	public function curl($url, $params_data = array(), $verb = "", $custom_method = "") {
		if ($this->version == 1) {
			// find the method from the URL.
			$method = preg_match("/api_action=[^&]*/i", $url, $matches);
			if ($matches) {
				$method = preg_match("/[^=]*$/i", $matches[0], $matches2);
				$method = $matches2[0];
			} elseif ($custom_method) {
				$method = $custom_method;
			}
		} elseif ($this->version == 2) {
			$method = $custom_method;
			$url .= "?api_key=" . $this->api_key;
		}

		$debug_str1 = "";

		$request = curl_init();

		$debug_str1 .= "\$ch = curl_init();\n";

		curl_setopt($request, CURLOPT_HEADER, 0);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($request, CURLOPT_TIMEOUT, $this->timeout);

		$debug_str1 .= "curl_setopt(\$ch, CURLOPT_HEADER, 0);\n";
		$debug_str1 .= "curl_setopt(\$ch, CURLOPT_RETURNTRANSFER, true);\n";
		$debug_str1 .= "curl_setopt(\$ch, CURLOPT_TIMEOUT, " . $this->timeout . ");\n";

		if ($params_data && $verb == "GET") {
			if ($this->version == 2) {
				$url .= "&" . $params_data;
				curl_setopt($request, CURLOPT_URL, $url);
			}
		}
		else {
			curl_setopt($request, CURLOPT_URL, $url);
			if ($params_data && !$verb) {
				// if no verb passed but there IS params data, it's likely POST.
				$verb = "POST";
			} elseif ($params_data && $verb) {
				// $verb is likely "POST" or "PUT".
			} else {
				$verb = "GET";
			}
		}

		$debug_str1 .= "curl_setopt(\$ch, CURLOPT_URL, \"" . $url . "\");\n";

		if ($this->debug) {
			$this->dbg($url, 1, "pre", "Description: Request URL");
		}

		if ($verb == "POST" || $verb == "PUT" || $verb == "DELETE") {
			if ($verb == "PUT") {
				curl_setopt($request, CURLOPT_CUSTOMREQUEST, "PUT");

				$debug_str1 .= "curl_setopt(\$ch, CURLOPT_CUSTOMREQUEST, \"PUT\");\n";
			} elseif ($verb == "DELETE") {
				curl_setopt($request, CURLOPT_CUSTOMREQUEST, "DELETE");

				$debug_str1 .= "curl_setopt(\$ch, CURLOPT_CUSTOMREQUEST, \"DELETE\");\n";
			} else {
				$verb = "POST";
				curl_setopt($request, CURLOPT_POST, 1);

				$debug_str1 .= "curl_setopt(\$ch, CURLOPT_POST, 1);\n";
			}
			$data = "";
			if (is_array($params_data)) {
				foreach($params_data as $key => $value) {
					if (is_array($value)) {

						if (is_int($key)) {
							// array two levels deep
							foreach ($value as $key_ => $value_) {
								if (is_array($value_)) {
									foreach ($value_ as $k => $v) {
										$k = urlencode($k);
										$data .= "{$key_}[{$key}][{$k}]=" . urlencode($v) . "&";
									}
								} else {
									$data .= "{$key_}[{$key}]=" . urlencode($value_) . "&";
								}
							}
						} elseif (preg_match('/^field\[.*,0\]/', $key)) {
							// if the $key is that of a field and the $value is that of an array
							if (is_array($value)) {
								// then join the values with double pipes
								$value = implode('||', $value);
							}
							$data .= "{$key}=" . urlencode($value) . "&";
						} else {
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

					} else {
						$data .= "{$key}=" . urlencode($value) . "&";
					}
				}
			} else {
				// not an array - perhaps serialized or JSON string?
				// just pass it as data
				$data = "data={$params_data}";
			}

			$data = rtrim($data, "& ");

			curl_setopt($request, CURLOPT_HTTPHEADER, array("Expect:"));

			$debug_str1 .= "curl_setopt(\$ch, CURLOPT_HTTPHEADER, array(\"Expect:\"));\n";

			if ($this->debug) {
				curl_setopt($request, CURLINFO_HEADER_OUT, 1);

				$debug_str1 .= "curl_setopt(\$ch, CURLINFO_HEADER_OUT, 1);\n";

				$this->dbg($data, 1, "pre", "Description: POST data");
			}

			curl_setopt($request, CURLOPT_POSTFIELDS, $data);

			$debug_str1 .= "curl_setopt(\$ch, CURLOPT_POSTFIELDS, \"" . $data . "\");\n";
		}

		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 0);

		$debug_str1 .= "curl_setopt(\$ch, CURLOPT_SSL_VERIFYPEER, false);\n";
		$debug_str1 .= "curl_setopt(\$ch, CURLOPT_SSL_VERIFYHOST, 0);\n";

		$response = curl_exec($request);

		$curl_error = curl_error($request);
		
		if (!$response && $curl_error) {
			return $curl_error;
		}

		$debug_str1 .= "curl_exec(\$ch);\n";

		if ($this->debug) {
			$this->dbg($response, 1, "pre", "Description: Raw response");
		}

		$http_code = curl_getinfo($request, CURLINFO_HTTP_CODE);
		if (!preg_match("/^[2-3][0-9]{2}/", $http_code)) {
			// If not 200 or 300 range HTTP code, return custom error.
			return "HTTP code $http_code returned";
		}

		$debug_str1 .= "\$http_code = curl_getinfo(\$ch, CURLINFO_HTTP_CODE);\n";

		if ($this->debug) {
			$this->dbg($http_code, 1, "pre", "Description: Response HTTP code");

			$request_headers = curl_getinfo($request, CURLINFO_HEADER_OUT);

			$debug_str1 .= "\$request_headers = curl_getinfo(\$ch, CURLINFO_HEADER_OUT);\n";

			$this->dbg($request_headers, 1, "pre", "Description: Request headers");
		}

		curl_close($request);

		$debug_str1 .= "curl_close(\$ch);\n";

		$object = json_decode($response);

		if ($this->debug) {
			$this->dbg($object, 1, "pre", "Description: Response object (json_decode)");
		}
		if ( !is_object($object) || (!isset($object->result_code) && !isset($object->succeeded) && !isset($object->success)) ) {
			// add methods that only return a string
			$string_responses = array("tags_list", "segment_list", "tracking_event_remove", "contact_list", "form_html", "tracking_site_status", "tracking_event_status", "tracking_whitelist", "tracking_log", "tracking_site_list", "tracking_event_list");
			if (in_array($method, $string_responses)) {
				return $response;
			}

			$this->throwRequestException($response);
		}

		if ($this->debug) {
			echo "<textarea style='height: 300px; width: 600px;'>" . $debug_str1 . "</textarea>";
		}

		$object->http_code = $http_code;

		if (isset($object->result_code)) {
			$object->success = $object->result_code;

			if (!(int)$object->result_code) {
				$object->error = $object->result_message;
			}
		} elseif (isset($object->succeeded)) {
			// some calls return "succeeded" only
			$object->success = $object->succeeded;

			if (!(int)$object->succeeded) {
				$object->error = $object->message;
			}
		}

		return $object;
	}

	/**
	 * Throw the request exception
	 *
	 * @param $message
	 *
	 * @throws \RequestException
	 */
	protected function throwRequestException($message) {
		$requestException = new RequestException;
		$requestException->setFailedMessage($message);

		throw $requestException;
	}
}
