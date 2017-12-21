<?php

namespace ActiveCampaign\Api\V1;

class Account extends ActiveCampaign
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
        $request_url = "{$this->url}&api_action=account_add&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function cancel($params)
    {
        $request_url = "{$this->url}&api_action=account_cancel&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function edit($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=account_edit&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function list_($params)
    {
        $request_url = "{$this->url}&api_action=account_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function nameCheck($params)
    {
        $request_url = "{$this->url}&api_action=account_name_check&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function plans($params)
    {
        $request_url = "{$this->url}&api_action=account_plans&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function status($params)
    {
        $request_url = "{$this->url}&api_action=account_status&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function statusSet($params)
    {
        $request_url = "{$this->url}&api_action=account_status_set&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function view()
    {
        $request_url = "{$this->url}&api_action=account_view&api_output={$this->output}";
        $response = $this->curl($request_url);
        return $response;
    }
}
