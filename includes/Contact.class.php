<?php

class AC_Contact extends ActiveCampaign
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
        $request_url = "{$this->url}&api_action=contact_add&api_output={$this->output}";
        if ($params) {
            $request_url .= "&{$params}";
        }
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function automation_list($params)
    {
        $request_url = "{$this->url}&api_action=contact_automation_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function delete_list($params)
    {
        $request_url = "{$this->url}&api_action=contact_delete_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function delete($params)
    {
        $request_url = "{$this->url}&api_action=contact_delete&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function edit($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=contact_edit&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function list_($params)
    {
        if ($this->version == 1) {
            $request_url = "{$this->url}&api_action=contact_list&api_output={$this->output}&{$params}";
            $response = $this->curl($request_url);
        } elseif ($this->version == 2) {
            $request_url = "{$this->url_base}/contact/emails";
            // $params example: offset=0&limit=1000&listid=4
            $response = $this->curl($request_url, $params, "GET", "contact_list");
        }
        return $response;
    }

    public function note_add($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=contact_note_add&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function note_edit($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=contact_note_edit&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function note_delete($params)
    {
        $request_url = "{$this->url}&api_action=contact_note_delete&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function paginator($params)
    {
        $request_url = "{$this->url}&api_action=contact_paginator&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function sync($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=contact_sync&api_output={$this->output}";
        if ($params) {
            $request_url .= "&{$params}";
        }
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function tag_add($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=contact_tag_add&api_output={$this->output}";
        if ($params) {
            $request_url .= "&{$params}";
        }
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function tag_remove($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=contact_tag_remove&api_output={$this->output}";
        if ($params) {
            $request_url .= "&{$params}";
        }
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function view($params)
    {
        // can be a contact ID, email, or hash
        if (preg_match("/^email=/", $params)) {
            $action = "contact_view_email";
        } elseif (preg_match("/^hash=/", $params)) {
            $action = "contact_view_hash";
        } elseif (preg_match("/^id=/", $params)) {
            $action = "contact_view";
        } else {
            // default
            $action = "contact_view";
        }
        $request_url = "{$this->url}&api_action={$action}&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }
}
