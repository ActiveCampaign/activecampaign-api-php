<?php

namespace ActiveCampaign\Api\V1;

class Automation extends ActiveCampaign
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

    public function list_($params)
    {
        $request_url = "{$this->url}&api_action=automation_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function contactAdd($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=automation_contact_add&api_output={$this->output}";
        if ($params) {
            $request_url .= "&{$params}";
        }
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function contactRemove($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=automation_contact_remove&api_output={$this->output}";
        if ($params) {
            $request_url .= "&{$params}";
        }
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function contactList($params)
    {
        $request_url = "{$this->url}&api_action=automation_contact_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function contactView($params)
    {
        $request_url = "{$this->url}&api_action=automation_contact_view&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }
}
