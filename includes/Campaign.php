<?php

namespace ActiveCampaign\Api\V1;

class Campaign extends ActiveCampaign
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

    public function create($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=campaign_create&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function deleteList($params)
    {
        $request_url = "{$this->url}&api_action=campaign_delete_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function delete($params)
    {
        $request_url = "{$this->url}&api_action=campaign_delete&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    // phpcs:disable
    public function list_($params)
    {
        // phpcs:enable
        $request_url = "{$this->url}&api_action=campaign_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function paginator($params)
    {
        $request_url = "{$this->url}&api_action=campaign_paginator&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function reportBounceList($params)
    {
        $request_url = "{$this->url}&api_action=campaign_report_bounce_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function reportBounceTotals($params)
    {
        $request_url = "{$this->url}&api_action=campaign_report_bounce_totals&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function reportForwardList($params)
    {
        $request_url = "{$this->url}&api_action=campaign_report_forward_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function reportForwardTotals($params)
    {
        $request_url = "{$this->url}&api_action=campaign_report_forward_totals&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function reportLinkList($params)
    {
        $request_url = "{$this->url}&api_action=campaign_report_link_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function reportLinkTotals($params)
    {
        $request_url = "{$this->url}&api_action=campaign_report_link_totals&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function reportOpenList($params)
    {
        $request_url = "{$this->url}&api_action=campaign_report_open_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function reportOpenTotals($params)
    {
        $request_url = "{$this->url}&api_action=campaign_report_open_totals&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function reportTotals($params)
    {
        $request_url = "{$this->url}&api_action=campaign_report_totals&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function reportUnopenList($params)
    {
        $request_url = "{$this->url}&api_action=campaign_report_unopen_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function reportUnsubscriptionList($params)
    {
        $request_url = "{$this->url}&api_action=campaign_report_unsubscription_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function reportUnsubscriptionTotals($params)
    {
        $request_url = "{$this->url}&api_action=campaign_report_unsubscription_totals&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function send($params)
    {
        $request_url = "{$this->url}&api_action=campaign_send&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function status($params)
    {
        $request_url = "{$this->url}&api_action=campaign_status&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }
}
