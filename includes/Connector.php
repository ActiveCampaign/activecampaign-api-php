<?php

namespace ActiveCampaign\Api\V1;

use ActiveCampaign\Api\V1\Exceptions\RequestException;
use ActiveCampaign\Api\V1\Exceptions\TimeoutException;
use ActiveCampaign\Api\V1\Exceptions\ClientException;
use ActiveCampaign\Api\V1\Exceptions\ServerException;

/**
 * Class AC_Connector
 */
class Connector
{

    /**
     * Default curl timeout after connection established (waiting for the response)
     */
    const DEFAULT_TIMEOUT = 30;

    /**
     * Default curl timeout before connection established (waiting for a server connection)
     */
    const DEFAULT_CONNECTTIMEOUT = 10;

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
    private $connect_timeout = self::DEFAULT_CONNECTTIMEOUT;

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
    public function __construct($url, $api_key, $api_user = "", $api_pass = "")
    {
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
        } elseif ($api_user && $api_pass) {
            $this->url = "{$url}{$base}/api.php?api_user={$api_user}&api_pass={$api_pass}";
        }
        $this->api_key = $api_key;
    }

    /**
     * Test the api credentials. Will throw an exception if credentials are not valid
     *
     * @return true
     * @throws RequestException
     */
    public function credentials_test()
    {
        $test_url = "{$this->url}&api_action=user_me&api_output={$this->output}";
        $this->curl($test_url);
        return true;
    }

    /**
     * Set curl timeout
     *
     * @param $seconds
     */
    public function set_curl_timeout($seconds)
    {
        $this->timeout = $seconds;
    }

    /**
     * Get curl timeout
     *
     * @return int
     */
    public function get_curl_timeout()
    {
        return $this->timeout;
    }

    /**
     * Set curl connect timeout
     *
     * @param $seconds
     */
    public function set_curl_connect_timeout($seconds)
    {
        $this->connect_timeout = $seconds;
    }

    /**
     * Get curl connect timeout
     *
     * @return int
     */
    public function get_curl_connect_timeout()
    {
        return $this->connect_timeout;
    }

    /**
     * Make the curl request
     *
     * @param        $url
     * @param array $params_data
     * @param string $verb
     * @param string $custom_method
     *
     * @return mixed
     * @throws \RequestException
     */
    public function curl($url, $params_data = array(), $verb = "", $custom_method = "")
    {
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

        $request = curl_init();

        curl_setopt($request, CURLOPT_HEADER, 0);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_CONNECTTIMEOUT, $this->get_curl_connect_timeout());
        curl_setopt($request, CURLOPT_TIMEOUT, $this->get_curl_timeout());

        if ($params_data && $verb == "GET") {
            if ($this->version == 2) {
                $url .= "&" . $params_data;
                curl_setopt($request, CURLOPT_URL, $url);
            }
        } else {
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

        if ($verb == "POST" || $verb == "PUT" || $verb == "DELETE") {
            if ($verb == "PUT") {
                curl_setopt($request, CURLOPT_CUSTOMREQUEST, "PUT");
            } elseif ($verb == "DELETE") {
                curl_setopt($request, CURLOPT_CUSTOMREQUEST, "DELETE");
            } else {
                $verb = "POST";
                curl_setopt($request, CURLOPT_POST, 1);
            }
            $data = "";
            if (is_array($params_data)) {
                foreach ($params_data as $key => $value) {
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

            curl_setopt($request, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 0);

        $response = curl_exec($request);

        $data = isset($data) ? $data : null;

        $this->checkForRequestErrors($request, $response, $data);

        $http_code = (string)curl_getinfo($request, CURLINFO_HTTP_CODE);

        curl_close($request);

        $object = json_decode($response);

        if (!is_object($object) || (!isset($object->result_code) && !isset($object->succeeded) && !isset($object->success))) {
            // add methods that only return a string
            $string_responses = array("tags_list", "segment_list", "tracking_event_remove", "contact_list", "form_html", "tracking_site_status", "tracking_event_status", "tracking_whitelist", "tracking_log", "tracking_site_list", "tracking_event_list");
            if (in_array($method, $string_responses)) {
                return $response;
            }

            $this->throwRequestException($response, $request, $data);
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
     * @param string $message
     * @param resource $request
     * @param string $data
     *
     * @throws RequestException
     */
    protected function throwRequestException($message, $request = null, $data = null)
    {
        $requestException = new RequestException;

        if ($request) {
            $requestException->setContext(array(
                "request_url"  => curl_getinfo($request, CURLINFO_EFFECTIVE_URL),
                "request_body" => json_encode($data)
            ));
        }

        $requestException->setFailedMessage($message);

        throw $requestException;
    }

    /**
     * Checks the cURL request for errors and throws exceptions appropriately
     *
     * @param resource $request
     * @param string $response The response from the request
     * @param string $data     The post data if it exists
     * @throws RequestException
     * @throws ClientException
     * @throws ServerException
     * @throws TimeoutException
     */
    protected function checkForRequestErrors($request, $response, $data = null)
    {
        $exception = null;

        // if curl has an error number
        if (curl_errno($request)) {
            switch (curl_errno($request)) {
                // curl timeout error
                case CURLE_OPERATION_TIMEDOUT:
                    $exception = new TimeoutException(curl_error($request));
                    break;
                default:
                    $this->throwRequestException(curl_error($request), $request, $data);
                    break;
            }
        } else {
            $http_code = (string)curl_getinfo($request, CURLINFO_HTTP_CODE);
            if (preg_match("/^4.*/", $http_code)) {
                // 4** status code
                $exception = new ClientException($response, $http_code);
            } elseif (preg_match("/^5.*/", $http_code)) {
                // 5** status code
                $exception = new ServerException($response, $http_code);
            }
        }

        if (!$exception) {
            return;
        }

        $exception->setContext(array(
            "api_request_url"  => curl_getinfo($request, CURLINFO_EFFECTIVE_URL),
            "api_request_body" => json_encode($data)
        ));

        throw $exception;
    }
}
