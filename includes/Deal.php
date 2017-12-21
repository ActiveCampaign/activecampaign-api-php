<?php

namespace ActiveCampaign\Api\V1;

class Deal extends ActiveCampaign
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
        $request_url = "{$this->url}&api_action=deal_add&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function edit($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=deal_edit&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function delete($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=deal_delete&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function get($params)
    {
        $request_url = "{$this->url}&api_action=deal_get&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    // phpcs:disable
    public function list_($params)
    {
        // phpcs:enable
        $request_url = "{$this->url}&api_action=deal_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function noteAdd($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=deal_note_add&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function noteEdit($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=deal_note_edit&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function pipelineAdd($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=deal_pipeline_add&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function pipelineEdit($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=deal_pipeline_edit&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function pipelineDelete($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=deal_pipeline_delete&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function pipelineList($params)
    {
        $request_url = "{$this->url}&api_action=deal_pipeline_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function stageAdd($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=deal_stage_add&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function stageEdit($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=deal_stage_edit&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function stageDelete($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=deal_stage_delete&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function stageList($params)
    {
        $request_url = "{$this->url}&api_action=deal_stage_list&api_output={$this->output}&{$params}";
        $response = $this->curl($request_url);
        return $response;
    }

    public function taskAdd($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=deal_task_add&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function taskEdit($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=deal_task_edit&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function tasktypeAdd($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=deal_tasktype_add&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function tasktypeEdit($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=deal_tasktype_edit&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }

    public function tasktypeDelete($params, $post_data)
    {
        $request_url = "{$this->url}&api_action=deal_tasktype_delete&api_output={$this->output}";
        $response = $this->curl($request_url, $post_data);
        return $response;
    }
}
