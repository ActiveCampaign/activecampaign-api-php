<?php

class AC_Webhook extends ActiveCampaign
{

    public $version;
    public $url_base;
    public $url;
    public $api_key;

    public function __construct($version, $url_base, $url, $api_key)
    {
        $this->version = $version;
        $this->url_base = $url_base;
        $this->url = $url;
        $this->api_key = $api_key;
    }

    public function add($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=webhook_add&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function delete($params)
    {
        $request_url = "{$this->url}&api_action=webhook_delete&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function delete_list($params)
    {
        $request_url = "{$this->url}&api_action=webhook_delete_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function edit($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=webhook_edit&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function list_($params)
    {
        $request_url = "{$this->url}&api_action=webhook_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function view($params)
    {
        $request_url = "{$this->url}&api_action=webhook_view&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function events($params)
    {
        $request_url = "{$this->url}&api_action=webhook_events&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function process($params)
    {
        // process an incoming webhook payload (from ActiveCampaign), and format it (or do something with it)

        $r = array();
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            return $r;
        }

        $params_array = explode("&", $params);
        $params_ = array();
        foreach ($params_array as $expression) {
            // IE: css=1
            list($var, $val) = explode("=", $expression);
            $params_[$var] = $val;
        }

        $event = $params_["event"];
        $format = $params_["output"];

        if ($format == "json") {
            return json_encode($_POST);
        }
    }
}
